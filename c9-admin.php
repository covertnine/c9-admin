<?php
/**
 * C9 Admin Base
 *
 * @package C9_Admin
 * @link    https://www.covertnine.com
 * @since   1.0.1
 *
 * @wordpress-plugin
 * Plugin Name:       C9 Admin
 * Plugin URI:        https://github.com/covertnine/c9-admin
 * Description:       Essential WordPress admin features for managing client sites including customization of admin screens, labels, plugin/theme update screen visibility and more.
 * Version:           1.0.4
 * Author:            COVERT NINE
 * Author URI:        https://www.covertnine.com
 * License:           GPL-2.0+
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       c9-admin
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! defined( 'ATTACHMENT_REDIRECT_CODE' ) ) {
	define( 'ATTACHMENT_REDIRECT_CODE', '301' ); // Default redirect code for attachments with existing parent post.
}

if ( ! defined( 'ORPHAN_ATTACHMENT_REDIRECT_CODE' ) ) {
	define( 'ORPHAN_ATTACHMENT_REDIRECT_CODE', '302' ); // Default redirect code for attachments with no parent post.
}

/**
 * Currently plugin version.
 */
define( 'C9_ADMIN_VERSION', '1.0.4' );


/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'admin/class-c9-admin.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since 1.0.0
 */
function run_c9_admin() {
	new C9_Admin( 'C9_Admin', C9_ADMIN_VERSION );
}
run_c9_admin();

/**
 * Adds dashboard widgets 
 *
 * @since 1.0.5
 */
add_action('wp_dashboard_setup', 'c9_dashboard_widgets');
  
function c9_dashboard_widgets() {
	global $wp_meta_boxes;
	wp_add_dashboard_widget('c9_help_widget', 'COVERT NINE Support', 'c9_dashboard_help');
	//wp_add_dashboard_widget('c9_user_notes', 'Notes', 'c9_');
}
 
function c9_dashboard_help() {
	echo 'Get paid support <a href="https://www.covertnine.com/get-support">here</a>. For community support, head over to: <a href="https://www.covertnine.com/community/" target="_blank">Community Support</a></p>';
}

//require_once 'admin/widget-notes-user.php';
		
//$name_user_notes = esc_html__('User Notes', 'c9-admin');
//$name_user_notes = apply_filters('dashboard_widgets_suite_name_user_notes', $name_user_notes, $name_user_notes, '', $link);

//wp_add_dashboard_widget('dashboard_widgets_suite_notes_user', $name_user_notes, 'dashboard_widgets_suite_notes_user');

/**
 * Remove default metaboxes that we don't need
 *
 * @since 1.0.5
 */
if ( ! function_exists('c9_remove_all_dashboard_metaboxes') ) {
	function c9_remove_all_dashboard_metaboxes() {
		// Remove Welcome panel
		remove_action( 'welcome_panel', 'wp_welcome_panel' );
		// Remove the rest of the dashboard widgets
		remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
		remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
		remove_meta_box( 'dashboard_site_health', 'dashboard', 'normal' );
		remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
		remove_meta_box( 'dashboard_activity', 'dashboard', 'normal');
		remove_meta_box( 'wsal', 'dashboard', 'normal');

}
}
add_action( 'wp_dashboard_setup', 'c9_remove_all_dashboard_metaboxes' );