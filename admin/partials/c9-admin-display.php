<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link  http://covertnine.com
 * @since 1.0.0
 *
 * @package    C9_Admin
 * @subpackage C9_Admin/admin/partials
 */

/**
 * Helper function to safely retrieve the value of `get_option($this->plugin_name)[$name]`
 */
$c9_option = function($name) {
    $opt = get_option($this->plugin_name);
    return ((false !== $opt) && array_key_exists($name, $opt) && !empty($opt[$name]) ? $opt[$name] : false);
};

// Cleanup
$admin_only_notifications = $c9_option('admin_only_notifications');
$suppress_update_notice = $c9_option('suppress_update_notice');
$disable_admin = $c9_option('disable_admin');
$disable_attachment_pages = $c9_option('disable_attachment_pages');
$hide_developer_items = $c9_option('hide_developer_items');
$hide_seo_settings = $c9_option('hide_seo_settings');
$hide_matomo_settings = $c9_option('hide_matomo_settings');
$hide_user_settings = $c9_option('hide_user_settings');
$hide_default_posts = $c9_option('hide_default_posts');
$limit_image_size = $c9_option('limit_image_size');
$max_px = $c9_option('max_px');
$max_size = $c9_option('max_size');
$min_px = $c9_option('min_px');
$custom_skin = $c9_option('custom_skin');
$admin_menu_color = $c9_option('admin_menu_color');
$admin_menu_text_color = $c9_option('admin_menu_text_color');
$admin_login_bg_color = $c9_option('admin_login_bg_color');
$hide_plugin_menu_item = $c9_option('hide_plugin_menu_item');
$hide_update_menu_item = $c9_option('hide_update_menu_item');
$hide_comment_menu_item = $c9_option('hide_comment_menu_item');

