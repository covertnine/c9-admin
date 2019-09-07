<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://covertnine.com
 * @since      1.0.0
 *
 * @package    C9_Admin
 * @subpackage C9_Admin/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">

    <h2><?php echo esc_html(get_admin_page_title()); ?></h2>
    <hr>
    <form method="post" name="base_options" action="options.php">

        <?php
        $options = get_option($this->plugin_name);
        // Cleanup
        $admin_only_notifications = $options['admin_only_notifications'];
        $disable_admin = $options['disable_admin'];
        $disable_attachment_pages = $options['disable_attachment_pages'];
        $hide_developer_items = $options['hide_developer_items'];
        $limit_image_size = $options['limit_image_size'];
        $max_px = $options['max_px'];
        $max_size = $options['max_size'];
        $min_px = $options['min_px'];
        ?>

        <?php settings_fields($this->plugin_name);
        do_settings_sections($this->plugin_name);
        ?>

        <!-- remove some meta and generators from the <head> -->
        <fieldset>
            <legend class="screen-reader-text"><span>Disable admin bar on frontend</span></legend>
            <label for="<?php echo $this->plugin_name; ?>-disable_admin">
                <input type="checkbox" id="<?php echo $this->plugin_name; ?>-disable_admin" name="<?php echo $this->plugin_name; ?>[disable_admin]" value="1" <?php checked($disable_admin, 1); ?> />
                <span><?php esc_attr_e('Disable admin bar on frontend', $this->plugin_name); ?></span>
            </label>
        </fieldset>

        <!-- remove injected CSS from comments widgets -->
        <fieldset>
            <legend class="screen-reader-text"><span>Disable media attachment pages</span></legend>
            <label for="<?php echo $this->plugin_name; ?>-disable_attachment_pages">
                <input type="checkbox" id="<?php echo $this->plugin_name; ?>-disable_attachment_pages" name="<?php echo $this->plugin_name; ?>[disable_attachment_pages]" value="1" <?php checked($disable_attachment_pages, 1); ?> />
                <span><?php esc_attr_e('Disable media attachment pages', $this->plugin_name); ?></span>
            </label>
        </fieldset>

        <!-- remove injected CSS from gallery -->
        <fieldset>
            <legend class="screen-reader-text"><span>Hide developer-specific menu items</span></legend>
            <label for="<?php echo $this->plugin_name; ?>-hide_developer_items">
                <input type="checkbox" id="<?php echo $this->plugin_name; ?>-hide_developer_items" name="<?php echo $this->plugin_name; ?>[hide_developer_items]" value="1" <?php checked($hide_developer_items, 1); ?> />
                <span><?php esc_attr_e('Hide developer-specific menu items', $this->plugin_name); ?></span>
            </label>
        </fieldset>

        <!-- add post,page or product slug class to body class -->
        <fieldset>
            <legend class="screen-reader-text"><span><?php _e('Make notification visible to admins only', $this->plugin_name); ?></span></legend>
            <label for="<?php echo $this->plugin_name; ?>-admin_only_notifications">
                <input type="checkbox" id="<?php echo $this->plugin_name; ?>-admin_only_notifications" name="<?php echo $this->plugin_name; ?>[admin_only_notifications]" value="1" <?php checked($admin_only_notifications, 1); ?> />
                <span><?php esc_attr_e('Make notification visible to admins only', $this->plugin_name); ?></span>
            </label>
        </fieldset>
        <hr>
        <h3><?php esc_attr_e('Image Size Limit', $this->plugin_name); ?></h3>
        <!-- load jQuery from CDN -->
        <fieldset>
            <legend class="screen-reader-text"><span><?php _e('Limit Image Size', $this->plugin_name); ?></span></legend>
            <label for="<?php echo $this->plugin_name; ?>-limit_image_size">
                <div><?php esc_attr_e('Limit Image Size', $this->plugin_name); ?></div>
                <input type="checkbox" id="<?php echo $this->plugin_name; ?>-limit_image_size" name="<?php echo $this->plugin_name; ?>[limit_image_size]" value="1" <?php checked($limit_image_size, 1); ?> />
            </label>
            <fieldset>
                <legend class="screen-reader-text">
                    <div><?php _e('Define your local Minimums and Maximums', $this->plugin_name); ?></div>
                </legend>
                <label for="<?php echo $this->plugin_name; ?>-max_px">
                    <div><?php esc_attr_e('Max Side Length', $this->plugin_name); ?></div>
                    <input type="text" class="small" id="<?php echo $this->plugin_name; ?>-max_px" name="<?php echo $this->plugin_name; ?>[max_px]" value="<?php if (!empty($max_px)) echo $max_px; ?>" /><span>px</span>
                </label>
                <br>
                <label for="<?php echo $this->plugin_name; ?>-min_px">
                    <div><?php esc_attr_e('Min Side Length', $this->plugin_name); ?></div>
                    <input type="text" class="small" id="<?php echo $this->plugin_name; ?>-min_pix" name="<?php echo $this->plugin_name; ?>[min_px]" value="<?php if (!empty($min_px)) echo $min_px; ?>" /><span>px</span>
                </label>
                <br>
                <label for="<?php echo $this->plugin_name; ?>-max_size">
                    <div><?php esc_attr_e('Min File Size', $this->plugin_name); ?></div>
                    <input type="text" class="small" id="<?php echo $this->plugin_name; ?>-max_size" name="<?php echo $this->plugin_name; ?>[max_size]" value="<?php if (!empty($max_size)) echo $max_size; ?>" /><span>mb</span>
                </label>
            </fieldset>
        </fieldset>

        <?php submit_button('Save all changes', 'primary', 'submit', TRUE); ?>

    </form>

</div>