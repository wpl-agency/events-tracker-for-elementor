<?php
/**
 * Events Tracker For Elementor
 *
 * @link              https://wordpress.org/plugins/wpl-elementor-events-tracker/
 * @package           wpl-elementor-events-tracker
 *
 * @wordpress-plugin
 * Plugin Name:       Events Tracker for Elementor
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

/**
 * Load gettext translate for our text domain.
 *
 * @since 1.1
 *
 * @return void
 */
function wpl_elementor_events_tracker() {

	load_plugin_textdomain( 'wpl-elementor-events-tracker' );

	if ( ! did_action( 'elementor/loaded' ) ) {
		add_action( 'admin_notices', __NAMESPACE__ . '\wpl_elementor_pro_fail_load' );

		return;
	}

	require_once WPL_ELEMENTOR_EVENTS_TRACKER_DIR . 'includes/class-main.php';
	new Main();
}

add_action( 'plugins_loaded', __NAMESPACE__ . '\wpl_elementor_events_tracker' );

/**
 * Show in WP Dashboard notice about the plugin is not activated.
 *
 * @since 1.1
 *
 * @return void
 */
function wpl_elementor_pro_fail_load() {
	$message = sprintf(
	/* translators: 1: Plugin name 2: Elementor */
		esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'wpl-elementor-events-tracker' ),
		'<strong>' . esc_html__( 'Events Tracker For Elementor', 'wpl-elementor-events-tracker' ) . '</strong>',
		'<strong>' . esc_html__( 'Elementor', 'wpl-elementor-events-tracker' ) . '</strong>'
	);

	echo '<div class="error"><p>' . $message . '</p></div>';
}

// eol.
