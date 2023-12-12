<?php

namespace Rpungello\PhpExif\Harnesses;

use RuntimeException;

class ExiftoolHarness implements Harness
{

    public function readExif(string $path): array
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
