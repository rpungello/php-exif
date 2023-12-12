<?php

use Rpungello\PhpExif\Adapters\Adapter;
use Rpungello\PhpExif\Harnesses\DummyHarness;
use Rpungello\PhpExif\Reader;

it('can read exif from Nikon images', function () {
    $reader = new Reader(new DummyHarness(), new Adapter());
    $exif = $reader->read(__DIR__  . '/images/NikonZ8.jpg');

    expect($exif->camera->make)->toBe('Dummy Make')
        ->and($exif->camera->model)->toBe('Dummy Model')
        ->and($exif->lens->make)->toBe('Dummy Lens Make')
        ->and($exif->lens->model)->toBe('Dummy Lens Model')
        ->and($exif->exposure->aperture)->toBe('4.0')
        ->and($exif->exposure->shutterSpeed)->toBe('1/1250')
        ->and($exif->exposure->iso)->toBe(400)
        ->and($exif->location->latitude->toFixed(6))->toBe('0.000000')
        ->and($exif->location->longitude->toFixed(6))->toBe('0.000000');

});
