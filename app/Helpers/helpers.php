<?php

use Vinkla\Hashids\Facades\Hashids;

if (!function_exists('decode_hashid')) {
    function decode_hashid(string $value): ?int
    {
        $decoded = Hashids::decode($value);

        return !empty($decoded) ? $decoded[0] : null;
    }
}
