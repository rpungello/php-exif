<?php

namespace Rpungello\PhpExif\Harnesses;

interface Harness
{
    public function readExif(string $path): array;
}
