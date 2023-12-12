<?php

use Rpungello\PhpExif\Reader;

it('can read exif from iPhone 15 images', function () {
    $reader = new Reader();
    $exif = $reader->read(__DIR__  . '/images/iPhone15.jpeg');

    expect($exif->camera->make)->toBe('Apple')
        ->and($exif->camera->model)->toBe('iPhone 15 Pro Max')
        ->and($exif->lens->make)->toBe('Apple')
        ->and($exif->lens->model)->toBe('iPhone 15 Pro Max back triple camera 6.86mm f/1.78')
        ->and($exif->exposure->aperture)->toBe('1.8')
        ->and($exif->exposure->shutterSpeed)->toBe('1/120')
        ->and($exif->exposure->iso)->toBe('160')
        ->and($exif->location->latitude->toFixed(6))->toBe('40.268550')
        ->and($exif->location->longitude->toFixed(6))->toBe('-74.639114');

});
