<?php

namespace Rpungello\PhpExif\Exif;

use Rpungello\PhpExif\Contracts\Arrayable;
use Rpungello\PhpExif\Contracts\Jsonable;

class Exposure implements Arrayable, Jsonable
{
    public function __construct(public string $aperture, public string $shutterSpeed, public int $iso)
    {
    }

    public function toArray(): array
    {
        return [
            'aperture' => $this->aperture,
            'shutterSpeed' => $this->shutterSpeed,
            'iso' => $this->iso,
        ];
    }

    public function toJson(int $options = 0): string
    {
        return json_encode($this->toArray(), $options);
    }
}
