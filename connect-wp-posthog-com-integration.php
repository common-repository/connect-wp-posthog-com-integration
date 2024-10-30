<?php
/**
 * Plugin Name:       Connect WP - PostHog.com Integration
 * Plugin URI:        https://saad.codes/plugins/connect-wp-posthog-com-integration/
 * Description:       Simple, lightweight solution for inserting your PostHog.com Snippet code to your WordPress website.
 * Version:           1.3
 * Author:            Saad Malik
 * Author URI:        https://saad.codes/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       connect-wp-posthog-com-integration
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Invalid request.' );
}

class ConnectWPPostHogIntegration {

// Actions and Filters to add code to website header

    function __construct() {
        add_action('wp_head', [$this, 'snippet_code']);
        add_action('admin_menu', [$this, 'add_menu']);
        add_action('admin_init', [$this, 'save_settings']);
        add_filter('plugin_action_links_' . plugin_basename(__FILE__), [$this, 'add_plugin_actions']);
    }

    public function add_plugin_actions($links) {
        $links[] = '<a href="' . esc_url(get_admin_url(null, 'options-general.php?page=posthog-settings')) . '">Settings</a>';
        return $links;
    }

// Save settings to server

    
    public function save_settings() {
        register_setting('connectwp-posthog-settings-group', 'connectwp_posthog_snippet_code', [$this, 'snippet_code_validate']);
        register_setting('connectwp-posthog-settings-group', 'connectwp_posthog_plugin_enabled');
    }

// Empty field error

    public function snippet_code_validate($value) {

        if (empty($value)) {
            add_settings_error('connectwp_posthog_snippet_code', 'connectwp_posthog_snippet_code_validate', 'Error: The code snippet field is empty. Please paste the code snippet below.If you are a new customer, visit posthog.com to generate your your code snippet.', 'error');
        }

        return $value;
    }

    public function add_menu() {
        add_options_page('Connect WP-PostHog Settings', 'Connect WP-PostHog Settings', 'administrator', 'posthog-settings', [$this, 'settings_page']);
    }

    public function snippet_code() {

        if (get_option('connectwp_posthog_plugin_enabled')) {

            print PHP_EOL . '<!-- Connect WP - PostHog Integration Snippet [START] -->' . PHP_EOL;

            print PHP_EOL . get_option('connectwp_posthog_snippet_code') . PHP_EOL;

            print PHP_EOL . '<!-- Connect WP - PostHog Integration Snippet [END] -->' . PHP_EOL . PHP_EOL;
        }
    }

// Settings page

    public function settings_page() {
        ?>
        <div class="wrap">

            <h2 class="title"><?php _e('Connect WP - PostHog Integration'); ?></h2>
            <h5><?php _e('Copy your PostHog snippet code from "Project" -> "Settings" and paste it below.<br/> You can also click here to visit the <a href="https://app.posthog.com/ingestion/web" target="_blank">PostHog settings</a> page.'); ?></h5>
<!--  Code Snippet form -->
            <form method="POST" action="options.php">
                <?php settings_fields('connectwp-posthog-settings-group'); ?>
                <?php do_settings_sections('connectwp-posthog-settings-group'); ?>
                <table class="form-table">

                    <tr>
                        <th><label for="connectwp_posthog_plugin_enabled">Activate Code Snippet?</label></th>
                        <td>
                            <label class="radio-inline">
                                <input type="radio" name="connectwp_posthog_plugin_enabled" value="1" <?php print (get_option('connectwp_posthog_plugin_enabled') == 1) ? 'checked' : ''; ?> /> Yes 
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="connectwp_posthog_plugin_enabled" value="0"  <?php print (get_option('connectwp_posthog_plugin_enabled') == 0) ? 'checked' : ''; ?> /> No
                            </label>
                        </td>
                    </tr>

                    <tr>
                        <th><label for="connectwp_posthog_snippet_code"><?php _e('Paste your PostHog snippet code below'); ?></label></th>
                        <td>
                            <textarea class="form-control" style="width: 100%; height: 300px;" name="connectwp_posthog_snippet_code"><?php print esc_attr(get_option('connectwp_posthog_snippet_code')); ?></textarea>
                        </td>
                    </tr>

                </table>

                <p class="submit">
                    <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Update Settings') ?>">
                </p>
            </form>
        </div>
        <?php
    }

}

$ConnectWPPostHogIntegration = new ConnectWPPostHogIntegration();
