<?php

namespace App\ForecastData;

use App\Abstractions\Collection;
use App\Interfaces\DataBlock;

class ForecastDataCollection extends Collection
{

    public function add(DataBlock $dayDataBlock)
    {
        $date = $dayDataBlock->getDate()->format('Y-m-d');
        parent::addByKeyValue($date, $dayDataBlock);
    }

    public function getToday()
    {
        if (isset($this->dataBlocks[date('Y-m-d')])) {
            return $this->dataBlocks[date('Y-m-d')];
        }
        return false;
    }
}
 