$define_custom_labels = $c9_option('define_custom_labels');
$custom_media_label = $c9_option('custom_media_label');
$custom_posts_label = $c9_option('custom_posts_label');
$custom_pages_label = $c9_option('custom_pages_label');
$custom_menu_label = $c9_option('custom_menu_label');
$custom_post_categories_label = $c9_option('custom_post_categories_label');
$custom_post_tags_label = $c9_option('custom_post_tags_label');
$custom_upload_files_label = $c9_option('custom_upload_files_label');
$custom_all_files_label = $c9_option('custom_all_files_label');
// $custom_analytics_label = $c9_option('custom_analytics_label');
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">

    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    <form method="post" name="base_options" action="options.php">


        <?php
        settings_fields($this->plugin_name);
        do_settings_sections($this->plugin_name);
        ?>
        <div class="c9-admin-settings-wrap">
            <div class="col">
                <h3><?php esc_html_e('C9 WordPress Admin Settings', 'c9-admin'); ?></h3>
                <hr>
                <!-- remove some meta and generators from the <head> -->
                <fieldset>
                    <legend class="screen-reader-text"><span><?php _e('Disable admin bar on frontend', 'c9-admin'); ?></span></legend>
                    <label for="<?php echo $this->plugin_name; ?>-disable_admin">
                        <input type="checkbox" id="<?php echo $this->plugin_name; ?>-disable_admin" name="<?php echo $this->plugin_name; ?>[disable_admin]" value="1" <?php checked($disable_admin, 1); ?> />
                        <span><?php esc_attr_e('Disable admin bar on frontend', 'c9-admin'); ?></span>
                    </label>
                </fieldset>

                <!-- remove injected CSS from comments widgets -->
                <fieldset>
                    <legend class="screen-reader-text"><span><?php _e('Disable media attachment pages', 'c9-admin'); ?></span></legend>
                    <label for="<?php echo $this->plugin_name; ?>-disable_attachment_pages">
                        <input type="checkbox" id="<?php echo $this->plugin_name; ?>-disable_attachment_pages" name="<?php echo $this->plugin_name; ?>[disable_attachment_pages]" value="1" <?php checked($disable_attachment_pages, 1); ?> />
                        <span><?php esc_attr_e('Disable media attachment pages', 'c9-admin'); ?></span>
                    </label>
                </fieldset>

                <!-- remove injected CSS from gallery -->

                <fieldset>
                    <legend class="screen-reader-text"><span><?php _e('Hide default Posts', 'c9-admin'); ?></span></legend>
                    <label for="<?php echo $this->plugin_name; ?>-hide_default_posts">
                        <input type="checkbox" id="<?php echo $this->plugin_name; ?>-hide_default_posts" name="<?php echo $this->plugin_name; ?>[hide_default_posts]" value="1" <?php checked($hide_default_posts, 1); ?> />
                        <span><?php esc_attr_e('Hide default posts', 'c9-admin'); ?></span>
                    </label>
                </fieldset>

                <fieldset>
                    <legend class="screen-reader-text"><span><?php _e('Hide developer-specific menu items', 'c9-admin'); ?></span></legend>
                    <label for="<?php echo $this->plugin_name; ?>-hide_developer_items">
                        <input type="checkbox" id="<?php echo $this->plugin_name; ?>-hide_developer_items" name="<?php echo $this->plugin_name; ?>[hide_developer_items]" value="1" <?php checked($hide_developer_items, 1); ?> />
                        <span><?php esc_attr_e('Hide developer-specific menu items', 'c9-admin'); ?></span>
                    </label>
                </fieldset>

                <fieldset>
                    <legend class="screen-reader-text"><span><?php _e('Hide Matomo Analytics', 'c9-admin'); ?></span></legend>
                    <label for="<?php echo $this->plugin_name; ?>-hide_matomo_settings">
                        <input type="checkbox" id="<?php echo $this->plugin_name; ?>-hide_matomo_settings" name="<?php echo $this->plugin_name; ?>[hide_matomo_settings]" value="1" <?php checked($hide_matomo_settings, 1); ?> />
                        <span><?php esc_attr_e('Hide Matomo analytics settings', 'c9-admin'); ?></span>
                    </label>
                </fieldset>

                <fieldset>
                    <legend class="screen-reader-text"><span><?php _e('Hide user settings', 'c9-admin'); ?></span></legend>
                    <label for="<?php echo $this->plugin_name; ?>-hide_user_settings">
                        <input type="checkbox" id="<?php echo $this->plugin_name; ?>-hide_user_settings" name="<?php echo $this->plugin_name; ?>[hide_user_settings]" value="1" <?php checked($hide_user_settings, 1); ?> />
                        <span><?php esc_attr_e('Hide user settings', 'c9-admin'); ?></span>
                    </label>
                </fieldset>

                <!-- add post,page or product slug class to body class -->
                <fieldset>
                    <legend class="screen-reader-text"><span><?php _e('Make notification visible to admins only', 'c9-admin'); ?></span></legend>
                    <label for="<?php echo $this->plugin_name; ?>-admin_only_notifications">
                        <input type="checkbox" id="<?php echo $this->plugin_name; ?>-admin_only_notifications" name="<?php echo $this->plugin_name; ?>[admin_only_notifications]" value="1" <?php checked($admin_only_notifications, 1); ?> />
                        <span><?php esc_attr_e('Make notification visible to admins only', 'c9-admin'); ?></span>
                    </label>
                </fieldset>
                <!-- add post,page or product slug class to body class -->
                <fieldset>
                    <legend class="screen-reader-text"><span><?php _e('Suppress update notifications', 'c9-admin'); ?></span></legend>
                    <label for="<?php echo $this->plugin_name; ?>-suppress_update_notice">
                        <input type="checkbox" id="<?php echo $this->plugin_name; ?>-suppress_update_notice" name="<?php echo $this->plugin_name; ?>[suppress_update_notice]" value="1" <?php checked($suppress_update_notice, 1); ?> />
                        <span><?php esc_attr_e('Suppress update notifications', 'c9-admin'); ?></span>
                    </label>
                </fieldset>
                <fieldset>
                    <legend class="screen-reader-text"><span><?php _e('Hide Updates menu item', 'c9-admin'); ?></span></legend>
                    <label for="<?php echo $this->plugin_name; ?>-hide_update_menu_item">
                        <input type="checkbox" id="<?php echo $this->plugin_name; ?>-hide_update_menu_item" name="<?php echo $this->plugin_name; ?>[hide_update_menu_item]" value="1" <?php checked($hide_update_menu_item, 1); ?> />
                        <span><?php esc_attr_e('Hide Updates menu item', 'c9-admin'); ?></span>
                    </label>
                </fieldset>
                <fieldset>
                    <legend class="screen-reader-text"><span><?php _e('Hide Comments menu item', 'c9-admin'); ?></span></legend>
                    <label for="<?php echo $this->plugin_name; ?>-hide_comment_menu_item">
                        <input type="checkbox" id="<?php echo $this->plugin_name; ?>-hide_comment_menu_item" name="<?php echo $this->plugin_name; ?>[hide_comment_menu_item]" value="1" <?php checked($hide_comment_menu_item, 1); ?> />
                        <span><?php esc_attr_e('Hide Comments menu item', 'c9-admin'); ?></span>
                    </label>
                </fieldset>
                <fieldset>
                    <legend class="screen-reader-text"><span><?php _e('Hide Plugin Menu Item', 'c9-admin'); ?></span></legend>
                    <label for="<?php echo $this->plugin_name; ?>-hide_plugin_menu_item">
                        <input type="checkbox" id="<?php echo $this->plugin_name; ?>-hide_plugin_menu_item" name="<?php echo $this->plugin_name; ?>[hide_plugin_menu_item]" value="1" <?php checked($hide_plugin_menu_item, 1); ?> />
                        <span><?php esc_attr_e('Hide Plugins menu item', 'c9-admin'); ?></span>
                    </label>
                </fieldset>
            </div>
            <div class="col">
                <h3><?php esc_html_e('Admin Styles + Colors', 'c9-admin'); ?></h3>
                <hr>
                <fieldset>
                    <legend class="screen-reader-text"><span><?php esc_html_e('Custom Skin Admin', 'c9-admin'); ?></span></legend>
                    <label for="<?php echo $this->plugin_name; ?>-custom_skin">
                        <input type="checkbox" id="<?php echo $this->plugin_name; ?>-custom_skin" name="<?php echo $this->plugin_name; ?>[custom_skin]" value="1" <?php checked($custom_skin, 1); ?> />
                        <span><?php esc_html_e('Disable custom skin for admin', 'c9-admin'); ?></span>
                        <br /><br />
                    </label>
                    <h4><?php esc_html_e('Admin Sidebar Menu Background Color', 'c9-admin'); ?></h4>
                    <label for="<?php echo $this->plugin_name; ?>-admin_menu_color">
                        <div class="c9-label sr-only"><?php esc_html_e('Select Admin Menu Color', 'c9-admin'); ?></div>
                        <input type="text" class="small c9-color-picker" id="<?php echo $this->plugin_name; ?>-admin_menu_color" name="<?php echo $this->plugin_name; ?>[admin_menu_color]" value=<?php echo '"';
                                                                                                                                                                                                    if (!empty($admin_menu_color)) {
                                                                                                                                                                                                        echo $admin_menu_color;
                                                                                                                                                                                                    }
                                                                                                                                                                                                    echo '"';
                                                                                                                                                                                                    ?> />
                    </label>
                    <h4><?php esc_html_e('Admin Sidebar Menu Text Color', 'c9-admin'); ?></h4>
                    <label for="<?php echo $this->plugin_name; ?>-admin_menu_text_color">
                        <div class="c9-label sr-only"><?php esc_html_e('Select Admin Menu Text Color', 'c9-admin'); ?></div>
                        <input type="text" class="small c9-color-picker" id="<?php echo $this->plugin_name; ?>-admin_menu_text_color" name="<?php echo $this->plugin_name; ?>[admin_menu_text_color]" value=<?php echo '"';
                                                                                                                                                                                                            if (!empty($admin_menu_text_color)) {
                                                                                                                                                                                                                echo $admin_menu_text_color;
                                                                                                                                                                                                            }
                                                                                                                                                                                                            echo '"';
                                                                                                                                                                                                            ?> />
                    </label>
                    <h4><?php esc_html_e('Admin Login Screen Background Color', 'c9-admin'); ?></h4>
                    <label for="<?php echo $this->plugin_name; ?>-admin_login_bg_color">
                        <div class="c9-label sr-only"><?php esc_html_e('Select Background Color', 'c9-admin'); ?></div>
                        <input type="text" class="small c9-color-picker" id="<?php echo $this->plugin_name; ?>-admin_login_bg_color" name="<?php echo $this->plugin_name; ?>[admin_login_bg_color]" value=<?php echo '"';
                                                                                                                                                                                                            if (!empty($admin_login_bg_color)) {
                                                                                                                                                                                                                echo $admin_login_bg_color;
                                                                                                                                                                                                            }
                                                                                                                                                                                                            echo '"';
                                                                                                                                                                                                            ?> />
                    </label>
                </fieldset>

            </div>
            <div class="col">
                <h3><?php esc_attr_e('Custom Menu Labels', 'c9-admin'); ?></h3>
                <hr>
                <fieldset>
                    <legend class="screen-reader-text"><span><?php _e('Custom Menu Labels', 'c9-admin'); ?></span></legend>
                    <label for="<?php echo $this->plugin_name; ?>-define_custom_labels">
                        <input type="checkbox" id="<?php echo $this->plugin_name; ?>-define_custom_labels" name="<?php echo $this->plugin_name; ?>[define_custom_labels]" value="1" <?php checked($define_custom_labels, 1); ?> />
                        <?php esc_attr_e('Define custom labels', 'c9-admin'); ?>
                    </label>
                    <fieldset>
                        <legend class="screen-reader-text">
                            <div class="c9-label"><?php _e('Menu Label', 'c9-admin'); ?></div>
                        </legend>
                        <label for="<?php echo $this->plugin_name; ?>-custom_menu_label">
                            <div class="c9-label"><?php esc_html_e('Menu Label', 'c9-admin'); ?></div>
                            <input type="text" class="small" id="<?php echo $this->plugin_name; ?>-custom_menu_label" name="<?php echo $this->plugin_name; ?>[custom_menu_label]" value=<?php
                                                                                                                                                                                        echo '"';
                                                                                                                                                                                        if (!empty($custom_menu_label)) {
                                                                                                                                                                                            echo $custom_menu_label;
                                                                                                                                                                                        }
                                                                                                                                                                                        echo '"';
                                                                                                                                                                                        ?> />
                        </label>


                        <legend class="screen-reader-text">
                            <div class="c9-label"><?php esc_html_e('Pages Label', 'c9-admin'); ?></div>
                        </legend>
                        <label for="<?php echo $this->plugin_name; ?>-custom_pages_label">
                            <div class="c9-label"><?php esc_attr_e('Pages Label', 'c9-admin'); ?></div>
                            <input type="text" class="small" id="<?php echo $this->plugin_name; ?>-custom_pages_label" name="<?php echo $this->plugin_name; ?>[custom_pages_label]" value=<?php
                                                                                                                                                                                            echo '"';
                                                                                                                                                                                            if (!empty($custom_pages_label)) {
                                                                                                                                                                                                echo $custom_pages_label;
                                                                                                                                                                                            }
                                                                                                                                                                                            echo '"';
                                                                                                                                                                                            ?> />
                        </label>


                        <legend class="screen-reader-text">
                            <div class="c9-label"><?php _e('Media Label', 'c9-admin'); ?></div>
                        </legend>
                        <label for="<?php echo $this->plugin_name; ?>-custom_media_label">
                            <div class="c9-label"><?php esc_attr_e('Media Label', 'c9-admin'); ?></div>
                            <input type="text" class="small" id="<?php echo $this->plugin_name; ?>-custom_media_label" name="<?php echo $this->plugin_name; ?>[custom_media_label]" value=<?php
                                                                                                                                                                                            echo '"';
                                                                                                                                                                                            if (!empty($custom_media_label)) {
                                                                                                                                                                                                echo $custom_media_label;
                                                                                                                                                                                            }
                                                                                                                                                                                            echo '"';
                                                                                                                                                                                            ?> />
                        </label>


                        <label for="<?php echo $this->plugin_name; ?>-custom_posts_label">
                            <div class="c9-label"><?php esc_attr_e('Posts Label', 'c9-admin'); ?></div>
                            <input type="text" class="small" id="<?php echo $this->plugin_name; ?>-custom_posts_label" name="<?php echo $this->plugin_name; ?>[custom_posts_label]" value=<?php
                                                                                                                                                                                            echo '"';
                                                                                                                                                                                            if (!empty($custom_posts_label)) {
                                                                                                                                                                                                echo $custom_posts_label;
                                                                                                                                                                                            }
                                                                                                                                                                                            echo '"';
                                                                                                                                                                                            ?> />
                        </label>


                        <label for="<?php echo $this->plugin_name; ?>-custom_post_categories_label">
                            <div class="c9-label"><?php esc_attr_e('Post Categories Label', 'c9-admin'); ?></div>
                            <input type="text" class="small" id="<?php echo $this->plugin_name; ?>-custom_post_categories_label" name="<?php echo $this->plugin_name; ?>[custom_post_categories_label]" value=<?php
                                                                                                                                                                                                                echo '"';
                                                                                                                                                                                                                if (!empty($custom_post_categories_label)) {
                                                                                                                                                                                                                    echo $custom_post_categories_label;
                                                                                                                                                                                                                }
                                                                                                                                                                                                                echo '"';
                                                                                                                                                                                                                ?> />
                        </label>


                        <label for="<?php echo $this->plugin_name; ?>-custom_post_tags_label">
                            <div class="c9-label"><?php esc_attr_e('Posts Tags Label', 'c9-admin'); ?></div>
                            <input type="text" class="small" id="<?php echo $this->plugin_name; ?>-custom_post_tags_label" name="<?php echo $this->plugin_name; ?>[custom_post_tags_label]" value=<?php
                                                                                                                                                                                                    echo '"';
                                                                                                                                                                                                    if (!empty($custom_post_tags_label)) {
                                                                                                                                                                                                        echo $custom_post_tags_label;
                                                                                                                                                                                                    }
                                                                                                                                                                                                    echo '"';
                                                                                                                                                                                                    ?> />
                        </label>


                        <label for="<?php echo $this->plugin_name; ?>-custom_upload_files_label">
                            <div class="c9-label"><?php esc_attr_e('Upload Files Label', 'c9-admin'); ?></div>
                            <input type="text" class="small" id="<?php echo $this->plugin_name; ?>-custom_upload_files_label" name="<?php echo $this->plugin_name; ?>[custom_upload_files_label]" value=<?php
                                                                                                                                                                                                        echo '"';
                                                                                                                                                                                                        if (!empty($custom_upload_files_label)) {
                                                                                                                                                                                                            echo $custom_upload_files_label;
                                                                                                                                                                                                        }
                                                                                                                                                                                                        echo '"';
                                                                                                                                                                                                        ?> />
                        </label>


                        <label for="<?php echo $this->plugin_name; ?>-custom_all_files_label">
                            <div class="c9-label"><?php esc_attr_e('All Files Label', 'c9-admin'); ?></div>
                            <input type="text" class="small" id="<?php echo $this->plugin_name; ?>-custom_all_files_label" name="<?php echo $this->plugin_name; ?>[custom_all_files_label]" value=<?php
                                                                                                                                                                                                    echo '"';
                                                                                                                                                                                                    if (!empty($custom_all_files_label)) {
                                                                                                                                                                                                        echo $custom_all_files_label;
                                                                                                                                                                                                    }
                                                                                                                                                                                                    echo '"';
                                                                                                                                                                                                    ?> />
                        </label>
                    </fieldset>
                    <?php submit_button('Save all changes', 'primary', 'submit', true); ?>
            </div>

            <div class="col">
                <h3><?php esc_attr_e('Admin Image Upload Size Limit', 'c9-admin'); ?></h3>
                <hr>
                <fieldset>
                    <legend class="screen-reader-text"><span><?php _e('Limit Image Size', 'c9-admin'); ?></span></legend>
                    <label for="<?php echo $this->plugin_name; ?>-limit_image_size">
                        <input type="checkbox" id="<?php echo $this->plugin_name; ?>-limit_image_size" name="<?php echo $this->plugin_name; ?>[limit_image_size]" value="1" <?php checked($limit_image_size, 1); ?> />
                        <?php esc_attr_e('Limit image size', 'c9-admin'); ?>
                    </label>
                    <fieldset>
                        <legend class="screen-reader-text">
                            <div class="c9-label"><?php _e('Define your local Minimums and Maximums', 'c9-admin'); ?></div>
                        </legend>
                        <label for="<?php echo $this->plugin_name; ?>-max_px">
                            <div class="c9-label"><?php esc_attr_e('Max Side Length', 'c9-admin'); ?></div>
                            <input type="text" class="small" id="<?php echo $this->plugin_name; ?>-max_px" name="<?php echo $this->plugin_name; ?>[max_px]" value=<?php
                                                                                                                                                                    echo '"';
                                                                                                                                                                    if (!empty($max_px)) {
                                                                                                                                                                        echo $max_px;
                                                                                                                                                                    }
                                                                                                                                                                    echo '"';
                                                                                                                                                                    ?> />
                            <span>px</span>
                        </label>


                        <label for="<?php echo $this->plugin_name; ?>-min_px">
                            <div class="c9-label"><?php esc_attr_e('Min Side Length', 'c9-admin'); ?></div>
                            <input type="text" class="small" id="<?php echo $this->plugin_name; ?>-min_px" name="<?php echo $this->plugin_name; ?>[min_px]" value=<?php
                                                                                                                                                                    echo '"';
                                                                                                                                                                    if (!empty($min_px)) {
                                                                                                                                                                        echo $min_px;
                                                                                                                                                                    }
                                                                                                                                                                    echo '"';
                                                                                                                                                                    ?> />
                            <span>px</span>
                        </label>


                        <label for="<?php echo $this->plugin_name; ?>-max_size">
                            <div class="c9-label"><?php esc_attr_e('Max File Size', 'c9-admin'); ?></div>
                            <input type="text" class="small" id="<?php echo $this->plugin_name; ?>-max_size" name="<?php echo $this->plugin_name; ?>[max_size]" value=<?php
                                                                                                                                                                        echo '"';
                                                                                                                                                                        if (!empty($max_size)) {
                                                                                                                                                                            echo $max_size;
                                                                                                                                                                        }
                                                                                                                                                                        echo '"';
                                                                                                                                                                        ?> />
                            <span>mb</span>
                        </label>
                    </fieldset>
                </fieldset>
            </div><!-- .col-->


    </form>

</div><!-- .c9-admin-settings-wrap-->

<h3><?php echo esc_html_e('Have a suggestion for Tim + the team?', 'c9-admin'); ?></h3>
<p><?php echo __('Don\'t just sit there; post it on our <a href="https://www.covertnine.com/community/" target="_blank">community forum</a>, and we\'ll respond personally.', 'c9-admin'); ?></p>
</div>
<!-- .wrap-->
