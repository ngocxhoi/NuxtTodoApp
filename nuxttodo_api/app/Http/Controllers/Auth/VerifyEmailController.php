<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(Request $request, $id, $hash): RedirectResponse
    {
        $user = $request->user() ?? User::findOrFail($id);

        // Kiểm tra chữ ký URL
        if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return redirect()->route('login')->withErrors(['email' => 'Verification link is invalid.']);
        }

        // Kiểm tra xem người dùng đã xác minh email chưa
        if ($user->hasVerifiedEmail()) {
            return redirect()->intended(
                config('app.frontend_url') . '/dashboard?verified=1'
            );
        }

        // Đánh dấu email là đã xác minh và kích hoạt sự kiện Verified
        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        $credentials = $user->only('email', 'password');
        Auth::attempt($credentials);

        // Chuyển hướng đến trang dashboard
        return redirect()->intended(
            config('app.frontend_url') . '/dashboard?verified=1'
        );
    }
}
