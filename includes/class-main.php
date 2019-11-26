<?php
/**
 * @package events-tracker-for-elementor
 */
namespace WPL\Events_Tracker_For_Elementor;

use Elementor\Controls_Manager;
use Elementor\Element_Base;
use Elementor\Settings;
use Elementor\Widget_Base;

class Main {
	/**
	 * @var array $allowed_widget Array of allowed widgets to tracking.
	 */
	private $allowed_widget = array( 'button', 'form', 'heading', 'image' );

	public function __construct() {
		$this->hooks();
	}

	/**
	 * Register hooks
	 */
	public function hooks() {
		add_action( 'elementor/element/button/section_button/after_section_end', array( $this, 'add_tracking_controls' ), 10, 2 );
		add_action( 'elementor/element/form/section_form_fields/after_section_end', array( $this, 'add_tracking_controls' ), 10, 2 );
		add_action( 'elementor/element/heading/section_title/after_section_end', array( $this, 'add_tracking_controls' ), 10, 2 );
		add_action( 'elementor/element/image/section_image/after_section_end', array( $this, 'add_tracking_controls' ), 10, 2 );
		add_action( 'elementor/widget/before_render_content', array( $this, 'before_render' ) );
		add_action( 'elementor/frontend/before_enqueue_scripts', array( $this, 'enqueue_scripts' ), 9 );
		add_action( 'elementor/admin/after_create_settings/elementor', [ $this, 'register_settings' ] );
		add_action( 'wp_footer', [ $this, 'add_tracker_code' ] );
	}

	/**
	 * Get option value for plugin.
	 *
	 * @param string $key
	 * @param bool   $default
	 *
	 * @return mixed|void
	 */
	public function get_option( $key, $default = false ) {
		return get_option( 'elementor_' . WPL_ELEMENTOR_EVENTS_TRACKER_SLUG . '_' . $key, $default );
	}

