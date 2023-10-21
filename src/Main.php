<?php
/**
 * Main class.
 *
 * @package events-tracker-for-elementor
 */

namespace WPL\Events_Tracker_For_Elementor;

use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use Elementor\Element_Base;
use Elementor\Plugin;
use Elementor\Widget_Base;

/**
 * Main class.
 */
class Main {
	/**
	 * Options instance.
	 *
	 * @var Options $options
	 */
	private $options;

	/**
	 * Array of allowed widgets to tracking.
	 *
	 * @var array $allowed_widgets
	 */
	private $allowed_widgets = [
		'button'         => [
			'section' => 'section_button',
			'element' => '_wrapper',
		],
		'call-to-action' => [
			'section' => 'section_ribbon',
			'element' => '_wrapper',
		],
		'form'           => [
			'section' => 'section_form_options',
			'element' => 'form',
		],
		'heading'        => [
			'section' => 'section_title',
			'element' => '_wrapper',
		],
		'image'          => [
			'section' => 'section_image',
			'element' => '_wrapper',
		],
		'price-table'    => [
			'section' => 'section_ribbon',
			'element' => '_wrapper',
		],
		'icon-list'      => [
			'section' => 'section_icon',
			'element' => '_wrapper',
		],
	];

	/**
	 * Advanced widgets list.
	 *
	 * @var string[][] $advanced_widgets
	 */
	private $advanced_widgets = [
		'flip-box'      => [
			'section' => 'section_box_settings',
			'element' => '_wrapper',
		],
		'image-box'     => [
			'section' => 'section_image',
			'element' => '_wrapper',
		],
		'icon'          => [
			'section' => 'section_icon',
			'element' => '_wrapper',
		],
		'icon-box'      => [
			'section' => 'section_icon',
			'element' => '_wrapper',
		],
		'paypal-button' => [
			'section' => 'section_settings',
			'element' => 'button',
		],
	];

	/**
	 * Main constructor.
	 *
	 * @param Options $options Options instance.
	 *
	 * @return void
	 */
	public function __construct( Options $options ) {
		$this->options = $options;

		$this->allowed_widgets = apply_filters( 'wpl/events_tracker_for_elementor/allowed_widgets', $this->allowed_widgets );

		do_action( 'wpl/events_tracker_for_elementor/init', $this );
	}

	/**
	 * Get allowed widgets.
	 *
	 * @return array
	 */
	public function get_allowed_widgets(): array {
		return $this->allowed_widgets;
	}

	/**
	 * Get advanced widgets.
	 *
	 * @return array
	 */
	public function get_advanced_widgets(): array {
		return $this->advanced_widgets;
	}

	/**
	 * Register hooks
	 *
	 * @return void
	 */
	public function setup_hooks(): void {

		if ( is_array( $this->get_allowed_widgets() ) ) {
			foreach ( $this->get_allowed_widgets() as $widget => $hook ) {
				// Repeaters.
				if ( isset( $hook['control'] ) ) {
					add_action(
						'elementor/element/' . $widget . '/' . $hook['section'] . '/before_section_end',
						array(
							$this,
							'add_tracking_controls_for_repeaters',
						)
					);
				} else {
					add_action(
						'elementor/element/' . $widget . '/' . $hook['section'] . '/after_section_end',
						array(
							$this,
							'add_tracking_controls',
						),
						10,
						2
					);
				}
			}
		}

		if ( is_array( $this->get_advanced_widgets() ) ) {
			foreach ( $this->get_advanced_widgets() as $widget => $hook ) {
				add_action(
					'elementor/element/' . $widget . '/' . $hook['section'] . '/after_section_end',
					array(
						$this,
						'add_pro_section',
					),
					10,
					2
				);
			}
		}

		add_action( 'elementor/widget/before_render_content', array( $this, 'before_render' ) );
		add_action( 'elementor/frontend/before_enqueue_scripts', array( $this, 'enqueue_scripts' ), 9 );
		add_action( 'wp_footer', [ $this, 'add_tracker_code_to_footer' ] );
		add_action( 'wp_head', [ $this, 'add_tracker_code_to_header' ] );
		add_action( 'wp_body_open', [ $this, 'add_tracker_code_to_body' ] );

		add_filter( 'plugin_action_links', [ $this, 'add_settings_link' ], 10, 2 );
		add_filter( 'admin_footer_text', array( $this, 'admin_footer_text' ), 999 );
	}

