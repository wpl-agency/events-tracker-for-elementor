=== Events Tracker for Elementor ===
Contributors: mihdan
Donate link: https://www.kobzarev.com/donate/
Tags: elementor, seo, links, vkontakte, metrika, gtag, analytics, tracker, events
Requires at least: 6.3
Tested up to: 6.8
Stable tag: 1.3.5.1
Requires PHP: 7.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Track Click or Submit events and conversions for any Elementor widget with Google Analytics, Facebook, Yandex Metrika, Vkontakte.

== Description ==

Simple but extremely useful Elementor addon that allows you to track events and conversions on your website, that made with our favorite page builder. All you need is turn necessary toggle(s) on and you're in business 😎

### ✅ For now, you can track ###

- Button widget click
- Call To Action widget click
- Form widget submit
- Heading widget click
- Icon List widget click
- Image widget click
- Pricing Table widget click

#### ⏳ Coming soon ####
- Flipbox widget
- Icon widget
- Icon Box widget
- Image Box widget
- Media Carousel widget
- Paypal Button widget
- Share Buttons widget
- Slider widget
- Social Icons widget

### 📈 Supports all popular analytics and advertisement systems

**Google Analytics** — supports old Analytics.js code and current Gtag.js. You can create targets for any available Elementor widget events and track conversions.

**Google Ads (Adwords)** — supports advertisement conversion tracking via gtag.js

**Google Tag Manager** — allows you to add IDs into elements that do not have a special field for this. For example: Heading, Image etc. On the next step, you can create GTM Trigger and use this IDs to track events.

**Facebook** — you can select built in Facebook event types to spot which action match to which event: Lead, Contact, Purchase, etc. or even Custom events. Then you can create Facebook conversions and audiences and setup the more targeted ads.

**Yandex Metrika** — analytics system of Russian search engine Yandex . You can track events and create and analyse conversion targets.

**Vkontakte** — Russian social net. You can create audiences by tracked events, and make targeted ads more relevant.


### ⚓ If you haven't tracking codes and pixels, we can help too ###

Events Tracker for Elementor has built in integration with all analytics/ads systems. You can put your tracking IDs into the fields, and plugin will insert the right code at the right place.

#### This moment you can add:
- Google Analytics tracking code (analytics.js or gtag.js)
- Google Ads (Adwords) tracking code
- Google Tag Manager container code (js and no js)
- Facebook pixel
- Yandex Metrika tracking code. Webvisor, scroll map and forms analytics are also supports
- Vkontakte pixel

### External services

This plugin uses external services, which are documented below with links to the service’s Privacy Policy and License agreement. These services are integral to the functionality and features offered by the plugin. However, we acknowledge the importance of transparency regarding the use of such services.

#### Google Tag Manager

To work with the plugin, you need to register in the GTM service,
which will allow you to analyze the received statistics.
For more information, please click on the links below.

Service Link: https://tagmanager.google.com/
Service Privacy Policy: https://policies.google.com/privacy

#### Google Analytics

To work with the plugin, you need to register in the Google Analytics service,
which will allow you to analyze the received statistics.
For more information, please click on the links below.

Service Link: https://marketingplatform.google.com/about/analytics/
Service Link: https://www.google-analytics.com/
Service Privacy Policy: https://www.google.com/intl/en/policies/privacy/

#### VK.com

To work with the plugin, you need to register in the VK.com service,
which will allow you to analyze the received statistics.
For more information, please click on the links below.

Service Link: https://ads.vk.com/
Service Link: https://vk.com/
Service Privacy Policy: https://ads.vk.com/documents

#### Yandex Metrika

To work with the plugin, you need to register in the metrika.yandex.ru service,
which will allow you to analyze the received statistics.
For more information, please click on the links below.

Service Link: https://metrika.yandex.ru/
Service Link: https://cdn.jsdelivr.net/
Service Privacy https://yandex.ru/legal/confidential/

#### Facebook

To work with the plugin, you need to register in the FB.com service,
which will allow you to analyze the received statistics.
For more information, please click on the links below.

Service Link: https://www.facebook.com/business/tools/meta-pixel
Service Privacy https://www.facebook.com/policies_center/

### ⛑️ Documentation and support

