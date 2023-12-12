<?php

namespace Rpungello\PhpExif\Exif;

use Rpungello\PhpExif\Contracts\Arrayable;
use Rpungello\PhpExif\Contracts\Jsonable;

class Main implements Arrayable, Jsonable
{
    public ?Camera $camera;

    public ?Lens $lens;

    public ?Exposure $exposure;

    public ?Location $location;

    public function __construct()
    {
        $this->camera = null;
        $this->lens = null;
        $this->exposure = null;
    }

    public function withCamera(Camera $camera): self
    {
        $this->camera = $camera;

        return $this;
    }

    public function withLens(Lens $lens): self
    {
        $this->lens = $lens;

        return $this;
    }

    public function withExposure(Exposure $exposure): self
    {
        $this->exposure = $exposure;

        return $this;
    }

    public function withLocation(Location $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'camera' => $this->camera?->toArray(),
            'lens' => $this->lens?->toArray(),
            'exposure' => $this->exposure?->toArray(),
            'location' => $this->location?->toArray(),
        ];
    }

    public function toJson(int $options = 0): string
    {
        return json_encode($this->toArray(), $options);
    }
}
