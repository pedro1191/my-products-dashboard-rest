<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        // User input validation
        $this->validate($request, [
            'email' => ['required', 'email', 'exists:users,email'],
            'password' => ['required'],
        ]);

        $credentials = $request->only(['email', 'password']);

        if (! $token = Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'status_code' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'errors' => [
                    'password' => ['Your password was incorrect.']
                ]
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        \Log::info('User ' . Auth::user()->id . ' is logging in...');

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(Auth::user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        \Log::info('User ' . Auth::user()->id . ' is logging out...');

        Auth::logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        \Log::info('User ' . Auth::user()->id . ' is refreshing his/her token...');

        return $this->respondWithToken(Auth::refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60
        ]);
    }
}
