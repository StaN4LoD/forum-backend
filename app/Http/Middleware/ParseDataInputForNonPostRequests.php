<?php

namespace App\Http\Middleware;

use Closure;
use App\Utilities\ParseInputStream;
class ParseDataInputForNonPostRequests
{
    public function handle($request, Closure $next)
    {
        if ($request->method() == 'POST' OR $request->method() == 'GET') {
            return $next($request);
        }

        $params = array();
        new ParseInputStream($params);
        $request->request->add($params);

        return $next($request);
    }
}
