<?php

namespace App\Http\Controllers\ForgotPassword;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PasswordReset;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Http\Traits\ResponseTrait;
use App\Models\User;
class ResetPasswordController extends Controller
{
    use ResponseTrait;
    public function showResetForm(Request $request,$token)
    {

        return view('landing.auth.forgot_password.reset-password', [
            'token' => $token,'email' => $request->query('email'),

        ]);
    }
    public function resetPassword(Request $request)

    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8',
        ]);

        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->password_set_at = now();
        $user->save();

        PasswordReset::where('email', $request->email)->delete();
        return $this->sendSuccess(__('Password reset successfully'));
    }

}
