<?php

namespace App\Http\Middleware;

use Closure;

class AdminValidation
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
        $role = auth()->user()['role'];
        try {
            if ($role === 'admin') {
                return $next($request);
            } else {
                throw new \Exception('Forbidden','403');
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'You don\'t have rule'],403);
        }
    }
}
