<?php
/*
Plugin Name: WooCommerce All Countries Counties
Plugin URI: https://github.com/hoshomoh/WooCommerce-All-Country-States
Description: A plugin that adds postal counties to WooCommerce's list of states.
Version: 1.0.0
Author: Oforomeh Oshomo
Author URI: http://hoshomoh.github.io/
*/
/**
 * Copyright (c) 2016 Oforomeh Oshomo. All rights reserved.
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * **********************************************************************
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Check if WooCommerce is active
 **/
if ( ! class_exists( 'WC_All_Country_Counties' ) ) :
    class WC_All_Country_Counties {
        /**
         * Construct the plugin.
         */
        public function __construct() {
            add_action( 'plugins_loaded', array( $this, 'init' ) );
        }

        /**
        * Initialize the plugin.
        */
        public function init() {
            // Checks if WooCommerce is installed.
            if ( class_exists( 'WC_Integration' ) ) {
              add_filter( 'woocommerce_states', array( $this, 'wc_add_counties' ) );
            } else {
                // throw an admin error if you like
            }
        }

        public function  wc_add_counties( $states ) {
            $allowed_countries = $this->get_store_allowed_countries();
            if ( ! empty( $allowed_countries ) ) {
                foreach ($allowed_countries as $code => $country) {
                    if (file_exists($this->plugin_path() . '/states/' . $code . '.php')) {
                        include($this->plugin_path() . '/states/' . $code . '.php');
                    }
                }
            }

            return $states;
        }

        public function get_store_allowed_countries() {
            $allowed_countries = new WC_Countries();
            return array_merge( $allowed_countries->get_allowed_countries(), $allowed_countries->get_shipping_countries() );
        }

        public function plugin_path() {
            return untrailingslashit( plugin_dir_path( __FILE__ ) );
        }

    }

    $WC_All_Country_Counties = new WC_All_Country_Counties( __FILE__ );

endif;
