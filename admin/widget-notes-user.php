<?php

//makes the user notes dashboard widget

if (!defined('ABSPATH')) exit;

function dashboard_widgets_suite_notes_user() {
	
	echo dashboard_widgets_suite_notes_user_content();
	
}

function dashboard_widgets_suite_notes_user_content() {
	
	$data = get_option('dws_notes_user_data') ? get_option('dws_notes_user_data') : array();
	
	$data = apply_filters('dashboard_widgets_suite_notes_user_data_form', array_reverse($data));
	
	do_action('dashboard_widgets_suite_notes_user', $data);
	
	return dashboard_widgets_suite_notes_user_form($data);
	
}

function dashboard_widgets_suite_notes_user_message() {
	
	global $dws_options_notes_user;
	
	$message = isset($dws_options_notes_user['widget_notes_message']) ? $dws_options_notes_user['widget_notes_message'] : '';
	
	$message = apply_filters('dashboard_widgets_suite_notes_user_message', $message);
	
	return $message;
}

function dashboard_widgets_suite_notes_user_example() {
	
	list($date, $time) = dashboard_widgets_suite_get_date();
	
	$example = array(
		array(
			'date'  => $date, 
			'id'    => 1,
			'name'  => esc_html__('Pat Smith', 'dashboard-widgets-suite'), 
			'note'  => esc_html__('Make sure you do something..', 'dashboard-widgets-suite'), 
			'role'  => 'all', 
			'time'  => $time, 
			'title' => esc_html__('Example Note', 'dashboard-widgets-suite'),
		)
	);
	
	$example = apply_filters('dashboard_widgets_suite_notes_user_example', $example);
	
	return $example;
	
}

function dashboard_widgets_suite_notes_user_style() {
	
	global $dws_options_notes_user;
	
	$height = isset($dws_options_notes_user['widget_notes_height']) ? intval($dws_options_notes_user['widget_notes_height']) : 0;
		
	if ($height > 0) $style = 'style="height:'. $height .'px;"';
	else             $style = 'style="min-height:77px;"';
	
	$style = apply_filters('dashboard_widgets_suite_notes_user_style', $style);
	
	return $style;
	
}

function dashboard_widgets_suite_notes_user_form($data) {
	
	global $dws_options_notes_user;
	
	$count    = isset($dws_options_notes_user['widget_notes_count'])    ? $dws_options_notes_user['widget_notes_count']    : 0;
	$edit     = isset($dws_options_notes_user['widget_notes_edit'])     ? $dws_options_notes_user['widget_notes_edit']     : null;
	$username = isset($dws_options_notes_user['widget_notes_username']) ? $dws_options_notes_user['widget_notes_username'] : false;
	
	$notes = count($data);
	
	$i = 0;
	
	$return = '<div id="dws-notes-user" class="dws-dashboard-widget">';
	
	foreach ($data as $key => $value) {
		
		if ($i === $count) break;
		
		$id     = isset($value['id'])     ? intval($value['id']) : '';
		$date   = isset($value['date'])   ? sanitize_text_field($value['date']) : '';
		$name   = isset($value['name'])   ? sanitize_text_field($value['name']) : '';
		$role   = isset($value['role'])   ? sanitize_text_field($value['role']) : '';
		$time   = isset($value['time'])   ? sanitize_text_field($value['time']) : '';
		$title  = isset($value['title'])  ? sanitize_text_field(stripslashes_deep($value['title'])) : '';
		$format = isset($value['format']) ? sanitize_text_field($value['format']) : 'text';
		$note   = isset($value['note'])   ? stripslashes_deep($value['note']) : '';
		
		if (dashboard_widgets_suite_check_role($edit)) {
			
			$return .= dashboard_widgets_suite_notes_user_form_edit($id, $date, $name, $role, $time, $title, $format, $note, $key);
			
			$i++;
			
		} elseif (dashboard_widgets_suite_check_role($role)) {
			
			$return .= dashboard_widgets_suite_notes_user_form_view($id, $date, $name, $time, $title, $format, $note);
			
			$i++;
			
		}
		
	}
	
	if ($i === 0) {
		
		$return .= '<div class="dws-notes-user-default">';
		
		if ($count === 0 && $notes > 0) {
			
			$return .= esc_html__('To view your notes, adjust the setting &ldquo;Number of Notes&rdquo;.', 'dashboard-widgets-suite');
			
		} else {
			
			$return .= dashboard_widgets_suite_notes_user_message();
			
		}
		
		$return .= '</div>';
		
	}
	
	if (dashboard_widgets_suite_check_role($edit)) {
		
		$display_name = false;
		
		if ($username) {
			
			$current_user = wp_get_current_user();
			
			$display_name = $current_user->display_name;
			
		}
		
		$return .= '<div class="dws-notes-user-button-w">';
		$return .= '<span class="fa fa-plus-circle"></span> <a href="#dws-notes-user-add">'. esc_html__('Add Note', 'dashboard-widgets-suite') .'</a>';
		$return .= ($i > 0) ? '<span class="dws-notes-caption">'. esc_html__('Double-click any note to edit', 'dashboard-widgets-suite') .'</span>' : '';
		$return .= '</div>';
		
		$return .= dashboard_widgets_suite_notes_user_form_add($display_name);
		
	}
	
	$return .= '</div>';
	
	return $return;
	
}

