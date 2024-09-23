<?php

/**
 * The plugin bootstrap file
 *
 *
 * @link              mandsconsulting.com
 * @since             6.0.0
 * @package           Email_Before_Download
 *
 * @wordpress-plugin
 * Plugin Name:       Email Before Download
 * Plugin URI:        mandsconsulting.com
 * Description:       Email Before Download (EBD) presents your users with a form where they submit information, like their name and email address, prior to receiving a download.
 * Version:           6.9.8
 * Author:            M&S Consulting
 * Author URI:        mandsconsulting.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       email-before-download
 * Domain Path:       /languages
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'EMAIL_BEFORE_DOWNLOAD_VERSION', '6.9.8' );

function activate_email_before_download() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-email-before-download-activator.php';
	Email_Before_Download_Activator::activate();
}

register_activation_hook( __FILE__, 'activate_email_before_download' );
require plugin_dir_path( __FILE__ ) . 'includes/class-email-before-download.php';

function run_email_before_download() {

  $plugin = new Email_Before_Download();
  $plugin->run();

}
// run_email_before_download();
add_action('plugins_loaded', 'run_email_before_download');
