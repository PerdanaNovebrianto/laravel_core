<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Vinkla\Hashids\Facades\Hashids;

class DecodeHashids
{
    public function handle(Request $request, Closure $next): Response
    {
        foreach ($request->route()->parameters() as $key => $value) {
            if (is_string($value)) {
                $decoded = Hashids::decode($value);

                if (!empty($decoded)) {
                    $request->route()->setParameter($key, $decoded[0]);
                }
            }
        }

        return $next($request);
    }
}
