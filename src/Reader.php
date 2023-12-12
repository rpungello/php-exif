<?php

namespace Rpungello\PhpExif;

use Rpungello\PhpExif\Adapters\Adapter;
use Rpungello\PhpExif\Exif\Main;
use Rpungello\PhpExif\Harnesses\Harness;
use RuntimeException;

class Reader
{
    public function __construct(protected Harness $harness, protected Adapter $adapter)
    {
    }

    /**
     * @param string $path
     * @return Main
     * @throws RuntimeException
     */
    public function read(string $path): Main
    {
        $data = $this->harness->readExif($path);

        return $this->adapter->parseExif($data);
    }
}
