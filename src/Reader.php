<?php

namespace Rpungello\PhpExif;

use Decimal\Decimal;
use InvalidArgumentException;
use Rpungello\PhpExif\Exif\Main;
use RuntimeException;

class Reader
{
    /**
     * @param string $path
     * @return Main
     * @throws RuntimeException
     */
    public function read(string $path): Main
    {
        $exif = new Main();
        $data = $this->readExif($path);

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
        if (! isset($data[0]['Make']) || ! isset($data[0]['Model'])) {
            return;
        }

        $exif->withCamera(
            new Exif\Camera(
                $data[0]['Make'],
                $data[0]['Model'],
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
        if (! isset($data[0]['LensMake']) || ! isset($data[0]['LensModel'])) {
            return;
        }

        $exif->withLens(
            new Exif\Lens(
                $data[0]['LensMake'],
                $data[0]['LensModel'],
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
        if (! isset($data[0]['Aperture']) || ! isset($data[0]['ShutterSpeed']) || ! isset($data[0]['ISO'])) {
            return;
        }

        $exif->withExposure(
            new Exif\Exposure(
                $data[0]['Aperture'],
                $data[0]['ShutterSpeed'],
                $data[0]['ISO'],
            )
        );
    }

    protected function processLocation(Main $exif, array $data): void
    {
        if (! isset($data[0]['GPSLatitude']) || ! isset($data[0]['GPSLongitude'])) {
            return;
        }

        try {
            $latitude = $this->parseCoordinate($data[0]['GPSLatitude']);
            $longitude = $this->parseCoordinate($data[0]['GPSLongitude']);

            $exif->withLocation(
                new Exif\Location(
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

    /**
     * Reads exif data using exiftool
     *
     * @param string $path
     * @return array
     * @throws RuntimeException
     */
    private function readExif(string $path): array
    {
        if (! file_exists($path)) {
            throw new RuntimeException(
                'File does not exist'
            );
        } elseif (! is_readable($path)) {
            throw new RuntimeException(
                'File is not readable'
            );
        }

        $process = proc_open("exiftool -json $path", [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ], $pipes);

        if (! is_resource($process)) {
            throw new RuntimeException(
                'Missing exiftool binary'
            );
        }

        $result = stream_get_contents($pipes[1]);
        $error = stream_get_contents($pipes[2]);

        if (! empty($error)) {
            throw new RuntimeException(
                $error
            );
        }

        fclose($pipes[0]);
        fclose($pipes[1]);
        fclose($pipes[2]);

        proc_close($process);

        return json_decode($result, true);
    }
}
