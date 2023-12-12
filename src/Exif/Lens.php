<?php

namespace Rpungello\PhpExif\Exif;

use Rpungello\PhpExif\Contracts\Arrayable;
use Rpungello\PhpExif\Contracts\Jsonable;

class Lens implements Arrayable, Jsonable
{
    public function __construct(public string $make, public string $model)
    {
    }

    public function toArray(): array
    {
        return [
            'make' => $this->make,
            'model' => $this->model,
        ];
    }

    public function toJson(int $options = 0): string
    {
        return json_encode($this->toArray(), $options);
    }
}
