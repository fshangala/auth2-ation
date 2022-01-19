<?php

namespace Fshangala\Auth2Ation\Http\Middleware;

use Closure;

class Errors
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        
        $statusCode = $response->getStatusCode();
        if($statusCode != 200){
            $res['success'] = false;
        } else {
            $res['success'] = true;
        }
        $res['message'] = $response->statusText();
        $res["data"] = $response->getOriginalContent();
        return response()->json($res,$statusCode);
    }
}
