<?php
/**
 * Plugin Name: RV Super Editors
 * Plugin URI: http://www.romavirtuale.com/new/wordpress-plugin-rv-super-editors/
 * Description: This plugin will add an advanced role "RV Super Editors" to the pre-defined roles of Wordpress. The "RV Super Editors" users will have the capability to customize your site Appearance (Widgets, Menus, all option in Customize, Background and Header). 
 * Version: 1.1.0
 * Author: Roma Virtuale S.r.L.
 * Author URI: http://www.romavirtuale.com
 * License: GPLv2
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: rvsupereditors_lang
 * 
 */
 
/*
 * erreV (Wordpress Plugin)
 * Copyright (C) 2014 Roma Virtuale S.r.L.
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *  
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *  
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
**/

if ( ! defined('ABSPATH') ) {
	die( _e('Please do not load this file directly!', 'rvsupereditors_lang') );
}

// if the Dashboard or the Administration Panel is attempting to be displayed
if ( is_admin() ){ // admin actions
	add_action('admin_menu', 'rvsupereditors_menu');
}

// Create menu
function rvsupereditors_menu() {
	// add_options_page( $page_title, $menu_title, $capability, $menu_slug, $function);
	add_options_page( __('RV Super Editors Options', 'rvsupereditors_lang'), __('RV Super Editors', 'rvsupereditors_lang'), 'activate_plugins', 'rvsupereditors', 'rvsupereditors_options_page');
}

// Initialise the internationalisation
add_action('init', 'rvsupereditors_plugin_lang_start');
function rvsupereditors_plugin_lang_start() {
  load_plugin_textdomain( 'rvsupereditors_lang', false, dirname( plugin_basename(__FILE__) ) . '/languages/');
  // remember! 
  // The .mo file should be named based on the domain ('rvsupereditors_lang') followed by a dash (-), and then the locale exactly (it_IT) 
  // -> 'rvsupereditors_lang'-it_IT.mo
  // __("text", mytextdomain) -> pass text to var
  // _e("text", mytextdomain) -> echo text
}


/****** MAIN FUNCTION _____ BEGIN _____ role -> 'rv_super_editor', 'RV Super Editors' ******/

// ADD RV Super Editors Rule!!! 
function rvsupereditors_activate() {
    // Create RV Super Editors Role! NO OUTPUT IN THIS FUNCTION!
	$rv_the_roles = new WP_Roles(); // get roles
	$rv_copy_from = 'editor';
	$rv_extra_caps = array(
		//'moderate_comments' => 1
		'edit_theme_options' => 1
		// * Each capability you want to grant the new role should follow the same format as above
		// * For Sample Role Object and Capability see at the end of the script
		// * Key = Capability
		// * Value = 1 (grant, 0 would be deny but we don't typically add non-granted caps to roles)
	);

	if ( current_user_can('activate_plugins') ){ // perform add only if admin (checking if user can activate plugin -> action reserved to admin !)
		$rv_caps_for_role = array_merge( $rv_extra_caps, (array)$rv_the_roles->roles[$rv_copy_from]['capabilities'] );
		add_role('rv_super_editor', 'RV Super Editors', $rv_caps_for_role );
	}
	
	register_activation_hook( __FILE__, 'rvsupereditors_activate' );
}
register_activation_hook( __FILE__, 'rvsupereditors_activate' ); // Run code to create RV Super Editors on Activation!

// REMOVE RV Super Editors Role!!! 
function rvsupereditors_deactivate() {
	// Run Code to Delete RV Super Editors Role! NO OUTPUT IN THIS FUNCTION!
	if ( current_user_can('activate_plugins') ){ // perform add only if admin (checking if user can activate plugin -> action reserved to admin !)
		$rv_the_roles = new WP_Roles();
		remove_role( 'rv_super_editor', 'RV Super Editors' ); // ie remove_role( $role );
	}
	
	register_activation_hook( __FILE__, 'rvsupereditors_deactivate' );

}

