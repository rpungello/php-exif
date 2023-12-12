<?php

namespace Rpungello\PhpExif\Adapters;

use Rpungello\PhpExif\Exif\Main;

class MultiAdapter extends Adapter
{
    public function parseExif(array $data): Main
    {
        return parent::parseExif($data[0]);
    }
}
