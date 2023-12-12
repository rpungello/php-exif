<?php

namespace Rpungello\PhpExif;

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
     * Takes a string in the format 40 deg 16' 7.54" N and returns a float
     *
     * @param string $coordinate
     * @return float
     */
    private function parseCoordinate(string $coordinate): float
    {
        if (preg_match('/^(\d+) deg (\d+)\' (\d+\.\d+)"/', $coordinate, $matches)) {
            return (float) $matches[1] + ((float) $matches[2] / 60) + ((float) $matches[3] / 3600);
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