	/**
	 * Add tracker codes to site footer.
	 */
	public function add_tracker_code() {
		$vkontakte_pixel_id = $this->get_option( 'vkontakte_pixel_id' );
		$yandex_metrika_id  = $this->get_option( 'yandex_metrika_id' );
		$facebook_pixel_id  = $this->get_option( 'facebook_pixel_id' );
		$gtag_id            = $this->get_option( 'gtag_id' );
		$adwords_id         = $this->get_option( 'adwords_id' );
		$analytics_id       = $this->get_option( 'analytics_id' );

		if ( $vkontakte_pixel_id ) {
			?>
			<div id="vk_api_transport"></div>
			<script>
				var pixel;
				window.vkAsyncInit = function() {
					pixel = new VK.Pixel( '<?php echo esc_js( $vkontakte_pixel_id ); ?>' );
				};
				setTimeout(function() {
					var el = document.createElement( 'script' );
					el.type = 'text/javascript';
					el.src = 'https://vk.com/js/api/openapi.js?159';
					el.async = true;
					document.getElementById( 'vk_api_transport' ).appendChild(el);
				}, 0);
			</script>
			<?php
		}

		if ( $yandex_metrika_id ) {
			?>
			<!-- Yandex.Metrika counter -->
			<script type="text/javascript" >
				(function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
					m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
				(window, document, "script", "https://cdn.jsdelivr.net/npm/yandex-metrica-watch/tag.js", "ym");

				ym(<?php echo esc_js( $yandex_metrika_id ); ?>, "init", {
					clickmap:true,
					trackLinks:true,
					accurateTrackBounce:true,
					webvisor:true,
					trackHash:true
				});
			</script>
			<noscript><div><img src="https://mc.yandex.ru/watch/5695870" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
			<!-- /Yandex.Metrika counter -->
			<?php
		}

		if ( $facebook_pixel_id ) {
			?>
			<!-- Facebook Pixel Code -->
			<script>
				!function(f,b,e,v,n,t,s)
				{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
					n.callMethod.apply(n,arguments):n.queue.push(arguments)};
					if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
					n.queue=[];t=b.createElement(e);t.async=!0;
					t.src=v;s=b.getElementsByTagName(e)[0];
					s.parentNode.insertBefore(t,s)}(window, document,'script',
					'https://connect.facebook.net/en_US/fbevents.js');
				fbq('init', '<?php echo esc_js( $facebook_pixel_id ); ?>');
				fbq('track', 'PageView');
			</script>
			<noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=<?php echo esc_js( $facebook_pixel_id ); ?>&ev=PageView&noscript=1" alt="" /></noscript>
			<!-- End Facebook Pixel Code -->
			<?php
		}

		if ( $gtag_id || $adwords_id ) {
			?>
			<!-- Global site tag (gtag.js) - Google Analytics -->
			<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_js( $gtag_id ); ?>"></script>
			<script>
				window.dataLayer = window.dataLayer || [];
				function gtag(){dataLayer.push(arguments);}
				gtag('js', new Date());
				gtag('config', '<?php echo esc_js( $gtag_id ); ?>');
			</script>
			<?php
		}

		if ( $analytics_id ) {
			?>
			<!-- Google Analytics -->
			<script>
				window.ga=window.ga||function(){(ga.q=ga.q||[]).push(arguments)};ga.l=+new Date;
				ga('create', '<?php echo esc_js( $analytics_id ); ?>', 'auto');
				ga('send', 'pageview');
			</script>
			<script async src='https://www.google-analytics.com/analytics.js'></script>
			<!-- End Google Analytics -->
			<?php
		}
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
		$settings->add_section(
			Settings::TAB_INTEGRATIONS,
			WPL_ELEMENTOR_EVENTS_TRACKER_SLUG,
			[
				'label'    => __( 'Events Tracker', 'events-tracker-for-elementor' ),
				'callback' => function() {
					$message = __( '<p>After you select the service, the form appears. In this form, you need to provide your contact information. After you fill in the form, the “Service successfully connected” text appears. The created key is now available in the “Keys” section. Use it when you enable the API.</p>', 'events-tracker-for-elementor' );

					echo $message;
				},
				'fields'   => [
					WPL_ELEMENTOR_EVENTS_TRACKER_SLUG . '_gtag_id' => [
						'label'      => __( 'Global Site Tag ID (gtag.js)', 'events-tracker-for-elementor' ),
						'field_args' => [
							'type' => 'text',
							'desc' => __( 'Learn <a href="https://support.google.com/analytics/answer/1008080?hl=en" target="_blank">how to set up the Analytics tag</a> and where to get the code' ),
						],
					],
					WPL_ELEMENTOR_EVENTS_TRACKER_SLUG . '_adwords_id' => [
						'label'      => __( 'Adwords Converion ID (gtag.js)', 'events-tracker-for-elementor' ),
						'field_args' => [
							'type' => 'text',
							'desc' => __( 'Learn where to find <a href="https://support.google.com/google-ads/thread/1449693?hl=en" target="_blank">Google Ads Conversion ID</a>' ),
						],
					],
					WPL_ELEMENTOR_EVENTS_TRACKER_SLUG . '_analytics_id' => [
						'label'      => __( 'Google Analytics ID (analytics.js)', 'events-tracker-for-elementor' ),
						'field_args' => [
							'type' => 'text',
							'desc' => __( 'Know how to add <a href="https://developers.google.com/analytics/devguides/collection/analyticsjs" target="_blank">Analytics.js code</a> and <a href="https://support.google.com/analytics/answer/1008080?hl=en" target="_blank">where to get</a> the tracking code' ),
						],
					],
					WPL_ELEMENTOR_EVENTS_TRACKER_SLUG . '_facebook_pixel_id' => [
						'label'      => __( 'Facebook Pixel ID', 'events-tracker-for-elementor' ),
						'field_args' => [
							'type' => 'text',
							'desc' => __( 'Know how to create a <a href="https://www.facebook.com/business/help/952192354843755?id=1205376682832142" target="_blank">Facebook Pixel</a> and get a code.' ),
						],
					],
					WPL_ELEMENTOR_EVENTS_TRACKER_SLUG . '_yandex_metrika_id' => [
						'label'      => __( 'Yandex Metrika ID', 'events-tracker-for-elementor' ),
						'field_args' => [
							'type' => 'text',
							'desc' => __( 'See Yandex Metrika <a href="https://yandex.ru/support/metrica/quick-start.html?lang=en" target="_blank">Quick Start Guide</a>' ),
						],
					],
					WPL_ELEMENTOR_EVENTS_TRACKER_SLUG . '_vkontakte_pixel_id' => [
						'label'      => __( 'Vkontakte Pixel ID', 'events-tracker-for-elementor' ),
						'field_args' => [
							'type' => 'text',
							'desc' => __( 'See <a href="https://vk.com/faq12142" target="_blank">VK FAQ</a> to create pixel and get code' ),
						],
					],
				],
			]
		);
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
	 * @param Element_Base $element
	 * @param array $args
	 */
	public function add_tracking_controls( $element, $args ) {

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
	 * @param Widget_Base $element
	 */
	public function before_render( $element ) {

		if ( in_array( $element->get_name(), $this->allowed_widget ) ) {

			$data = $element->get_data();

			$settings     = $data['settings'];
			$attr         = array();
			$has_tracking = false;

			// Vkontakte.
			if ( isset( $settings['events_tracker_for_elementor_vkontakte'] ) ) {
				$has_tracking                 = true;
				$attr['vkontakte']            = true;
				$attr['vkontakte_event_name'] = $settings['events_tracker_for_elementor_vkontakte_event_name'];
			}

			// Yandex Metrika.
			if ( isset( $settings['events_tracker_for_elementor_yandex_metrika'] ) ) {
				$has_tracking                      = true;
				$attr['yandex_metrika']            = true;
				$attr['yandex_metrika_event_name'] = $settings['events_tracker_for_elementor_yandex_metrika_event_name'];
				$attr['yandex_metrika_id']         = $this->get_option( 'yandex_metrika_id' );
			}

			// Facebook.
			if ( isset( $settings['events_tracker_for_elementor_facebook'] ) ) {
				$has_tracking                = true;
				$attr['facebook']            = true;
				$attr['facebook_event_name'] = $settings['events_tracker_for_elementor_facebook_event_name'];

				if ( isset( $settings['events_tracker_for_elementor_facebook_event_name_custom'] ) ) {
					$attr['facebook_event_name_custom'] = $settings['events_tracker_for_elementor_facebook_event_name_custom'];
				}
			}

			// Google Analytics.
			if ( isset( $settings['events_tracker_for_elementor_analytics'] ) ) {
				$has_tracking               = true;
				$attr['analytics']          = true;
				$attr['analytics_category'] = $settings['events_tracker_for_elementor_analytics_category'];
				$attr['analytics_action']   = $settings['events_tracker_for_elementor_analytics_action'];
				$attr['analytics_label']    = $settings['events_tracker_for_elementor_analytics_label'];
			}

			// Google Global Tag (gtag).
			if ( isset( $settings['events_tracker_for_elementor_gtag'] ) ) {
				$has_tracking          = true;
				$attr['gtag']          = true;
				$attr['gtag_category'] = $settings['events_tracker_for_elementor_gtag_category'];
				$attr['gtag_action']   = $settings['events_tracker_for_elementor_gtag_action'];
				$attr['gtag_label']    = $settings['events_tracker_for_elementor_gtag_label'];
			}

			// Google Adwords Conversion (gtag).
			if ( isset( $settings['events_tracker_for_elementor_adwords'] ) ) {
				$has_tracking             = true;
				$attr['adwords']          = true;
				$attr['adwords_label']    = $settings['events_tracker_for_elementor_adwords_label'];
				$attr['adwords_currency'] = $settings['events_tracker_for_elementor_adwords_currency'];
				$attr['adwords_value']    = $settings['events_tracker_for_elementor_adwords_value'];
				$attr['adwords_id']       = $this->get_option( 'adwords_id' );
			}

			if ( $has_tracking ) {
				$element->add_render_attribute(
					'_wrapper',
					array(
						'data-wpl_tracker' => json_encode( $attr ),
						'class'            => 'events-tracker-for-elementor',
					)
				);
			}
		}
	}
}

// eol.
