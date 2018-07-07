<?php

namespace App\Interfaces;

use App\Location\LocationDataBlock;

interface DataProviderInterface
{
    public function getDataByLocation(LocationDataBlock $location);
}
 