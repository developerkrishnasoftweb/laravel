<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

class AuthController extends Controller {
    /**
     * Render login page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request) {
        return view('admin.pages.login');
    }

    /**
     * Render forgot password page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function forgotPassword(Request $request) {
        return view('admin.pages.forgot');
    }

    /**
     * Render reset password page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  String  $token
     * @return \Illuminate\Http\Response
     */
    public function resetPassword(Request $request, $token) {
        return view('admin.pages.reset-password', ['token' => $token]);
    }

    /**
     * Render confirm password page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function confirmPassword(Request $request) {
        return view('admin.pages.confirm-password');
    }

    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function authenticate(Request $request) {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if(Auth::attempt($credentials, $request->has('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'error' => 'Invalid email or password'
        ]);
    }

    /**
     * Send forgot password email
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sendForgotPasswordEmail(Request $request) {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if($status === Password::RESET_LINK_SENT) {
            return back()->with('success', __($status));
        }
        return back()->with('error', __($status));
    }

    /**
     * Change user password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function changePassword(Request $request) {
        $credentials = $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'confirm_password' => 'required|same:password|min:6',
        ]);

        $credentials = $request->only('email', 'password', 'password_confirmation', 'token');
        $status = Password::reset($credentials, function($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password)
            ])->setRememberToken(Str::random(60));
            $user->save();
            event(new PasswordReset($user));
        });

        if($status === Password::PASSWORD_RESET) {
            return redirect()->route('admin.login')->with('success', __($status));
        }
        return back()->with('error', __($status));
    }

    /**
     * Confirm user password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function verifyConfirmPassword(Request $request) {
        if(!Hash::check($request->password, $request->user()->password)) {
            return back()->withErrors(['password' => 'The provided password does not match our records.']);
        }
        $request->session()->passwordConfirmed();
        return redirect()->intended();
    }

    /**
     * Logout current user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request) {
        Auth::logout();
        return back();
    }
}
