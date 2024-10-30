<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}
delete_option('connectwp_posthog_snippet_code');
delete_option('connectwp_posthog_plugin_enabled');
?> 
