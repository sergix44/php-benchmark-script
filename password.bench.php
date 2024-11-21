<?php
// Save this file as password.bench.php in the same directory as bench.php

return [
    'bcrypt' => function ($multiplier = 1, $count = 50) {
        $password = 'MySecurePassword123!@#';
        $count = (int)($count * $multiplier);
        $costs = [4, 5, 7, 10, 12]; // Different cost values to test

        foreach ($costs as $cost) {
            $options = [
                'cost' => $cost
            ];

            $startTime = microtime(true);
            for ($i = 0; $i < $count; $i++) {
                password_hash($password, PASSWORD_BCRYPT, $options);
            }
            $endTime = microtime(true);
            $duration = $endTime - $startTime;

            extraStat("bcrypt(cost=$cost)", number_format($duration, 4) . ' s');
        }

        return 0;
    },

    'argon2' => function ($multiplier = 1, $count = 50) {
        if (!defined('PASSWORD_ARGON2I')) {
            return INF; // Skip if Argon2 is not available
        }

        $password = 'MySecurePassword123!@#';
        $count = (int)($count * $multiplier);

        // Test different memory and time costs
        $configs = [
            ['memory_cost' => 512, 'time_cost' => 1],
            ['memory_cost' => 1024, 'time_cost' => 2],
            ['memory_cost' => 2048, 'time_cost' => 3],
        ];

        foreach ($configs as $config) {
            $options = [
                'memory_cost' => $config['memory_cost'],
                'time_cost' => $config['time_cost'],
                'threads' => 2
            ];

            $startTime = microtime(true);
            for ($i = 0; $i < $count; $i++) {
                password_hash($password, PASSWORD_ARGON2I, $options);
            }
            $endTime = microtime(true);
            $duration = $endTime - $startTime;

            extraStat(
                "argon2(mem={$config['memory_cost']}KB,time={$config['time_cost']})",
                number_format($duration, 4) . ' s'
            );
        }

        return 0;
    },

    'argon2id' => function ($multiplier = 1, $count = 50) {
        if (!defined('PASSWORD_ARGON2ID')) {
            return INF; // Skip if Argon2id is not available
        }

        $password = 'MySecurePassword123!@#';
        $count = (int)($count * $multiplier);

        // Test different memory and time costs
        $configs = [
            ['memory_cost' => 512, 'time_cost' => 1],
            ['memory_cost' => 1024, 'time_cost' => 2],
            ['memory_cost' => 2048, 'time_cost' => 3],
        ];

        foreach ($configs as $config) {
            $options = [
                'memory_cost' => $config['memory_cost'],
                'time_cost' => $config['time_cost'],
                'threads' => 2
            ];

            $startTime = microtime(true);
            for ($i = 0; $i < $count; $i++) {
                password_hash($password, PASSWORD_ARGON2ID, $options);
            }
            $endTime = microtime(true);
            $duration = $endTime - $startTime;

            extraStat(
                "argon2id(mem={$config['memory_cost']}KB,time={$config['time_cost']})",
                number_format($duration, 4) . ' s'
            );
        }

        return 0;
    },

    'verify' => function ($multiplier = 1, $count = 100) {
        $password = 'MySecurePassword123!@#';
        $count = (int)($count * $multiplier);

        // Create hashes using different algorithms
        $hashes = [
            'bcrypt' => password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]),
        ];

        if (defined('PASSWORD_ARGON2I')) {
            $hashes['argon2'] = password_hash($password, PASSWORD_ARGON2I);
        }

        if (defined('PASSWORD_ARGON2ID')) {
            $hashes['argon2id'] = password_hash($password, PASSWORD_ARGON2ID);
        }

        // Benchmark verification
        foreach ($hashes as $algo => $hash) {
            $startTime = microtime(true);
            for ($i = 0; $i < $count; $i++) {
                password_verify($password, $hash);
            }
            $endTime = microtime(true);
            $duration = $endTime - $startTime;

            extraStat("verify($algo)", number_format($duration, 4) . ' s');
        }

        return 0;
    }
];