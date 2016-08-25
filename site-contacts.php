<?php
/*
	Plugin Name:	Site Contacts
	Plugin URI:		https://github.com/Hedgehog333/site-contacts
	Description:	It allows you to save contact details in the variables and use them on the website.
	Version:		0.1.0
	Author:			Hedgehog333
	Author URI:		https://github.com/Hedgehog333
	Text Domain:	site-contacts
	License:		GPL-2.0+
	License URI:	https://www.gnu.org/licenses/gpl-2.0.txt
	Domain Path:	/languages
*/

if(!defined('WPINC'))
{
	die;
}

namespace Hedgehog333;

$path = plugin_dir_path( __FILE__ );
require_once $path . 'includes/site-contacts-manager.php';
require_once $path . 'admin/site-contacts-manager-admin.php';
require_once $path . 'db/site-contacts-manager-db.php';
require_once $path . 'db/site-contacts-initial-db.php';

global $scm;
$scm = new Site_Contacts_Manager();
global $scma;
$scma = new Site_Contacts_Manager_Admin($scm->get_version());
global $wpdb;
global $dbinit;
$dbinit = new Site_Contacts_Initial_DB($wpdb, 'hgh_site_contacts', '1.0');
global $db;
$db = new Site_Contacts_Manager_DB($dbinit->get_wpdb(), $dbinit->get_table_name());

function run_site_contacts_manager()
{
	global $scm;
	$scm->run();
	add_options_page('Контакты', 'Site Contacts', 8, basename(__FILE__), 'hgh_add_to_menu');

	if(isset($_POST['hgh_site_contact_map']))
		update_option('hgh_site_contact_map', $_POST['hgh_site_contact_map']);
}

function hgh_add_to_menu()
{
	global $wpdb;
	global $scma;
	$scma->render($wpdb);
}
add_action('admin_menu', 'run_site_contacts_manager');

function hgh_site_contact_install ()
{
	add_option('hgh_site_contact_map', '');
	global $dbinit;
	add_option("hgh_site_contact_db_version", $dbinit->get_version());
	$dbinit->create_table();
}
register_activation_hook(__FILE__,'hgh_site_contact_install');

function hgh_site_contact_uninstall ()
{
	delete_option('hgh_site_contact_db_version');
	delete_option('hgh_site_contact_map');
	global $dbinit;
	$dbinit->drop_table();
}
register_deactivation_hook( __FILE__, 'hgh_site_contact_uninstall');

function hgh_shortcode_sitecontact($atts) {
	global $db;
	return $db->select($atts['code'])[0]->value;
}
add_shortcode( 'sitecontact', 'hgh_shortcode_sitecontact' );

function hgh_insert_callback() {
	global $db;
	$result = $db->insert(array( 
		'code' => $_POST['code'], 
		'title' => $_POST['title'], 
		'value' => $_POST['value'] 
	));
	
	echo json_encode( $result );
	wp_die();
}
add_action( 'wp_ajax_hgh_insert_contact', 'hgh_insert_callback' );

function hgh_delete_callback() {
	global $db;
	$db->delete($_POST['id']);
	wp_die();
}
add_action( 'wp_ajax_hgh_delete_contact', 'hgh_delete_callback' );

function hgh_update_callback() {
	global $db;
	$result = $db->update($_POST['id'], array( 
		'code' => $_POST['code'], 
		'title' => $_POST['title'], 
		'value' => $_POST['value'] 
	));
	
	echo json_encode( $result );
	wp_die();
}
add_action( 'wp_ajax_hgh_update_contact', 'hgh_update_callback' );