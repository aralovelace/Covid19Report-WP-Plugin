<?php declare(strict_types=1); // phpcs:disable PSR1.Files.SideEffects.FoundWithSymbols
/*
Plugin Name:  Covid19APIReport
Description:  This is a plugin created for wordpress users so they can display live updates of COVID-19 live status
Author:       April Smith
Version:      1.0
Text Domain:  covid19APIReport
License:      GPL v2 or later
License URI:  https://www.gnu.org/licenses/gpl-2.0.txt
*/
namespace Covid19APIReport;

// exit if file is called directly
if (!defined('ABSPATH')) {
    exit;
}

register_activation_hook(__FILE__, ['Covid19APIReport\Inc\ClassActivator', 'activate']);
register_deactivation_hook(__FILE__, ['Covid19APIReport\Inc\ClassDeactivator', 'deactivate']);

/**
 * Define Constants
 */
const PLUGIN_NAME = 'Covid19APIReport';
define('PLUGIN_NAME_DIR', plugin_dir_path(__FILE__));
define('PLUGIN_NAME_URL', plugins_url().'/'.PLUGIN_NAME);
define('PLUGIN_CSS_URL', plugins_url().'/'.PLUGIN_NAME.'/public/css/');
define('PLUGIN_JS_URL', plugins_url().'/'.PLUGIN_NAME.'/public/js/');

class Covid19APIReport
{
    public function __construct()
    {
        add_shortcode('covid19-reports', [$this, 'callbackCovid19']);
        add_action('wp_enqueue_scripts', [$this, 'covid19APIReportAddScripts']);
    }

    public function covid19APIReportAddScripts()
    {
        // Add CSS
        wp_enqueue_style('covid19APIReport-main-style', PLUGIN_CSS_URL . 'style.css');
        wp_enqueue_style('covid19APIReport-dataTables-style', PLUGIN_CSS_URL . 'jquery.dataTables.min.css');
        // ADD JS
        wp_enqueue_script('covid19APIReport-jquery-script', PLUGIN_JS_URL . 'jquery-3.5.1.js');
        wp_enqueue_script('covid19APIReport-datatable-script', PLUGIN_JS_URL . 'jquery.dataTables.min.js');
        wp_enqueue_script('covid19APIReport-main-script', PLUGIN_JS_URL . 'main.js');
    }

    public function callbackCovid19(): string
    {
        $url = 'https://api.covid19api.com/summary';
        $args = [ 'method' => 'GET' ];
        $response = wp_remote_get($url, $args);

        if (is_wp_error($response)) {
            $errorMessage = $response->get_error_message();
            return "Something went wrong" . $errorMessage;
        }
        $results = json_decode(wp_remote_retrieve_body($response));

        $html =  '<div class="content">';
        $html .= '<div class="card">';
        $html .= '<div class="card-body">';
        $html .= '<div class="row">';
        $html .= '<div class="col"><h4>Global Cases</h4></div>';
        $html .= '</div>';
        $html .= '<div class="row">';
        $html .= '<div class="col">New Confirmed Cases: '.number_format_i18n($results->Global->NewConfirmed).'</div>';
        $html .= '</div>';
        $html .= '<div class="row">';
        $html .= '<div class="col">Total Confirmed Cases: '.number_format_i18n($results->Global->TotalConfirmed).'</div>';
        $html .= '</div>';
        $html .= '<div class="row">';
        $html .= '<div class="col">New Deaths: '.number_format_i18n($results->Global->NewDeaths).'</div>';
        $html .= '</div>';
        $html .= '<div class="row">';
        $html .= '<div class="col">New Recovered: '.number_format_i18n($results->Global->NewRecovered).'</div>';
        $html .= '</div>';
        $html .= '<div class="row">';
        $html .= '<div class="col">Total Recovered: '.number_format_i18n($results->Global->TotalRecovered).'</div>';
        $html .= '</div></div></div>';
        $html .= '<div class="content">';
        $html .= '<div class="row">';
        $html .='<div class="form-group">
         <label for="covid19APIReport-countries">Select cases by country:</label>
            <select class="form-control" id="covid19APIReport-countries">
              <option>Select Country</option>';
        $resultsCountries  = $results->Countries; // phpcs:disable NeutronStandard.AssignAlign.DisallowAssignAlign.Aligned
        for ($num=0; $num<count($resultsCountries); $num++) {
            $html .='<option value="'.$resultsCountries[$num]->Slug.'">'
                .$resultsCountries[$num]->Country.
                ' (Total Confirmed: '.number_format_i18n($resultsCountries[$num]->TotalConfirmed).')
            </option>';
        }
        $html .=' </select></div>';
        $html .= '</div>';
        $html .= '<div id="covid19APIReport-display-details"></div>';
        $html .= '</div>';
        return $html;
    }
}

new Covid19APIReport();