function dashboard_widgets_suite_notes_user_form_edit($id, $date, $name, $role, $time, $title, $format, $note, $key) { 
	
	$user_role = ($role === 'all') ? esc_attr__('Any', 'dashboard-widgets-suite') : ucfirst($role);
	
	$form  = '<div class="dws-notes-user dws-notes-user-format-'. esc_attr($format) .'">';
	$form .= '<form method="post" action="">';
	
	$form .= '<div class="dws-notes-user-meta">';
	$form .= '<span class="fa fa-pad fa-file-text"></span> ';
	$form .= '<strong class="dws-info" title="'. esc_attr__('Note ID: ', 'dashboard-widgets-suite') . $id . esc_attr__(', User Role: ', 'dashboard-widgets-suite') . $user_role .'">'. $title .'</strong> ';
	$form .= '<em>'. esc_html__(' by ', 'dashboard-widgets-suite') . $name .', <span class="dws-info" title="'. $time .'">'. $date .'</span></em>';
	$form .= '</div>';
	
	$form .= '<label for="note">'. esc_html__('Note', 'dashboard-widgets-suite') .'</label>';
	$form .= '<textarea name="dws-notes-user[note]"'. dashboard_widgets_suite_notes_user_style() .' data-key="'. intval($key + 1) .'" data-rows="3" rows="3" cols="50" ';
	$form .= 'class="dws-hidden" placeholder="'. esc_attr__('Enter some notes..', 'dashboard-widgets-suite') .'">'. $note .'</textarea>';
	$form .= '<div '. dashboard_widgets_suite_notes_user_style() .' class="dws-notes-user-note" data-key="'. intval($key + 1) .'"></div>';
	
	$form .= '<div class="dws-notes-user-buttons dws-hidden">';
	$form .= '<input class="button button-secondary" type="submit" name="dws-notes-user[edit]" value="'. esc_attr__('Save Changes', 'dashboard-widgets-suite') .'">';
	$form .= '<input class="button button-secondary" type="submit" name="dws-notes-user[delete]" value="'. esc_attr__('Delete Note', 'dashboard-widgets-suite') .'">';
	$form .= '<input class="button button-secondary" type="submit" name="dws-notes-user[cancel]" value="'. esc_attr__('Cancel', 'dashboard-widgets-suite') .'" data-key="'. intval($key + 1) .'">';
	$form .= '</div>';
	
	$form .= '<input type="hidden" name="dws-notes-user[id]" value="'. $id .'">';
	$form .= '<input type="hidden" name="dws-notes-user[name]" value="'. $name .'">';
	$form .= '<input type="hidden" name="dws-notes-user[title]" value="'. $title .'">';
	
	$form .= wp_nonce_field('dws-notes-user-nonce', 'dws-notes-user[nonce]', false, false);
	
	$form .= '</form>';
	$form .= '</div>';
	
	return $form;
	
}

function dashboard_widgets_suite_notes_user_form_view($id, $date, $name, $time, $title, $format, $note) { 
	
	$form  = '<div class="dws-notes-user dws-notes-user-format-'. esc_attr($format) .'">';
	
	$form .= '<div class="dws-notes-user-meta">';
	$form .= '<span class="fa fa-pad fa-file-text-o"></span> ';
	$form .= '<strong class="dws-info" title="'. esc_attr__('Note ID: ', 'dashboard-widgets-suite') . $id .'">'. $title .'</strong> ';
	$form .= '<em>'. esc_html__(' &ndash; ', 'dashboard-widgets-suite') . $name .', <span class="dws-info" title="'. $time .'">'. $date .'</span></em>';
	$form .= '</div>';
	
	$form .= '<div '. dashboard_widgets_suite_notes_user_style() .' class="dws-notes-user-note">'. $note .'</div>';
	
	$form .= '</div>';
	
	return $form;
	
}

