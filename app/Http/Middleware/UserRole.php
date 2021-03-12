<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Config;
use App\Helper\Helper;

class UserRole
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
        if(!Helper::isProjectAdmin()) {  
           return Helper::apiErrorResponse(trans('responses.unauthorize'), config::get('constant.header_code.unauthorize'));
        }
        return $next($request);
    }
}
