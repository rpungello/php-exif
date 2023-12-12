<?php

use Rpungello\PhpExif\Reader;

it('fails to read missing files', function () {
    $reader = new Reader();
    $reader->read(__DIR__  . '/images/Missing.jpg');
})->throws(RuntimeException::class);
