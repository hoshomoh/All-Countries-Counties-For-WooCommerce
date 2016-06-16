<?php
/*
Plugin Name: All Countries Counties For WooCommerce
Plugin URI: https://github.com/hoshomoh/WooCommerce-All-Country-States
Description: A Wordpress WooCommerce Plugin that add counties/provinces/states for WooCommerce Countries
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
        public $error = null;

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
                $this->showError( __( 'All Countries Counties For WooCommerce is enabled but not effective. It requires WooCommerce in order to work. Kindly Install/Activate WooCommerce.',
                'all-countries-counties-for-wc' ) );

                return false;
            }
        }

        /**
         * @param $states
         * @return mixed
         */
        public function  wc_add_counties($states ) {
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

        /**
         * @return array
         */
        public function get_store_allowed_countries() {
            $allowed_countries = new WC_Countries();
            return array_merge( $allowed_countries->get_allowed_countries(), $allowed_countries->get_shipping_countries() );
        }

        /**
         * @return mixed
         */
        public function plugin_path() {
            return untrailingslashit( plugin_dir_path( __FILE__ ) );
        }

        /**
         * Output notice
         *
         * @param string $message
         * @param bool $success
         */
        public function outputNotice( $message, $success = true ) {
            echo '
                <div class="' . ( $success ? 'updated' : 'error' ) . '" style="position: relative;">
                    <p>' . $message . '</p>
                </div>
            ';
        }

        /**
         * Show error
         *
         * @param string $error
         */
        public function showError( $error ) {
            $this->error = $error;
            add_action( 'admin_notices', array( &$this, 'outputLastError' ) );
        }

        /**
         * Output last error
         */
        function outputLastError() {
            $this->outputNotice( $this->error, false );
        }

    }

    $WC_All_Country_Counties = new WC_All_Country_Counties( __FILE__ );

endif;
