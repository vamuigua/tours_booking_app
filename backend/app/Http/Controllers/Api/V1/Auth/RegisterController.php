<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

class RegisterController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Request from the frontend web app (session is managed by cookies)
        if (EnsureFrontendRequestsAreStateful::fromFrontend($request)) {
            Auth::guard('web')->login($user, true);

            $request->session()->regenerate();

            return response()->noContent();
        } else {
            // Request from API clients (mobile app or third-party service)
            $device = substr($request->userAgent() ?? '', 0, 255);

            $expiresAt = now()->addMinutes(config('session.lifetime'));

            $token = $user->createToken($device, expiresAt: $expiresAt)->plainTextToken;

            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'expires_in' => $request->remember ? null : config('session.lifetime') * 60, // in seconds
            ], Response::HTTP_CREATED);
        }
    }
}
