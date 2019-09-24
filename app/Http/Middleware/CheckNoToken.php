<?php

namespace App\Http\Middleware;

use Closure;
use App\Token;

class CheckNoToken
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
            if (Token::where('token', $request->headers->get('authorization'))->count() != 0)
            {
                return redirect('/');
            }
        }   
        return $next($request);
    }
}
