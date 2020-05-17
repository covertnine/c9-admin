<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    C9_Admin
 * @subpackage C9_Admin/admin
 */
class C9_Admin
{


	/**
	 * The version of this plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version     The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		if (!get_option($this->plugin_name)['custom_skin']) {
			add_action('admin_enqueue_scripts', array($this, 'enqueue_styles'));
			add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
		}
		// Add menu item
		add_action('admin_menu', array($this, 'add_plugin_admin_menu'));

		// Add Settings link to the plugin
		$plugin_basename = plugin_basename(plugin_dir_path(__DIR__) . $this->plugin_name . '.php');
		add_filter('plugin_action_links_' . $plugin_basename, array($this, 'add_action_links'));

		// Core Functionality
		add_action('admin_head', array($this, 'show_updated_only_to_admins'));

		if (get_option($this->plugin_name)['suppress_update_notice']) {
			add_filter('pre_site_transient_update_core', array($this, 'suppress_update_notice'));
			add_filter('pre_site_transient_update_plugins', array($this, 'suppress_update_notice'));
			add_filter('pre_site_transient_update_themes', array($this, 'suppress_update_notice'));
		}

		add_action('admin_menu', array($this, 'remove_admin_menu_items'));
		add_action('admin_init', array($this, 'remove_custom_admin_menu_items'));

		add_action('template_redirect', array($this, 'attachment_redirect'));
		add_action('admin_init', array($this, 'options_update'));

		add_filter('show_admin_bar', array($this, 'toggle_admin'));
		add_filter('edit_post_link', array($this, 'toggle_edit_link'));
		add_filter('wp_handle_upload_prefilter', array($this, 'custom_upload_filter'));

		add_filter('custom_menu_order', array($this, 'custom_menu_order'));
		add_filter('menu_order', array($this, 'custom_menu_order'));

		add_action('admin_menu', array($this, 'customize_post_admin_menu_labels'), 1000);

		$this->edit_roles();
		add_filter('manage_posts_columns', array($this, 'add_post_admin_thumbnail_column'), 2);
		add_filter('manage_pages_columns', array($this, 'add_post_admin_thumbnail_column'), 2);

		add_action('manage_posts_custom_column', array($this, 'show_post_thumbnail_column'), 5, 2);
		add_action('manage_pages_custom_column', array($this, 'show_post_thumbnail_column'), 5, 2);
	}

	/**
	 * * Add featured image column
	 */
	public function add_post_admin_thumbnail_column($columns)
	{
		$columns['c9_thumb'] = __('Image');
		return $columns;
	}

