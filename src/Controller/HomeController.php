<?php

namespace App\Controller;

use App\ForecastData\ForecastDataSource;
use App\HistoricData\HistoryDataSource;
use App\Location\LocationDataSource;
use App\PresentData\PresentDataBlock;
use App\PresentData\PresentDataSource;
use App\RainData\RainDataSource;
use App\Rating\RatingCalculator;
use App\Station\StationFactory;
use App\Station\StationFinder;
use DateInterval;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends Controller
{
    /**
     * @var StationFinder
     */
    private $stationFinder;

    /**
     * @var LocationDataSource
     */
    private $locationDataSource;

    /**
     * @var StationFactory
     */
    private $stationFactory;

    /**
     * @var HistoryDataSource
     */
    private $historyDataSource;

    /**
     * @var PresentDataSource
     */
    private $presentDataSource;

    /**
     * @var ForecastDataSource
     */
    private $forecastDataSource;

    /**
     * @var RainDataSource
     */
    private $rainDataSource;

    /**
     * @var RatingCalculator
     */
    private $ratingCalculator;

    public function __construct(
        StationFinder $stationFinder,
        LocationDataSource $locationDataSource,
        StationFactory $stationFactory,
        HistoryDataSource $historyDataSource,
        PresentDataSource $presentDataSource,
        ForecastDataSource $forecastDataSource,
        RainDataSource $rainDataSource,
        RatingCalculator $ratingCalculator
    ) {
        $this->stationFinder = $stationFinder;
        $this->locationDataSource = $locationDataSource;
        $this->stationFactory = $stationFactory;
        $this->historyDataSource = $historyDataSource;
        $this->presentDataSource = $presentDataSource;
        $this->forecastDataSource = $forecastDataSource;
        $this->rainDataSource = $rainDataSource;
        $this->ratingCalculator = $ratingCalculator;
    }

    /**
    * @Route("/")
    */
    public function showHome()
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        $location = $this->locationDataSource->getData($ip);
        $station = $this->stationFinder->findStationByLocation($location);
        // Prefer given location over station location
        if (is_null($location)) {
            $location = $station->getLocation();
        }

        // Get data based on station or location
        $historyData = $this->historyDataSource->getData($station);
        $presentData = $this->presentDataSource->getData($station);
        $forecastData = $this->forecastDataSource->getData($location);

        // Rate current and future weather based on historical data and other rules
        $currentRating = $this->ratingCalculator->getPresentRating($presentData, $historyData);
        $forecastRatings = $this->ratingCalculator->getRatingCollection($forecastData, $historyData);

        // Retrieve weather dependant background
        $backgroundImage = $this->getBackground($presentData);

        // Retrieve weather data for coming 2 hours
        $rainData = $this->rainDataSource->getData($location);

        // Retrieve list of all stations
        $stations = $this->stationFactory->getStations();

        return $this->render('home.html.twig', [
            'station' => $station,
            'stations' => $stations,
            'presentRating' => $currentRating,
            'forecastRatings' => $forecastRatings,
            'historicData' => $historyData,
            'presentData' => $presentData,
            'forecastData' => $forecastData,
            'rainData' => $rainData,
            'backgroundImage' => $backgroundImage
        ]);
    }

    public function getBackground(PresentDataBlock $dataBlock)
    {
        $temp = $dataBlock->getTemp();
        $rain = $dataBlock->getRain();
        $wind = $dataBlock->getBeaufort();
        $sight = $dataBlock->getSight();
        $dateTime = $dataBlock->getDate();
        return $this->getImageByData($temp, $rain, $wind, $sight, $dateTime);
    }

    private function getImageByData($temp, $rain, $wind, $sight, \DateTime $dateTime)
    {
        if ($temp < 0 && $rain > 0) {
            return 'snow.jpg';
        }
        if ($rain > 3) {
            return 'rain-2.jpg';
        }
        if ($rain > 0 && $rain <= 3) {
            return 'rain-1.jpg';
        }
        $nightStart = new \DateTime('23:00');
        $nightEnd = (new \DateTime('07:00'))->add(new DateInterval('P1D'));
        if ($dateTime > $nightStart && $dateTime < $nightEnd) {
            return 'clear-night.jpg';
        }
        if ($sight < 1000) {
            return 'fog.jpg';
        }
        if ($wind > 7) {
            return 'wind-2.jpg';
        }
        if ($wind > 5) {
            return 'wind-1.jpg';
        }
        if ($temp > 20 && $rain == 0) {
            return 'clear-day-2.jpg';
        }
        if ($rain == 0) {
            return 'clear-day-1.jpg';
        }
        return 'default.jpg';
    }
}
