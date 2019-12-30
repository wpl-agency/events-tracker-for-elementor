<?php
/**
 * @package events-tracker-for-elementor
 */
namespace WPL\Events_Tracker_For_Elementor;

use Elementor\Settings;

class Options {
	public function __construct() {
		$this->hooks();
	}

	public function hooks() {
		add_action( 'elementor/admin/after_create_settings/elementor', [ $this, 'register_settings' ] );
	}

	/**
	 * Create Setting Tab
	 *
	 * @param Settings $settings Elementor "Settings" page in WordPress Dashboard.
	 *
	 * @since 1.3
	 *
	 * @access public
	 */
	public function register_settings( Settings $settings ) {
		$settings->add_tab(
			WPL_ELEMENTOR_EVENTS_TRACKER_SLUG,
			[
				'label'    => __( 'Events Tracker', 'events-tracker-for-elementor' ),
				'sections' => [
					WPL_ELEMENTOR_EVENTS_TRACKER_SLUG . '_gtag' => [
						'label'  => __( 'Global Site Tag', 'events-tracker-for-elementor' ),
						'fields' => [
							WPL_ELEMENTOR_EVENTS_TRACKER_SLUG . '_gtag_id' => [
								'label'      => __( 'Global Site Tag ID (gtag.js)', 'events-tracker-for-elementor' ),
								'field_args' => [
									'type' => 'text',
									'desc' => __( 'Learn <a href="https://support.google.com/analytics/answer/1008080?hl=en" target="_blank">how to set up the Analytics tag</a> and where to get the code' ),
								],
							],
							WPL_ELEMENTOR_EVENTS_TRACKER_SLUG . '_gtag_code_type' => [
								'label'      => '',
								'field_args' => [
									'type'    => 'checkbox_list',
									'options' => [
										'tracking' => __( 'Add gtag simple tracking code', 'events-tracker-for-elementor' ),
									],
								],
							],
						],
					],
					WPL_ELEMENTOR_EVENTS_TRACKER_SLUG . '_adwords' => [
						'label'  => __( 'Google Adwords', 'events-tracker-for-elementor' ),
						'fields' => [
							WPL_ELEMENTOR_EVENTS_TRACKER_SLUG . '_adwords_id' => [
								'label'      => __( 'Adwords Converion ID (gtag.js)', 'events-tracker-for-elementor' ),
								'field_args' => [
									'type' => 'text',
									'desc' => __( 'Learn where to find <a href="https://support.google.com/google-ads/thread/1449693?hl=en" target="_blank">Google Ads Conversion ID</a>' ),
								],
							],
							WPL_ELEMENTOR_EVENTS_TRACKER_SLUG . '_adwords_code_type' => [
								'label'      => '',
								'field_args' => [
									'type'    => 'checkbox_list',
									'options' => [
										'tracking' => __( 'Add adwords simple tracking code', 'events-tracker-for-elementor' ),
									],
								],
							],
						],
					],
					WPL_ELEMENTOR_EVENTS_TRACKER_SLUG . '_analytics' => [
						'label'  => __( 'Google Analytics', 'events-tracker-for-elementor' ),
						'fields' => [
							WPL_ELEMENTOR_EVENTS_TRACKER_SLUG . '_analytics_id' => [
								'label'      => __( 'Google Analytics ID (analytics.js)', 'events-tracker-for-elementor' ),
								'field_args' => [
									'type' => 'text',
									'desc' => __( 'Know how to add <a href="https://developers.google.com/analytics/devguides/collection/analyticsjs" target="_blank">Analytics.js code</a> and <a href="https://support.google.com/analytics/answer/1008080?hl=en" target="_blank">where to get</a> the tracking code' ),
								],
							],
							WPL_ELEMENTOR_EVENTS_TRACKER_SLUG . '_analytics_code_type' => [
								'label'      => '',
								'field_args' => [
									'type'    => 'checkbox_list',
									'options' => [
										'tracking' => __( 'Add analytics simple tracking code', 'events-tracker-for-elementor' ),
									],
								],
							],
						],
					],

					WPL_ELEMENTOR_EVENTS_TRACKER_SLUG . '_gtm' => [
						'label'  => __( 'Google Tag Manager', 'events-tracker-for-elementor' ),
						'fields' => [
							WPL_ELEMENTOR_EVENTS_TRACKER_SLUG . '_gtm_id' => [
								'label'      => __( 'GTM ID', 'events-tracker-for-elementor' ),
								'field_args' => [
									'type' => 'text',
									'desc' => __( 'See Google Tag Manager <a href="https://developers.google.com/tag-manager/quickstart" target="_blank">Quick Start Guide</a>' ),
								],
							],
						],
					],

					WPL_ELEMENTOR_EVENTS_TRACKER_SLUG . '_facebook' => [
						'label'  => __( 'Facebook Pixel', 'events-tracker-for-elementor' ),
						'fields' => [
							WPL_ELEMENTOR_EVENTS_TRACKER_SLUG . '_facebook_pixel_id' => [
								'label'      => __( 'Facebook Pixel ID', 'events-tracker-for-elementor' ),
								'field_args' => [
									'type' => 'text',
									'desc' => __( 'Know how to create a <a href="https://www.facebook.com/business/help/952192354843755?id=1205376682832142" target="_blank">Facebook Pixel</a> and get a code.' ),
								],
							],
						],
					],
					WPL_ELEMENTOR_EVENTS_TRACKER_SLUG . '_yandex_metrika' => [
						'label'    => __( 'Yandex Metrika', 'events-tracker-for-elementor' ),
						'callback' => function() {
							//_e( 'See Yandex Metrika <a href="https://yandex.ru/support/metrica/quick-start.html?lang=en" target="_blank">Quick Start Guide</a>', 'events-tracker-for-elementor' );
						},
						'fields'   => [
							WPL_ELEMENTOR_EVENTS_TRACKER_SLUG . '_yandex_metrika_id' => [
								'label'      => __( 'Yandex Metrika ID', 'events-tracker-for-elementor' ),
								'field_args' => [
									'type' => 'text',
									'desc' => __( 'See Yandex Metrika <a href="https://yandex.ru/support/metrica/quick-start.html?lang=en" target="_blank">Quick Start Guide</a>', 'events-tracker-for-elementor' ),
								],
							],
							WPL_ELEMENTOR_EVENTS_TRACKER_SLUG . '_yandex_metrika_code_type' => [
								'label'      => '',
								'field_args' => [
									'type'    => 'checkbox_list',
									'options' => [
										'tracking' => __( 'Add YM simple tracking code', 'events-tracker-for-elementor' ),
										'webvisor' => __( 'Add YM Webvisor, scroll map and forms analytics', 'events-tracker-for-elementor' ),
									],
								],
							],
						],
					],
					WPL_ELEMENTOR_EVENTS_TRACKER_SLUG . '_vkontakte' => [
						'label'  => __( 'Vkontakte', 'events-tracker-for-elementor' ),
						'fields' => [
							WPL_ELEMENTOR_EVENTS_TRACKER_SLUG . '_vkontakte_pixel_id' => [
								'label'      => __( 'Vkontakte Pixel ID', 'events-tracker-for-elementor' ),
								'field_args' => [
									'type' => 'text',
									'desc' => __( 'See <a href="https://vk.com/faq12142" target="_blank">VK FAQ</a> to create pixel and get code' ),
								],
							],
						],
					],
				],
			]
		);
	}
}

// eol.
