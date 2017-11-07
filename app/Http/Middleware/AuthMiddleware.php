<?php

namespace App\Http\Middleware;

use Closure;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $request->isAuthed = FALSE;

        $token = $request->header('X-Request-Token');
        if (!empty($token)) {
          $request->player = \App\Player::where('api_key', $token)->first();

          if ($request->player) {
            $request->isAuthed = TRUE;
          }
        }

        return $next($request);
    }
}
