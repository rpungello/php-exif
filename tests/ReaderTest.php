<?php

use Rpungello\PhpExif\Adapters\Adapter;
use Rpungello\PhpExif\Harnesses\ExiftoolHarness;
use Rpungello\PhpExif\Reader;

it('fails to read missing files', function () {
    $reader = new Reader(new ExiftoolHarness(), new Adapter());
    $reader->read(__DIR__  . '/images/Missing.jpg');
})->throws(RuntimeException::class);
