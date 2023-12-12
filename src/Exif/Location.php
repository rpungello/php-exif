<?php

namespace Rpungello\PhpExif\Exif;

use Decimal\Decimal;
use Rpungello\PhpExif\Contracts\Arrayable;
use Rpungello\PhpExif\Contracts\Jsonable;

class Location implements Arrayable, Jsonable
{
    public function __construct(public Decimal $latitude, public Decimal $longitude)
    {

    }

    public function toArray(): array
    {
        return [
            'latitude' => $this->latitude->toFixed(6),
            'longitude' => $this->longitude->toFixed(6),
        ];
    }

    public function toJson(int $options = 0): string
    {
        return json_encode($this->toArray(), $options);
    }
}
