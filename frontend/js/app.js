(
	function ( $, window, document ) {
		'use strict';

		$(
			function ( $ ) {
				$( document ).on(
					// Click - for buttons, submit_success - for forms.
					'click submit_success',
					'[data-wpl_tracker]',
					function ( e ) {
						var $element = $( this ),
							options  = $element.data( 'wpl_tracker' );

						track_element( options );
					}
				);

				function track_element( options ) {
					if ( options.vkontakte ) {
						track_vkontakte( options.vkontakte_event_name );
					}

					if ( options.yandex_metrika ) {
						track_yandex_metrika( options.yandex_metrika_event_name, options.yandex_metrika_id );
					}

					if ( options.gtag ) {
						track_gtag( options.gtag_event_name );
					}

					if ( options.analytics ) {
						track_analytics( options.analytics_category, options.analytics_action, options.analytics_label );
					}

					if ( options.facebook ) {
						if ( 'Custom' === options.facebook_event_name ) {
							track_facebook( options.facebook_event_name_custom, 'trackCustom' );
						} else {
							track_facebook( options.facebook_event_name, 'track' );
						}
					}
				}

				function track_vkontakte( event_name ) {
					if ( window.VK && typeof ( VK ) === 'object' ) {
						VK.Retargeting.Event( event_name );
					} else {
						window.console.log( 'Vkontakte not loaded' );
					}
				}

				function track_yandex_metrika( event_name, id ) {
					if ( window.ym && typeof ( ym ) === 'function' ) {
						ym( id, 'reachGoal', event_name );
					} else {
						window.console.log( 'Yandex Metrika not loaded' );
					}
				}

				function track_gtag( event_name ) {
					if ( window.gtag && typeof ( gtag ) === 'function' ) {
						gtag( 'event', event_name );
					} else {
						window.console.log( 'Global Google Tag (gtag.js) not loaded' );
					}
				}

				function track_analytics( category, action, label ) {
					if ( window.ga && typeof ( ga ) === 'function' ) {
						ga(
							'send',
							'event',
							{
								eventCategory: category,
								eventAction: action,
								eventLabel: label,
							}
						);
					} else {
						window.console.log( 'Google Analytics (analytics.js) not loaded' );
					}
				}

				function track_facebook( event_name, type ) {
					if ( window.fbq && typeof ( fbq ) === 'function' ) {
						fbq( type, event_name );
					} else {
						window.console.log( 'Yandex Metrika not loaded' );
					}
				}
			}
		);
	}
)( window.jQuery, window, document );

// eof.
