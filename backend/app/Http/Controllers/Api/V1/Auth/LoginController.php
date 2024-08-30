<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

class LoginController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Request from the frontend web app (session is managed by cookies)
        if (EnsureFrontendRequestsAreStateful::fromFrontend($request)) {
            if (!Auth::guard('web')->attempt(request(['email', 'password']), request('remember'))) {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                ]);
            }

            $request->session()->regenerate();

            return response()->noContent();
        } else {
            // Request from API clients (mobile app or third-party service)
            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                ]);
            }

            $device = substr($request->userAgent() ?? '', 0, 255);

            $expiresAt = $request->remember ? null : now()->addMinutes(config('session.lifetime'));

            $token = $user->createToken($device, expiresAt: $expiresAt)->plainTextToken;

            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'expires_in' => $request->remember ? null : config('session.lifetime') * 60 // in seconds
            ], Response::HTTP_CREATED);
        }
    }
}
