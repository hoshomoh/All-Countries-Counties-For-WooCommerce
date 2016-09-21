<?php
/*
Plugin Name: All Countries Counties For WooCommerce
Plugin URI: https://github.com/hoshomoh/WooCommerce-All-Country-States
Description: A Wordpress WooCommerce Plugin that add counties/provinces/states for WooCommerce Countries
Version: 1.0.1
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
              if ( ! empty( $this->get_countries_with_local_government() ) ) {
                  add_filter('woocommerce_checkout_fields', array($this, 'wc_add_local_government_fields'));
                  add_action('woocommerce_checkout_process', array($this, 'wc_process_local_government_fields'));
                  add_action( 'woocommerce_checkout_update_order_meta', array($this, 'wc_save_local_government_fields'));
                  add_action( 'woocommerce_admin_order_data_after_billing_address', array($this, 'wc_billing_local_government_checkout_field_display_admin_order_meta'), 10, 1 );
                  add_action( 'woocommerce_admin_order_data_after_shipping_address', array($this, 'wc_shipping_local_government_checkout_field_display_admin_order_meta'), 10, 1 );
              }
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
         * @param $local_governments
         * @return mixed
         */
        public function  wc_add_counties_local_government($local_governments) {
            $countries_with_local_government = $this->get_countries_with_local_government();
            $countries = new WC_Countries();
            if ( ! empty( $countries_with_local_government ) ) {
                foreach ($countries_with_local_government as $a => $b) {
                    if ( ! empty($countries->get_states($a)) ) {
                        foreach ($countries->get_states($a) as $k => $v) {
                            if (file_exists($this->plugin_path() . '/local-governments/' . $a . '/' . $k . '.php')) {
                                include($this->plugin_path() . '/local-governments/' . $a . '/' . $k . '.php');
                            }
                        }
                    }
                }
            }

            return $local_governments;
        }

        /**
         * @param $fields
         * @return mixed
         */
        function wc_add_local_government_fields($fields ) {

            $fields['shipping']['shipping_local_government'] = array(
                'label'     => __('Local Government', 'woocommerce'),
                'placeholder'   => _x('Local Government', 'placeholder', 'woocommerce'),
                'required'  => false,
                'class'     => array('form-row-wide'),
                'clear'     => true,
                'type'        => 'select',
                'options'     => array(
                    '' => __('Select an option…', 'woocommerce' )
                )
            );

            $fields['billing']['billing_local_government'] = array(
                'label'     => __('Local Government', 'woocommerce'),
                'placeholder'   => _x('Local Government', 'placeholder', 'woocommerce'),
                'required'  => false,
                'class'     => array('form-row-wide'),
                'clear'     => true,
                'type'        => 'select',
                'options'     => array(
                    '' => __('Select an option…', 'woocommerce' )
                )
            );

            echo '<script type="text/javascript">' .
                '/* <![CDATA[ */' .
                    'var local_government_for_states_country = ' . json_encode($this->wc_add_counties_local_government()) . ';' .
                '/* ]]> */' .
                'var country_with_local_governments = ' . json_encode(array_keys($this->get_countries_with_local_government())) . ';' .
                'var billing_select = jQuery("select#billing_local_government");' .
                'var billing_field = jQuery("#billing_local_government_field");' .
                'var shipping_select = jQuery("select#shipping_local_government");' .
                'var shipping_field = jQuery("#shipping_local_government_field");' .
                'function update_select(selected_country, selected_state, select, field, default_value) {' .
                    'if (selected_country != "" && selected_state != "" && jQuery.inArray( selected_country, country_with_local_governments ) >= 0 && typeof local_government_for_states_country[selected_country][selected_state] != "undefined") {' .
                        'select.empty();' .
                        'jQuery.each(local_government_for_states_country[selected_country][selected_state], function(key,value) {' .
                            'select.append(jQuery("<option></option>").attr("value", value).text(value));' .
                        '});' .
                        'if(default_value) {' .
                            'select.val(default_value);' .
                        '}' .
                        'select.trigger("change.select2");' .
                        'select.trigger("chosen:updated");' .
                        'field.show();' .
                    '}' .
                    'else {' .
                        'select.empty();' .
                        'select.trigger("change.select2");' .
                        'select.trigger("chosen:updated");' .
                        'field.hide();' .
                    '}' .
                '}' .
                'if(jQuery("select#billing_state").val() != "") {' .
                    'update_select(jQuery("select#billing_country").val(), jQuery("select#billing_state").val(), billing_select, billing_field, "")' .
                '}' .
                'if(jQuery("select#shipping_state").val() != "") {' .
                    'update_select(jQuery("select#shipping_country").val(), jQuery("select#shipping_state").val(), shipping_select, shipping_field, "")' .
                '}' .
                'jQuery("select#billing_state").on("change", function(){' .
                    'var billing_selected_country = jQuery("select#billing_country").val();' .
                    'var billing_selected_state = jQuery("select#billing_state").val();' .
                    'update_select(billing_selected_country, billing_selected_state, billing_select, billing_field)' .
                '});' .
                'jQuery("select#shipping_state").on("change", function(){' .
                    'var shipping_selected_country = jQuery("select#shipping_country").val();' .
                    'var shipping_selected_state = jQuery("select#shipping_state").val();' .
                    'update_select(shipping_selected_country, shipping_selected_state, shipping_select, shipping_field)' .
                '});' .
            '</script>';

            return $fields;
        }

        /**
         * Local Government fields Validation
         */
        public function wc_process_local_government_fields() {
            if (!empty($this->wc_add_counties_local_government()[$_POST['billing_country']][$_POST['billing_state']])) {
                if (!$_POST['billing_local_government']) {
                    wc_add_notice(__('Billing Local Government is a required field.'), 'error');
                }

                if ($_POST['ship_to_different_address'] == 1 &&!$_POST['shipping_local_government']) {
                    wc_add_notice(__('Shipping Local Government is a required field.'), 'error');
                }
            }
        }

        /**
         * Save Local Government fields against Order
         */
        public function wc_save_local_government_fields($order_id) {
            if ( ! empty( $_POST['billing_local_government'] ) ) {
                update_post_meta( $order_id, 'Billing Local Government', sanitize_text_field( $_POST['billing_local_government'] ) );
            }

            if ( ! empty( $_POST['shipping_local_government'] ) ) {
                update_post_meta( $order_id, 'Shipping Local Government', sanitize_text_field( $_POST['shipping_local_government'] ) );
            }else {
                if ( ! empty( $_POST['billing_local_government'] ) ) {
                    update_post_meta( $order_id, 'Shipping Local Government', sanitize_text_field( $_POST['billing_local_government'] ) );
                }
            }
        }

        /**
         * Billing Local Government fields admin order details display
         */
        public function wc_billing_local_government_checkout_field_display_admin_order_meta($order) {
            $billing_local_government_meta = get_post_meta( $order->id, 'Billing Local Government', true );
            if (!empty($billing_local_government_meta)) {
                echo '<p><strong>' . __('Billing Local Government') . ':</strong> ' . $billing_local_government_meta . '</p>';
            }
        }

        /**
         *  Shipping Local Government fields admin order details display
         */
        public function wc_shipping_local_government_checkout_field_display_admin_order_meta($order) {
            $shipping_local_government_meta = get_post_meta( $order->id, 'Shipping Local Government', true );
            if (!empty($shipping_local_government_meta)) {
                echo '<p><strong>' . __('Shipping Local Government') . ':</strong> ' . $shipping_local_government_meta . '</p>';
            }
        }

        /**
         * @return array
         */
        public function get_store_allowed_countries() {
            $allowed_countries = new WC_Countries();
            return array_merge( $allowed_countries->get_allowed_countries(), $allowed_countries->get_shipping_countries() );
        }

        /**
         * @return array
         */
        public function get_countries_with_local_government() {
            return ['NG' => 'Nigeria'];
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
        public function outputLastError() {
            $this->outputNotice( $this->error, false );
        }

    }

    $WC_All_Country_Counties = new WC_All_Country_Counties( __FILE__ );

endif;
