<?php

/**
 * C9 Admin Base
 *
 * @package C9_Admin
 * @link    https://www.covertnine.com
 * @since   1.0.1
 *
 * @wordpress-plugin
 * Plugin Name:       C9 Admin Dashboard
 * Plugin URI:        https://www.covertnine.com/c9-admin-dashboard-plugin
 * Description:       Essential WordPress admin features for managing client sites including customization of admin screens, labels, plugin/theme update screen visibility and more.
 * Version:           1.1.2
 * Author:            COVERT NINE
 * Author URI:        https://www.covertnine.com
 * License:           GPL-2.0+
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       c9-admin
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

if (!defined('C9_ADMIN_ATTACHMENT_REDIRECT_CODE')) {
	define('C9_ADMIN_ATTACHMENT_REDIRECT_CODE', '301'); // Default redirect code for attachments with existing parent post.
}

if (!defined('C9_ADMIN_ORPHAN_ATTACHMENT_REDIRECT_CODE')) {
	define('C9_ADMIN_ORPHAN_ATTACHMENT_REDIRECT_CODE', '302'); // Default redirect code for attachments with no parent post.
}

/**
 * Currently plugin version.
 */
define('C9_ADMIN_VERSION', '1.1.2');


/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'admin/class-c9-admin.php';

/**
 * Add different filetypes to allowed uploads
 */
function c9_admin_mime_types($mimes)
{
	$mimes['svg']     = 'image/svg+xml';
	$mimes['ogg|oga'] = 'audio/ogg';
	$mimes['webm']    = 'video/webm';

	return $mimes;
}
add_filter('upload_mimes', 'c9_admin_mime_types');

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since 1.0.0
 */
function run_c9_admin()
{
	new C9_Admin('C9_Admin', C9_ADMIN_VERSION);
}
run_c9_admin();
