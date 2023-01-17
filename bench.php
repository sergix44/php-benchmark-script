<?php

$V = '1.0';

/** @var array<string, callable> $benchmarks */
// the benchmarks!
$benchmarks = [
    'math' => function ($count = 200000) {
        $x = 0;
        for ($i = 0; $i < $count; $i++) {
            $x += $i + $i;
            $x += $i * $i;
            $x += $i ** $i;
            $x += $i / (($i + 1) * 2);
            $x += $i % (($i + 1) * 2);
            abs($i);
            acos($i);
            acosh($i);
            asin($i);
            asinh($i);
            atan2($i, $i);
            atan($i);
            atanh($i);
            ceil($i);
            cos($i);
            cosh($i);
            decbin($i);
            dechex($i);
            decoct($i);
            deg2rad($i);
            exp($i);
            expm1($i);
            floor($i);
            fmod($i, $i);
            hypot($i, $i);
            is_infinite($i);
            is_finite($i);
            is_nan($i);
            log10($i);
            log1p($i);
            log($i);
            pi();
            pow($i, $i);
            rad2deg($i);
            sin($i);
            sinh($i);
            sqrt($i);
            tan($i);
            tanh($i);
        }

        return $x;
    },
    'loops' => function ($count = 20000000) {
        for ($i = 0; $i < $count; ++$i) ;
        $i = 0;
        while ($i < $count) ++$i;
        return $i;
    },
    'ifelse' => function ($count = 10000000) {
        $a = 0;
        $b = 0;
        for ($i = 0; $i < $count; $i++) {
            $k = $i % 4;
            if ($k === 0) {
                $i;
            } elseif ($k === 1) {
                $a = $i;
            } elseif ($k === 2) {
                $b = $i;
            } else {

            }
        }
        return $a - $b;
    },
    'switch' => function ($count = 10000000) {
        $a = 0;
        $b = 0;
        for ($i = 0; $i < $count; $i++) {
            switch ($i % 4) {
                case 0:
                    $i;
                    break;
                case 1:
                    $a = $i;
                    break;
                case 2:
                    $b = $i;
                    break;
                default:
                    break;
            }
        }
        return $a - $b;
    },
    'strings' => function ($count = 50000) {
        $string = '<i>the</i> quick brown fox jumps over the lazy dog  ';
        for ($i = 0; $i < $count; $i++) {
            addslashes($string);
            bin2hex($string);
            chunk_split($string);
            convert_uudecode(convert_uuencode($string));
            count_chars($string);
            explode(' ', $string);
            htmlentities($string);
            md5($string);
            metaphone($string);
            ord($string);
            rtrim($string);
            sha1($string);
            soundex($string);
            str_getcsv($string);
            str_ireplace('fox', 'cat', $string);
            str_pad($string, 50);
            str_repeat($string, 10);
            str_replace('fox', 'cat', $string);
            str_rot13($string);
            str_shuffle($string);
            str_word_count($string);
            strip_tags($string);
            strpos($string, 'fox');
            strlen($string);
            strtolower($string);
            strtoupper($string);
            substr_count($string, 'the');
            trim($string);
            ucfirst($string);
            ucwords($string);
        }
        return $string;
    },
    'array' => function ($count = 50000) {
        $a = range(0, 100);
        for ($i = 0; $i < $count; $i++) {
            array_keys($a);
            array_values($a);
            array_flip($a);
            array_map(function ($e) {
            }, $a);
            array_walk($a, function ($e, $i) {
            });
            array_reverse($a);
            array_sum($a);
            array_merge($a, [101, 102, 103]);
            array_replace($a, [1, 2, 3]);
            array_chunk($a, 2);
        }
        return $a;
    },
    'regex' => function ($count = 1000000) {
        for ($i = 0; $i < $count; $i++) {
            preg_match("#http[s]?://\w+[^\s\[\]\<]+#", 'this is a link to https://google.com which is a really popular site');
            preg_replace("#(^|\s)(http[s]?://\w+[^\s\[\]\<]+)#i", '\1<a href="\2">\2</a>', 'this is a link to https://google.com which is a really popular site');
        }
        return $i;
    },
    'is_{type}' => function ($count = 2500000) {
        $o = new stdClass();
        for ($i = 0; $i < $count; $i++) {
            is_array([1]);
            is_array('1');
            is_int(1);
            is_int('abc');
            is_string('foo');
            is_string(123);
            is_bool(true);
            is_bool(5);
            is_numeric('hi');
            is_numeric('123');
            is_float(1.3);
            is_float(0);
            is_object($o);
            is_object('hi');
        }
        return $o;
    },
];


$isCli = php_sapi_name() === 'cli';
$lf = $isCli ? PHP_EOL : '<br>';
$w = 50;

$p = function ($str, $endStr = '', $pad = '.') use ($w, $lf) {
    if (!empty($endStr)) {
        $endStr = " $endStr";
    }
    $length = max(0, $w - strlen($endStr));
    echo str_pad($str, $length, $pad) . $endStr . $lf;
};

echo $isCli ? '' : '<pre>';
$p('', '', '-');
printf('|%s|%s', str_pad(sprintf("PHP BENCHMARK SCRIPT v.%s by @SergiX44", $V), $w - 2, ' ', STR_PAD_BOTH), $lf);
$p('', '', '-');
$p('PHP version', PHP_VERSION);
$p('Platform', PHP_OS);
if ($isCli) {
    $p('Server:', gethostname());
} else {
    $name = @$_SERVER['SERVER_NAME'] ?: 'null';
    $addr = @$_SERVER['SERVER_ADDR'] ?: 'null';
    $p('Server:', "{$name}@{$addr}");
}
$opStatus = opcache_get_status();
$p('OPCache status:', is_array($opStatus) && @$opStatus['opcache_enabled'] ? 'enabled' : 'disabled');
$p('OPCache JIT:', is_array($opStatus) && @$opStatus['jit']['enabled'] ? 'enabled' : 'disabled/unavailable');
$p('PCRE JIT:', ini_get('pcre.jit') ? 'enabled' : 'disabled');
$p('Started at:', DateTime::createFromFormat('U.u', microtime(true))->format('d/m/Y H:i:s.v'));
$p('', '', '-');

$stopwatch = new StopWatch();

foreach ($benchmarks as $name => $benchmark) {
    $stopwatch->start();
    $benchmark();
    $time = $stopwatch->stop();
    $p($name, number_format($time, 4) . ' s');
}
$p('', '', '-');
$p('Total time', number_format($stopwatch->totalTime, 4) . ' s');
$p('Peak memory usage', round(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MiB');

echo $isCli ? '' : '</pre>';


class StopWatch
{
    /**
     * @var float
     */
    public $totalTime = .0;

    private $start;


    /**
     * @return float
     */
    public function start()
    {
        return $this->start = $this->t();
    }

    /**
     * @return float
     */
    public function stop()
    {
        $time = $this->t() - $this->start;
        $this->totalTime += $time;

        return $time;
    }

    /**
     * @return float
     */
    private function t()
    {
        return function_exists('hrtime') ? hrtime(true) / 1e9 : microtime(true);
    }
}