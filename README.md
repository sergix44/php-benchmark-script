# PHP Benchmark Script

A simple script that calculate execution time of common control flows
and function of the PHP. Helps you to compare performances of servers,
web hosting and also across PHP versions.

## Usage

From your SSH:

```sh
curl https://raw.githubusercontent.com/SergiX44/php-benchmark-script/master/bench.php | php
```

Or upload the file `bench.php` to your web document root and visit it.

### Additional tests

You must have the `bench.php` file to your server.
You can download additional benchmarks (must be in the same directory as `bench.php`) using:

```sh
# rand: random number generation
wget https://raw.githubusercontent.com/SergiX44/php-benchmark-script/master/rand.bench.php
```

Then you can run the benchmark using:

```sh
php bench.php
```

If the file is in the same directory as `bench.php`, it will be automatically loaded.

## Custom tests

You can create your own tests by creating a file in the same directory as `bench.php` and the file must be
named `*.bench.php`.

The file must return a closure, or an array of closures. Each closure should take a parameter `$multiplier` which is
how hard the test should be. The higher the `$multiplier`, the longer the test will take, by default it is `1`.
You should choose a reasonable number of iterations for your test (e.g. 1000) and multiply it by the `$multiplier`.

Example:

```php
// mytest.bench.php

return function ($multiplier = 1) {
    $iterations = 1000 * $multiplier;
    for ($i = 0; $i < $iterations; ++$i) {
        // do something
    }
};
```

Or with multiple tests:

```php
// mytest.bench.php

return [
    'my_test' => function ($multiplier = 1) {
        $iterations = 1000 * $multiplier;
        for ($i = 0; $i < $iterations; ++$i) {
            // do something
        }
    },
    'another_test' => function ($multiplier = 1) {
        $iterations = 1000 * $multiplier;
        for ($i = 0; $i < $iterations; ++$i) {
            // do something else
        }
    },
];
```

# Example Output

```sh
--------------------------------------------------
|    PHP BENCHMARK SCRIPT v.2.0 by @SergiX44     |
--------------------------------------------------
PHP........................................ 8.2.10
Platform................................... Darwin
Arch........................................ arm64
Server................................... hostname
Max memory usage............................. 512M
OPCache status............................ enabled
OPCache JIT............................... enabled
PCRE JIT.................................. enabled
XDebug extension......................... disabled
Difficulty multiplier.......................... 1x
Started at................ 06/12/2023 12:00:57.292
--------------------------------------------------
math..................................... 0.0835 s
loops.................................... 0.0126 s
ifelse................................... 0.0183 s
switch................................... 0.0185 s
string................................... 0.1936 s
array.................................... 0.3233 s
regex.................................... 0.1771 s
is_{type}................................ 0.0322 s
hash..................................... 0.1199 s
json..................................... 0.1521 s
--------------Additional Benchmarks---------------
rand::rand............................... 0.0090 s
rand::mt_rand............................ 0.0091 s
rand::random_int......................... 0.0680 s
rand::random_bytes....................... 0.2320 s
rand::openssl_random_pseudo_bytes........ 0.3018 s
--------------------------------------------------
Total time............................... 1.7510 s
Peak memory usage........................... 2 MiB
```

## Authors

- [@SergiX44](https://www.github.com/SergiX44)

## License

[MIT](https://choosealicense.com/licenses/mit/)