If you have some questions or suggestions, welcome to our [support forum](https://wordpress.org/support/plugin/events-tracker-for-elementor/).

- [How to Install Events Tracker for Elementor](https://wpl.agency/docs/how-to-install-events-tracker-for-elementor/)
- [How to track Elementor Pro forms with Google Analytics](https://wpl.agency/docs/how-to-track-elementor-pro-forms-with-google-analytics/)

### 💙 Love Events Tracker for Elementor?
If the plugin was useful, rate it with a [5 star rating](https://wordpress.org/support/plugin/events-tracker-for-elementor/reviews/) and write a few nice words.

### 🏳️ Translations
- [Russian](https://translate.wordpress.org/locale/ru/default/wp-plugins/events-tracker-for-elementor/) – (ru_RU)

Can you help with plugin translation? Please feel free to contribute!

== Installation ==

### Installing from the WordPress control panel

1. Go to the page "Plugins > Add New".
2. Input the name "Elementor Events Tracker" in the search field
3. Find the "Elementor Events Tracker" plugin in the search result and click on the "Install Now" button, the installation process of plugin will begin.
4. Click "Activate" when the installation is complete.

### Installing with the archive

1. Go to the page "Plugins > Add New" on the WordPress control panel
2. Click on the "Upload Plugin" button, the form to upload the archive will be opened.
3. Select the archive with the plugin and click "Install Now".
4. Click on the "Activate Plugin" button when the installation is complete.

### Manual installation

1. Upload the folder `wpl-elementor-events-tracker` to a directory with the plugin, usually it is `/wp-content/plugins/`.
2. Go to the page "Plugins > Add New" on the WordPress control panel
3. Find "Elementor Events Tracker" in the plugins list and click "Activate".

== Frequently Asked Questions ==

= Does the plugin support widget A, B, С?.. =

All supported widgets for this moment are listed at the beginning of the plugin readme (top of this page).

= I already have tracking code or pixel, should I remove it? =

No. You can use any tracking or pixel code insertion method. Nevermind yours or built in to Events Tracker for Elementor

= I have idea, how can I share? =

You're welcome to create new issue in our [support forum](https://wordpress.org/support/plugin/events-tracker-for-elementor/) and say something about your idea

= How to exclude links or forms from tracking? =

Add class `events-tracker-for-elementor-exclude` for forms or links

== Screenshots ==

1. Heading widget with link click events tracking with Google Analytics
2. Button widget click events tracking with Facebook
3. Form widget submit events tracking with Google Analytics
4. Events tracking with VK.com
5. Events and conversions tracking with Yandex Metrika
6. Tracking codes and pixels insertion

== Changelog ==

= 1.3.5 (18.04.2025) =
* Tested with WordPress 6.8
* Tested with Elementor 3.28.3
* Tested with Elementor Pro 3.28.3

= 1.3.4 (03.11.2024) =
* Tested with WordPress 6.6.2

= 1.3.3 (09.09.2024) =
* Tested with WordPress 6.6.1
* Tested with Elementor 3.23.4
* Tested with Elementor Pro 3.23.3

= 1.3.2 (21.10.2023) =
* Tested with WordPress 6.3
* Tested with Elementor 3.16.6
* Tested with PHP 8.2
* Code refactoring
* Fixed tracking bug in Icon List widget

= 1.3.1 (23.06.2022) =
* Fixed fatal errors

= 1.3.0 (22.06.2022) =
* Added Global widgets to tracking
* Tested with WordPress 6.0
* Code refactoring

= 1.2.9 (10.03.2021) =
* Added support for WordPress 5.7
* Added Pricing Table widget to tracking
* Fixed tons of bugs

= 1.2.8 (18.01.2021) =
* Added Call to Action to tracking
* Fixed tons of bugs

= 1.2.7 (05.11.2020) =
* Removed the hard-coded metrics tag
* Fixed bugs

= 1.2.6 (15.04.2020) =
* Fixed bug with redirect to page 404 when opening a popup via button

= 1.2.5 (18.02.2020) =
* Fixed bug with Adwords tracking code inserted with GTM
* Fixed bug with Google Analytics tracking code inserted with GTM
* Removed unused code

= 1.2.4 (12.02.2020) =
* Added class `events-tracker-for-elementor-exclude` for exclude forms and links from tracking

= 1.2.3 (28.01.2020) =
* Fixed bug with Yandex tracking code
* Fixed bug with gtag tracking code
* Fixed bug with adwords tracking code
* Fixed bug with analytics tracking code

= 1.2.2 (30.12.2019) =
* Fixed bugs with GTM

= 1.2.1 (29.12.2019) =
* Added Icon List to tracking
* Fixed bug with target="_blank" for Button

= 1.2 (05.12.2019) =
* Added Google Tag Manager tracking

= 1.1 (22.11.2019) =
* Fixed bugs
* Added images & headings to tracking
* Added new fields to gtag: action, category, label
* Added Adwords conversion tracking

= 1.0 (21.11.2019) =
* Plugin init

== Upgrade Notice ==
You should update for better plugin work
