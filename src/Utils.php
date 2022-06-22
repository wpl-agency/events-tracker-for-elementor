<?php
/**
 * Class Utils
 *
 * @package events-tracker-for-elementor
 */

namespace WPL\Events_Tracker_For_Elementor;

/**
 * Class Utils
 *
 * @package events-tracker-for-elementor
 */
class Utils {
	/**
	 * Check if pro activated.
	 *
	 * @return int|void
	 */
	public static function is_pro_actived() {
		return did_action( 'wpl/events-tracker-for-elementor-advanced/init' );
	}
}