/****** MAIN FUNCTION _____ END _____ ******/
?>
<?php 
if (isset($_POST['rv-se-action'])) {

	if($_POST['rv-se-action'] == 'rv_se_delete') { 
		//_e('RV Super Editors Message: "RV Super Editors" role succesfully <strong>deleted</strong>', 'rvsupereditors_lang');
		add_action( 'plugins_loaded', 'rvsupereditors_deactivate' );
	} else if($_POST['rv-se-action'] == 'rv_se_add') { 
		//_e('RV Super Editors Message: "RV Super Editors" role succesfully <strong>added</strong>', 'rvsupereditors_lang');
		add_action( 'plugins_loaded', 'rvsupereditors_activate' );
	/*} else {
		echo ' Nothing :-D ';*/
	}

}
?>
<?php
// Function to create content for the Option Page
function rvsupereditors_options_page() {

	if ( current_user_can( 'activate_plugins' ) ){

	?>
	<div class="wrap">
		<h2>
		<?php _e('RV Super Editors Options', 'rvsupereditors_lang'); ?><br />
		</h2>
		<?php if (isset($_POST['rv-se-action'])) { ?>
		<div id="rv-se-action" style="margin: 10px; padding: 10px; background-color:#CCCCCC;">
			<?php 
				if($_POST['rv-se-action'] == 'rv_se_delete') { 
					_e('RV Super Editors Message: "RV Super Editors" role succesfully <strong>deleted</strong>', 'rvsupereditors_lang');
				} else if ($_POST['rv-se-action'] == 'rv_se_add') { 
					_e('RV Super Editors Message: "RV Super Editors" role succesfully <strong>added</strong>', 'rvsupereditors_lang');
				}
			?>
		</div>
		<?php } ?>
		<div id="rv-se-description">
			<?php _e('Hi! Thanks for using "RV Super Editors" Plugin. If you like or you think this plugin is usefull please...', 'rvsupereditors_lang'); ?>
			<ul style="margin-left: 10px;">
				<li><a href="http://www.romavirtuale.com" title="Roma Virtuale" target="_blank"><?php _e('Visit our site', 'rvsupereditors_lang'); ?></a></li>
				<li><a href="https://www.facebook.com/pages/Roma-Virtuale-Srl/106656872849653" title="Roma Virtuale on Facebook" target="_blank"><?php _e('Like our Facebook Page', 'rvsupereditors_lang'); ?></a></li>
				<li><a href="https://twitter.com/romavirtuale" title="Roma Virtuale on Twitter" target="_blank"><?php _e('Follow us on Twitter', 'rvsupereditors_lang'); ?></a></li>
			</ul>
			<?php _e('Thanks :)', 'rvsupereditors_lang'); ?>
		</div>
		<hr />
		<div id="rv-se-roles">
			<?php
			//$rv_the_roles = new WP_Roles(); // get roles
			// get_role -----> Returns a WP_Role object on success, null on failure. 
			if (get_role( 'rv_super_editor' )) { // if the role exist thanks for using template
			// ROLE EXIST so go on
			?>
			<p style="color: #006a41;"><strong><?php _e('All done: the "RV Super Editors" role exists! :-)', 'rvsupereditors_lang'); ?></strong></p>
			<?php _e('Assigning the role ', 'rvsupereditors_lang'); ?><strong><?php _e('RV Super Editors', 'rvsupereditors_lang'); ?></strong> <?php _e(' to an user means that this user will be capable to do all pre-defined action for Editors, plus customize your site Appearance:', 'rvsupereditors_lang'); ?>
					<ul style="list-style:disc; margin-left: 10px;">
						<li><?php _e('Customize the theme (if supported by current theme)', 'rvsupereditors_lang'); ?></li>
						<li><?php _e('Edit Widgets', 'rvsupereditors_lang'); ?></li>
						<li><?php _e('Edit Menus', 'rvsupereditors_lang'); ?></li>
						<li><?php _e('Edit Background', 'rvsupereditors_lang'); ?></li>
						<li><?php _e('Edit Header', 'rvsupereditors_lang'); ?></li>
					</ul>
			<p><strong><?php _e('Don\'t change role of Admins Users!<strong> To test the new "RV Super Editors" role use a New User ;-)', 'rvsupereditors_lang'); ?></strong></p>
			<div id="rv-se-action" style="padding: 10px; background-color:#CCCCCC;">
			<form method="post" action="<?php $_SERVER['PHP_SELF']; ?>">
			<input type="hidden" name="rv-se-action" value="rv_se_delete">
				<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e('Delete RV Super Editors Role', 'rvsupereditors_lang'); ?>" />
				</p>
			</form>
			</div>
			<br /><br /><br /><br /> <?php _e('Sorry for our bad English ... we Italians! :-D', 'rvsupereditors_lang'); ?>
			<?php } else {
			  // ROLE DOESN'T EXIST so STOP
			?>
			<p style="color: #FF0000; "><strong><?php _e('RV Super Editors', 'rvsupereditors_lang'); ?></strong> <?php _e('role doesn\'t exist. Do you want to create it?', 'rvsupereditors_lang'); ?></p>
				<div id="rv-se-action" style="padding: 10px; background-color:#CCCCCC;">
				<form method="post" action="<?php $_SERVER['PHP_SELF']; ?>">
				<input type="hidden" name="rv-se-action" value="rv_se_add">
					<p class="submit">
					<input type="submit" class="button-primary" value="<?php _e('Create RV Super Editors Role', 'rvsupereditors_lang'); ?>" />
					</p>
				</form>
				</div>
			<?php
	
			}
			?>
		</div>
	</div>
	<?php
	}
}
?>
<?php
/*
 * Sample Role Object
 * You can find out more information on 
 * https://codex.wordpress.org/Roles_and_Capabilities
 *

WP_Role Object
(
    [name] => administrator
    [capabilities] => Array
        (
            [switch_themes] => 1
            [edit_themes] => 1
            [activate_plugins] => 1
            [edit_plugins] => 1
            [edit_users] => 1
            [edit_files] => 1
            [manage_options] => 1
            [moderate_comments] => 1
            [manage_categories] => 1
            [manage_links] => 1
            [upload_files] => 1
            [import] => 1
            [unfiltered_html] => 1
            [edit_posts] => 1
            [edit_others_posts] => 1
            [edit_published_posts] => 1
            [publish_posts] => 1
            [edit_pages] => 1
            [read] => 1
            [level_10] => 1
            [level_9] => 1
            [level_8] => 1
            [level_7] => 1
            [level_6] => 1
            [level_5] => 1
            [level_4] => 1
            [level_3] => 1
            [level_2] => 1
            [level_1] => 1
            [level_0] => 1
            [edit_others_pages] => 1
            [edit_published_pages] => 1
            [publish_pages] => 1
            [delete_pages] => 1
            [delete_others_pages] => 1
            [delete_published_pages] => 1
            [delete_posts] => 1
            [delete_others_posts] => 1
            [delete_published_posts] => 1
            [delete_private_posts] => 1
            [edit_private_posts] => 1
            [read_private_posts] => 1
            [delete_private_pages] => 1
            [edit_private_pages] => 1
            [read_private_pages] => 1
            [delete_users] => 1
            [create_users] => 1
            [unfiltered_upload] => 1
            [edit_dashboard] => 1
            [update_plugins] => 1
            [delete_plugins] => 1
            [install_plugins] => 1
            [update_themes] => 1
            [install_themes] => 1
            [update_core] => 1
            [list_users] => 1
            [remove_users] => 1
            [add_users] => 1
            [promote_users] => 1
            [edit_theme_options] => 1
            [delete_themes] => 1
            [export] => 1
            [edit_comment] => 1
            [approve_comment] => 1
            [unapprove_comment] => 1
            [reply_comment] => 1
            [quick_edit_comment] => 1
            [spam_comment] => 1
            [unspam_comment] => 1
            [trash_comment] => 1
            [untrash_comment] => 1
            [delete_comment] => 1
            [edit_permalink] => 1
        )
)


*
* Sample Role Object END
*
*/
?>