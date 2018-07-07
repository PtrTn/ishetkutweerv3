<?php

namespace App\ForecastData;

use App\Interfaces\DataFactory;

class ForecastDataFactory implements DataFactory
{
    public function createDataBlock($data)
    {
        $forecast = new ForecastDataCollection();
        foreach($data->getDaily()->getData() as $dayData) {
            $forecast->add(new ForecastDataBlock(
                $dayData->getTime(),
                $dayData->getTemperature()->getMin(),
                $dayData->getTemperature()->getMax(),
                $dayData->getPrecipitation()->getProbability(),
                $dayData->getPrecipitation()->getMaxIntensity(),
                $dayData->getWindSpeed(),
                $dayData->getWindBearing()
            ));
        }
        return $forecast;
    }
}
 