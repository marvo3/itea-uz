<?php
/**
 * Plugin Name:       WP Custom Category Meta
 * Plugin URI:        http://musilda.cz/wp-custom-category-meta/
 * Description:       Add ability to add meta tags for category
 * Version:           1.1.0
 * Author:            Vladislav Musilek
 * Author URI:        http://musilda.cz        
 * Text Domain:       wp-ccm
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * 
 * @package   WP Custom Category Meta
 * @author    Vladislav Musilek
 * @license   GPL-2.0+
 * @copyright 2013 Vladislav Musilek
 *   
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once( plugin_dir_path( __FILE__ ) . 'class-ccm.php' );

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 */
register_activation_hook( __FILE__, array( 'WPCustomCategoryMeta', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'WPCustomCategoryMeta', 'deactivate' ) );

/*
 * TODO:
 *
 * - replace Plugin_Name with the name of the class defined in
 *   `class-plugin-name.php`
 * - replace Plugin_Name_Admin with the name of the class defined in
 *   `class-plugin-name-admin.php`
 */
add_action( 'plugins_loaded', array( 'WPCustomCategoryMeta', 'get_instance' ) );

?>