<?php

return [
    'file_read' => function($multiplier = 1, $count = 1000) {
        file_put_contents('test.txt', "test");
        $count = $count * $multiplier;
        for ($i = 0; $i < $count; $i++) {
            file_get_contents('test.txt');
        }
        unlink('test.txt');
        return $i;
    },
    'file_write' => function($multiplier = 1, $count = 1000) {
        $count = $count * $multiplier;
        for ($i = 0; $i < $count; $i++) {
            file_put_contents('test.txt', "test $i");
        }
        unlink('test.txt');
        return $i;
    },
    'file_zip' => function($multiplier = 1, $count = 1000) {
        file_put_contents('test.txt', "test");
        $count = $count * $multiplier;
        for ($i = 0; $i < $count; $i++) {
            $zip = new ZipArchive();
            $zip->open('test.zip', ZipArchive::CREATE);
            $zip->addFile('test.txt');
            $zip->close();
        }
        unlink('test.txt');
        unlink('test.zip');
        return $i;
    },
    'file_unzip' => function($multiplier = 1, $count = 1000) {
        file_put_contents('test.txt', "test");
        $zip = new ZipArchive();
        $zip->open('test.zip', ZipArchive::CREATE);
        $zip->addFile('test.txt');
        $zip->close();
        $count = $count * $multiplier;
        for ($i = 0; $i < $count; $i++) {
            $zip = new ZipArchive();
            $zip->open('test.zip');
            $zip->extractTo('test');
            $zip->close();
        }
        unlink('test.txt');
        unlink('test.zip');
        unlink('test/test.txt');
        rmdir('test');
        return $i;
    },

];