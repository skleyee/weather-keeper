<?php

namespace App\Services\Weather;

use GuzzleHttp\Client;
use Illuminate\Http\Exceptions\HttpResponseException;

class OpenWeather implements WeatherService
{
    private Client $httpClient;

    public function __construct()
    {
        $this->httpClient = new Client([
            'headers' => ['Content-Type' => 'application/json'],
        ]);
    }

    public function getTodaysWeatherByCoordinates(array $coordinates)
    {
        try {
            $response = $this->httpClient->get(config('weather.api_url') . '/data/2.5/weather?lat=' . $coordinates['lat'] . '&lon=' . $coordinates['lon'] . '&appid=' . config('weather.api_key') . '&lang=ru&units=metric');

            if ($response->getStatusCode() !== 200) {
                logger('request failed: ' . $response->getStatusCode());
                return false;
            }

            $responseJSON = $response->getBody();
            $responseJSON->rewind();
            $responseJSON = json_decode($responseJSON->getContents());

            return $responseJSON;

        } catch (HttpResponseException $exception) {
            logger('getCoordinatesByCity http request error: '. $exception->getCode(), [$exception->getMessage()]);
        }
    }

    public function getCoordinatesByCity(string $city): array|false
    {
        try {
            $response = $this->httpClient->get(config('weather.api_url') . '/geo/1.0/direct?q=' . $city . '&limit=1&appid=' . config('weather.api_key') . '&lang=ru');

            if ($response->getStatusCode() !== 200) {
                logger('request failed: ' . $response->getStatusCode());
                return false;
            }

            $responseJSON = $response->getBody();
            $responseJSON->rewind();
            $responseJSON = json_decode($responseJSON->getContents());

            return [
                'lat' => $responseJSON[0]->lat,
                'lon' => $responseJSON[0]->lon
                ];

        } catch (HttpResponseException $exception) {
            logger('getCoordinatesByCity http request error: '. $exception->getCode(), [$exception->getMessage()]);
        }
    }

    public function getCityByCoordinates(object $coordinates)
    {
        try {
            $response = $this->httpClient->get(config('weather.api_url') . '/geo/1.0/reverse?lat=' . $coordinates->latitude . '&lon=' . $coordinates->longitude . '&limit=1&appid=' . config('weather.api_key'));

            if ($response->getStatusCode() !== 200) {
                logger('request failed: ' . $response->getStatusCode());
                return false;
            }

            $responseJSON = $response->getBody();
            $responseJSON->rewind();
            $responseJSON = json_decode($responseJSON->getContents());

            return $responseJSON[0]->local_names->ru;

        } catch (HttpResponseException $exception) {
            logger('getCityByCoordinates http request error: ' . $exception->getCode(), [$exception->getMessage()]);
        }
    }
}
