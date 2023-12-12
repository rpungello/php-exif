<?php

namespace Rpungello\PhpExif\Exif;

use Rpungello\PhpExif\Contracts\Arrayable;
use Rpungello\PhpExif\Contracts\Jsonable;

class Location implements Arrayable, Jsonable
{
    public function __construct(public float $latitude, public float $longitude)
    {

    }

    public function toArray(): array
    {
        return [
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
        ];
    }

    public function toJson(int $options = 0): string
    {
        return json_encode($this->toArray(), $options);
    }
}
