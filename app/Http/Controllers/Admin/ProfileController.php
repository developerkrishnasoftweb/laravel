<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Exception;

class ProfileController extends Controller {
    /**
     * Render profile page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    function index(Request $request) {
        $profile = Auth::user();
        return view('admin.pages.profile', ['profile' => $profile]);
    }

    /**
     * Update user profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    function update(Request $request) {
        try {
            $request->validate([
                'name' => 'required',
                'email' => 'required|email'
            ]);
            $user = User::findOrFail(Auth::id());
            $user->name = $request->name;
            $user->email = $request->email;
            if($request->hasFile('profile_image')) {
                // Old file path
                $oldFile = $user->profile_image;
                // Generate new unique file name
                $newFileName = 'user-'.rand(1000000,9999999).'-'.$request->file('profile_image')->getClientOriginalName();
                // Store file
                $user->profile_image = $request->file('profile_image')->storeAs('images/user', $newFileName, 'public');
                // Delete old file from server
                if($user->profile_image) {
                    Storage::disk('public')->delete($oldFile);
                }
            }
            $user->save();
            return back()->with('success', 'Profile updated successfully.');
        } catch(Exception $e) {
            return back()->with('error', 'Profile not updated.');
        }
    }

    /**
     * Change user password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    function changePassword(Request $request) {
        try {
            $request->validate([
                'old_password' => 'required',
                'password' => 'required|min:6',
                'confirm_password' => 'required|same:password|min:6',
            ]);
            // Verify old password
            if(Hash::check($request->old_password, $request->user()->password)) {
                $user = User::findOrFail(Auth::id());
                $user->password = Hash::make($request->password);
                $user->save();
                return back()->with('success', 'Password updated successfully');
            }
            return back()->with('error', 'Your old password does not matched');
        } catch(Execption $e) {
            return back()->with('error', 'Password not updated');
        }
    }
}
