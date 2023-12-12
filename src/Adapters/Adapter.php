<?php

namespace Rpungello\PhpExif\Adapters;

use Decimal\Decimal;
use InvalidArgumentException;
use Rpungello\PhpExif\Exif\Camera;
use Rpungello\PhpExif\Exif\Exposure;
use Rpungello\PhpExif\Exif\Lens;
use Rpungello\PhpExif\Exif\Location;
use Rpungello\PhpExif\Exif\Main;

class Adapter
{
    public function parseExif(array $data): Main
    {
        $exif = new Main();

        $this->processCamera($exif, $data);
        $this->processLens($exif, $data);
        $this->processExposure($exif, $data);
        $this->processLocation($exif, $data);

        return $exif;
    }

    /**
     * Processes camera information into Exif data
     *
     * @param Main $exif
     * @param array $data
     * @return void
     */
    protected function processCamera(Main $exif, array $data): void
    {
        if (! isset($data['Make']) || ! isset($data['Model'])) {
            return;
        }

        $exif->withCamera(
            new Camera(
                $data['Make'],
                $data['Model'],
            )
        );
    }

    /**
     * Processes lens information into Exif data
     *
     * @param Main $exif
     * @param array $data
     * @return void
     */
    protected function processLens(Main $exif, array $data): void
    {
        if (! isset($data['LensMake']) || ! isset($data['LensModel'])) {
            return;
        }

        $exif->withLens(
            new Lens(
                $data['LensMake'],
                $data['LensModel'],
            )
        );
    }

    /**
     * Processes exposure information into Exif data
     *
     * @param Main $exif
     * @param array $data
     * @return void
     */
    protected function processExposure(Main $exif, array $data): void
    {
        if (! isset($data['Aperture']) || ! isset($data['ShutterSpeed']) || ! isset($data['ISO'])) {
            return;
        }

        $exif->withExposure(
            new Exposure(
                $data['Aperture'],
                $data['ShutterSpeed'],
                $data['ISO'],
            )
        );
    }

    protected function processLocation(Main $exif, array $data): void
    {
        if (! isset($data['GPSLatitude']) || ! isset($data['GPSLongitude'])) {
            return;
        }

        try {
            $latitude = $this->parseCoordinate($data['GPSLatitude']);
            $longitude = $this->parseCoordinate($data['GPSLongitude']);

            $exif->withLocation(
                new Location(
                    $latitude,
                    $longitude,
                )
            );
        } catch (InvalidArgumentException) {
        }
    }

    /**
     * Takes a coordinate in degrees, minutes, seconds and converts it to a decimal coordinate
     *
     * @param string $coordinate
     * @return Decimal
     */
    private function parseCoordinate(string $coordinate): Decimal
    {
        if (preg_match('/^(\d+) deg (\d+)\' (\d+\.\d+)" ([NSEW])?/', $coordinate, $matches)) {
            $degrees = new Decimal($matches[1]);
            $minutes = new Decimal($matches[2]);
            $seconds = new Decimal($matches[3]);
            $dir = $matches[4] ?? null;

            $decimal = $degrees->add($minutes->div(60))->add($seconds->div(3600));

            // If the direction is South or West, we need to make the decimal negative
            if (in_array($dir, ['S', 'W'])) {
                $decimal = $decimal->mul(-1);
            }
            return $decimal;
        } else {
            throw new InvalidArgumentException('Invalid coordinate format');
        }
    }
}
