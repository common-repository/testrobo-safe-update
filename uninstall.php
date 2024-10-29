<?php
// if uninstall.php is not called by WordPress, die
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

error_log("[TRSU] Deleting plugin");

delete_option("trsu_suite_id");
delete_option("trsu_api_key");
delete_option("trsu_is_enabled");
