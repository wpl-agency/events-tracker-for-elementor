=== Events Tracker for Elementor ===
Contributors: wplagency, mihdan, donatory
Tags: elementor, seo, links, vlontakte, metrika, gtag, analytics, tracker, events
Donate link: https://www.kobzarev.com/donate/
Requires at least: 5.0
Tested up to: 5.3
Requires PHP: 5.6.20
Stable tag: 1.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Track Click or Submit events and conversions for any Elementor widget with Google Analytics, Facebook, Yandex Metrika, Vkontakte.

== Description ==
Simple but extremely useful Elementor addon that allows you to track events and conversions on your website, that made with our favorite page builder. All you need is turn necessary toggle(s) on and you're in business :-)

For now, you can track:
* Button widget click;
* Image widget click;
* Heading widget click;
* Form widget submit;
* others are upcoming…

### Supports all popular analytics and advertisement systems

**Google Analytics** — supports old Analytics.js code and current Gtag.js You can create targets for any available Elementor widget events and track conversions.

**Google Ads (Adwords)** — supports advertisement conversion tracking via gtag.js

**Google Tag Manager** — allows you to add CSS IDs for elements that do not have a special field for this. For example: Heading, Image etc. On the next step, you can create GTM Trigger and use this IDs to track events.

**Facebook** — you can select built in Facebook event types to spot which action match to which event: Lead, Contact, Purchase, etc. or even Custom events. Then you can create Facebook conversions and audiences and setup the more targeted ads.

**Yandex Metrika** — analytics system of Russian search engine Yandex . You can track events and create and analyse conversion targets.

**Vkontakte** — Russian social net. You can create audiences by tracked events, and make targeted ads more relevant.


### If you haven't tracking codes and pixels, we can help too

Events Tracker for Elementor has built in integration with all analytics/ads systems. You can put your tracking IDs into the fields, and plugin will insert the right code at the right place.
If this is not enough, there is an opportunity to paste you own code.

This moment you can add:
* Google Analytics tracking code (analytics.js or gtag.js);
* Google Ads (Adwords) tracking code;
* Google Tag Manager container code (js and no js);
* Facebook pixel;
* Yandex Metrika tracking code. Webvisor, scroll map and forms analytics are also supports;
* Vkontakte pixel and JS Api.

###  Documentation

We made some helpful documentation articles. See on the [wpl.agency](jttps://wpl.agency/) website.

### Thanks, and Community

If you have some some questions or suggestions, welcome to our support forum.

If the plugin was useful, rate it with a 5 star rating and write a few nice words here.

Can you help with plugin translation? Look here to contribute.

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

You're welcome to create new issue in our support forum and say something about your idea

== Changelog ==

= 1.1 (22.11.2019) =
* Fixed bugs
* Added images & headings to tracking
* Added new fields to gtag: action, category, label
* Added Adwords conversion tracking

= 1.0 (21.11.2019) =
* Plugin init
