<?php

namespace App\Http\Controllers\Admin;

use App\Enum\DocumentStatus;
use App\Http\Controllers\Controller;
use App\Http\Traits\ResponseTrait;
use App\Models\Booking;
use App\Models\User;
use App\Models\UserDetail;
use App\Models\UserPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;
use App\Mail\Documentverification;

class UserController extends Controller
{
    use ResponseTrait;

    public function index()
    {
        // Fetch full Permission objects, not just key
        $visiblePermissions = UserPermission::all();
        return view('admin.user.index', compact('visiblePermissions'));
    }

    public function listUser(Request $request)
    {
        try {

            $response = User::with('bookings');

            return DataTables::eloquent($response)
                ->addIndexColumn()

                // ---------------- ID -------------------
                ->addColumn('id', function ($row) {
                    return $row->id;
                })
                ->orderColumn('id', function ($query, $order) {
                    $query->orderBy('users.id', $order);
                })

                // ---------------- IMAGE ----------------
                ->addColumn('image', function ($row) {

                    if (!$row->profile_img) {
                        return '<img src="' . asset('uploads/default/default.jpg') . '" width="50">';
                    }

                    return '
                    <a href="' . asset($row->profile_img) . '" target="_blank">
                        <img src="' . asset($row->profile_img) . '" width="50">
                    </a>';
                })

                // --------------- NAME -------------------
                ->addColumn('name', function ($row) {
                    $fname = $row->first_name ?? '';
                    $lname = $row->last_name ?? '';
                    $name = trim("$fname $lname");
                    return $name !== '' ? $name : '-';
                })
                ->orderColumn('name', function ($query, $order) {
                    $query->orderBy('first_name', $order)->orderBy('last_name', $order);
                })
                ->filterColumn('name', function ($query, $keyword) {
                    $query->where(function ($q) use ($keyword) {
                        $q->where('first_name', 'LIKE', "%{$keyword}%")
                            ->orWhere('last_name', 'LIKE', "%{$keyword}%")
                            ->orWhereRaw("CONCAT_WS(' ', first_name, last_name) LIKE ?", ["%{$keyword}%"]);
                    });
                })

                // --------------- EMAIL ------------------
                ->addColumn('email', function ($row) {
                    return $row->email ?? '-';
                })
                ->orderColumn('email', function ($query, $order) {
                    $query->orderBy('email', $order);
                })
                ->filterColumn('email', function ($query, $keyword) {
                    $query->where('email', 'LIKE', "%{$keyword}%");
                })

                // ------------- CREATED AT ---------------
                ->addColumn('created_at', function ($row) {
                    return $row->created_at
                        ? $row->created_at->format('d M Y')
                        : '-';
                })
                ->orderColumn('created_at', function ($query, $order) {
                    $query->orderBy('created_at', $order);
                })

                // ------------- ACTIONS ------------------
                ->addColumn('action', function ($row) {
                    return '<ul class="list-inline mb-0 d-flex justify-content-center text-center">
                        <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Edit">
                            <button type="button" onclick="editUser(' . $row->id . ', this)" class="btn btn-outline-info btn-icon waves-effect waves-light material-shadow-none">
                                <i class="ri-pencil-fill fs-16"></i>
                            </button>
                        </li>
                        <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Delete">
                            <button type="button" onclick="deleteUser(' . $row->id . ', this)" class="btn btn-outline-danger btn-icon waves-effect waves-light material-shadow-none">
                                <i class="ri-delete-bin-5-fill fs-16"></i>
                            </button>
                        </li>
                    </ul>';
                })

                ->rawColumns(['image', 'action'])
                ->make(true);

        } catch (\Exception $exception) {
            return $this->sendDataTableError(ERROR_500, [], 500);
        }
    }

