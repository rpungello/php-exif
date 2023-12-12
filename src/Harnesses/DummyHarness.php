<?php

namespace Rpungello\PhpExif\Harnesses;

class DummyHarness implements Harness
{
    public function readExif(string $path): array
    {
        return [
            'Make' => 'Dummy Make',
            'Model' => 'Dummy Model',
            'LensMake' => 'Dummy Lens Make',
            'LensModel' => 'Dummy Lens Model',
            'Aperture' => '4.0',
            'ShutterSpeed' => '1/1250',
            'ISO' => 400,
            'GPSLatitude' => 0.0,
            'GPSLongitude' => 0.0,
        ];
    }
}
