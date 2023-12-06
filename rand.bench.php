<?php

/** @var array<string, callable> */
return [
    'rand' => function($multiplier = 1, $count = 1000000) {
        $count = $count * $multiplier;
        for ($i = 0; $i < $count; $i++) {
            rand(0, $i);
        }
        return $i;
    },
    'mt_rand' => function($multiplier = 1, $count = 1000000) {
        $count = $count * $multiplier;
        for ($i = 0; $i < $count; $i++) {
            mt_rand(0, $i);
        }
        return $i;
    },
    'random_int' => function($multiplier = 1, $count = 1000000) {
        if (!function_exists('random_int')) {
            return INF;
        }

        $count = $count * $multiplier;
        for ($i = 0; $i < $count; $i++) {
            random_int(0, $i);
        }
        return $i;
    },
    'random_bytes' => function($multiplier = 1, $count = 1000000) {
        if (!function_exists('random_bytes')) {
            return INF;
        }

        $count = $count * $multiplier;
        for ($i = 0; $i < $count; $i++) {
            random_bytes(32);
        }
        return $i;
    },
    'openssl_random_pseudo_bytes' => function($multiplier = 1, $count = 1000000) {
        if (!function_exists('openssl_random_pseudo_bytes')) {
            return INF;
        }

        $count = $count * $multiplier;
        for ($i = 0; $i < $count; $i++) {
            openssl_random_pseudo_bytes(32);
        }
        return $i;
    },
];