	/**
	 * Add pro section.
	 *
	 * @param Controls_Stack $element    Controls_Stack instance.
	 * @param string         $section_id Section ID.
	 *
	 * @return void
	 */
	public function add_pro_section( Controls_Stack $element, $section_id ) {

		if ( Utils::is_pro_actived() ) {
			return;
		}

		$element->start_controls_section(
			'events_tracker_for_elementor_advanced_section_go_advanced',
			array(
				'label' => esc_html__( 'Events Tracking', 'events-tracker-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$element->add_control(
			'events_tracker_for_elementor_advanced_go_advanced',
			array(
				'type'        => Controls_Manager::RAW_HTML,
				'raw'         => '<div style="text-align: center; font-size: 11px; line-height: 18px;"><b style="font-size: 12px">Want to squeeze extra from your marketing?</b><br>Track more with <a href="https://wpl.agency/events-tracker-for-elementor/?utm_source=plugin&utm_medium=banner&utm_campaign=tracker_advanced&utm_content=spoiler" target="_blank" style="color: #0A5EF2; font-weight: bold">advanced version</a></div>',
				'render_type' => 'none',
				'show_label'  => false,
			)
		);

		$element->end_controls_section();
	}

	/**
	 * Add tracking controls for repeaters.
	 *
	 * @param Widget_Base $widget Widget_Base instance.
	 *
	 * @return void
	 */
	public function add_tracking_controls_for_repeaters( Widget_Base $widget ) {
		$elementor   = Plugin::instance()->controls_manager;
		$widget_name = $widget->get_unique_name();
		$all_widgets = $this->get_allowed_widgets();
		$control_id  = $all_widgets[ $widget_name ]['control'];

		$control_data = $elementor->get_control_from_stack( $widget_name, $control_id );

		if ( is_wp_error( $control_data ) ) {
			return;
		}

		$controls = [
			'кцукцукцу'   =>
				[
					'name'  => 'events_tracker_for_elementor_vkontaktee',
					'label' => __( 'Events Tracking', 'masked-input-for-elementor' ),
					'type'  => Controls_Manager::HEADING,
					'tab'   => 'events',
				],
			'masked'      =>
				[
					'name'  => 'events_tracker_for_elementor_vkontakte',
					'label' => __( 'VK', 'masked-input-for-elementor' ),
					'type'  => Controls_Manager::SWITCHER,
					'tab'   => 'events',
				],
			'maskeed'     =>
				[
					'name'  => 'events_tracker_for_elementor_vkontakteeeee',
					'label' => __( 'Yandex', 'masked-input-for-elementor' ),
					'type'  => Controls_Manager::SWITCHER,
					'tab'   => 'events',
				],
			'masked_type' =>
				[
					'name'       => 'events_tracker_for_elementor_vkontakte_event_name',
					'label'      => __( 'Event Name', 'masked-input-for-elementor' ),
					'type'       => Controls_Manager::TEXT,
					'default'    => '',
					'conditions' => [
						'terms' => [
							[
								'name'     => 'events_tracker_for_elementor_vkontakte',
								'operator' => '==',
								'value'    => 'yes',
							],
						],
					],
				],
		];

		// $control_data

		$control_data['fields'] = array_merge( $control_data['fields'], $controls );

		$elementor->update_control_in_stack( $widget, $control_id, $control_data );
	}

	/**
	 * Add plugin action links
	 *
	 * @param array  $actions     Array of actions.
	 * @param string $plugin_file Plugin file.
	 *
	 * @return array
	 */
	public function add_settings_link( $actions, $plugin_file ) {
		if ( 'events-tracker-for-elementor/events-tracker-for-elementor.php' === $plugin_file ) {
			$actions[] = sprintf(
				'<a href="%s">%s</a>',
				admin_url( 'admin.php?page=elementor#tab-events_tracker_for_elementor' ),
				esc_html__( 'Settings', 'events-tracker-for-elementor' )
			);

			if ( ! Utils::is_pro_actived() ) {
				$actions[] = sprintf(
					'<a href="%s" target="_blank" style="color: #0A5EF2; font-weight: bold">%s</a>',
					esc_url( 'https://wpl.agency/events-tracker-for-elementor/?utm_source=plugin&utm_medium=link&utm_campaign=tracker_advanced&utm_content=plugins_list' ),
					esc_html__( 'Go Advanced', 'events-tracker-for-elementor' )
				);
			}
		}

		return $actions;
	}

	/**
	 * Add admin footer text.
	 *
	 * @param string $text Default text.
	 *
	 * @return string
	 */
	public function admin_footer_text( $text ) {

		$current_screen = get_current_screen();

		$white_list = array(
			'toplevel_page_elementor',
		);

		if ( isset( $current_screen ) && in_array( $current_screen->id, $white_list, true ) ) {
			$text = '<span class="mytf-admin-footer-text">';
			// translators: Admin footer text.
			$text .= sprintf( __( 'Enjoyed <strong>Events Tracker For Elementor</strong>? Please leave us a <a href="%s" target="_blank" title="Rate & review it">★★★★★</a> rating. We really appreciate your support', 'events-tracker-for-elementor' ), 'https://wordpress.org/support/plugin/events-tracker-for-elementor/reviews/#new-post' );
			$text .= '</span>';
		}

		return $text;
	}

	/**
	 * Get option value for plugin.
	 *
	 * @param string $key     Option name.
	 * @param bool   $default Default value.
	 *
	 * @return mixed|void
	 */
	public function get_option( $key, $default = false ) {
		return get_option( 'elementor_' . WPL_ELEMENTOR_EVENTS_TRACKER_SLUG . '_' . $key, $default );
	}

	/**
	 * Add tracker codes to site header.
	 */
	public function add_tracker_code_to_header() {
		$gtm_id = $this->get_option( 'gtm_id' );

		if ( $gtm_id ) {
			?>
			<!-- Google Tag Manager -->
			<script>(function (w, d, s, l, i) {
					w[l] = w[l] || [];
					w[l].push({
						'gtm.start':
							new Date().getTime(), event: 'gtm.js'
					});
					var f = d.getElementsByTagName(s)[0],
						j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
					j.async = true;
					j.src =
						'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
					f.parentNode.insertBefore(j, f);
				})(window, document, 'script', 'dataLayer', '<?php echo esc_js( $gtm_id ); ?>');</script>
			<!-- End Google Tag Manager -->
			<?php
		}
	}

	/**
	 * Add tracker codes to site body.
	 */
	public function add_tracker_code_to_body() {
		$gtm_id = $this->get_option( 'gtm_id' );

		if ( $gtm_id ) {
			?>
			<!-- Google Tag Manager (noscript) -->
			<noscript>
				<iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo esc_js( $gtm_id ); ?>" height="0"
						width="0" style="display:none;visibility:hidden"></iframe>
			</noscript>
			<!-- End Google Tag Manager (noscript) -->
			<?php
		}
	}

	/**
	 * Add tracker codes to site footer.
	 */
	public function add_tracker_code_to_footer() {
		$vkontakte_pixel_id       = $this->get_option( 'vkontakte_pixel_id' );
		$yandex_metrika_code_type = (array) $this->get_option( 'yandex_metrika_code_type' );
		$yandex_metrika_id        = $this->get_option( 'yandex_metrika_id' );
		$facebook_pixel_id        = $this->get_option( 'facebook_pixel_id' );
		$gtag_id                  = $this->get_option( 'gtag_id' );
		$gtag_code_type           = (array) $this->get_option( 'gtag_code_type' );
		$adwords_id               = $this->get_option( 'adwords_id' );
		$adwords_code_type        = (array) $this->get_option( 'adwords_code_type' );
		$analytics_id             = $this->get_option( 'analytics_id' );
		$analytics_code_type      = (array) $this->get_option( 'analytics_code_type' );

		if ( $vkontakte_pixel_id ) {
			?>
			<script type="text/javascript">!function () {
					var t = document.createElement("script");
					t.type = "text/javascript", t.async = !0, t.src = "https://vk.com/js/api/openapi.js?162", t.onload = function () {
						VK.Retargeting.Init("<?php echo esc_js( $vkontakte_pixel_id ); ?>"), VK.Retargeting.Hit()
					}, document.head.appendChild(t)
				}();</script>
			<noscript><img src="https://vk.com/rtrg?p=<?php echo esc_js( $vkontakte_pixel_id ); ?>" style="position:fixed; left:-999px;" alt=""/></noscript>
			<?php
		}

		if ( $yandex_metrika_id && in_array( 'tracking', $yandex_metrika_code_type, true ) ) {
			// Настройки метрики по умолчанию.
			$yandex_metrika_config = array(
				'clickmap'            => true,
				'trackLinks'          => true,
				'accurateTrackBounce' => true,
				'trackHash'           => true,
			);

			// Вебвизор, карта скроллинга, аналитика форм.
			if ( in_array( 'webvisor', $yandex_metrika_code_type, true ) ) {
				$yandex_metrika_config['webvisor'] = true;
			}
			?>
			<!-- Yandex.Metrika counter -->
			<script type="text/javascript">
				(function (m, e, t, r, i, k, a) {
					m[i] = m[i] || function () {
						(m[i].a = m[i].a || []).push(arguments)
					};
					m[i].l = 1 * new Date();
					k = e.createElement(t), a = e.getElementsByTagName(t)[0], k.async = 1, k.src = r, a.parentNode.insertBefore(k, a)
				})
				(window, document, "script", "https://cdn.jsdelivr.net/npm/yandex-metrica-watch/tag.js", "ym");

				ym(<?php echo esc_js( $yandex_metrika_id ); ?>, "init", <?php echo wp_json_encode( $yandex_metrika_config ); ?>);
			</script>
			<noscript>
				<div><img src="https://mc.yandex.ru/watch/<?php echo esc_js( $yandex_metrika_id ); ?>" style="position:absolute; left:-9999px;" alt=""/></div>
			</noscript>
			<!-- /Yandex.Metrika counter -->
			<?php
		}

		if ( $facebook_pixel_id ) {
			?>
			<!-- Facebook Pixel Code -->
			<script>
				!function (f, b, e, v, n, t, s) {
					if (f.fbq) return;
					n = f.fbq = function () {
						n.callMethod ?
							n.callMethod.apply(n, arguments) : n.queue.push(arguments)
					};
					if (!f._fbq) f._fbq = n;
					n.push = n;
					n.loaded = !0;
					n.version = '2.0';
					n.queue = [];
					t = b.createElement(e);
					t.async = !0;
					t.src = v;
					s = b.getElementsByTagName(e)[0];
					s.parentNode.insertBefore(t, s)
				}(window, document, 'script',
					'https://connect.facebook.net/en_US/fbevents.js');
				fbq('init', '<?php echo esc_js( $facebook_pixel_id ); ?>');
				fbq('track', 'PageView');
			</script>
			<noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=<?php echo esc_js( $facebook_pixel_id ); ?>&ev=PageView&noscript=1" alt=""/></noscript>
			<!-- End Facebook Pixel Code -->
			<?php
		}

		if ( $gtag_id && in_array( 'tracking', $gtag_code_type, true ) ) {
			?>
			<!-- Global site tag (gtag.js) - Google Analytics -->
			<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_js( $gtag_id ); ?>"></script>
			<script>
				window.dataLayer = window.dataLayer || [];

				function gtag() {
					dataLayer.push(arguments);
				}

				gtag('js', new Date());
				gtag('config', '<?php echo esc_js( $gtag_id ); ?>');
			</script>
			<?php
		}

		if ( $adwords_id ) {
			?>
			<!-- Global site tag (gtag.js) - Google Analytics -->
			<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_js( $gtag_id ); ?>"></script>
			<script>
				window.dataLayer = window.dataLayer || [];

				function gtag() {
					dataLayer.push(arguments);
				}

				gtag('js', new Date());
				gtag('config', '<?php echo esc_js( $gtag_id ); ?>');
			</script>
			<?php
		}

		if ( $analytics_id && in_array( 'tracking', $analytics_code_type, true ) ) {
			?>
			<!-- Google Analytics -->
			<script>
				window.ga = window.ga || function () {
					(ga.q = ga.q || []).push(arguments)
				};
				ga.l = +new Date;
				ga('create', '<?php echo esc_js( $analytics_id ); ?>', 'auto');
				ga('send', 'pageview');
			</script>
			<script async src='https://www.google-analytics.com/analytics.js'></script>
			<!-- End Google Analytics -->
			<?php
		}
	}

	/**
	 * Add required scripts.
	 */
	public function enqueue_scripts() {
		wp_enqueue_script(
			WPL_ELEMENTOR_EVENTS_TRACKER_SLUG . '_app',
			WPL_ELEMENTOR_EVENTS_TRACKER_URL . 'frontend/js/app.js',
			array( 'jquery', 'elementor-frontend' ),
			filemtime( WPL_ELEMENTOR_EVENTS_TRACKER_DIR . 'frontend/js/app.js' ),
			true
		);
	}

	/**
	 * Add new Events Tracking section to buttons/forms
	 *
	 * @param Element_Base $element Element_Base instance.
	 * @param array        $args    Array of arguments.
	 */
	public function add_tracking_controls( $element, $args ) {

		// Element name.
		$name = $element->get_name();

		$element->start_controls_section(
			'events_tracker_for_elementor',
			array(
				'label' => esc_html__( 'Events Tracking', 'events-tracker-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$element->add_control(
			'events_tracker_for_elementor_gtag',
			array(
				'label'       => esc_html__( 'Track with Google Universal Tag (gtag.js)', 'events-tracker-for-elementor' ),
				'type'        => Controls_Manager::SWITCHER,
				'render_type' => 'none',
			)
		);

		$element->add_control(
			'events_tracker_for_elementor_gtag_note',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'show_label'      => false,
				'raw'             => esc_html__( 'Tracking events with new Google Analytics code (gtag.js)', 'events-tracker-for-elementor' ),
				'condition'       => array(
					'events_tracker_for_elementor_gtag' => 'yes',
				),
				'render_type'     => 'none',
				'content_classes' => 'elementor-descriptor',
			)
		);

		$element->add_control(
			'events_tracker_for_elementor_gtag_category',
			array(
				'label'       => esc_html__( 'Event Category', 'events-tracker-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'show_label'  => true,
				'placeholder' => esc_html__( 'i.e Outbound Link', 'events-tracker-for-elementor' ),
				'condition'   => array(
					'events_tracker_for_elementor_gtag' => 'yes',
				),
				'render_type' => 'none',
			)
		);

		$element->add_control(
			'events_tracker_for_elementor_gtag_action',
			array(
				'label'       => esc_html__( 'Event Action', 'events-tracker-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'show_label'  => true,
				'placeholder' => esc_html__( 'i.e click', 'events-tracker-for-elementor' ),
				'condition'   => array(
					'events_tracker_for_elementor_gtag' => 'yes',
				),
				'render_type' => 'none',
			)
		);

		$element->add_control(
			'events_tracker_for_elementor_gtag_label',
			array(
				'label'       => esc_html__( 'Event Label', 'events-tracker-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'show_label'  => true,
				'placeholder' => esc_html__( 'i.e Fall Campaign', 'events-tracker-for-elementor' ),
				'condition'   => array(
					'events_tracker_for_elementor_gtag' => 'yes',
				),
				'render_type' => 'none',
			)
		);

		$element->add_control(
			'events_tracker_for_elementor_adwords',
			array(
				'label'       => esc_html__( 'Track Adwords Conversion (gtag.js)', 'events-tracker-for-elementor' ),
				'type'        => Controls_Manager::SWITCHER,
				'render_type' => 'none',
			)
		);

		$element->add_control(
			'events_tracker_for_elementor_adwords_label',
			array(
				'label'       => esc_html__( 'Event Label', 'events-tracker-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'show_label'  => true,
				'placeholder' => esc_html__( 'bC-D_efG-h12_34-567', 'events-tracker-for-elementor' ),
				'condition'   => array(
					'events_tracker_for_elementor_adwords' => 'yes',
				),
				'render_type' => 'none',
			)
		);

		/**
		 * Add a currency control.
		 *
		 * @link https://support.google.com/analytics/answer/6205902
		 */
		$element->add_control(
			'events_tracker_for_elementor_adwords_currency',
			array(
				'label'       => esc_html__( 'Event Currency', 'events-tracker-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => [
					'USD' => esc_html__( 'US Dollars', 'events-tracker-for-elementor' ),
					'AED' => esc_html__( 'United Arab Emirates Dirham', 'events-tracker-for-elementor' ),
					'ARS' => esc_html__( 'Argentine Pesos', 'events-tracker-for-elementor' ),
					'AUD' => esc_html__( 'Australian Dollars', 'events-tracker-for-elementor' ),
					'BGN' => esc_html__( 'Bulgarian Lev', 'events-tracker-for-elementor' ),
					'BOB' => esc_html__( 'Bolivian Boliviano', 'events-tracker-for-elementor' ),
					'BRL' => esc_html__( 'Brazilian Real', 'events-tracker-for-elementor' ),
					'CAD' => esc_html__( 'Canadian Dollars', 'events-tracker-for-elementor' ),
					'CHF' => esc_html__( 'Swiss Francs', 'events-tracker-for-elementor' ),
					'CLP' => esc_html__( 'Chilean Peso', 'events-tracker-for-elementor' ),
					'CNY' => esc_html__( 'Yuan Renminbi', 'events-tracker-for-elementor' ),
					'COP' => esc_html__( 'Colombian Peso', 'events-tracker-for-elementor' ),
					'CZK' => esc_html__( 'Czech Koruna', 'events-tracker-for-elementor' ),
					'DKK' => esc_html__( 'Denmark Kroner', 'events-tracker-for-elementor' ),
					'EGP' => esc_html__( 'Egyptian Pound', 'events-tracker-for-elementor' ),
					'EUR' => esc_html__( 'Euros', 'events-tracker-for-elementor' ),
					'FRF' => esc_html__( 'French Francs', 'events-tracker-for-elementor' ),
					'GBP' => esc_html__( 'British Pounds', 'events-tracker-for-elementor' ),
					'HKD' => esc_html__( 'Hong Kong Dollars', 'events-tracker-for-elementor' ),
					'HRK' => esc_html__( 'Croatian Kuna', 'events-tracker-for-elementor' ),
					'HUF' => esc_html__( 'Hungarian Forint', 'events-tracker-for-elementor' ),
					'IDR' => esc_html__( 'Indonesian Rupiah', 'events-tracker-for-elementor' ),
					'ILS' => esc_html__( 'Israeli Shekel', 'events-tracker-for-elementor' ),
					'INR' => esc_html__( 'Indian Rupee', 'events-tracker-for-elementor' ),
					'JPY' => esc_html__( 'Japanese Yen', 'events-tracker-for-elementor' ),
					'KRW' => esc_html__( 'South Korean Won', 'events-tracker-for-elementor' ),
					'LTL' => esc_html__( 'Lithuanian Litas', 'events-tracker-for-elementor' ),
					'MAD' => esc_html__( 'Moroccan Dirham', 'events-tracker-for-elementor' ),
					'MXN' => esc_html__( 'Mexican Peso', 'events-tracker-for-elementor' ),
					'MYR' => esc_html__( 'Malaysian Ringgit', 'events-tracker-for-elementor' ),
					'NOK' => esc_html__( 'Norway Kroner', 'events-tracker-for-elementor' ),
					'NZD' => esc_html__( 'New Zealand Dollars', 'events-tracker-for-elementor' ),
					'PEN' => esc_html__( 'Peruvian Nuevo Sol', 'events-tracker-for-elementor' ),
					'PHP' => esc_html__( 'Philippine Peso', 'events-tracker-for-elementor' ),
					'PKR' => esc_html__( 'Pakistan Rupee', 'events-tracker-for-elementor' ),
					'PLN' => esc_html__( 'Polish New Zloty', 'events-tracker-for-elementor' ),
					'RON' => esc_html__( 'New Romanian Leu', 'events-tracker-for-elementor' ),
					'RSD' => esc_html__( 'Serbian Dinar', 'events-tracker-for-elementor' ),
					'RUB' => esc_html__( 'Russian Ruble', 'events-tracker-for-elementor' ),
					'SAR' => esc_html__( 'Saudi Riyal', 'events-tracker-for-elementor' ),
					'SEK' => esc_html__( 'Sweden Kronor', 'events-tracker-for-elementor' ),
					'SGD' => esc_html__( 'Singapore Dollars', 'events-tracker-for-elementor' ),
					'THB' => esc_html__( 'Thai Baht', 'events-tracker-for-elementor' ),
					'TRY' => esc_html__( 'Turkish Lira', 'events-tracker-for-elementor' ),
					'TWD' => esc_html__( 'New Taiwan Dollar', 'events-tracker-for-elementor' ),
					'UAH' => esc_html__( 'Ukrainian Hryvnia', 'events-tracker-for-elementor' ),
					'VEF' => esc_html__( 'Venezuela Bolivar Fuerte', 'events-tracker-for-elementor' ),
					'VND' => esc_html__( 'Vietnamese Dong', 'events-tracker-for-elementor' ),
					'ZAR' => esc_html__( 'South African Rand', 'events-tracker-for-elementor' ),
				],
				'default'     => 'RUB',
				'show_label'  => true,
				'condition'   => array(
					'events_tracker_for_elementor_adwords' => 'yes',
				),
				'render_type' => 'none',
			)
		);

		$element->add_control(
			'events_tracker_for_elementor_adwords_value',
			array(
				'label'       => esc_html__( 'Event Value', 'events-tracker-for-elementor' ),
				'type'        => Controls_Manager::NUMBER,
				'show_label'  => true,
				'placeholder' => esc_html__( 'i.e 100', 'events-tracker-for-elementor' ),
				'default'     => 0,
				'condition'   => array(
					'events_tracker_for_elementor_adwords' => 'yes',
				),
				'render_type' => 'none',
			)
		);

		$element->add_control(
			'events_tracker_for_elementor_analytics',
			array(
				'label'       => esc_html__( 'Track with Google Analytics (analytics.js)', 'events-tracker-for-elementor' ),
				'type'        => Controls_Manager::SWITCHER,
				'render_type' => 'none',
			)
		);

		$element->add_control(
			'events_tracker_for_elementor_analytics_note',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'show_label'      => false,
				'raw'             => esc_html__( 'Tracking events with old Google Analytics code (analytics.js)', 'events-tracker-for-elementor' ),
				'condition'       => array(
					'events_tracker_for_elementor_analytics' => 'yes',
				),
				'render_type'     => 'none',
				'content_classes' => 'elementor-descriptor',
			)
		);

		$element->add_control(
			'events_tracker_for_elementor_analytics_category',
			array(
				'label'       => esc_html__( 'Event Category', 'events-tracker-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'show_label'  => true,
				'placeholder' => esc_html__( 'i.e Outbound Link', 'events-tracker-for-elementor' ),
				'condition'   => array(
					'events_tracker_for_elementor_analytics' => 'yes',
				),
				'render_type' => 'none',
			)
		);

		$element->add_control(
			'events_tracker_for_elementor_analytics_action',
			array(
				'label'       => esc_html__( 'Event Action', 'events-tracker-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'show_label'  => true,
				'placeholder' => esc_html__( 'i.e click', 'events-tracker-for-elementor' ),
				'condition'   => array(
					'events_tracker_for_elementor_analytics' => 'yes',
				),
				'render_type' => 'none',
			)
		);

		$element->add_control(
			'events_tracker_for_elementor_analytics_label',
			array(
				'label'       => esc_html__( 'Event Label', 'events-tracker-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'show_label'  => true,
				'placeholder' => esc_html__( 'i.e Fall Campaign', 'events-tracker-for-elementor' ),
				'condition'   => array(
					'events_tracker_for_elementor_analytics' => 'yes',
				),
				'render_type' => 'none',
			)
		);

		$element->add_control(
			'events_tracker_for_elementor_gtm',
			array(
				'label'       => esc_html__( 'Track with Google Tag Manager', 'events-tracker-for-elementor' ),
				'type'        => Controls_Manager::SWITCHER,
				'render_type' => 'none',
			)
		);

		// Hidden control from Button & Form widgets.
		if ( ! in_array( $name, [ 'form', 'button' ], true ) ) {
			$element->add_control(
				'events_tracker_for_elementor_gtm_css_id',
				array(
					'label'       => esc_html__( 'CSS ID', 'events-tracker-for-elementor' ),
					'type'        => Controls_Manager::TEXT,
					'show_label'  => true,
					'placeholder' => esc_html__( 'Without #', 'events-tracker-for-elementor' ),
					'condition'   => array(
						'events_tracker_for_elementor_gtm' => 'yes',
					),
					'render_type' => 'none',
				)
			);
		}

		$element->add_control(
			'events_tracker_for_elementor_facebook',
			array(
				'label'       => esc_html__( 'Track with Facebook', 'events-tracker-for-elementor' ),
				'type'        => Controls_Manager::SWITCHER,
				'render_type' => 'none',
			)
		);

		$element->add_control(
			'events_tracker_for_elementor_facebook_event_name',
			array(
				'label'       => esc_html__( 'Facebook Event', 'events-tracker-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => [
					'AddPaymentInfo'       => esc_html__( 'AddPaymentInfo', 'events-tracker-for-elementor' ),
					'AddToCart'            => esc_html__( 'AddToCart', 'events-tracker-for-elementor' ),
					'AddToWishlist'        => esc_html__( 'AddToWishlist', 'events-tracker-for-elementor' ),
					'CompleteRegistration' => esc_html__( 'CompleteRegistration', 'events-tracker-for-elementor' ),
					'Contact'              => esc_html__( 'Contact', 'events-tracker-for-elementor' ),
					'CustomizeProduct'     => esc_html__( 'CustomizeProduct', 'events-tracker-for-elementor' ),
					'Donate'               => esc_html__( 'Donate', 'events-tracker-for-elementor' ),
					'FindLocation'         => esc_html__( 'FindLocation', 'events-tracker-for-elementor' ),
					'InitiateCheckout'     => esc_html__( 'InitiateCheckout', 'events-tracker-for-elementor' ),
					'Lead'                 => esc_html__( 'Lead', 'events-tracker-for-elementor' ),
					'Purchase'             => esc_html__( 'Purchase', 'events-tracker-for-elementor' ),
					'Schedule'             => esc_html__( 'Schedule', 'events-tracker-for-elementor' ),
					'Search'               => esc_html__( 'Search', 'events-tracker-for-elementor' ),
					'StartTrial'           => esc_html__( 'StartTrial', 'events-tracker-for-elementor' ),
					'SubmitApplication'    => esc_html__( 'SubmitApplication', 'events-tracker-for-elementor' ),
					'Subscribe'            => esc_html__( 'Subscribe', 'events-tracker-for-elementor' ),
					'ViewContent'          => esc_html__( 'ViewContent', 'events-tracker-for-elementor' ),
					'Custom'               => esc_html__( 'Custom', 'events-tracker-for-elementor' ),
				],
				'default'     => 'ViewContent',
				'condition'   => array(
					'events_tracker_for_elementor_facebook' => 'yes',
				),
				'render_type' => 'none',
			)
		);

		$element->add_control(
			'events_tracker_for_elementor_facebook_event_name_custom',
			array(
				'label'       => esc_html__( 'Custom Event', 'events-tracker-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'show_label'  => true,
				'placeholder' => esc_html__( 'i.e Whatsapp', 'events-tracker-for-elementor' ),
				'condition'   => array(
					'events_tracker_for_elementor_facebook'            => 'yes',
					'events_tracker_for_elementor_facebook_event_name' => 'Custom',
				),
				'render_type' => 'none',
			)
		);

		$element->add_control(
			'events_tracker_for_elementor_yandex_metrika',
			array(
				'label'       => esc_html__( 'Track with Yandex Metrika', 'events-tracker-for-elementor' ),
				'type'        => Controls_Manager::SWITCHER,
				'render_type' => 'none',
			)
		);

		$element->add_control(
			'events_tracker_for_elementor_yandex_metrika_event_name',
			array(
				'label'       => esc_html__( 'Event Name', 'events-tracker-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'show_label'  => true,
				'placeholder' => esc_html__( 'i.e Lead', 'events-tracker-for-elementor' ),
				'condition'   => array(
					'events_tracker_for_elementor_yandex_metrika' => 'yes',
				),
				'render_type' => 'none',
			)
		);

		$element->add_control(
			'events_tracker_for_elementor_vkontakte',
			array(
				'label'       => esc_html__( 'Track with Vkontakte', 'events-tracker-for-elementor' ),
				'type'        => Controls_Manager::SWITCHER,
				'render_type' => 'none',
			)
		);

		$element->add_control(
			'events_tracker_for_elementor_vkontakte_event_name',
			array(
				'label'       => esc_html__( 'Event Name', 'events-tracker-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'show_label'  => true,
				'placeholder' => esc_html__( 'i.e Lead', 'events-tracker-for-elementor' ),
				'condition'   => array(
					'events_tracker_for_elementor_vkontakte' => 'yes',
				),
				'render_type' => 'none',
			)
		);

		$element->end_controls_section();
	}

	/**
	 * Before render hook.
	 *
	 * @param Widget_Base $element Widget_Base instance.
	 */
	public function before_render( $element ) {

		$name = $element->get_name();

		if ( isset( $this->allowed_widgets[ $name ] ) ) {

			$data = $element->get_data();

			$settings = $data['settings'];

			$attr         = array();
			$has_tracking = false;

			// Vkontakte.
			if (
				isset( $settings['events_tracker_for_elementor_vkontakte'] ) &&
				! empty( $settings['events_tracker_for_elementor_vkontakte_event_name'] )
			) {
				$has_tracking                 = true;
				$attr['vkontakte']            = true;
				$attr['vkontakte_event_name'] = $settings['events_tracker_for_elementor_vkontakte_event_name'];
			}

			// Yandex Metrika.
			if (
				isset( $settings['events_tracker_for_elementor_yandex_metrika'] ) &&
				! empty( $settings['events_tracker_for_elementor_yandex_metrika_event_name'] )
			) {
				$has_tracking                      = true;
				$attr['yandex_metrika']            = true;
				$attr['yandex_metrika_event_name'] = $settings['events_tracker_for_elementor_yandex_metrika_event_name'];
				$attr['yandex_metrika_id']         = $this->get_option( 'yandex_metrika_id' );
			}

			// Facebook.
			if (
				isset( $settings['events_tracker_for_elementor_facebook'] ) &&
				! empty( $settings['events_tracker_for_elementor_facebook_event_name'] )
			) {
				$has_tracking                = true;
				$attr['facebook']            = true;
				$attr['facebook_event_name'] = $settings['events_tracker_for_elementor_facebook_event_name'];

				if ( isset( $settings['events_tracker_for_elementor_facebook_event_name_custom'] ) ) {
					$attr['facebook_event_name_custom'] = $settings['events_tracker_for_elementor_facebook_event_name_custom'];
				}
			}

			// Google Analytics.
			if (
				isset( $settings['events_tracker_for_elementor_analytics'] ) &&
				! empty( $settings['events_tracker_for_elementor_analytics_category'] ) &&
				! empty( $settings['events_tracker_for_elementor_analytics_action'] ) &&
				! empty( $settings['events_tracker_for_elementor_analytics_label'] )
			) {
				$has_tracking               = true;
				$attr['analytics']          = true;
				$attr['analytics_category'] = $settings['events_tracker_for_elementor_analytics_category'];
				$attr['analytics_action']   = $settings['events_tracker_for_elementor_analytics_action'];
				$attr['analytics_label']    = $settings['events_tracker_for_elementor_analytics_label'];
			}

			// Google Global Tag (gtag).
			if (
				isset( $settings['events_tracker_for_elementor_gtag'] ) &&
				! empty( $settings['events_tracker_for_elementor_gtag_category'] ) &&
				! empty( $settings['events_tracker_for_elementor_gtag_action'] ) &&
				! empty( $settings['events_tracker_for_elementor_gtag_label'] )
			) {
				$has_tracking          = true;
				$attr['gtag']          = true;
				$attr['gtag_category'] = $settings['events_tracker_for_elementor_gtag_category'];
				$attr['gtag_action']   = $settings['events_tracker_for_elementor_gtag_action'];
				$attr['gtag_label']    = $settings['events_tracker_for_elementor_gtag_label'];
			}

			// Google Tag Manager (gtm).
			if ( isset( $settings['events_tracker_for_elementor_gtm'] ) ) {
				$has_tracking = true;
				$attr['gtm']  = true;
			}

			// Google Adwords Conversion (gtag).
			if (
				isset( $settings['events_tracker_for_elementor_adwords'] ) &&
				! empty( $settings['events_tracker_for_elementor_adwords_label'] ) &&
				! empty( $settings['events_tracker_for_elementor_adwords_currency'] ) &&
				! empty( $settings['events_tracker_for_elementor_adwords_value'] )
			) {
				$has_tracking             = true;
				$attr['adwords']          = true;
				$attr['adwords_label']    = $settings['events_tracker_for_elementor_adwords_label'];
				$attr['adwords_currency'] = $settings['events_tracker_for_elementor_adwords_currency'];
				$attr['adwords_value']    = $settings['events_tracker_for_elementor_adwords_value'];
				$attr['adwords_id']       = $this->get_option( 'adwords_id' );
			}

			if ( $has_tracking ) {
				$element->add_render_attribute(
					$this->allowed_widgets[ $name ]['element'],
					array(
						'data-wpl_tracker' => wp_json_encode( $attr ),
						'class'            => 'events-tracker-for-elementor',
					)
				);
			}

			if ( isset( $settings['events_tracker_for_elementor_gtm_css_id'] ) ) {
				$control = 'url';

				if ( 'image' === $name ) {
					$control = 'link';
				}

				$element->add_render_attribute(
					$control,
					'data-wpl_id',
					esc_attr( $settings['events_tracker_for_elementor_gtm_css_id'] )
				);
			}
		}
	}
}

// eol.
