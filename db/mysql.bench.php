<?php

$initialRowCount = 1000;
$mysqli = null;

setup(function ($args) use (&$mysqli, $initialRowCount) {
    if (!extension_loaded('mysqli')) {
        print('The mysqli extension is not loaded' . PHP_EOL);
        return;
    }

    if ($args['mysql_host'] === null || $args['mysql_user'] === null || $args['mysql_password'] === null) {
        print('The --mysql_host=, --mysql_user=, and --mysql_password= arguments are required' . PHP_EOL);
        return;
    }

    $mysqli = new mysqli($args['mysql_host'], $args['mysql_user'], $args['mysql_password'], null,
        isset($args['mysql_port']) ? $args['mysql_port'] : 3306);

    if ($mysqli->connect_error) {
        printf("Mysql Connect Error (%s) %s\n", $mysqli->connect_errno, $mysqli->connect_error);
        return;
    }

    // drop database
    $mysqli->query("DROP DATABASE IF EXISTS `bench_test`");
    // create database
    $mysqli->query("CREATE DATABASE IF NOT EXISTS `bench_test`");
    $mysqli->select_db('bench_test');
    $mysqli->query("CREATE TABLE IF NOT EXISTS `bench_test`.`test` (id INT PRIMARY KEY AUTO_INCREMENT, name VARCHAR(255))");

    for ($i = 0; $i < $initialRowCount; $i++) {
        $values[] = "('test$i')";
    }
    $mysqli->query("INSERT INTO `bench_test`.`test` (name) VALUES " . implode(',', $values));
});

teardown(function () use (&$mysqli) {
    if ($mysqli === null) {
        return;
    }

    $mysqli->query("DROP DATABASE IF EXISTS `bench_test`");
    $mysqli->close();
});


return [
    'ping' => function () use (&$mysqli) {
        if ($mysqli === null) {
            return INF;
        }
        $mysqli->ping();
        return 1;
    },
    'select_version' => function ($multiplier = 1, $count = 1000) use (&$mysqli) {
        if ($mysqli === null) {
            return INF;
        }

        $count = $count * $multiplier;
        for ($i = 0; $i < $count; $i++) {
            $mysqli->query("SELECT VERSION()");
        }
        return $i;
    },
    'select_all' => function ($multiplier = 1, $count = 1000) use (&$mysqli) {
        if ($mysqli === null) {
            return INF;
        }

        $count = $count * $multiplier;
        for ($i = 0; $i < $count; $i++) {
            $mysqli->query("SELECT * FROM `bench_test`.`test`");
        }
        return $i;
    },
    'select_cursor' => function ($multiplier = 1, $count = 1000) use (&$mysqli) {
        if ($mysqli === null) {
            return INF;
        }

        $count = $count * $multiplier;
        for ($i = 0; $i < $count; $i++) {
            $result = $mysqli->query("SELECT * FROM `bench_test`.`test`");
            while ($row = $result->fetch_assoc()) {
            }
            $result->close();
        }
        return $i;
    },
    'seq_insert' => function ($multiplier = 1, $count = 1000) use (&$mysqli) {
        if ($mysqli === null) {
            return INF;
        }

        $count = $count * $multiplier;
        for ($i = 0; $i < $count; $i++) {
            $mysqli->query("INSERT INTO `bench_test`.`test` (name) VALUES ('test')");
        }
        return $i;
    },
    'bulk_insert' => function ($multiplier = 1, $count = 100000) use (&$mysqli) {
        if ($mysqli === null) {
            return INF;
        }

        $count = $count * $multiplier;
        $values = [];
        for ($i = 0; $i < $count; $i++) {
            $values[] = "('test$i')";
        }
        $mysqli->query("INSERT INTO `bench_test`.`test` (name) VALUES " . implode(',', $values));
        return $i;
    },
    'update' => function ($multiplier = 1, $count = 100) use (&$mysqli) {
        if ($mysqli === null) {
            return INF;
        }

        $count = $count * $multiplier;
        for ($i = 0; $i < $count; $i++) {
            $mysqli->query("UPDATE `bench_test`.`test` SET name = 'test' WHERE id % 2 = 0");
        }
        return $i;
    },
    'transaction_insert' => function ($multiplier = 1, $count = 1000) use (&$mysqli) {
        if ($mysqli === null) {
            return INF;
        }

        $count = $count * $multiplier;
        for ($i = 0; $i < $count; $i++) {
            $mysqli->begin_transaction();
            $mysqli->query("INSERT INTO `bench_test`.`test` (name) VALUES ('test')");
            $mysqli->commit();
        }
        return $i;
    },
    'aes_encrypt' => function ($multiplier = 1, $count = 10000) use (&$mysqli) {
        if ($mysqli === null) {
            return INF;
        }

        $stmt = $mysqli->prepare("SELECT AES_ENCRYPT(?, 'key')");
        $stmt->bind_param('s', $data);

        $data = str_repeat('a', 16);
        $count = $count * $multiplier;
        for ($i = 0; $i < $count; $i++) {
            $stmt->execute();
            $stmt->get_result()->fetch_assoc();
        }
        $stmt->close();
        return $i;
    },
    'aes_decrypt' => function ($multiplier = 1, $count = 10000) use (&$mysqli) {
        if ($mysqli === null) {
            return INF;
        }

        $stmt = $mysqli->prepare("SELECT AES_DECRYPT(?, 'key')");
        $stmt->bind_param('s', $data);

        $data = str_repeat('a', 16);
        $count = $count * $multiplier;
        for ($i = 0; $i < $count; $i++) {
            $stmt->execute();
            $stmt->get_result()->fetch_assoc();
        }
        $stmt->close();
        return $i;
    },
    'indexes' => function ($multiplier = 1, $count = 100) use (&$mysqli) {
        if ($mysqli === null) {
            return INF;
        }

        $mysqli->query("CREATE INDEX idx_name ON `bench_test`.`test` (name)");
        $mysqli->query("DROP INDEX idx_name ON `bench_test`.`test`");
        return 1;
    },
    'delete' => function ($multiplier = 1, $count = 100) use (&$mysqli) {
        if ($mysqli === null) {
            return INF;
        }

        $count = $count * $multiplier;
        for ($i = 0; $i < $count; $i++) {
            $mysqli->query("DELETE FROM `bench_test`.`test` WHERE id % 2 = 0");
        }
        return $i;
    },
];