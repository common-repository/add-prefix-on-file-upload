<?php
/**
 * Plugin Name: Add Prefix on File Upload
 * Plugin URI: https://www.drfdesigner.com/plugins/add-prefix-file-upload/
 * Description: This plugin allows you to automatically add a custom prefix after uploading a file.
 * Version: 0.5
 * Author: DRF Designer
 * Author URI: https://www.drfdesigner.com/
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: add-prefix-file-upload
  */

/**
 * Adds the custom options page to the WordPress Settings menu.
 */
function add_prefix_file_upload_add_options_page() {
  add_options_page(
    'Add Prefix on File Upload', // Page Title
    'Add Prefix on File Upload', // Menu Title
    'manage_options', // Capability
    'add-prefix-file-upload', // Menu Slug
    'add_prefix_file_upload_options_page' // Callback Function
  );
}
add_action('admin_menu', 'add_prefix_file_upload_add_options_page');

/**
 * Displays the custom options page content.
 */
function add_prefix_file_upload_options_page() {
  if (!current_user_can('manage_options')) {
    wp_die('You do not have sufficient permissions to access this page.');
  }
  
  $prefix = esc_html(get_option('new_file_name_prefix'));
  
  echo '<div class="wrap">';
  echo '<h1>Add Prefix on File Upload</h1>';
  
  echo '<p><a href="https://www.drfdesigner.com/plugins/" target="_blank">Visit our website to see our plugins</a></p>';

  echo '<form action="options.php" method="post">';
  settings_fields('add-prefix-file-upload-settings-group');
  do_settings_sections('add-prefix-file-upload');
  
  // Table for the form fields
  echo '<table class="form-table">';

  // Table row for the file prefix field
  echo '<tr valign="top">';
  echo '<th scope="row">New File Name Prefix</th>';
  echo '<td><input type="text" name="new_file_name_prefix" value="' .esc_html(get_option('new_file_name_prefix')) . '" placeholder="Custom prefix" /></td>';
  echo '</tr>';
  
  // End of the table
  echo '</table>';
  
  // Submit button
  submit_button();
  
  // End of the form
  echo '</form>';
  echo '</div>';
}

/**
 * Registers the settings used by the plugin.
 */
function add_prefix_file_upload_register_settings() {
  register_setting(
    'add-prefix-file-upload-settings-group', // Option Group
    'new_file_name_prefix', // Option Name
    'sanitize_text_field' // Sanitize user input
  );
}
add_action('admin_init', 'add_prefix_file_upload_register_settings');

/**
 * Renames the uploaded file using the custom prefix.
 */
function add_prefix_file_upload_rename_uploaded_file($file) {
  $prefix = get_option('new_file_name_prefix');
  $prefix = sanitize_text_field(get_option('new_file_name_prefix'));
  $file['name'] = $prefix . '-' . $file['name'];
  return $file;
}
add_filter('wp_handle_upload_prefilter', 'add_prefix_file_upload_rename_uploaded_file');

?>