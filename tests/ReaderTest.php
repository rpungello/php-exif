<?php

use Rpungello\PhpExif\Reader;

it('can read exif from Nikon images', function () {
    $reader = new Reader();
    $exif = $reader->read(__DIR__  . '/images/NikonZ8.jpg');

    expect($exif->camera->make)->toBe('NIKON CORPORATION')
        ->and($exif->camera->model)->toBe('NIKON Z 8')
        ->and($exif->lens->make)->toBe('NIKON')
        ->and($exif->lens->model)->toBe('NIKKOR Z 35mm f/1.8 S')
        ->and($exif->exposure->aperture)->toBe('1.8')
        ->and($exif->exposure->shutterSpeed)->toBe('1/4')
        ->and($exif->exposure->iso)->toBe('400');

});
