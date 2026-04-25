<?php

namespace App\Http\Middleware\API\V1;

use App\Utilities\JwtUtility;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // Ambil token dari header
            $authHeader = $request->header('Authorization');

            if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
                return $this->unauthorized('Token tidak ditemukan');
            }

            $token = str_replace('Bearer ', '', $authHeader);

            // Decode token
            $payload = JwtUtility::decode($token);

            // Validasi type token
            if (!JwtUtility::isAccessToken($payload)) {
                return $this->unauthorized('Token bukan access token');
            }

            // Ambil user berdasarkan NIK
            $user = \App\Models\User::find($payload->sub);

            if (!$user) {
                return $this->unauthorized('User tidak ditemukan');
            }

            if ($payload->ver !== $user->token_version) {
                return $this->unauthorized('Token sudah tidak valid');
            }

            // Inject user ke request (biar bisa dipakai di controller)
            $request->attributes->set('auth_user', $user);
            Auth::setUser($user);

            return $next($request);
        } catch (\Firebase\JWT\ExpiredException $e) {
            return $this->unauthorized('Token expired');
        } catch (\Exception $e) {
            return $this->unauthorized('Token tidak valid');
        }
    }

    private function unauthorized($message)
    {
        return response()->json([
            'message' => $message
        ], 401);
    }
}