function dashboard_widgets_suite_notes_user_form_add($display_name) {
	
	if ($display_name) {
		
		$name_field = '<input name="dws-notes-user[name]" type="hidden" value="'. $display_name .'">';
		
	} else {
		
		$name_field  = '<label for="dws-notes-user[name]">'. esc_html__('Name', 'dashboard-widgets-suite') .'</label>';
		$name_field .= '<input name="dws-notes-user[name]" type="text" size="40" value="" placeholder="'. esc_attr__('Name', 'dashboard-widgets-suite') .'">';
		
	}
	
	$form  = '<div id="dws-notes-user-add" class="dws-notes-user dws-hidden">';
	$form .= '<form method="post" action="">';
	
	$form .= '<div class="dws-notes-user-meta">';
	$form .= '<label for="dws-notes-user[title]">'. esc_html__('Title', 'dashboard-widgets-suite') .'</label>';
	$form .= '<input name="dws-notes-user[title]" type="text" size="40" value="" placeholder="'. esc_attr__('Title', 'dashboard-widgets-suite') .'" autofocus="autofocus">';
	
	$form .= $name_field;
	$form .= '</div>';
	
	$form .= '<label for="dws-notes-user[note]">'. esc_html__('Note', 'dashboard-widgets-suite') .'</label>';
	$form .= '<textarea name="dws-notes-user[note]" data-key="0" data-rows="3" rows="3" cols="50" placeholder="'. esc_attr__('Enter some notes..', 'dashboard-widgets-suite') .'"></textarea>';
	
	$form .= '<div class="dws-notes-user-options">';
	$form .= dashboard_widgets_suite_notes_user_roles();
	$form .= dashboard_widgets_suite_notes_format();
	$form .= '</div>';
	
	$form .= '<div class="dws-notes-user-buttons">';
	$form .= '<input class="button button-secondary" type="submit" name="dws-notes-user[add]" value="'. esc_attr__('Add Note', 'dashboard-widgets-suite') .'">';
	$form .= '</div>';
	
	$form .= wp_nonce_field('dws-notes-user-nonce', 'dws-notes-user[nonce]', false, false);
	
	$form .= '</form>';
	$form .= '</div>';
	
	return $form;
	
}

function dashboard_widgets_suite_notes_user_roles() {
	
	global $dws_options_notes_user;
	
	$default_role = isset($dws_options_notes_user['widget_notes_view']) ? $dws_options_notes_user['widget_notes_view'] : 'all';
	
	$roles = dashboard_widgets_suite_user_roles();
	
	$field  = '<select name="dws-notes-user[role]">';
	$field .= '<option value="'. $default_role .'">'. esc_html__('Role required to view this note..', 'dashboard-widgets-suite') .'</option>';
	
	foreach ($roles as $key => $value) {
		
		$text = ucfirst($key);
		
		if ($key === 'all') $text = esc_html__('Any Role', 'dashboard-widgets-suite');
		
		$field .= '<option value="'. $key .'">'. $text .'</option>';
		
	}
	
	$field .= '</select> <label for="dws-notes-user[role]">'. esc_html__('Role', 'dashboard-widgets-suite') .'</label>';
	
	return $field;
	
}

function dashboard_widgets_suite_notes_format() {
	
	$field  = '<select name="dws-notes-user[format]">';
	$field .= '<option value="text">'. esc_html__('Format..',       'dashboard-widgets-suite') .'</option>';
	$field .= '<option value="text">'. esc_html__('Text (default)', 'dashboard-widgets-suite') .'</option>';
	$field .= '<option value="html">'. esc_html__('HTML',           'dashboard-widgets-suite') .'</option>';
	$field .= '<option value="code">'. esc_html__('Code',           'dashboard-widgets-suite') .'</option>';
	$field .= '</select> <label for="dws-notes-user[format]">'. esc_html__('Format', 'dashboard-widgets-suite') .'</label>';
	
	return $field;
	
}
