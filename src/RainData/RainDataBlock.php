<?php

namespace App\RainData;

use App\Interfaces\DataBlock;

class RainDataBlock implements DataBlock
{
    private $rainMapping;

    public function __construct()
    {
        $this->rainMapping = [];
    }

    public function addRain($time, $amount)
    {
        $this->rainMapping[$time] = $amount;
    }
}
 