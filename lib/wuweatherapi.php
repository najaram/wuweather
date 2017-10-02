<?php 

namespace WuWeatherApi;

/**
 * WuWeather Class Api
 */
class WuWeatherApi
{
    /**
     * @var int Cache time 
     */
    const CACHE_TIME = 3600;

    /**
     * @var string Api key
     */
    protected $apiKey;

    /**
     * @var string Base url
     */
    protected $baseUrl = 'http://api.wunderground.com/api/';

    public function __construct($apiKey)
    {
        if (!$apiKey) {
            throw new \Exception('No api key provided.', 500);
        }

        $this->apiKey = $apiKey;
    }
    /**
     * Get information from Wunderground Api
     * @param  string $capitalAbr  Abbreviation of capital
     * @param  string $capitalName Capital name
     * @return response object         
     */
    public function getWeatherCondition($capitalAbr, $capitalName)
    {
        $data = $this->getRequest(sprintf('/%s/%s', $capitalAbr, $capitalName));

        if (!$data) {
            throw new \Exception('Unable to retrieve info.');
        }

        return $data;
    }

    /**
     * Inner method for getting records
     */
    protected function getRequest($method)
    {
        $cache = 'wu_weather_api_' . '/conditions/q' . $method . '.json';

        if ($data = wp_cache_get($cache, 'wu_weather')) {
            return $data;
        }

        $url = $this->baseUrl . $this->apiKey . '/conditions/q' . $method . '.json';
        // var_dump($url); die();
        $response = wp_remote_get($url);

        if (is_wp_error($response)) {
            throw new \Exception($response->get_error_message());
        }

        $data = wp_remote_retrieve_body($response);
        $data = json_decode($data, true);

        wp_cache_set($cache, $data, 'wu_weather', WuWeatherApi::CACHE_TIME);

        return $data;
    }
}