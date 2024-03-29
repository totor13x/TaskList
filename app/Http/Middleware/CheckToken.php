<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Token;

class CheckToken
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
        if ($request->headers->get('authorization') !== null) 
        {
            if (Token::where('token', $request->headers->get('authorization'))->count() == 0)
            {
                return redirect('/auth');
            }
        }   
        return $next($request);
    }
}
