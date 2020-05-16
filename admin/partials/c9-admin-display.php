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
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">

	<h2><?php echo esc_html(get_admin_page_title()); ?></h2>
	<hr>
	<form method="post" name="base_options" action="options.php">

		<?php
		$options = get_option($this->plugin_name);
		// Cleanup
		$admin_only_notifications = $options['admin_only_notifications'];
		$suppress_update_notice = $options['suppress_update_notice'];
		$disable_admin            = $options['disable_admin'];
		$disable_attachment_pages = $options['disable_attachment_pages'];
		$hide_developer_items     = $options['hide_developer_items'];
		$hide_seo_settings     	  = $options['hide_seo_settings'];
		$hide_matomo_settings     = $options['hide_matomo_settings'];
		$hide_user_settings       = $options['hide_user_settings'];
		$hide_default_posts		  = $options['hide_default_posts'];
		$limit_image_size         = $options['limit_image_size'];
		$max_px                   = $options['max_px'];
		$max_size                 = $options['max_size'];
		$min_px                   = $options['min_px'];
		$custom_skin              = $options['custom_skin'];
		$hide_plugin_menu_item              = $options['hide_plugin_menu_item'];
		$hide_update_menu_item              = $options['hide_update_menu_item'];

		$define_custom_labels         = $options['define_custom_labels'];
		$custom_media_label                  = $options['custom_media_label'];
		$custom_posts_label                   = $options['custom_posts_label'];
		$custom_pages_label                  = $options['custom_pages_label'];
		$custom_menu_label                  = $options['custom_menu_label'];
		$custom_post_categories_label                  = $options['custom_post_categories_label'];
		$custom_post_tags_label                  = $options['custom_post_tags_label'];
		$custom_upload_files_label                  = $options['custom_upload_files_label'];
		$custom_all_files_label                  = $options['custom_all_files_label'];

		?>

		<?php
		settings_fields($this->plugin_name);
		do_settings_sections($this->plugin_name);
		?>

		<!-- remove some meta and generators from the <head> -->
		<fieldset>
			<legend class="screen-reader-text"><span><?php _e('Disable admin bar on frontend', 'C9_Admin'); ?></span></legend>
			<label for="<?php echo $this->plugin_name; ?>-disable_admin">
				<input type="checkbox" id="<?php echo $this->plugin_name; ?>-disable_admin" name="<?php echo $this->plugin_name; ?>[disable_admin]" value="1" <?php checked($disable_admin, 1); ?> />
				<span><?php esc_attr_e('Disable admin bar on frontend', 'C9_Admin'); ?></span>
			</label>
		</fieldset>

		<!-- remove injected CSS from comments widgets -->
		<fieldset>
			<legend class="screen-reader-text"><span><?php _e('Disable media attachment pages', 'C9_Admin'); ?></span></legend>
			<label for="<?php echo $this->plugin_name; ?>-disable_attachment_pages">
				<input type="checkbox" id="<?php echo $this->plugin_name; ?>-disable_attachment_pages" name="<?php echo $this->plugin_name; ?>[disable_attachment_pages]" value="1" <?php checked($disable_attachment_pages, 1); ?> />
				<span><?php esc_attr_e('Disable media attachment pages', 'C9_Admin'); ?></span>
			</label>
		</fieldset>

		<!-- remove injected CSS from gallery -->

		<fieldset>
			<legend class="screen-reader-text"><span><?php _e('Hide default Posts', 'C9_Admin'); ?></span></legend>
			<label for="<?php echo $this->plugin_name; ?>-hide_default_posts">
				<input type="checkbox" id="<?php echo $this->plugin_name; ?>-hide_default_posts" name="<?php echo $this->plugin_name; ?>[hide_default_posts]" value="1" <?php checked($hide_default_posts, 1); ?> />
				<span><?php esc_attr_e('Hide default Posts', 'C9_Admin'); ?></span>
			</label>
		</fieldset>

		<fieldset>
			<legend class="screen-reader-text"><span><?php _e('Hide developer-specific menu items', 'C9_Admin'); ?></span></legend>
			<label for="<?php echo $this->plugin_name; ?>-hide_developer_items">
				<input type="checkbox" id="<?php echo $this->plugin_name; ?>-hide_developer_items" name="<?php echo $this->plugin_name; ?>[hide_developer_items]" value="1" <?php checked($hide_developer_items, 1); ?> />
				<span><?php esc_attr_e('Hide developer-specific menu items', 'C9_Admin'); ?></span>
			</label>
		</fieldset>

		<fieldset>
			<legend class="screen-reader-text"><span><?php _e('Hide SEO settings', 'C9_Admin'); ?></span></legend>
			<label for="<?php echo $this->plugin_name; ?>-hide_seo_settings">
				<input type="checkbox" id="<?php echo $this->plugin_name; ?>-hide_seo_settings" name="<?php echo $this->plugin_name; ?>[hide_seo_settings]" value="1" <?php checked($hide_seo_settings, 1); ?> />
				<span><?php esc_attr_e('Hide SEO settings', 'C9_Admin'); ?></span>
			</label>
		</fieldset>

		<fieldset>
			<legend class="screen-reader-text"><span><?php _e('Hide Matomo Analytics', 'C9_Admin'); ?></span></legend>
			<label for="<?php echo $this->plugin_name; ?>-hide_matomo_settings">
				<input type="checkbox" id="<?php echo $this->plugin_name; ?>-hide_matomo_settings" name="<?php echo $this->plugin_name; ?>[hide_matomo_settings]" value="1" <?php checked($hide_matomo_settings, 1); ?> />
				<span><?php esc_attr_e('Hide Matomo Analytics settings', 'C9_Admin'); ?></span>
			</label>
		</fieldset>

		<fieldset>
			<legend class="screen-reader-text"><span><?php _e('Hide User settings', 'C9_Admin'); ?></span></legend>
			<label for="<?php echo $this->plugin_name; ?>-hide_user_settings">
				<input type="checkbox" id="<?php echo $this->plugin_name; ?>-hide_user_settings" name="<?php echo $this->plugin_name; ?>[hide_user_settings]" value="1" <?php checked($hide_user_settings, 1); ?> />
				<span><?php esc_attr_e('Hide User settings', 'C9_Admin'); ?></span>
			</label>
		</fieldset>

		<!-- add post,page or product slug class to body class -->
		<fieldset>
			<legend class="screen-reader-text"><span><?php _e('Make notification visible to admins only', 'C9_Admin'); ?></span></legend>
			<label for="<?php echo $this->plugin_name; ?>-admin_only_notifications">
				<input type="checkbox" id="<?php echo $this->plugin_name; ?>-admin_only_notifications" name="<?php echo $this->plugin_name; ?>[admin_only_notifications]" value="1" <?php checked($admin_only_notifications, 1); ?> />
				<span><?php esc_attr_e('Make notification visible to admins only', 'C9_Admin'); ?></span>
			</label>
		</fieldset>
		<!-- add post,page or product slug class to body class -->
		<fieldset>
			<legend class="screen-reader-text"><span><?php _e('Suppress Update Notifications', 'C9_Admin'); ?></span></legend>
			<label for="<?php echo $this->plugin_name; ?>-suppress_update_notice">
				<input type="checkbox" id="<?php echo $this->plugin_name; ?>-suppress_update_notice" name="<?php echo $this->plugin_name; ?>[suppress_update_notice]" value="1" <?php checked($suppress_update_notice, 1); ?> />
				<span><?php esc_attr_e('Suppress Update Notifications', 'C9_Admin'); ?></span>
			</label>
		</fieldset>
		<fieldset>
			<legend class="screen-reader-text"><span><?php _e('Hide Updates Menu Item', 'C9_Admin'); ?></span></legend>
			<label for="<?php echo $this->plugin_name; ?>-hide_update_menu_item">
				<input type="checkbox" id="<?php echo $this->plugin_name; ?>-hide_update_menu_item" name="<?php echo $this->plugin_name; ?>[hide_update_menu_item]" value="1" <?php checked($hide_update_menu_item, 1); ?> />
				<span><?php esc_attr_e('Hide Updates Menu Item', 'C9_Admin'); ?></span>
			</label>
		</fieldset>
		<fieldset>
			<legend class="screen-reader-text"><span><?php _e('Hide Plugin Menu Item', 'C9_Admin'); ?></span></legend>
			<label for="<?php echo $this->plugin_name; ?>-hide_plugin_menu_item">
				<input type="checkbox" id="<?php echo $this->plugin_name; ?>-hide_plugin_menu_item" name="<?php echo $this->plugin_name; ?>[hide_plugin_menu_item]" value="1" <?php checked($hide_plugin_menu_item, 1); ?> />
				<span><?php esc_attr_e('Hide Plugin Menu Item', 'C9_Admin'); ?></span>
			</label>
		</fieldset>
		<hr>
		<h3><?php esc_attr_e('Custom Admin Styles', 'C9_Admin'); ?></h3>
		<fieldset>
			<legend class="screen-reader-text"><span><?php _e('Custom Skin Admin', 'C9_Admin'); ?></span></legend>
			<label for="<?php echo $this->plugin_name; ?>-custom_skin">
				<input type="checkbox" id="<?php echo $this->plugin_name; ?>-custom_skin" name="<?php echo $this->plugin_name; ?>[custom_skin]" value="1" <?php checked($custom_skin, 1); ?> />
				<span><?php esc_attr_e('Disable Custom Skin for Admin', 'C9_Admin'); ?></span>
			</label>
		</fieldset>
		<br>
		<hr>
		<h3><?php esc_attr_e('Image Size Limit', 'C9_Admin'); ?></h3>
		<fieldset>
			<legend class="screen-reader-text"><span><?php _e('Limit Image Size', 'C9_Admin'); ?></span></legend>
			<label for="<?php echo $this->plugin_name; ?>-limit_image_size">
				<div><?php esc_attr_e('Limit Image Size', 'C9_Admin'); ?></div>
				<input type="checkbox" id="<?php echo $this->plugin_name; ?>-limit_image_size" name="<?php echo $this->plugin_name; ?>[limit_image_size]" value="1" <?php checked($limit_image_size, 1); ?> />
			</label>
			<fieldset>
				<legend class="screen-reader-text">
					<div><?php _e('Define your local Minimums and Maximums', 'C9_Admin'); ?></div>
				</legend>
				<label for="<?php echo $this->plugin_name; ?>-max_px">
					<div><?php esc_attr_e('Max Side Length', 'C9_Admin'); ?></div>
					<input type="text" class="small" id="<?php echo $this->plugin_name; ?>-max_px" name="<?php echo $this->plugin_name; ?>[max_px]" value=<?php
																																							echo '"';
																																							if (!empty($max_px)) {
																																								echo $max_px;
																																							}
																																							echo '"';
																																							?> />
					<span>px</span>
				</label>
				<br>
				<label for="<?php echo $this->plugin_name; ?>-min_px">
					<div><?php esc_attr_e('Min Side Length', 'C9_Admin'); ?></div>
					<input type="text" class="small" id="<?php echo $this->plugin_name; ?>-min_px" name="<?php echo $this->plugin_name; ?>[min_px]" value=<?php
																																							echo '"';
																																							if (!empty($min_px)) {
																																								echo $min_px;
																																							}
																																							echo '"';
																																							?> />
					<span>px</span>
				</label>
				<br>
				<label for="<?php echo $this->plugin_name; ?>-max_size">
					<div><?php esc_attr_e('Max File Size', 'C9_Admin'); ?></div>
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
		<hr>
		<h3><?php esc_attr_e('Custom Menu Labels', 'C9_Admin'); ?></h3>
		<fieldset>
			<legend class="screen-reader-text"><span><?php _e('Custom Menu Labels', 'C9_Admin'); ?></span></legend>
			<label for="<?php echo $this->plugin_name; ?>-define_custom_labels">
				<div><?php esc_attr_e('Define Custom Menu Labels', 'C9_Admin'); ?></div>
				<input type="checkbox" id="<?php echo $this->plugin_name; ?>-define_custom_labels" name="<?php echo $this->plugin_name; ?>[define_custom_labels]" value="1" <?php checked($define_custom_labels, 1); ?> />
			</label>
			<fieldset>
				<legend class="screen-reader-text">
					<div><?php _e('Menu Label', 'C9_Admin'); ?></div>
				</legend>
				<label for="<?php echo $this->plugin_name; ?>-custom_menu_label">
					<div><?php esc_attr_e('Menu Label', 'C9_Admin'); ?></div>
					<input type="text" class="small" id="<?php echo $this->plugin_name; ?>-custom_menu_label" name="<?php echo $this->plugin_name; ?>[custom_menu_label]" value=<?php
																																												echo '"';
																																												if (!empty($custom_menu_label)) {
																																													echo $custom_menu_label;
																																												}
																																												echo '"';
																																												?> />
				</label>
				<br>
				<legend class="screen-reader-text">
					<div><?php _e('Pages Label', 'C9_Admin'); ?></div>
				</legend>
				<label for="<?php echo $this->plugin_name; ?>-custom_pages_label">
					<div><?php esc_attr_e('Pages Label', 'C9_Admin'); ?></div>
					<input type="text" class="small" id="<?php echo $this->plugin_name; ?>-custom_pages_label" name="<?php echo $this->plugin_name; ?>[custom_pages_label]" value=<?php
																																													echo '"';
																																													if (!empty($custom_pages_label)) {
																																														echo $custom_pages_label;
																																													}
																																													echo '"';
																																													?> />
				</label>
				<br>
				<legend class="screen-reader-text">
					<div><?php _e('Media Label', 'C9_Admin'); ?></div>
				</legend>
				<label for="<?php echo $this->plugin_name; ?>-custom_media_label">
					<div><?php esc_attr_e('Media Label', 'C9_Admin'); ?></div>
					<input type="text" class="small" id="<?php echo $this->plugin_name; ?>-custom_media_label" name="<?php echo $this->plugin_name; ?>[custom_media_label]" value=<?php
																																													echo '"';
																																													if (!empty($custom_media_label)) {
																																														echo $custom_media_label;
																																													}
																																													echo '"';
																																													?> />
				</label>
				<br>
				<label for="<?php echo $this->plugin_name; ?>-custom_posts_label">
					<div><?php esc_attr_e('Posts Label', 'C9_Admin'); ?></div>
					<input type="text" class="small" id="<?php echo $this->plugin_name; ?>-custom_posts_label" name="<?php echo $this->plugin_name; ?>[custom_posts_label]" value=<?php
																																													echo '"';
																																													if (!empty($custom_posts_label)) {
																																														echo $custom_posts_label;
																																													}
																																													echo '"';
																																													?> />
				</label>
				<br>
				<label for="<?php echo $this->plugin_name; ?>-custom_post_categories_label">
					<div><?php esc_attr_e('Post Categories Label', 'C9_Admin'); ?></div>
					<input type="text" class="small" id="<?php echo $this->plugin_name; ?>-custom_post_categories_label" name="<?php echo $this->plugin_name; ?>[custom_post_categories_label]" value=<?php
																																																		echo '"';
																																																		if (!empty($custom_post_categories_label)) {
																																																			echo $custom_post_categories_label;
																																																		}
																																																		echo '"';
																																																		?> />
				</label>
				<br>
				<label for="<?php echo $this->plugin_name; ?>-custom_post_tags_label">
					<div><?php esc_attr_e('Posts Tags Label', 'C9_Admin'); ?></div>
					<input type="text" class="small" id="<?php echo $this->plugin_name; ?>-custom_post_tags_label" name="<?php echo $this->plugin_name; ?>[custom_post_tags_label]" value=<?php
																																															echo '"';
																																															if (!empty($custom_post_tags_label)) {
																																																echo $custom_post_tags_label;
																																															}
																																															echo '"';
																																															?> />
				</label>
				<br>
				<label for="<?php echo $this->plugin_name; ?>-custom_upload_files_label">
					<div><?php esc_attr_e('Upload Files Label', 'C9_Admin'); ?></div>
					<input type="text" class="small" id="<?php echo $this->plugin_name; ?>-custom_upload_files_label" name="<?php echo $this->plugin_name; ?>[custom_upload_files_label]" value=<?php
																																																echo '"';
																																																if (!empty($custom_upload_files_label)) {
																																																	echo $custom_upload_files_label;
																																																}
																																																echo '"';
																																																?> />
				</label>
				<br>
				<label for="<?php echo $this->plugin_name; ?>-custom_all_files_label">
					<div><?php esc_attr_e('All Files Label', 'C9_Admin'); ?></div>
					<input type="text" class="small" id="<?php echo $this->plugin_name; ?>-custom_all_files_label" name="<?php echo $this->plugin_name; ?>[custom_all_files_label]" value=<?php
																																															echo '"';
																																															if (!empty($custom_all_files_label)) {
																																																echo $custom_all_files_label;
																																															}
																																															echo '"';
																																															?> />
				</label>
			</fieldset>
		</fieldset>

		<?php submit_button('Save all changes', 'primary', 'submit', true); ?>

	</form>

</div>