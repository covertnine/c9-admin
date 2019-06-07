<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://covertnine.com
 * @since             1.0.0
 * @package           Cortex_Base
 *
 * @wordpress-plugin
 * Plugin Name:       Cortex Base
 * Plugin URI:        https://github.com/covertnine/cortex-base
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Sam
 * Author URI:        https://covertnine.com
 * License:           GPL-2.0+
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       cortex-base
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
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
