<?php

namespace Fshangala\Auth2Ation\Http\Middleware;
use Illuminate\Http\Response;

class ErrorHandler extends Response
{
    public function getStatusText(){
        return $this->statusText();
    }
}
