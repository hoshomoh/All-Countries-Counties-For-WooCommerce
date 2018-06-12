=== All Countries Counties For WooCommerce ===
Contributors: hoshomoh, rodmontgt, bosunolanrewaju
Donate link: https://github.com/hoshomoh/WooCommerce-All-Country-States <Just star the repo>
Tags: e-commerce, woocommerce-counties, woocommerce-nigerian-states, woocommerce-uk-provinces, woocommerce-chile-counties, woocommerce-kenya-pronvinces, woocommerce-nigerian-LGA
Requires at least: 4.1
Tested up to: 4.8
Stable tag: 1.1.1
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

A Wordpress WooCommerce Plugin that add counties/provinces/states for WooCommerce Countries

== Description ==

All Countries Counties For WooCommerce is a plugin that automatically pre-populate your WooCommerce state fields to allows customers select from preconfigured states rather than typing it in manually.

It also have support for local governments. So, countries whose states have local government would automatically have a local government dropdown field on checkout page.

The local government feauture is only available in Nigeria at the moment.

= Supported Countries =

* United Kingdom
* Nigeria
* Chile
* Kenya
* Guatemala

= Overriding local governments in theme function.php

```php
add_filter( 'wc_add_counties_local_government', 'my_custom_lga' );

function my_custom_lga( $local_governments ) { 
    my_custom_lga_array = [
        '' => __( 'Select an option...' , 'woocommerce' ),
        'Agege/ijaiye' => __( 'Agege/ijaiye', 'woocommerce' ),
        'Ajeromi/ifelodun' => __( 'Ajeromi/ifelodun', 'woocommerce' ),
        'Alimosho' => __( 'Alimosho', 'woocommerce' ),
	'Amuwo Odofin' => __( 'Amuwo Odofin', 'woocommerce' ),
        'Apapa' => __( 'Apapa', 'woocommerce' ),
    ];
    
    $local_governments['NG']['LA'] = my_custom_lga_array;

    return $local_governments;
}
```

== Installation ==

= Minimum Requirements =

* WordPress 3.8 or greater
* PHP version 5.2.4 or greater
* MySQL version 5.0 or greater

= Automatic installation =

Automatic installation is the easiest option as WordPress handles the file transfers itself and you don’t need to leave your web browser. To do an automatic install of WooCommerce, log in to your WordPress dashboard, navigate to the Plugins menu and click Add New.

In the search field type WooCommerce All Country States and click Search Plugins. Once you’ve found our plugin you can view details about it such as the point release, rating and description. Most importantly of course, you can install it by simply clicking “Install Now”.

= Manual installation =

The manual installation method involves [downloading the latest version our plugin](https://github.com/hoshomoh/WooCommerce-All-Country-States/releases) and uploading it to your WebServer via your Favourite FTP application. The WordPress codex contains [instructions on how to do this here](http://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation).

= Updating =

Automatic updates should work like a charm; as always though, ensure you backup your site just in case.

== Frequently Asked Questions ==

= Where can I report bugs to the project? =

Bugs can be reported on our [GitHub repository](https://github.com/hoshomoh/WooCommerce-All-Country-States/issues).

=All Countries Counties For WooCommerce is awesome! Can I contribute? =

Yes you can! Join in on our [GitHub repository](https://github.com/hoshomoh/WooCommerce-All-Country-States) :)

== Screenshots ==

1. The WooCommerce state field converted to a drop down.

== Changelog ==
= 1.1.1 - 22/05/2018 =
* Fix - Removed redundant user data update function

= 1.1.0 - 18/09/2017 =
* Feature - Added filters to override local governments collection in theme function

= 1.0.6 - 07/12/2016 =
* Feature - Added Guatemala Departments

= 1.0.5 - 29/09/2016 =
* Feature - Added local government to package destination
* Feature - Added Support for re-calculating shipping fee when local government custom field(s) changes
* Fix - Fix issue [#1](https://github.com/hoshomoh/All-Countries-Counties-For-WooCommerce/issues/1)
* Fix - Added Mixing files
* Feature - Added Support for Local Government
* Feature - Added Local Government for Nigerian States
* Feature - Added Local Government field to checkout for Nigeria
* Feature - Added Chile provinces
* Feature - Added Kenya Counties
* Fix - Displayed Admin Notice when WooCommerce is not installed or Activated
* Feature - Added states for Nigeria and United Kingdom

== Upgrade Notice ==

= 1.1.0 =
* Added Feature to override local governments collection in theme function.php file.
