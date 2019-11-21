<?php
/**
 * Events Tracker For Elementor
 *
 * @link              https://wordpress.org/plugins/wpl-elementor-events-tracker/
 * @package           wpl-elementor-events-tracker
 *
 * @wordpress-plugin
 * Plugin Name:       Events Tracker for Elementor. Google Analytics, GTM, Facebook, Yandex Metrika, Vkontakte
 * Plugin URI:        https://wordpress.org/plugins/wpl-elementor-events-tracker/
 * Description:       Track Click or Submit events and conversions for any Elementor widget with Google Analytics, Facebook, Yandex Metrika, Vkontakte.
 * Version:           1.1
 * Author:            wpl.agency
 * Author URI:        https://wpl.agency/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wpl-elementor-events-tracker
 * Domain Path:       /languages
 */
namespace WPL\Elementor_Events_Tracker;

if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'WPL_ELEMENTOR_EVENTS_TRACKER_VERSION', '1.1' );
define( 'WPL_ELEMENTOR_EVENTS_TRACKER_SLUG', 'wpl_elementor_events_tracker' );
define( 'WPL_ELEMENTOR_EVENTS_TRACKER_FILE', __FILE__ );
define( 'WPL_ELEMENTOR_EVENTS_TRACKER_DIR', trailingslashit( __DIR__ ) );
define( 'WPL_ELEMENTOR_EVENTS_TRACKER_URL', plugin_dir_url( WPL_ELEMENTOR_EVENTS_TRACKER_FILE ) );

function wpl_elementor_events_tracker() {
	require_once WPL_ELEMENTOR_EVENTS_TRACKER_DIR . 'includes/class-main.php';
	new Main();
}

add_action( 'plugins_loaded', __NAMESPACE__ . '\wpl_elementor_events_tracker' );

// eol.
