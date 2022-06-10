<?php
/*
   Plugin Name: Painting HTML
   Plugin URI: http://wordpress.org/extend/plugins/painting-html/
   Version: 0.1
   Author: Painting
   Description: Painting HTML
   Text Domain: painting-html
   License: GPLv3
  */

$PaintingHtml_minimalRequiredPhpVersion = '7.3';

/**
 * Check the PHP version and give a useful error message if the user's version is less than the required version
 * @return boolean true if version check passed. If false, triggers an error which WP will handle, by displaying
 * an error message on the Admin page
 */
function PaintingHtml_noticePhpVersionWrong() {
    global $PaintingHtml_minimalRequiredPhpVersion;
    echo '<div class="updated fade">' .
      __('Error: plugin "Painting HTML" requires a newer version of PHP to be running.',  'painting-html').
            '<br/>' . __('Minimal version of PHP required: ', 'painting-html') . '<strong>' . $PaintingHtml_minimalRequiredPhpVersion . '</strong>' .
            '<br/>' . __('Your server\'s PHP version: ', 'painting-html') . '<strong>' . phpversion() . '</strong>' .
         '</div>';
}


function PaintingHtml_PhpVersionCheck() {
    global $PaintingHtml_minimalRequiredPhpVersion;
    if (version_compare(phpversion(), $PaintingHtml_minimalRequiredPhpVersion) < 0) {
        add_action('admin_notices', 'PaintingHtml_noticePhpVersionWrong');
        return false;
    }
    return true;
}


/**
 * Initialize internationalization (i18n) for this plugin.
 * References:
 *      http://codex.wordpress.org/I18n_for_WordPress_Developers
 *      http://www.wdmac.com/how-to-create-a-po-language-translation#more-631
 * @return void
 */
function PaintingHtml_i18n_init() {
    $pluginDir = dirname(plugin_basename(__FILE__));
    load_plugin_textdomain('painting-html', false, $pluginDir . '/languages/');
}


//////////////////////////////////
// Run initialization
/////////////////////////////////

// Initialize i18n
add_action('plugins_loadedi','PaintingHtml_i18n_init');

// Run the version check.
// If it is successful, continue with initialization for this plugin
if (PaintingHtml_PhpVersionCheck()) {
    // Only load and run the init function if we know PHP version can parse it
    include_once('painting-html_init.php');
    PaintingHtml_init(__FILE__);
}
