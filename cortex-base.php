<?php

/**
 * C9 Admin Base
 *
 *
 * @link              https://www.covertnine.com
 * @since             1.0.0
 * @package           Cortex_Base
 *
 * @wordpress-plugin
 * Plugin Name:       C9 Admin
 * Plugin URI:        https://github.com/covertnine/cortex-base
 * Description:       This plugin makes a few enhancements to the WordPress admin interface including collapsing and re-labeling navigation items to be more intuitive, image upload size limitations, as well as hiding non-essential admin notices from regular users.
 * Version:           1.0.0
 * Author:            Sam
 * Author URI:        https://www.covertnine.com
 * License:           GPL-2.0+
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       cortex-base
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

if (!defined('ATTACHMENT_REDIRECT_CODE')) {
	define('ATTACHMENT_REDIRECT_CODE', '301'); // Default redirect code for attachments with existing parent post
}

if (!defined('ORPHAN_ATTACHMENT_REDIRECT_CODE')) {
	define('ORPHAN_ATTACHMENT_REDIRECT_CODE', '302'); // Default redirect code for attachments with no parent post
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('CORTEX_BASE_VERSION', '1.0.0');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-cortex-base-activator.php
 */
function activate_cortex_base()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-cortex-base-activator.php';
	Cortex_Base_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-cortex-base-deactivator.php
 */
function deactivate_cortex_base()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-cortex-base-deactivator.php';
	Cortex_Base_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_cortex_base');
register_deactivation_hook(__FILE__, 'deactivate_cortex_base');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-cortex-base.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_cortex_base()
{

	$plugin = new Cortex_Base();
	$plugin->run();
}
run_cortex_base();
