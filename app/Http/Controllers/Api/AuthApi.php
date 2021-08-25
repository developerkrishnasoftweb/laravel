<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class AuthApi extends Controller {
    /**
     * Authenticate user and generate access token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if($validator->fails()) {
                return response()->json([
                    'statusCode' => 400,
                    'data' => [],
                    'message' => $validator->errors(),
                ], 400);
            }

            // Authenticate user credentials
            $user = User::where('email', $request->email)->first();
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'statusCode' => 401,
                    'data' => [],
                    'message' => 'Invalid email or password',
                ], 401);
            }
            $token = $user->createToken($request->ip())->plainTextToken;
            return response()->json([
                'statusCode' => 200,
                'data' => [
                    'token' => $token
                ],
                'message' => 'Authenticated successfully'
            ]);
        } catch(Excepton $e) {
            return response()->json([
                'statusCode' => 500,
                'data' => [],
                'message' => 'Internal Server Error'
            ], 500);
        }
    }

    /**
     * Register new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function signup(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6',
            ]);

            if($validator->fails()) {
                return response()->json([
                    'statusCode' => 400,
                    'data' => [],
                    'message' => $validator->errors(),
                ], 400);
            }
            // Register new user
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            if($request->hasFile('profile_image')) {
                // Old file path
                $oldFile = $user->profile_image;
                // Generate new unique file name
                $newFileName = 'user-'.rand(1000000, 9999999).'-'.$request->file('profile_image')->getClientOriginalName();
                // Store file
                $user->profile_image = $request->file('profile_image')->storeAs('images/user', $newFileName, 'public');
                // Delete old file from server
                if($user->profile_image) {
                    Storage::disk('public')->delete($oldFile);
                }
            }
            $user->save();
            // Attach user roles
            $user->roles()->attach(Role::where('role', 'user')->pluck('id')->toArray());
            return response()->json([
                'statusCode' => 200,
                'data' => $user,
                'message' => 'Registered successfully'
            ]);
        } catch(Excepton $e) {
            return response()->json([
                'statusCode' => 500,
                'data' => [],
                'message' => 'Internal Server Error'
            ], 500);
        }
    }

    /**
     * Logout current user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request) {
        try {
            $request->user()->currentAccessToken()->delete();
            return response()->json([
                'statusCode' => 200,
                'data' => [],
                'message' => 'Logout successfully',
            ]);
        } catch(Exception $e) {
            return response()->json([
                'statusCode' => 500,
                'data' => [],
                'message' => 'Internal Server Error'
            ], 500);
        }
    }
}
