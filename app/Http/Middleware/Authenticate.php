<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            $apiRes = [];
            $apiRes['meta'] = [
                'code' => '401',
                'type' => 'fail',
                'message' => 'token invalid',
            ];

            abort(response()->json($apiRes, 401));
        }
    }
}
