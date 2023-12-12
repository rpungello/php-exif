<?php

use Decimal\Decimal;
use Rpungello\PhpExif\Adapters\Adapter;
use Rpungello\PhpExif\Harnesses\FakerHarness;
use Rpungello\PhpExif\Reader;

it('can generate fake Exif', function () {
    $reader = new Reader(new FakerHarness(), new Adapter());
    $exif = $reader->read(__DIR__  . '/images/NikonZ8.jpg');

    expect($exif->camera->make)->toBeString()
        ->and($exif->camera->model)->toBeString()
        ->and($exif->lens->make)->toBeString()
        ->and($exif->lens->model)->toBeString()
        ->and($exif->exposure->aperture)->toBeString()
        ->and($exif->exposure->shutterSpeed)->toBeString()
        ->and($exif->exposure->iso)->toBeInt()
        ->and($exif->location->latitude)->toBeInstanceOf(Decimal::class)
        ->and($exif->location->longitude)->toBeInstanceOf(Decimal::class);
});
