<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
}
