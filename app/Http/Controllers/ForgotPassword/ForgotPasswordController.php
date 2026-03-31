<?php

namespace App\Http\Controllers\ForgotPassword;
use App\Http\Traits\ResponseTrait;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendForgotPasswordMail;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\PasswordReset;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\EmailTemplates;
use League\HTMLToMarkdown\HtmlConverter;
class ForgotPasswordController extends Controller
{
    use ResponseTrait;
    public function showLinkRequestForm()
    {

        return view('landing.auth.forgot_password.forgot-password');
    }

    public function sendResetLink(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        try {
            DB::beginTransaction();

            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return $this->sendError(__('Email not registered. Please register first.'));
            }

            $existing = PasswordReset::where('email', $request->email)->first();
            $token = Str::random(64);

            if (!$existing) {

                PasswordReset::create([
                    'email' => $request->email,
                    'token' => $token,
                    'created_at' => now(),
                ]);
            } else {
                $isExpired = Carbon::parse($existing->created_at)->addMinutes(1)->isPast();

                if ($isExpired) {
                    // Generate new token and update
                    $token = Str::random(64);
                    $existing->update([
                        'token' => $token,
                        'created_at' => now(),
                    ]);
                } else {
                    // Reuse existing valid token
                    $token = $existing->token;
                }
            }

            // Build reset URL
            $resetUrl = url('/reset-password/' . $token . '?email=' . urlencode($request->email));

            // Send the reset email
            sendDynamicEmail($user->email, 'ForgotPasswordMail', [
                'name' => $user->first_name,
                'reset_url' => $resetUrl,
            ]);

            DB::commit();

            return $this->sendSuccess(__('Password reset link sent. Please check your email.'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->sendError($exception->getMessage());
        }
    }

}
