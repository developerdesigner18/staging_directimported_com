<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Traits\ResponseTrait;
use Illuminate\Support\Facades\Auth;
class CheckProfileComplete
{
    use ResponseTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (Auth::check()) {
            $user = Auth::user();

            // Check agar profile incomplete hai
            if (empty($user->mobile) || empty($user->address) || empty($user->country) || empty($user->userDetail->passport ) || empty($user->userDetail->international_lic) || empty($user->userDetail->regular_lic) ) {

                // Agar normal web request hai (browser)
                if (! $request->is('profile*')) {
                    return redirect()->route('profile.settings')
                        ->with('warning', 'Please complete your profile first.');
                }
            }
        }

        return $next($request);
    }
}
