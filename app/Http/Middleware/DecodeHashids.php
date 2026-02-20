<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DecodeHashids
{
    public function handle(Request $request, Closure $next): Response
    {
        foreach ($request->route()->parameters() as $key => $value) {
            if (is_string($value)) {
                $decoded = decode_hashid($value);

                if ($decoded !== null) {
                    $request->route()->setParameter($key, $decoded);
                }
            }
        }

        return $next($request);
    }
}
