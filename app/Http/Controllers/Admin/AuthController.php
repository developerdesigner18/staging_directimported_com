<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\ResponseTrait;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use ResponseTrait;

    public function login()
    {
        return view('admin.pages.login');
    }

    public function loginAsUser($id)
    {
        // Store admin ID
        session(['admin_id' => Auth::guard('admin')->id()]);

        // Logout admin
        Auth::guard('admin')->logout();

        // Get user
        $user = User::findOrFail($id);

        // Login as normal user (web guard)
        Auth::guard('web')->login($user);

        return redirect()->route('profile.settings');
        // or my.bookings
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

        $rememberToken = $request->has('remember_me') ? true : false;
        $credentials = ['email' => $request->input('email'), 'password' => $request->input('password')];

        if (Auth::guard('admin')->attempt($credentials, $rememberToken)) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard')->with('success', 'Login successful!');
        }

        if (Auth::guard('employee')->attempt($credentials, $rememberToken)) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard')->with('success', 'Login successful!');
        }

        return redirect()->back()
            ->withErrors(['email' => 'Invalid email or password.'])
            ->withInput($request->except('password'));
    }

    public function updateName(Request $request)
    {
        try {
            $user = Auth::guard('admin')->user();
            $dataToUpdate = ['name' => $request->name];

            if ($request->hasFile('profile_img')) {
                $fileName = uploadFile($request->file('profile_img'), ADMIN_PROFILE_IMAGE_PATH);
                $dataToUpdate['profile_img'] = $fileName;
                unlink(public_path(ADMIN_PROFILE_IMAGE_PATH) . basename(Auth::user()->profile_img));
            }

            if ($user->update($dataToUpdate)) {
                return $this->sendSuccess("Profile updated successfully");
            } else {
                return $this->sendError("Unable to update the profile");
            }

        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), 500);
        }
    }

    public function updatePassword(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'current_password' => 'required',
                'password' => 'required|confirmed',
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            if ($request->current_password == $request->password) {
                return $this->sendError("Your current password and new password are same");
            }

            if (Hash::check($request->current_password, Auth::guard('admin')->user()->password)) {
                Auth::guard('admin')->user()->update(['password' => Hash::make($request->password)]);
                return $this->sendSuccess("Password updated successful!");
            } else {
                return $this->sendError("Current password is wrong!");
            }

        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), 500);
        }
    }

    public function logout()
    {


        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
            return redirect()->route('admin.login');
        }

        if (Auth::guard('employee')->check()) {
            Auth::guard('employee')->logout();
            return redirect()->route('admin.login');
        }

        return redirect()->route('admin.login');

    }


}