    public function add(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'first_name' => ['required', 'string', 'max:191'],
                'last_name' => ['required', 'string', 'max:191'],
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:191',
                    Rule::unique('users', 'email')->whereNull('deleted_at')
                ],
                'mobile' => ['nullable', 'string', 'max:20'],
                'password' => ['required', 'string', 'min:8', 'max:191'],
            ], [
                'first_name.required' => 'The first name field is required.',
                'first_name.max' => 'The first name must not exceed 191 characters.',
                'last_name.required' => 'The last name field is required.',
                'last_name.max' => 'The last name must not exceed 191 characters.',
                'email.required' => 'The email field is required.',
                'email.email' => 'Please enter a valid email address.',
                'email.unique' => 'This email address is already in use.',
                'email.max' => 'The email must not exceed 191 characters.',
                'mobile.max' => 'The mobile number must not exceed 20 characters.',
                'password.required' => 'The password field is required.',
                'password.min' => 'The password must be at least 8 characters.',
                'password.max' => 'The password must not exceed 191 characters.',
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            $user = new User();
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->mobile = $request->mobile;
            $user->password = Hash::make($request->password);
            $user->password_set_at = now();
            $user->save();

            return $this->sendSuccess("User has been added successfully");
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), 500);
        }
    }

    public function edit(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => ['required', Rule::exists('users', 'id')->whereNull('deleted_at')],
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            $user = User::find($request->id);

            if ($user) {
                return $this->sendResponse("User details", [
                    'id' => $user->id,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                    'mobile' => $user->mobile,
                ]);
            }

            return $this->sendError("User not found");
        } catch (\Exception $exception) {
            return $this->sendError(ERROR_500, 500);
        }
    }

    public function update(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => ['required', Rule::exists('users', 'id')->whereNull('deleted_at')],
                'first_name' => ['required', 'string', 'max:191'],
                'last_name' => ['required', 'string', 'max:191'],
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:191',
                    Rule::unique('users', 'email')->whereNull('deleted_at')->ignore($request->id)
                ],
                'mobile' => ['nullable', 'string', 'max:20'],
            ], [
                'id.required' => 'User ID is required.',
                'id.exists' => 'Selected user does not exist.',
                'first_name.required' => 'The first name field is required.',
                'first_name.max' => 'The first name must not exceed 191 characters.',
                'last_name.required' => 'The last name field is required.',
                'last_name.max' => 'The last name must not exceed 191 characters.',
                'email.required' => 'The email field is required.',
                'email.email' => 'Please enter a valid email address.',
                'email.unique' => 'This email address is already in use.',
                'email.max' => 'The email must not exceed 191 characters.',
                'mobile.max' => 'The mobile number must not exceed 20 characters.',
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            $user = User::find($request->id);
            if (!$user) {
                return $this->sendError("User not found");
            }

            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->mobile = $request->mobile;
            $user->save();

            return $this->sendSuccess("User has been updated successfully");
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), 500);
        }
    }

    public function delete(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => ['required', Rule::exists('users', 'id')->whereNull('deleted_at')],
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            $user = User::find($request->id);
            if (!$user) {
                return $this->sendError("User not found");
            }

            $user->delete();

            return $this->sendSuccess("User has been removed successfully");
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), 500);
        }
    }
    //    function details(Request $request)
