<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package C9_Admin
 * @subpackage C9_Admin/admin
 */
class C9_Admin
{


    /**
     * The version of this plugin.
     *
     * @since 1.0.0
     * @access private
     * @var string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since 1.0.0
     * @param string $plugin_name The name of this plugin.
     * @param string $version The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

        // Add menu item
        add_action('admin_menu', array($this, 'add_plugin_admin_menu'));

        if (!$this->c9_option('custom_skin')) {
            add_action('admin_enqueue_scripts', array($this, 'enqueue_styles'));
            add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
        }

        // Add Settings link to the plugin
        $plugin_basename = plugin_basename(plugin_dir_path(__DIR__) . $this->plugin_name . '.php');
        add_filter('plugin_action_links_' . $plugin_basename, array($this, 'add_action_links'));

        // Core Functionality
        add_action('admin_head', array($this, 'show_updated_only_to_admins'));

        // Add dashboard widget
        add_action('wp_dashboard_setup', array($this, 'c9_remove_dashboard_widgets'));
        add_action('wp_dashboard_setup', array($this, 'c9_add_dashboard_widget'));

        if ($this->c9_option('suppress_update_notice')) {
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
        $columns['c9_thumb'] = __('Featured Image', 'c9-admin');
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
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'dist/c9-admin.build.css', array(), $this->version, 'all');

        // Css rules for Color Picker
        wp_enqueue_style('wp-color-picker');
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since 1.0.0
     */
    public function enqueue_scripts()
    {

        wp_enqueue_script('hoverIntent', '', array('jquery'), $this->version, false);

        // Add the js file
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'dist/c9-admin.build.js', array('jquery', 'wp-color-picker'), $this->version, false);
    }

    /**
     * Add a settings page for this plugin to the Settings menu.
     */
    public function add_plugin_admin_menu()
    {
        add_options_page(
            'C9 Admin Dashboard Plugin Settings',
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
            '<a href="' . admin_url('options-general.php?page=' . $this->plugin_name) . '">' . __('Settings', 'c9-admin') . '</a>',
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
     * Function that will check if value is a valid HEX color.
     */
    public function c9_check_color($value)
    {

        if (preg_match('/^#[a-f0-9]{6}$/i', $value)) { // if user insert a HEX color with #     
            return true;
        }

        return false;
    }

    /**
     * Helper function to safely retrieve the value of `get_option($this->plugin_name)[$name]`
     */
    private function c9_option($name)
    {
        $opt = get_option($this->plugin_name);
        return ((false !== $opt) && array_key_exists($name, $opt) && !empty($opt[$name]) ? $opt[$name] : false);
    }

    /**
     * Helper function to validate boolean form inputs
     */
    private function c9_validate($input, $name)
    {
        return ((isset($input[$name]) && !empty($input[$name])) ? 1 : 0);
    }

    /**
     * Validate Option Input
     **/
    public function validate($input)
    {
        // All checkboxes inputs
        $valid = array();

        // Cleanup
        $valid['disable_admin'] = $this->c9_validate($input, 'disable_admin');
        $valid['disable_attachment_pages'] = $this->c9_validate($input, 'disable_attachment_pages');
        $valid['hide_developer_items'] = $this->c9_validate($input, 'hide_developer_items');
        $valid['hide_seo_settings'] = $this->c9_validate($input, 'hide_seo_settings');
        $valid['hide_matomo_settings'] = $this->c9_validate($input, 'hide_matomo_settings');
        $valid['hide_user_settings'] = $this->c9_validate($input, 'hide_user_settings');
        //hide_default_posts
        $valid['hide_default_posts'] = $this->c9_validate($input, 'hide_default_posts');
        $valid['admin_only_notifications'] = $this->c9_validate($input, 'admin_only_notifications');
        $valid['suppress_update_notice'] = $this->c9_validate($input, 'suppress_update_notice');
        $valid['limit_image_size'] = $this->c9_validate($input, 'limit_image_size');
        $valid['define_custom_labels'] = $this->c9_validate($input, 'define_custom_labels');
        $valid['max_px'] = intval($input['max_px']);
        $valid['min_px'] = intval($input['min_px']);
        $valid['max_size'] = intval($input['max_size']);
        $valid['custom_skin'] = $this->c9_validate($input, 'custom_skin');
        $valid['hide_plugin_menu_item'] = $this->c9_validate($input, 'hide_plugin_menu_item');
        $valid['hide_update_menu_item'] = $this->c9_validate($input, 'hide_update_menu_item');
        $valid['hide_comment_menu_item'] = $this->c9_validate($input, 'hide_comment_menu_item');


        $valid['custom_media_label'] = strval($input['custom_media_label']);
        $valid['custom_posts_label'] = strval($input['custom_posts_label']);
        $valid['custom_pages_label'] = strval($input['custom_pages_label']);
        $valid['custom_menu_label'] = strval($input['custom_menu_label']);
        $valid['custom_post_categories_label'] = strval($input['custom_post_categories_label']);
        $valid['custom_post_tags_label'] = strval($input['custom_post_tags_label']);
        $valid['custom_upload_files_label'] = strval($input['custom_upload_files_label']);
        $valid['custom_all_files_label'] = strval($input['custom_all_files_label']);
        // $valid['custom_analytics_label'] = strval($input['custom_analytics_label']);

        // Validate Admin Menu Background Color
        $valid['admin_menu_color'] = trim($input['admin_menu_color']);
        $valid['admin_menu_color'] = strip_tags(stripslashes($valid['admin_menu_color']));

        // Check if is a valid hex color
        if ((!empty($this->plugin_name['admin_menu_color'])) && (FALSE === $this->c9_check_color($valid['admin_menu_color']))) {


            // Set the error message
            add_settings_error('admin_menu_color', 'admin_menu_color_bg_error', 'Insert a valid color for the WordPress admin menu background', 'error'); // $setting, $code, $message, $type

            // Get the previous valid value
            if (!empty($this->plugin_name['admin_menu_color'])) {
                $valid['admin_menu_color'] = $this->plugin_name['admin_menu_color'];
            }
        }

        // Validate Admin Menu Text Color
        $valid['admin_menu_text_color'] = trim($input['admin_menu_text_color']);
        $valid['admin_menu_text_color'] = strip_tags(stripslashes($valid['admin_menu_text_color']));

        // Check if is a valid hex color
        if ((!empty($this->plugin_name['admin_menu_text_color'])) && (FALSE === $this->c9_check_color($valid['admin_menu_text_color']))) {

            // Set the error message
            add_settings_error('admin_menu_text_color', 'admin_menu_text_color_bg_error', 'Insert a valid color for the WordPress admin menu text', 'error'); // $setting, $code, $message, $type

            // Get the previous valid value
            if (!empty($this->plugin_name['admin_menu_text_color'])) {
                $valid['admin_menu_text_color'] = $this->plugin_name['admin_menu_text_color'];
            }
        }

        // Validate Admin Login Background Color
        $valid['admin_login_bg_color'] = trim($input['admin_login_bg_color']);
        $valid['admin_login_bg_color'] = strip_tags(stripslashes($valid['admin_login_bg_color']));

        // Check if is a valid hex color
        if ((!empty($this->plugin_name['admin_login_bg_color'])) && (FALSE === $this->c9_check_color($valid['admin_login_bg_color']))) {

            // Set the error message
            add_settings_error('admin_menu_color', 'admin_login_bg_error', 'Insert a valid color for WordPress admin login screen background', 'error'); // $setting, $code, $message, $type

            // Get the previous valid value
            if (!empty($this->plugin_name['admin_login_bg_color'])) {
                $valid['admin_login_bg_color'] = $this->plugin_name['admin_login_bg_color'];
            }
        }
        return $valid;
    }

    /**
     * Supress Notification Updates
     */
    public function suppress_update_notice()
    {
        if ($this->c9_option('suppress_update_notice')) {
            global $wp_version;
            return (object) array('last_checked' => time(), 'version_checked' => $wp_version,);
        }
    }

    /**
     * Only show updates to logged-in admins.
     **/
    public function show_updated_only_to_admins()
    {
        if ($this->c9_option('admin_only_notifications')) {
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

        if ($this->c9_option('hide_developer_items')) {
            // $remove_menu_items[] = __('Events');
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

        if ($this->c9_option('hide_plugin_menu_item')) {
            $remove_menu_items[] = __('Plugins');
        }

        if ($this->c9_option('hide_comment_menu_item')) {
            $remove_menu_items[] = __('Comments');
        }

        if ($this->c9_option('hide_update_menu_item')) {
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
        if ($this->c9_option('hide_developer_items')) {
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

        // hide Matomo settings
        if ($this->c9_option('hide_matomo_settings')) {
            remove_menu_page('matomo');
            remove_submenu_page('index.php', 'index.php?page=wp-piwik_stats');
        }

        // hide User settings
        if ($this->c9_option('hide_user_settings')) {
            remove_menu_page('users.php');
        }

        //hide default posts
        if ($this->c9_option('hide_default_posts')) {
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
        if ($this->c9_option('limit_image_size')) {

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
                'height' => $min_px,
            );
            $maximum = array(
                'width' => $max_px,
                'height' => $max_px,
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
        if ($this->c9_option('disable_attachment_pages')) {
            global $post;
            if (is_attachment() && isset($post->post_parent) && is_numeric($post->post_parent) && ($post->post_parent != 0)) {

                $parent_post_in_trash = get_post_status($post->post_parent) === 'trash' ? true : false;

                if ($parent_post_in_trash) {
                    wp_die('Page not found.', '404 - Page not found', 404); // Prevent endless redirection loop in old WP releases and redirecting to trashed posts if an attachment page is visited when parent post is in trash
                }

                wp_safe_redirect(get_permalink($post->post_parent), C9_ADMIN_ATTACHMENT_REDIRECT_CODE); // Redirect to post/page from where attachment was uploaded
                exit;
            } elseif (is_attachment() && isset($post->post_parent) && is_numeric($post->post_parent) && ($post->post_parent < 1)) {

                wp_safe_redirect(get_bloginfo('wpurl'), C9_ADMIN_ORPHAN_ATTACHMENT_REDIRECT_CODE); // Redirect to home for attachments not associated to any post/page
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
        if ($this->c9_option('disable_admin') || (!is_user_logged_in())) {
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
        if ($this->c9_option('define_custom_labels')) {
            global $menu;
            global $submenu;
            // print_r($menu);
            // print_r($submenu["edit.php"]);
            // print_r($submenu);
            $menu[20][0] = $this->get_label('custom_pages_label', 'Landing Pages');
            $menu[10][0] = $this->get_label('custom_media_label', 'Media &amp; Files');
            $menu[5][0] = $this->get_label('custom_posts_label', 'Blog Post');
            $submenu['edit.php'][5][0] = 'List ' . $this->get_label('custom_posts_label', 'Blog Post');
            $submenu['edit.php'][10][0] = 'Add New ' . $this->get_label('custom_posts_label', 'Blog Post');
            $submenu['edit.php'][15][0] = $this->get_label('custom_post_categories_label', 'Blog Categories');
            $submenu['edit.php'][16][0] = $this->get_label('custom_post_tags_label', 'Blog Tags');
            $submenu['upload.php'][10][0] = $this->get_label('custom_upload_files_label', 'Upload Files');
            $submenu['upload.php'][5][0] = $this->get_label('custom_all_files_label', 'All Files');
            // $menu[100][0] = $this->get_label('custom_analytics_label', 'Matomo Analytics');
            // $menu[101][0] = $this->get_label('custom_analytics_label', 'Matomo Analytics');
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

    /**
     * Remove dashboard widgets seldom used
     *
     * @since 1.1
     */
    public function c9_remove_dashboard_widgets()
    {
        //first parameter -> slig/id of the widget
        //second parameter -> where the meta box is displayed, it can be page, post, dashboard etc.
        //third parameter -> position of the meta box. If you have used wp_add_dashboard_widget to create the widget or deleting default widget then provide the value "normal".
        remove_meta_box('dashboard_incoming_links', 'dashboard', "normal");
        remove_meta_box('dashboard_plugins', 'dashboard', 'normal');
        remove_meta_box('dashboard_primary', 'dashboard', 'side'); // WordPress.com blog
        remove_meta_box('dashboard_secondary', 'dashboard', 'side'); // other WordPress news
        remove_meta_box('dashboard_quick_press', 'dashboard', 'side');
        //remove_meta_box('dashboard_right_now', 'dashboard', 'normal');
        remove_meta_box('dashboard_activity', 'dashboard', 'normal');
        //remove_meta_box('dashboard_site_health', 'dashboard', 'normal');

        global $wp_meta_boxes;

        //move right now to side
        $dashboard_right_now = $wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now'];
        unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
        $wp_meta_boxes['dashboard']['side']['core']['dashboard_right_now'] = $dashboard_right_now;

        //move site health to side
        $dashboard_site_health = $wp_meta_boxes['dashboard']['normal']['core']['dashboard_site_health'];
        unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_site_health']);
        $wp_meta_boxes['dashboard']['side']['core']['dashboard_site_health'] = $dashboard_site_health;
    }

    /**
     * Add a widget to the dashboard
     *
     * @since 1.1
     */
    public function c9_add_dashboard_widget()
    {

        wp_add_dashboard_widget(
            'c9-admin-dashboard', // Widget slug.
            esc_html__('C9 Website Admin Dashboard', 'c9-admin'), // Title.
            array($this, 'c9_admin_dashboard_render') // Display function.
        );

        // Globalize the metaboxes array, this holds all the widgets for wp-admin.
        global $wp_meta_boxes;

        // Get the regular dashboard widgets array 
        // (which already has our new widget but appended at the end).
        $default_dashboard = $wp_meta_boxes['dashboard']['normal']['core'];

        // Backup and delete our new dashboard widget from the end of the array.
        $c9_widget_backup = array('c9-admin-dashboard' => $default_dashboard['c9-admin-dashboard']);
        unset($default_dashboard['c9-admin-dashboard']);

        // Merge the two arrays together so our widget is at the beginning.
        $sorted_dashboard = array_merge($c9_widget_backup, $default_dashboard);

        // Save the sorted array back into the original metaboxes. 
        $wp_meta_boxes['dashboard']['normal']['core'] = $sorted_dashboard;
    }

    /**
     * Create the function to output the contents of C9 Dashboard Widget
     * 
     * @since 1.1
     */
    public function c9_admin_dashboard_render()
    {

        echo '
        <div class="c9-admin-dashboard-widget">
        <h3>' . __('Add or Edit Content', 'c9-admin') . '</h3>
        <ul>
        <li><a href="' . admin_url('post-new.php?post_type=post') . '" class="btn-c9-admin btn-c9admin-addpost"><span>' . __('Add', 'c9-admin') . ' ' . $this->get_label('custom_posts_label', 'Post') . '</span></a></li>
        <li><a href="' . admin_url('post-new.php?post_type=page') . '" class="btn-c9-admin btn-c9admin-addpage"><span>' . __('Add', 'c9-admin') . ' ' . $this->get_label('custom_pages_label', 'Page') . '</span></a></li>
        <li><a href="' . admin_url('customize.php?autofocus[panel]=nav_menus') . '" class="btn-c9-admin btn-c9admin-navigation"><span>' . __('Navigation Links', 'c9-admin') . '</span></a></li>
        <li><a href="' . admin_url('customize.php?autofocus[panel]=widgets') . '" class="btn-c9-admin btn-c9admin-footer"><span>' . __('Footer Content', 'c9-admin') . '</span></a></li>
        </ul>
        </div>
        <div class="c9-admin-dashboard-widget">
        <h3>' . __('Theme Settings + Libraries', 'c9-admin') . '</h3>
        <ul>
        <li><a href="' . admin_url('customize.php?autofocus[section]=options') . '" class="btn-c9-admin btn-c9admin-theme"><span>' . __('Theme Appearance', 'c9-admin') . '</span></a></li>
        <li><a href="' . admin_url('options-general.php?page=C9_Admin') . '" class="btn-c9-admin btn-c9admin-settings"><span>' . __('C9 Admin Settings', 'c9-admin') . '</span></a></li>
        <li><a href="' . admin_url('edit.php?post_type=wp_block') . '" class="btn-c9-admin btn-c9admin-reusable"><span>' . __('Reusable Blocks', 'c9-admin') . '</span></a></li>
        <li><a href="' . admin_url('upload.php') . '" class="btn-c9-admin btn-c9admin-media"><span>' . $this->get_label('custom_media_label', 'Media &amp; Files') . '</span></a></li>
        </ul>
        </div>
        <div class="c9-admin-dashboard-widget">
        <h3>' . __('Using C9 Blocks', 'c9-admin') . '</h3>
        <ul>
        <li><a href="https://www.covertnine.com/c9-blocks-plugin/" title="' . __('C9 Blocks Overview', 'c9-admin') . '" class="btn-c9-admin btn-c9blocks-overview" target="_blank"><span>' . __('C9 Blocks Overview', 'c9-admin') . '</span></a></li>
        <li><a href="' . admin_url('themes.php?page=c9-blocks-getting-started') . '" class="btn-c9-admin btn-c9admin-blocks"><span>' . __('C9 Blocks Getting Started Guide', 'c9-admin') . '</span></a></li>
        <li><a href="https://www.youtube.com/covertnine" title="' . __('Block Videos', 'c9-admin') . '" class="btn-c9-admin btn-c9block-videos" target="_blank"><span>' . __('C9 Blocks Walk-through Videos', 'c9-admin') . '</span></a></li>
        </ul>
        </div>
        <div class="c9-admin-dashboard-widget">
        <h3>' . __('Get Support', 'c9-admin') . '</h3>
        <ul>
        <li><a href="https://c9.covertnine.com/" title="' . __('C9 Theme + Blocks Docs', 'c9-admin') . '" class="btn-c9-admin btn-c9admin-docs" target="_blank"><span>' . __('C9 Theme + Blocks Docs', 'c9-admin') . '</span></a></li>
        <li><a href="https://www.covertnine.com/account" title="' . __('COVERT NINE Account', 'c9-admin') . '" class="btn-c9-admin btn-c9admin-account" target="_blank"><span>' . __('C9 Account Dashboard', 'c9-admin') . '</span></a></li>
        <li><a href="https://www.covertnine.com/get-support" title="' . __('Get premium support from COVERT NINE', 'c9-admin') . '" class="btn-c9-admin btn-c9admin-support" target="_blank"><span>' . __('C9 Paid Support', 'c9-admin') . '</span></a></li>
        <li><a href="https://wordpress.org/support/forums/" title="' . __('WordPress.org Support', 'c9-admin') . '" class="btn-c9-admin btn-c9wp-support" target="_blank"><span>' . __('WP.org Support', 'c9-admin') . '</span></a></li>
        </ul>
        </div>
        ';
    }
}
