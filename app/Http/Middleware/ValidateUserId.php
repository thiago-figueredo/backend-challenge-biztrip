<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ValidateUserId
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        ["id" => $id] = $request->all();
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "http://localhost:8000/api/users/$id");
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
        // return $next($request);
    }
}