//    {
//        try {
//            $validator = Validator::make($request->all(), [
//                'id'  => ['required'],
//            ]);
//
//            if ($validator->fails()) {
//                return $this->sendValidationError($validator->errors());
//            }
//
//            $user_details = UserDetail::where(['user_id'=>$request->id])->first();
//
//            if (!$user_details){
//                $html = '<div class="text-center"><h5>No Document Found!</h5></div>';
//                $btn = '';
//            }else{
//                $html = '<div class="row justify-content-center">
//                    <div class="col-md-6 col-lg-4 text-center remove-image-div mb-3">
//                        <label>Passport</label>
//                        <a href="'.$user_details->passport.'" target="_blank"><img src="'.$user_details->passport.'" width="100%"></a>
//                    </div>';
//                    $html .= '<div class="col-md-6 col-lg-4 text-center remove-image-div mb-3">
//                        <label>International lic</label>
//                        <a href="'.$user_details->international_lic.'" target="_blank"><img src="'.$user_details->international_lic.'" width="100%"></a>
//                    </div>';
//                    $html .= '<div class="col-md-6 col-lg-4 text-center remove-image-div mb-3">
//                        <label>Regular lic</label>
//                        <a href="'.$user_details->regular_lic.'" target="_blank"><img src="'.$user_details->regular_lic.'" width="100%"></a>
//                    </div>
//                </div>';
//                $btn = '';
//                if ($user_details->status->name != 'VERIFIED'){
//                    $btn .= '
//                    <div class="text-center">
//                        <button type="button" onclick="updateStatusVerified(' . $user_details->id . ', this)" class="btn btn-outline-success waves-effect waves-light material-shadow-none">
//                            Verified
//                        </button>
//                        <button type="button" class="btn btn-outline-danger waves-effect waves-light material-shadow-none detailsRejectBtn" data-id="'.$user_details->id.'" data-user_id="'.$user_details->user_id.'">
//                            Rejected
//                        </button>
//                    </div>';
//                }
//            }
//
//            return $this->sendSuccess(['html'=>$html, 'btn'=>$btn]);
//        } catch (\Exception $exception) {
//            return $this->sendError($exception->getMessage(), 500);
//        }
//    }


    public function details(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => ['required'],
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            $user_details = UserDetail::where(['user_id' => $request->id])->first();

            if (!$user_details) {
                $html = '<div class="text-center"><h5>No Document Found!</h5></div>';
                return $this->sendSuccess(['html' => $html, 'btn' => '']);
            }

            $html = '<div class="row justify-content-center">';

            $documents = [
                'passport' => 'Passport',
                'international_lic' => 'International License',
                'regular_lic' => 'Regular License',
                'regular_lic_back' => 'Regular License Back',
                'international_lic_back' => 'International License Back',
            ];

            foreach ($documents as $field => $label) {
                $status_field = $field . '_status';
                $doc_url = $user_details->$field;
                $status = $user_details->$status_field;
                $field_value = match ($field) {
                    'passport' => $user_details->passport_number,
                    'international_lic',
                    'international_lic_back' => $user_details->idp_number,
                    'regular_lic',
                    'regular_lic_back' => $user_details->regular_lic_number,
                    default => '',
                };

                $has_image = $user_details->getRawOriginal($field) ? 1 : 0;

                $html .= '<div class="col-md-6 col-lg-4 text-center mb-3">
                <label>' . $label . '</label>
               <a href="javascript:void(0);"
                   class="openPreview"
                   data-img="' . $doc_url . '"
                   data-docno="' . $field_value . '"
                   data-id="' . $user_details->id . '"
                   data-user_id="' . $user_details->user_id . '"
                   data-field="' . $field . '"
                   data-has-image="' . $has_image . '"
                   data-status="' . $status . '">
                    <img src="' . $doc_url . '"
                         style="height:200px;object-fit:cover;width:100%;cursor:pointer;" alt="document image ">
                </a>
                <div class="mt-2">';

                if ($user_details->getRawOriginal($field)) {
                    if ($status == 'VERIFIED') {
                        $html .= '
                        <button class="btn btn-success btn-sm" disabled>Verified</button>
                        <button type="button"
                            class="btn btn-sm btn-outline-danger detailsRejectBtn"
                            data-id="' . $user_details->id . '"
                            data-user_id="' . $user_details->user_id . '"
                            data-field="' . $field . '">
                            Reject
                        </button>';
                    } elseif ($status == 'REJECTED') {
                        $html .= '
                        <button class="btn btn-danger btn-sm" disabled>Rejected</button>
                        <button type="button" class="btn btn-outline-success btn-sm"
                            onclick="verifyDocument(' . $user_details->id . ', \'' . $field . '\', this)">
                            Verify
                        </button>';
                    } else {
                        $html .= '
                        <button type="button" class="btn btn-outline-success btn-sm"
                            onclick="verifyDocument(' . $user_details->id . ', \'' . $field . '\', this)">
                            Verify
                        </button>
                        <button type="button"
                            class="btn btn-sm btn-outline-danger detailsRejectBtn"
                            data-id="' . $user_details->id . '"
                            data-user_id="' . $user_details->user_id . '"
                            data-field="' . $field . '">
                            Reject
                        </button>';
                    }
                }

                $html .= '</div></div>';
            }

            $html .= '</div>';

            return $this->sendSuccess(['html' => $html, 'btn' => '']);

        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), 500);
        }
    }

    public function verifySingleDocument(Request $request)
    {

        $user = UserDetail::findOrFail($request->id);
        $column = $request->field . '_status';

        $user->$column = DocumentStatus::VERIFIED->value;
        $user->save();

        return $this->sendSuccess('Document verified successfully.');
    }
    function statusVerified(Request $request)
    {
        try {
            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'id' => 'required'
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            $user_details = UserDetail::find($request->id);
            $user_details->update(['status' => DocumentStatus::VERIFIED]);

            DB::commit();
            return $this->sendSuccess('User Details Verified successfully!');
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), 500);
        }
    }

    function statusRejected(Request $request)
    {

        try {
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'user_id' => 'required',
                'message' => 'required',
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            $user = User::find($request->user_id);
            sendDynamicEmail($user->email, 'DocumentRejectedMail', [
                'name' => $user->first_name,
                'reason' => $request->message,
            ]);

            DB::commit();
            return $this->sendSuccess('User Details Rejected successfully!');
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), 500);
        }
    }
    function rejectedSingleDocument(Request $request)
    {

        try {
            DB::beginTransaction();
            $userDetail = UserDetail::findOrFail($request->id);
            $user = User::findOrFail($userDetail->user_id);

            $column = $request->field . '_status';

            $userDetail->$column = DocumentStatus::REJECTED->value;
            $userDetail->save();
            sendDynamicEmail($user->email, 'DocumentRejectedMail', [
                'name' => $user->first_name,
                'reason' => $request->message,
            ]);

            DB::commit();
            return $this->sendSuccess('User Details Rejected successfully!');
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), 500);
        }
    }


}
