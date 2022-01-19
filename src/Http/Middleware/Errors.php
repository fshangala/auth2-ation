<?php

namespace Fshangala\Auth2Ation\Http\Middleware;

use Closure;

class Errors
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        
        $res['success'] = false;
        $res['message'] = $response->statusText();
        $res["data"] = $response->getOriginalContent();
        return response()->json($res,$response->getStatusCode());
    }
}
