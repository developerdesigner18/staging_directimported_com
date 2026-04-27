<?php

namespace App\Http\Controllers\User;

use App\Enum\DocumentStatus;
use App\Http\Controllers\Controller;
use App\Http\Traits\ResponseTrait;
use App\Mail\DocumentSubmitted;
use App\Models\Admin;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Mail\RegisterMail;
use League\HTMLToMarkdown\HtmlConverter;
use App\Models\EmailTemplates;
use Illuminate\Support\Facades\Blade;
use App\Models\Booking;
use Yajra\DataTables\Facades\DataTables;

class AuthController extends Controller
{
    use ResponseTrait;

    public function login()
    {
        return view('landing.auth.login');
    }

    public function register()
    {
        return view('landing.auth.register');
    }

//    public function loginAction(Request $request)
//    {
//        $validator = Validator::make($request->all(), [
//            'email' => 'required|email',
//            'password' => 'required'
//        ]);
//
//        if ($validator->fails()) {
//            return $this->sendValidationError($validator->errors());
//        }
//
//        $rememberToken = $request->has('remember_me') ? true : false;
//
//        if (Auth::guard('web')->attempt(['email' => $request->input('email'), 'password' => $request->input('password')], $rememberToken) || ) {
//            return $this->sendSuccess('login successful');
//        } else {
//            return $this->sendError('Invalid email or password');
//        }
//    }

