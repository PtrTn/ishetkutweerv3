<?php

namespace App\PresentData;

use App\Interfaces\DataFactory;
use App\Interfaces\DataSource;
use App\Interfaces\HttpClient;
use App\Station\Station;

class PresentDataSource implements DataSource
{
    private $dataFactory;
    private $httpClient;
    private $apiUrl;

    public function __construct(DataFactory $dataFactory, HttpClient $httpClient, $apiUrl)
    {
        $this->dataFactory = $dataFactory;
        $this->httpClient = $httpClient;
        $this->apiUrl = $apiUrl;
    }

    public function getData(Station $station = null)
    {
        if (is_null($station)) {
            throw new \LogicException('No Station provided for LocationDataSource');
        }
        $data = $this->httpClient->getData($this->apiUrl, true);
        if(!isset($data) || empty($data)) {
            throw new \RuntimeException('No current data found');
        }
        return $this->dataFactory->createDataBlock($data, $station);
    }
}
 