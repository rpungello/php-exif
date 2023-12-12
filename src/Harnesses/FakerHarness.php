<?php

namespace Rpungello\PhpExif\Harnesses;

use Faker\Factory;
use Faker\Generator;

class FakerHarness implements Harness
{
    protected Generator $faker;
    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public function readExif(string $path): array
    {
        return [
            'Make' => $this->faker->company(),
            'Model' => $this->faker->word(),
            'LensMake' => $this->faker->company(),
            'LensModel' => $this->faker->word(),
            'Aperture' => $this->faker->randomFloat(1, 1, 22),
            'ShutterSpeed' => "1/{$this->faker->randomNumber(4)}",
            'ISO' => $this->faker->randomNumber(4),
            'GPSLatitude' => $this->faker->latitude(),
            'GPSLongitude' => $this->faker->longitude(),
        ];
    }
}
