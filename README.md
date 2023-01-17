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

# Example Output
```
--------------------------------------------------
|    PHP BENCHMARK SCRIPT v.1.0 by @SergiX44     |
--------------------------------------------------
PHP version................................. 8.2.1
Platform.................................... Linux
Server:.................................. hostname
OPCache status:........................... enabled
OPCache JIT:.............................. enabled
PCRE JIT:................................. enabled
Started at:............... 17/01/2023 15:22:51.079
--------------------------------------------------
math..................................... 0.1551 s
loops.................................... 0.0223 s
ifelse................................... 0.0305 s
switch................................... 0.0283 s
strings.................................. 0.4240 s
array.................................... 0.7684 s
regex.................................... 0.2929 s
is_{type}................................ 0.0611 s
--------------------------------------------------
Total time............................... 1.7828 s
Peak memory usage........................... 2 MiB
```

## Authors

- [@SergiX44](https://www.github.com/SergiX44)

## License

[MIT](https://choosealicense.com/licenses/mit/)
