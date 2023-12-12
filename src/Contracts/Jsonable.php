<?php

namespace Rpungello\PhpExif\Contracts;

interface Jsonable
{
    public function toJson(int $options = 0): string;
}
