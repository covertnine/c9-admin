<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://covertnine.com
 * @since      1.0.0
 *
 * @package    Cortex_Base
 * @subpackage Cortex_Base/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Cortex_Base
 * @subpackage Cortex_Base/admin
 * @author     Sam <sam@covertnine.com>
 */
class Cortex_Base_Admin
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Cortex_Base_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Cortex_Base_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/cortex-base-admin.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Cortex_Base_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Cortex_Base_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/cortex-base-admin.js', array('jquery'), $this->version, false);
	}

	/**
	 *
	 * admin/class-cortex-base-admin.php - Don't add this
	 *
	 **/

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */

	public function add_plugin_admin_menu()
	{

		/*
     * Add a settings page for this plugin to the Settings menu.
     *
     * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
     *
     *        Administration Menus: http://codex.wordpress.org/Administration_Menus
     *
     */
		add_options_page(
			'Cortex Base Options',
			'Cortex Base',
			'manage_options',
			$this->plugin_name,
			array($this, 'display_plugin_setup_page')
		);
	}

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 */

	public function add_action_links($links)
	{
		/*
    *  Documentation : https://codex.wordpress.org/Plugin_API/Filter_Reference/plugin_action_links_(plugin_file_name)
    */
		$settings_link = array(
			'<a href="' . admin_url('options-general.php?page=' . $this->plugin_name) . '">' . __('Settings', $this->plugin_name) . '</a>',
		);
		return array_merge($settings_link, $links);
	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */

	public function display_plugin_setup_page()
	{
		include_once('partials/cortex-base-admin-display.php');
	}

	/**
	 *
	 * admin/class-cortex-base-admin.php
	 *
	 **/
	public function options_update()
	{
		register_setting($this->plugin_name, $this->plugin_name, array($this, 'validate'));
	}

	/**
	 *
	 * admin/class-cortex-base-admin.php
	 *
	 **/
	public function validate($input)
	{
		// All checkboxes inputs        
		$valid = array();

		//Cleanup
		$valid['disable_admin'] = (isset($input['disable_admin']) && !empty($input['disable_admin'])) ? 1 : 0;
		$valid['disable_attachment_pages'] = (isset($input['disable_attachment_pages']) && !empty($input['disable_attachment_pages'])) ? 1 : 0;
		$valid['hide_developer_items'] = (isset($input['hide_developer_items']) && !empty($input['hide_developer_items'])) ? 1 : 0;
		$valid['admin_only_notifications'] = (isset($input['admin_only_notifications']) && !empty($input['admin_only_notifications'])) ? 1 : 0;
		$valid['limit_image_size'] = (isset($input['limit_image_size']) && !empty($input['limit_image_size'])) ? 1 : 0;
		$valid['max_px'] = intval($input['max_px']);
		$valid['min_px'] = intval($input['min_px']);
		$valid['max_size'] = intval($input["max_size"]);

		return $valid;
	}

	public function show_updated_only_to_admins()
	{
		if (get_option($this->plugin_name)['admin_only_notifications']) {
			if (!current_user_can('update_core')) {
				remove_action('admin_notices', 'update_nag', 3);
				remove_action('network_admin_notices', 'update_nag', 3);
			}
			if (!current_user_can('manage_options')) { // non-admin users
				echo '<style>#setting-error-tgmpa>.updated settings-error notice is-dismissible, .update-nag, .updated, .error { display: none; }</style>';
			}
		}
	}

	public function remove_admin_menu_items()
	{
		if (get_option($this->plugin_name)['hide_developer_items']) {
			$remove_menu_items = array(__('Events'), __('Comments'));
			global $menu;
			end($menu);
			while (prev($menu)) {
				$item = explode(' ', $menu[key($menu)][0]);
				if (in_array($item[0] != NULL ? $item[0] : "", $remove_menu_items)) {
					unset($menu[key($menu)]);
				}
			}

			remove_menu_page('wr2x_settings-menu');
			remove_menu_page('meowapps-main-menu');
		}
	}

	function custom_upload_filter($file)
	{
		if (get_option($this->plugin_name)['limit_image_size']) {

			$max_px = get_option($this->plugin_name)['max_px'];
			$min_px = get_option($this->plugin_name)['min_px'];
			$max_size = get_option($this->plugin_name)['max_size'];

			if (!in_array($file['type'], ['image/jpeg', 'image/gif', 'image/png', 'image/bmp', 'image/tiff', 'image/x-icon'])) {
				return $file;
			}
			$image = getimagesize($file['tmp_name']);
			if ($file['size'] >= $max_size * 1048576) {
				$file['error'] = "This image is larger than the {$max_size}mb maximum. Please resize your image so you do not break the internet with your very large image. Or change in settings";
				return $file;
			}
			$minimum = array(
				'width' => $min_px,
				'height' => $min_px
			);
			$maximum = array(
				'width' => $max_px,
				'height' => $max_px
			);
			$image_width = $image[0];
			$image_height = $image[1];

			$too_small = "Image dimensions are too small. Minimum size is {$minimum['width']} by {$minimum['height']} pixels. Uploaded image is $image_width by $image_height pixels. Please resize your image. Or change in settings";
			$too_large = "Image dimensions are too large. Maximum size is {$maximum['width']} by {$maximum['height']} pixels. Uploaded image is $image_width by $image_height pixels. Please resize your image. Or change in settings";

			if ($image_width < $minimum['width'] || $image_height < $minimum['height']) {
				// add in the field 'error' of the $file array the message 
				$file['error'] = $too_small;
				return $file;
			} elseif ($image_width > $maximum['width'] || $image_height > $maximum['height']) {
				//add in the field 'error' of the $file array the message
				$file['error'] = $too_large;
				return $file;
			} else
				return $file;
		} else {
			return $file;
		}
	}

	public function attachment_redirect()
	{
		if (get_option($this->plugin_name)['disable_attachment_pages']) {
			global $post;
			if (is_attachment() && isset($post->post_parent) && is_numeric($post->post_parent) && ($post->post_parent != 0)) {

				$parent_post_in_trash = get_post_status($post->post_parent) === 'trash' ? true : false;

				if ($parent_post_in_trash) {
					wp_die('Page not found.', '404 - Page not found', 404); // Prevent endless redirection loop in old WP releases and redirecting to trashed posts if an attachment page is visited when parent post is in trash
				}

				wp_safe_redirect(get_permalink($post->post_parent), ATTACHMENT_REDIRECT_CODE); // Redirect to post/page from where attachment was uploaded
				exit;
			} elseif (is_attachment() && isset($post->post_parent) && is_numeric($post->post_parent) && ($post->post_parent < 1)) {

				wp_safe_redirect(get_bloginfo('wpurl'), ORPHAN_ATTACHMENT_REDIRECT_CODE); // Redirect to home for attachments not associated to any post/page
				exit;
			}
		} else {
			return;
		}
	}

	public function toggle_admin()
	{
		if (get_option($this->plugin_name)['disable_admin']) {
			return false;
		} else {
			return true;
		}
	}
	public function toggle_edit_link()
	{
		return '';
	}
}