    public function userBookings(Request $request)
    {
        try {
            $userId = auth()->guard('web')->id();
            $response = Booking::with(['user', 'car'])->where('user_id', $userId)->latest();

            return DataTables::eloquent($response)
                ->addIndexColumn()
                ->filterColumn('name', function ($query, $keyword) {
                    $query->whereHas('user', function ($q) use ($keyword) {
                        $q->whereRaw("CONCAT_WS(' ', first_name, last_name) LIKE ?", ["%{$keyword}%"]);
                    });
                })
                ->addColumn('email', fn($row) => $row->user->email ?? '-')
                ->addColumn('name', fn($row) => trim(($row->user->first_name ?? '') . ' ' . ($row->user->last_name ?? '')))
                ->addColumn('price', fn($row) => number_format($row->price, 2))
                ->addColumn('start_date', fn($row) => optional($row->start_date)->format('d - M- Y'))
                ->addColumn('end_date', fn($row) => optional($row->end_date)->format('d - M- Y'))
                ->addColumn('status', fn($row) => $row->status?->label() ?? '')
                ->addColumn('action', function ($row) {
                    return '<a href="' . route('profile.booking.view', $row->booking_id) . '" class="btn btn-sm btn-primary">View</a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function viewBooking(Request $request)
    {
        $booking_id=$request->booking_id;
        $booking=Booking::with(['user','car'])->where('booking_id' ,$booking_id)->first();
        return view('landing.auth.view-bookings',compact('booking'));
    }

    public function loginAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except('password'));
        }

        $rememberToken = $request->has('remember');

        // Find user by email
        $user = User::where('email', $request->input('email'))->first();

        if (!$user) {
            return redirect()->back()
                ->withErrors(['email' => 'No account found with that email address.'])
                ->withInput($request->except('password'));
        }

        if (is_null($user->password_set_at)) {
            return redirect()->back()
                ->withErrors(['email' => 'Please set your password using the reset link sent to your email.'])
                ->withInput($request->except('password'));
        }

        if (Auth::guard('web')->attempt(
            ['email' => $request->input('email'), 'password' => $request->input('password')],
            $rememberToken
        )) {
            $request->session()->regenerate();
            return redirect()->route('profile.settings')->with('success', 'Login successful!');
        }

        return redirect()->back()
            ->withErrors(['email' => 'Invalid email or password.'])
            ->withInput($request->except('password'));
    }

    public function registerAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', Rule::unique('users', 'email')->whereNull('deleted_at')],
            'password' => 'required|confirmed',
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required',
            'address' => 'nullable',
            'country' => 'nullable',
            'g-recaptcha-response' => 'required'
        ], [
            'g-recaptcha-response.required' => 'Please complete the reCAPTCHA verification'
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        // Verify reCAPTCHA
        $recaptcha = $request->input('g-recaptcha-response');
        $secret_key = env('CAPTCHA_SECRET_KEY');
        $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . $secret_key . '&response=' . $recaptcha;

        $response = file_get_contents($url);
        $response = json_decode($response);

        if (!$response->success) {
            return $this->sendError('reCAPTCHA verification failed. Please try again.');
        }

        try {
            DB::beginTransaction();

            $user = new User();
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->mobile = $request->phone;
            $user->password = Hash::make($request->password);
            $user->email = $request->email;
            $user->address = $request->address ?? null;
            $user->country = $request->country ?? null;
            $user->save();

            DB::commit();
            if (sendDynamicEmail($user->email, 'RegisterMail', [
                'fname' => $user->first_name,
                'lname' => $user->last_name,
                'email' => $user->email,
                'mobile' => $user->mobile,
            ])) {
                return $this->sendSuccess('Register Successfully!');
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->sendError($exception->getMessage());
        }
    }

    public function profile()
    {

        $user = User::with('userDetail')->find(Auth::guard('web')->user()->id);
        return view('landing.auth.profile', compact('user'));
    }

    public function profileUpdate(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required',
            'email' => ['required', 'email', Rule::unique('users', 'email')->whereNull('deleted_at')->ignore(Auth::guard()->user()->id)],
            'address' => 'nullable',
            'country' => 'nullable',
            'date_of_birth' => 'required',

        ],
            [

                'first_name.required' => 'Please enter your first name.',
                'last_name.required' => 'Please enter your last name.',
                'phone.required' => 'Please enter your phone number.',
                'email.required' => 'Please enter your email address.',
                'email.email' => 'Please enter a valid email address.',
                'email.unique' => 'This email address is already in use.',
                'date_of_birth.required' => 'Please select your date of birth.',

            ]
        );

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        try {
            DB::beginTransaction();

            $user = User::find(Auth::guard('web')->user()->id);
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->mobile = $request->phone;
            $user->email = $request->email;
            if ($request->filled('date_of_birth')) {
                try {
                    $user->date_of_birth = \Carbon\Carbon::createFromFormat('d - M- Y', $request->date_of_birth)->format('Y-m-d');
                } catch (\Exception $e) {
                    // Fallback to plain date if format is different (unlikely)
                    $user->date_of_birth = $request->date_of_birth;
                }
            } else {
                $user->date_of_birth = null;
            }

            $user->address = $request->address ?? null;
            $user->country = $request->country ?? null;


            if ($request->hasFile('profile')) {
                $fileName = uploadFile($request->profile, USER_PROFILE_IMAGE_PATH, 'user_');
                $user->profile_img = $fileName;
            }

            $user->save();

            DB::commit();

            return $this->sendSuccess('Update Successfully!');
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->sendError($exception->getMessage());
        }
    }

    public function profileDocumentUpdate(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'regular_license_number' => ['nullable', 'regex:/^[A-Z0-9\s-]{5,20}$/i'],
            'idp_number' => ['nullable', 'regex:/^[A-Z0-9\s-]{5,20}$/i'],
            'passport' => ['nullable', 'file'],
            'passport_number' => ['nullable', 'regex:/^[A-Z0-9\s-]{5,20}$/i'], // optional

            'international_lic' => ['nullable', 'file'],
            'regular_lic' => ['nullable', 'file'],
        ], [

            //  Regular License
            'passport_number.required' => 'Please enter your Passport Number.',
            'passport_number.regex' => 'Passport Number must contain only letters, numbers, spaces, and dashes (5–20 characters).',


            //  Regular License
            'regular_license_number.required' => 'Please enter your Regular License Number.',
            'regular_license_number.regex' => 'Regular License Number must contain only letters, numbers, spaces, and dashes (5–20 characters).',


            'idp_number.required' => 'Please enter your International License (IDP) Number.',
            'idp_number.regex' => 'International License (IDP) Number must contain only letters, numbers, spaces, and dashes (5–20 characters).',

            'passport.file' => 'Passport must be a valid file.',

            //  International License Upload
            'international_lic.file' => 'International License must be a valid file.',


            //  International Back License Upload
            'international_lic_back.file' => 'International Back License must be a valid file.',


            //  Regular License Upload
            'regular_lic.file' => 'Regular License must be a valid file.',

            //  Regular Back License Upload
            'regular_lic_back.file' => 'Regular Back License must be a valid file.',
        ]);
        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }
        try {
            DB::beginTransaction();
            $user = Auth::guard('web')->user();
            $userId = $user->id;

            $isUpdated = false;
            $updateData = [];
            if ($request->filled('regular_license_number')) {
                $updateData['regular_lic_number'] = $request->regular_license_number;
                $isUpdated = true;
            }
            if ($request->filled('idp_number')) {
                $updateData['idp_number'] = $request->idp_number;
                $isUpdated = true;
            }


            if ($request->filled('passport_number')) {
                $updateData['passport_number'] = $request->passport_number;
                $isUpdated = true;
            }
            if ($request->hasFile('passport')) {
                $updateData['passport'] = uploadFile($request->passport, USER_DOCUMENT_PATH, 'passport_');
                $isUpdated = true;
            }
            if ($request->hasFile('international_lic')) {
                $updateData['international_lic'] = uploadFile($request->international_lic, USER_DOCUMENT_PATH, 'international_lic_');
                $isUpdated = true;
            }
            if ($request->hasFile('regular_lic')) {
                $updateData['regular_lic'] = uploadFile($request->regular_lic, USER_DOCUMENT_PATH, 'regular_lic_');
                $isUpdated = true;
            }

            if ($request->hasFile('international_lic_back')) {
                $updateData['international_lic_back'] = uploadFile($request->international_lic_back, USER_DOCUMENT_PATH, 'international_lic_back_');
                $isUpdated = true;
            }
            if ($request->hasFile('regular_lic_back')) {
                $updateData['regular_lic_back'] = uploadFile($request->regular_lic_back, USER_DOCUMENT_PATH, 'regular_lic_back_');
                $isUpdated = true;
            }

            if (!empty($updateData)) {
                $user->userDetail()->updateOrCreate(['user_id' => $userId], $updateData);
            }

            if ($isUpdated) {
                $user->userDetail()->update(['status' => DocumentStatus::PENDING]);
                sendDynamicEmail(env('RECEIVER_MAIL'), 'AdminDocumentNotificationMail', [
                    'name' => $user->first_name . ' ' . $user->last_name,
                    'email' => $user->email,
                    'admin_url' => url('admin/user/' . $userId),
                ]);
            }

            DB::commit();
            return $this->sendSuccess('Documents updated successfully!');
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->sendError('Error updating documents: ' . $exception->getMessage());
        }

    }

    public function profileChangePassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'old_password' => 'required',
                'new_password' => 'required',
                'confirm_password' => 'required',
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            if ($request->old_password == $request->new_password) {
                return $this->sendError("Your current password and new password are same");
            }

            if (Hash::check($request->old_password, Auth::guard('web')->user()->password)) {
                Auth::guard('web')->user()->update(['password' => Hash::make($request->new_password)]);
                return $this->sendSuccess("Password updated successful!");
            } else {
                return $this->sendError("Current password is wrong!");
            }

        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), 500);
        }
    }
    public function backToAdmin()
    {
        if (!session()->has('admin_id')) {
            abort(403);
        }

        $adminId = session('admin_id');

        $admin = Admin::find($adminId);

        if (!$admin) {
            abort(403);
        }

        // Logout user guard
        Auth::guard('web')->logout();

        // Login admin guard
        Auth::guard('admin')->login($admin);

        // Remove session
        session()->forget('admin_id');

        return redirect()->route('admin.dashboard');
    }
    public function logout()
    {
        Auth::guard('web')->logout();
        return redirect()->route('login');
    }
}
