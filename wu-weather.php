<?php
/**
 * Plugin Name
 *
 * @package     Wu Weather
 * @author      Jan Arambulo
 * @copyright   217 Jan Arambulo
 * @license     MIT
 *
 * @wordpress-plugin
 * Plugin Name: Wu Weather
 * Plugin URI:  https://github.com/najaram/wuweather
 * Description: A basic weather plugin powered by wunderground
 * Version:     1.0.0
 * Author:      Jan Arambulo
 * Author URI:  https://najaram.github.io/
 * License:     MIT
 * License URI: https://opensource.org/licenses/MIT
 */

require_once dirname(__FILE__) . '/lib/wuweatherapi.php';

use WuWeatherApi\WuWeatherApi;

class WuWeather
{
    /**
     * @var string
     */
    public $wuWeatherApi;

    public function __construct()
    {
        try {
            $apiKey = get_option('wu_weather_api_key');
            $this->wuWeatherApi = new WuWeatherApi($apiKey);
        } catch (\Exception $e) {
            $e->getMessage();
        }
    }

    public function currentWeatherCondition($capitalAbr, $capitalName)
    {
        if (!$this->wuWeatherApi) {
            throw new Exception('Something went wrong.');
        }
        return $this->wuWeatherApi->getWeatherCondition($capitalAbr, $capitalName);
    }
}

if (is_admin()) {
    add_action('admin_init', 'wu_weather_add_options');
    add_action('admin_menu', 'wu_weather_admin_options_page');
}

/**
 * Options Page 
 */
function wu_weather_admin_options_page() {
    add_options_page('Wundergroud Weather', 'Wundergroud Weather', 'manage_options', 'wu_weather', 'wu_weather_options_page');
}

function wu_weather_options_page() {
    if ($_POST) {
        wu_weather_update_options($_POST);
    }

    $data['wu_weather_api_key'] = get_option('wu_weather_api_key');

    require_once dirname(__FILE__) . '/views/settings.php';
}

function wu_weather_update_options($post) {
    if ($post['wu_weather_api_key']) {
        update_option('wu_weather_api_key', (sanitize_text_field(trim($post['wu_weather_api_key']))));
    } 
}

function wu_weather_add_options() {
    add_option('wu_weather_api_key', '');
}

/**
 * Styles
 */
function wu_weather_styles() {
    wp_register_style('wu_weather', plugins_url('assets/css/app.css', __FILE__));
    wp_enqueue_style('wu_weather');
}

if (!is_admin()) {
    add_action('wp_enqueue_scripts', 'wu_weather_styles');
}

/**
 * Shortcode Page 
 */
function wu_weather_shortcode($atts, $content = null) {
    $atts = shortcode_atts([
        'capital_abr' => 'CA',
        'capital_name' => 'San_Francisco'
    ], $atts, 'wu_weather');

    try {
        $wuWeather = new WuWeather();
        $currentConditions = $wuWeather->currentWeatherCondition($atts['capital_abr'], $atts['capital_name']);

        ob_start();
        include dirname(__FILE__) . '/views/shortcode.php';
        $output = ob_get_contents();
        ob_get_clean();

        return $output;
    } catch (Exception $e) {
        return '<p class="error">' . $e->getMessage() . '</p>';
    }
}

add_shortcode('wu_weather', 'wu_weather_shortcode');



