<?php
/*
Plugin Name: TestRobo Safe Update
Plugin URI: https://testrobo.io
Description: Automatically check your site for functional and visual changes after any plugin/theme update. Checks can also be additionally set to run on a schedule, for e.g. daily, weekly, etc.
Version: 0.0.5
Author: Karrot Labs
Author URI: https://karrotlabs.com/
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.txt


This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 
2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
with this program. If not, visit: https://www.gnu.org/licenses/

*/

function trsu_trigger_tests() {
  $suiteId = get_option("trsu_suite_id");
  $apiKey = get_option("trsu_api_key");
  $url = "https://api.testrobo.io/extapi/execRequest";
  $jsonBody = json_encode(array( 'suiteId' => $suiteId, 'type' => "suite"));
  $response = wp_remote_post( $url, array(
    'method' => 'POST',
    'timeout' => 45,
    'redirection' => 5,
    'httpversion' => '1.0',
    'blocking' => true,
    'headers' => array(
      'extapikey' => $apiKey,
      'Content-Type' => 'application/json; charset=utf-8'
    ),
    'body' => $jsonBody,
    'cookies' => array()
    )
  );

  if ( is_wp_error( $response ) ) {
     $error_message = $response->get_error_message();
     trsu_write_log("[TRSU] Something went wrong: $error_message");
  } else {
    trsu_write_log('[TRSU] job triggered. Details: ' . $response['body']);
  }
}

function trsu_on_update($upgrader_obj, $options) {
  if(get_option("trsu_is_enabled")) {
    trsu_write_log('[TRSU] ON UPDATE' . date('Y-m-d H:i:s', current_time('timestamp', 0)));
    trsu_write_log('[TRSU] $options[action]'. $options['action']);
    trsu_write_log('[TRSU] $options[type]'. $options['type']);
    if(isset($options['plugins'])) {
      trsu_write_log('[TRSU] $options[plugins]'. implode(',', $options['plugins']));
    }
    if(isset($options['themes'])) {
      trsu_write_log('[TRSU] $options[themes]'. implode(',', $options['themes']));
    }

    //trigger suite/test 
    trsu_trigger_tests();

  } else {
    trsu_write_log('[TRSU] Update detected but TRSU is not enabled');
  }
  
}

add_action( 'upgrader_process_complete', 'trsu_on_update',10, 2);



// show settings section
function trsu_register_settings() {
   add_option( 'trsu_is_enabled', '');    
   register_setting( 'trsu_options_group', 'trsu_is_enabled', 'trsu_callback' );   
   add_option( 'trsu_api_key', '');
   register_setting( 'trsu_options_group', 'trsu_api_key', 'trsu_callback' );
   add_option( 'trsu_suite_id', '');
   register_setting( 'trsu_options_group', 'trsu_suite_id', 'trsu_callback' );
}
add_action( 'admin_init', 'trsu_register_settings' );

function trsu_register_options_page() {
  add_options_page('Plugin Settings - TestRobo Safe Update', 'TestRobo Safe Update', 'manage_options', 'trsu', 'trsu_options_page2');
}
add_action('admin_menu', 'trsu_register_options_page');

function trsu_options_page2()
{
    trsu_write_log('[TRSU] attempting to render plugin option page');
    include 'trsu-settings-page2.php';
}

//add css to admin
function trsu_admin_style() {
  wp_enqueue_style('admin-styles', plugins_url('css/trsu.css', __FILE__) );
}
add_action('admin_enqueue_scripts', 'trsu_admin_style');

add_action( 'admin_footer', 'trsu_check_connection_javascript' ); // Write our JS below here

function trsu_check_connection_javascript() { ?>
  <script type="text/javascript" >
  jQuery("#trsu-test .test-button").click(function($) {
    var data = {
      'action': 'trsu_check_connection',
    };
    jQuery("#trsu-test .test-result p").html('Loading...')
    // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
    jQuery.post(ajaxurl, data, function(response) {
      jQuery("#trsu-test .test-result p").html(response)
  
    });
  });
  </script> 
  <?php
}


add_action( 'wp_ajax_trsu_check_connection', 'trsu_check_connection' );

function trsu_check_connection() {
  global $wpdb; 
  // do call to arick server test endpoint
  $url = "https://api.testrobo.io/extapi/extApiTest";
  $apiKey = get_option("trsu_api_key");
  $request = wp_remote_get($url, array(
    'headers' => array(
      'extapikey' => $apiKey,
      'Content-Type' => 'application/json; charset=utf-8'
    ),
  ));
  if( is_wp_error( $request ) ) {
    return false; // Bail early
  }
  $body = wp_remote_retrieve_body( $request );

  echo $body;

  wp_die(); 
}

// Helper code
if (!function_exists('trsu_write_log')) {

    function trsu_write_log($log) {
        if (true === WP_DEBUG) {
            if (is_array($log) || is_object($log)) {
                error_log(print_r($log, true));
            } else {
                error_log($log);
            }
        }
    }

}



