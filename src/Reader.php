<?php

namespace Rpungello\PhpExif;

use Rpungello\PhpExif\Exif\Main;
use RuntimeException;

class Reader
{
    public function read(string $path): Main
    {
        $exif = new Main();
        $data = $this->readExif($path);

        $this->processCamera($exif, $data);
        $this->processLens($exif, $data);
        $this->processExposure($exif, $data);

        return $exif;
    }

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
        fclose($pipes[0]);
        fclose($pipes[1]);
        fclose($pipes[2]);

        proc_close($process);

        return json_decode($result, true);
    }
}