	/**
	 * Grab thumbnail image and put it in there
	 */
	public function show_post_thumbnail_column($columns, $c9_id)
	{
		switch ($columns) {
			case 'c9_thumb':
				if (function_exists('the_post_thumbnail')) {
					echo "<div class='c9_col_img' style='max-height:60px;width:120px;overflow:hidden;'>" . get_the_post_thumbnail($c9_id, array(100, 100)) . '</div>';
				} else {
					echo 'hmm... your theme doesn\'t support featured image...';
				}
				break;
		}
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_styles()
	{
		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/c9-admin.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_scripts()
	{

		wp_enqueue_script('hoverintent', plugin_dir_url(__FILE__) . 'js/jquery.hoverIntent.min.js', array('jquery'), $this->version, false);

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/c9-admin.js', array('hoverintent'), $this->version, false);
	}

	/**
	 * Add a settings page for this plugin to the Settings menu.
	 */
	public function add_plugin_admin_menu()
	{
		add_options_page(
			'C9 Admin Options',
			'C9 Admin',
			'manage_options',
			$this->plugin_name,
			array($this, 'display_plugin_setup_page')
		);
	}

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since 1.0.0
	 */
	public function add_action_links($links)
	{
		$settings_link = array(
			'<a href="' . admin_url('options-general.php?page=' . $this->plugin_name) . '">' . __('Settings', 'C9_Admin') . '</a>',
		);
		return array_merge($settings_link, $links);
	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since 1.0.0
	 */
	public function display_plugin_setup_page()
	{
		include_once 'partials/c9-admin-display.php';
	}

	/**
	 * Update options
	 **/
	public function options_update()
	{
		register_setting($this->plugin_name, $this->plugin_name, array($this, 'validate'));
	}

	/**
	 * Validate Option Input
	 **/
	public function validate($input)
	{
		// All checkboxes inputs
		$valid = array();

		// Cleanup
		$valid['disable_admin']            = (isset($input['disable_admin']) && !empty($input['disable_admin'])) ? 1 : 0;
		$valid['disable_attachment_pages'] = (isset($input['disable_attachment_pages']) && !empty($input['disable_attachment_pages'])) ? 1 : 0;
		$valid['hide_developer_items']     = (isset($input['hide_developer_items']) && !empty($input['hide_developer_items'])) ? 1 : 0;
		$valid['hide_seo_settings']     = (isset($input['hide_seo_settings']) && !empty($input['hide_seo_settings'])) ? 1 : 0;
		$valid['hide_matomo_settings']     = (isset($input['hide_matomo_settings']) && !empty($input['hide_matomo_settings'])) ? 1 : 0;
		$valid['hide_user_settings']     = (isset($input['hide_user_settings']) && !empty($input['hide_user_settings'])) ? 1 : 0;
		//hide_default_posts
		$valid['hide_default_posts']     = (isset($input['hide_default_posts']) && !empty($input['hide_default_posts'])) ? 1 : 0;
		$valid['admin_only_notifications'] = (isset($input['admin_only_notifications']) && !empty($input['admin_only_notifications'])) ? 1 : 0;
		$valid['suppress_update_notice'] = (isset($input['suppress_update_notice']) && !empty($input['suppress_update_notice'])) ? 1 : 0;
		$valid['limit_image_size']         = (isset($input['limit_image_size']) && !empty($input['limit_image_size'])) ? 1 : 0;
		$valid['define_custom_labels']         = (isset($input['define_custom_labels']) && !empty($input['define_custom_labels'])) ? 1 : 0;
		$valid['max_px']                   = intval($input['max_px']);
		$valid['min_px']                   = intval($input['min_px']);
		$valid['max_size']                 = intval($input['max_size']);
		$valid['custom_skin']              = (isset($input['custom_skin']) && !empty($input['custom_skin'])) ? 1 : 0;
		$valid['hide_plugin_menu_item']              = (isset($input['hide_plugin_menu_item']) && !empty($input['hide_plugin_menu_item'])) ? 1 : 0;
		$valid['hide_update_menu_item']              = (isset($input['hide_update_menu_item']) && !empty($input['hide_update_menu_item'])) ? 1 : 0;

		$valid['custom_media_label']                   = strval($input['custom_media_label']);
		$valid['custom_posts_label']                   = strval($input['custom_posts_label']);
		$valid['custom_pages_label']                   = strval($input['custom_pages_label']);
		$valid['custom_menu_label']                   = strval($input['custom_menu_label']);
		$valid['custom_post_categories_label']                   = strval($input['custom_post_categories_label']);
		$valid['custom_post_tags_label']                   = strval($input['custom_post_tags_label']);
		$valid['custom_upload_files_label']                   = strval($input['custom_upload_files_label']);
		$valid['custom_all_files_label']                   = strval($input['custom_all_files_label']);
		$valid['custom_analytics_label']                   = strval($input['custom_analytics_label']);


		return $valid;
	}

	/**
	 * Supress Notification Updates
	 */
	public function suppress_update_notice()
	{
		if (get_option($this->plugin_name)['suppress_update_notice']) {
			global $wp_version;
			return (object) array('last_checked' => time(), 'version_checked' => $wp_version,);
		}
	}

	/**
	 * Only show updates to logged-in admins.
	 **/
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

	/**
	 * Remove Advanced Admin menu items.
	 */
	public function remove_admin_menu_items()
	{
		$remove_menu_items = array();

		if (get_option($this->plugin_name)['hide_developer_items']) {
//			$remove_menu_items[] = __('Events');
			$remove_menu_items[] = __('Comments');
			remove_menu_page('wr2x_settings-menu');
			remove_menu_page('meowapps-main-menu');
			remove_menu_page('edit.php?post_type=acf-field-group');
			remove_menu_page('wsal-auditlog');
			remove_menu_page('pmxi-admin-import');
			remove_menu_page('maxmegamenu');

			$remove_menu_items[] = __('Audit Log');
			$remove_menu_items[] = __('Search &amp; Filter');
			$remove_menu_items[] = __('Mega Menu');
			$remove_menu_items[] = __('Max Mega Menu');
		}

		if (get_option($this->plugin_name)['hide_plugin_menu_item']) {
			$remove_menu_items[] = __('Plugins');
		}

		if (get_option($this->plugin_name)['hide_update_menu_item']) {
			// xdebug_break();
			remove_submenu_page('index.php', 'update-core.php');
		}

		if (!empty($remove_menu_items)) {
			global $menu;
			end($menu);
			while (prev($menu)) {
				$item = explode(' ', $menu[key($menu)][0]);
				if (in_array(null != $item[0] ? $item[0] : '', $remove_menu_items)) {
					unset($menu[key($menu)]);
				}
			}
		}
	}

	/**
	 * Remove Custom Admin menu items.
	 */
	public function remove_custom_admin_menu_items()
	{
		if (get_option($this->plugin_name)['hide_developer_items']) {
			remove_menu_page('wsal-auditlog');
			remove_menu_page('pmxi-admin-home');
			remove_menu_page('maxmegamenu');
			remove_menu_page('searchandfilter-settings');
			remove_menu_page('tools.php');
			remove_menu_page('GOTMLS-settings');
			//remove_menu_page('themes.php');
			remove_submenu_page('themes.php', 'themes.php');
			remove_submenu_page('themes.php', 'tgmpa-install-plugins');
			remove_submenu_page('themes.php', 'theme-editor.php');
		}

		// hide seo settings
		if (get_option($this->plugin_name)['hide_seo_settings']) {
			remove_menu_page('theseoframework-settings');
		}

		// hide Matomo settings
		if (get_option($this->plugin_name)['hide_matomo_settings']) {
			remove_menu_page('matomo');
			remove_submenu_page('index.php', 'index.php?page=wp-piwik_stats');
		}

		// hide User settings
		if (get_option($this->plugin_name)['hide_user_settings']) {
			remove_menu_page('users.php');
		}				

		//hide default posts
		if (get_option($this->plugin_name)['hide_default_posts']) {
			remove_menu_page('edit.php');
		}		

	}	

	/**
	 * Test image dimensions on upload; return error message if user-defined limit is exceeded.
	 *
	 * @param array $file Image file being uploaded.
	 */
	public function custom_upload_filter($file)
	{
		if (get_option($this->plugin_name)['limit_image_size']) {

			$max_px   = get_option($this->plugin_name)['max_px'];
			$min_px   = get_option($this->plugin_name)['min_px'];
			$max_size = get_option($this->plugin_name)['max_size'];

			if (!in_array($file['type'], ['image/jpeg', 'image/gif', 'image/png', 'image/bmp', 'image/tiff', 'image/x-icon'])) {
				return $file;
			}
			$image = getimagesize($file['tmp_name']);
			if ($file['size'] >= $max_size * 1048576) {
				$file['error'] = "This image is larger than the {$max_size}mb maximum. Please resize your image so you do not break the internet with your very large image. Or change in settings";
				return $file;
			}
			$minimum      = array(
				'width'  => $min_px,
				'height' => $min_px,
			);
			$maximum      = array(
				'width'  => $max_px,
				'height' => $max_px,
			);
			$image_width  = $image[0];
			$image_height = $image[1];

			$too_small = "Image dimensions are too small. Minimum size is {$minimum['width']} by {$minimum['height']} pixels. Uploaded image is $image_width by $image_height pixels. Please resize your image. Or change in settings";
			$too_large = "Image dimensions are too large. Maximum size is {$maximum['width']} by {$maximum['height']} pixels. Uploaded image is $image_width by $image_height pixels. Please resize your image. Or change in settings";

			if ($image_width < $minimum['width'] || $image_height < $minimum['height']) {
				// add in the field 'error' of the $file array the message
				$file['error'] = $too_small;
				return $file;
			} elseif ($image_width > $maximum['width'] || $image_height > $maximum['height']) {
				// add in the field 'error' of the $file array the message
				$file['error'] = $too_large;
				return $file;
			} else {
				return $file;
			}
		} else {
			return $file;
		}
	}

	/**
	 * Get rid of attachment pages
	 */
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

	/**
	 * Turn admin off on frontend
	 */
	public function toggle_admin()
	{
		if (get_option($this->plugin_name)['disable_admin'] || !is_user_logged_in()) {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * Toggle edit link
	 */
	public function toggle_edit_link()
	{
		return '';
	}

	/**
	 * CUSTOMIZE ADMIN MENU ORDER
	 */
	public function custom_menu_order($menu_ord)
	{
		if (!$menu_ord) {
			return true;
		}
		return array(
			'index.php', // this represents the dashboard link
			'nav-menus.php',
			'edit.php?post_type=page', // the pages tab
			'edit.php', // the posts tab
			'upload.php', // the media manager
		);
	}

	function get_label($option_name, $default)
	{
		return !ctype_space(get_option($this->plugin_name)[$option_name]) && get_option($this->plugin_name)[$option_name] ? get_option($this->plugin_name)[$option_name] : $default;
	}

	/**
	 * Rename nav link names on WordPress backend.
	 */
	function customize_post_admin_menu_labels()
	{
		if (get_option($this->plugin_name)['define_custom_labels']) {
			global $menu;
			global $submenu;
			// print_r($menu);
			// print_r($submenu["edit.php"]);
			// print_r($submenu);
			$menu[20][0]                  = $this->get_label('custom_pages_label', 'Landing Pages');
			$menu[10][0]                  = $this->get_label('custom_media_label', 'Media &amp; Files');
			$menu[5][0]                   = $this->get_label('custom_posts_label', 'Blog Post') . "s";
			$submenu['edit.php'][5][0]    = 'List ' . $this->get_label('custom_posts_label', 'Blog Post') . "s";
			$submenu['edit.php'][10][0]   = 'Add New ' . $this->get_label('custom_posts_label', 'Blog Post');
			$submenu['edit.php'][15][0]   = $this->get_label('custom_post_categories_label', 'Blog Categories');
			$submenu['edit.php'][16][0]   = $this->get_label('custom_post_tags_label', 'Blog Tags');
			$submenu['upload.php'][10][0] = $this->get_label('custom_upload_files_label', 'Upload Files');
			$submenu['upload.php'][5][0]  = $this->get_label('custom_all_files_label', 'All Files');
			$menu[100][0]  				  = $this->get_label('custom_analytics_label', 'Matomo Analytics')
			$menu[101][0]  				  = $this->get_label('custom_analytics_label', 'Matomo Analytics');
			$nav_links_label = $this->get_label('custom_menu_label', 'Navigation Links');

			add_menu_page($nav_links_label, $nav_links_label, 'manage_categories', 'nav-menus.php', '', 'dashicons-menu', 1);
		}
	}

	/**
	 * Edit editor permissions so they can add menu links + manage user accounts.
	 *
	 * @return void
	 */
	public function edit_roles()
	{
		// get the the role object
		$role_object = get_role('editor');

		// add $cap capability to this role object
		$role_object->add_cap('edit_theme_options');
		$role_object->add_cap('list_users');
		$role_object->add_cap('create_users');
		$role_object->add_cap('add_users');
		$role_object->add_cap('promote_users');
		$role_object->add_cap('moderate_comments');
		$role_object->add_cap('upload_files');
	}
}
