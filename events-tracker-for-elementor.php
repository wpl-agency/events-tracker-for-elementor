<?php
/**
 * Events Tracker For Elementor
 *
 * @link              https://wordpress.org/plugins/events-tracker-for-elementor/
 * @package           events-tracker-for-elementor
 *
 * @wordpress-plugin
 * Plugin Name:       Events Tracker for Elementor
 * Plugin URI:        https://wordpress.org/plugins/events-tracker-for-elementor/
 * Description:       Track Click or Submit events and conversions for any Elementor widget with Google Analytics, Facebook, Yandex Metrika, Vkontakte.
 * Version:           1.2.3
 * Author:            wpl.agency
 * Author URI:        https://wpl.agency/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       events-tracker-for-elementor
 * Domain Path:       /languages
 */
namespace WPL\Events_Tracker_For_Elementor;

if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'WPL_ELEMENTOR_EVENTS_TRACKER_VERSION', '1.2.3' );
define( 'WPL_ELEMENTOR_EVENTS_TRACKER_SLUG', 'events_tracker_for_elementor' );
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
function wpl_events_tracker_for_elementor() {

	load_plugin_textdomain( 'events-tracker-for-elementor' );

	if ( ! did_action( 'elementor/loaded' ) ) {
		add_action( 'admin_notices', __NAMESPACE__ . '\wpl_events_tracker_for_elementor_fail_load' );

		return;
	}

	require_once __DIR__ . '/includes/class-options.php';
	require_once __DIR__ . '/includes/class-main.php';

	$options = new Options();
	$main    = new Main( $options );
}

add_action( 'plugins_loaded', __NAMESPACE__ . '\wpl_events_tracker_for_elementor' );

/**
 * Show in WP Dashboard notice about the plugin is not activated.
 *
 * @since 1.1
 *
 * @return void
 */
function wpl_events_tracker_for_elementor_fail_load() {
	$message = sprintf(
		/* translators: 1: Plugin name 2: Elementor */
		esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'events-tracker-for-elementor' ),
		'<strong>' . esc_html__( 'Events Tracker For Elementor', 'events-tracker-for-elementor' ) . '</strong>',
		'<strong>' . esc_html__( 'Elementor', 'events-tracker-for-elementor' ) . '</strong>'
	);

	echo '<div class="error"><p>' . $message . '</p></div>';
}

// eol.
