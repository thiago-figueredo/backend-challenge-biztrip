<?php

namespace App\Http\Middleware;

use App\Traits\ApiJsonResponse;
use App\Traits\UserJwtAuth;
use Illuminate\Http\Request;
use Closure;

class JwtMiddleware
{
    use ApiJsonResponse, UserJwtAuth;

    private function validateJwt(string $token)
    {
        $jwt_regex = "/^Bearer ((?:\.?(?:[A-Za-z0-9-_]+)){3})$/m";
        return preg_match_all($jwt_regex, $token);
    }

    public function handle(Request $request, Closure $next)
    {
        $token = $request->header("Authorization");

        if (empty($token)) {
            return $this->unauthorized([
                "error" => true,
                "message" => "Authorization header is required"
            ]);
        }

        if (!$this->validateJwt($token)) {
            return $this->unauthorized([
                "error" => true,
                "message" => "Authorization token invalid format"
            ]);
        }

        $user = $this->getUserByToken(substr($token, 7));

        if ($user->isEmpty()) {
            return $this->unauthorized([
                "error" => true,
                "message" => "Authorization token expired"
            ]);
        }

        return $next($request);
    }
}
