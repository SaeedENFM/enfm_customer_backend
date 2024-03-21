<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;

class CheckIsActive
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
        
        if (Auth::check())
        {
            
            $parameters = $this->checkParameters($request);
            $request->replace($parameters);
            // dd($request);
            // dd(Auth::User()); 
            if (Auth::User()->Locks == 1 || Auth::user()->ClearSession == 1)
            {
                // Auth::logout();
                return abort(403,"User Blocked");
            }
        }
        return $next($request); 
    }

    function checkParameters(Request $request) {
        $parameters = $request->all();
        $keys = array_keys($parameters); 
        foreach ($keys as $index => $key) { 
            $parameters[$key] = $this->clean($parameters[$key]);
        } 
        return $parameters;
    }

    function clean($string) {
        $string = str_replace(' ', ' ', $string); // Replaces all spaces with hyphens.
     
        return trim(preg_replace('/[^A-Za-z0-9]/', ' ', $string)); // Removes special chars.
    }
    

}
