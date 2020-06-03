<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/RELATO/rltves-wc-chamilo.git
 * @since             1.0.1
 * @package           Rltves_Wc_Chamilo
 *
 * @wordpress-plugin
 * Plugin Name:       WooCommerce Plugin for integration of Chamilo LMS
 * Plugin URI:        https://github.com/RELATO/rltves-wc-chamilo.git
 * Description:       This is a woocommerce plugin for sendind data to chamilo lms
 * Version:           1.0.1
 * Author:            relatives
 * Author URI:        https://relato.com.br
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       rltves_wc_chamilo
 * Domain Path:       /languages
 * 
 * 
 *  * References: 
 * https://medium.com/@paulmiller3000/how-to-extend-woocommerce-with-the-wordpress-plugin-boilerplate-adac178b5a9b
 * https://codex.wordpress.org/Creating_Tables_with_Plugins
 * https://businessbloomer.com/woocommerce-easily-get-product-info-title-sku-desc-product-object/
 * https://github.com/tarikul47/WordPress-Databse-Crud-Plugin-/blob/master/wpdeb-demo.php
 * https://github.com/KamranSyed/Wordpress-Plugin-Template-OOP
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'RLTVES_WC_CHAMILO_VERSION', '1.0.1' );


if (!function_exists('is_plugin_active')) {
    include_once(ABSPATH . '/wp-admin/includes/plugin.php');
}

/**
* Check for the existence of WooCommerce and any other requirements
*/
function rltves_check_requirements() {
    if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
        return true;
    } else {
        add_action( 'admin_notices', 'rltves_missing_wc_notice' );
        return false;
    }
}

/**
* Display a message advising WooCommerce is required
*/
function rltves_missing_wc_notice() { 
    $class = 'notice notice-error';
    $message = __( 'rltves-wc-chamilo requires WooCommerce to be installed.', 'rltves-wc-chamilo' );
 
    printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) ); 
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-rltves-wc-chamilo-activator.php
 */
function activate_rltves_wc_chamilo() {

	/*
	require_rltves_db(); // check db connection
	$rltvesdb = $GLOBALS["rltvesdb"];

	$charset_collate = $rltvesdb->get_charset_collate();

	$sql = "CREATE TABLE `tc_notes` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`info` text,
		`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
		PRIMARY KEY (`id`)
	) $charset_collate;";

	$query = $rltvesdb->prepare( "DESCRIBE `tc_notes`");
	$results = $rltvesdb->get_results($query);
	if ( !isset($results) ) {
		$results = $rltvesdb->query( $sql );
		// $sqlcmd = $rltvesdb->query( $sql );
		// $rltvesdb->get_results($sqlcmd);
	}
	*/

	require_once plugin_dir_path( __FILE__ ) . 'includes/class-rltves-wc-chamilo-activator.php';
	Rltves_Wc_Chamilo_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-rltves-wc-chamilo-deactivator.php
 */
function deactivate_rltves_wc_chamilo() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-rltves-wc-chamilo-deactivator.php';
	Rltves_Wc_Chamilo_Deactivator::deactivate();
}

add_action( 'plugins_loaded', 'rltves_check_requirements' );

register_activation_hook( __FILE__, 'activate_rltves_wc_chamilo' );
register_deactivation_hook( __FILE__, 'deactivate_rltves_wc_chamilo' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-rltves-wc-chamilo.php';

// using the init hook to easy test something 

/**
 * Fire on the initialization of the admin screen or scripts.
 */
add_action( 'admin_init', 'rltves_admin_init_do_something');
function rltves_admin_init_do_something() {

	$chamilo_databasename = get_option( 'rltves-wc-chamilo_databasename', true );
	if ( ! isset($chamilo_databasename) or empty($chamilo_databasename)) :
		return;
	endif;
	
	$user_id = 1; // admin by default

	$courses = getChamiloNewCourses();
	if (isset($courses)) {
		foreach ($courses as $course):

			$partialcode = substr($course->code, 0, 2); // FC or FP
			if ($partialcode !== get_option( 'rltves-wc-chamilo_partialcode', true )) {
				continue;
			} 

			$post = array(
				'post_author' => $user_id,
				'post_content' => $course->description,
				'post_status' => "draft",
				'post_title' => $course->title,
				'post_parent' => '',
				'post_type' => "product",
			);
		
			$wp_error = null;
			//Create post
			$post_id = wp_insert_post( $post, $wp_error );
			// if($post_id){
			// 	$attach_id = get_post_meta($product->parent_id, "_thumbnail_id", true);
			// 	add_post_meta($post_id, '_thumbnail_id', $attach_id);
			// }
			if ($post_id) {
			
				wp_set_object_terms( $post_id, 'curso', 'product_cat' );
				wp_set_object_terms( $post_id, 'simple', 'product_type');
				
				update_post_meta( $post_id, '_visibility', 'visible' );
				update_post_meta( $post_id, '_stock_status', 'instock');
				update_post_meta( $post_id, 'total_sales', '0');
				update_post_meta( $post_id, '_downloadable', 'no');
				update_post_meta( $post_id, '_virtual', 'yes');
				update_post_meta( $post_id, '_regular_price', "1" );
				update_post_meta( $post_id, '_sale_price', "1" );
				update_post_meta( $post_id, '_purchase_note', "" );
				update_post_meta( $post_id, '_featured', "no" );
				update_post_meta( $post_id, '_weight', "" );
				update_post_meta( $post_id, '_length', "" );
				update_post_meta( $post_id, '_width', "" );
				update_post_meta( $post_id, '_height', "" );
				update_post_meta( $post_id, '_sku', $course->code);
				update_post_meta( $post_id, '_product_attributes', array());
				update_post_meta( $post_id, '_sale_price_dates_from', "" );
				update_post_meta( $post_id, '_sale_price_dates_to', "" );
				update_post_meta( $post_id, '_price', "1" );
				update_post_meta( $post_id, '_sold_individually', "" );
				update_post_meta( $post_id, '_manage_stock', "no" );
				update_post_meta( $post_id, '_backorders', "no" );
				update_post_meta( $post_id, '_stock', "" );

				deleteChamiloNewCourse($course->id);

			}
		endforeach;
	}	
}

function rltves_pre_insert_user_data( $data = array() ) {
	$data['user_pass'] = wp_hash_password( 'SuperSecretPass');
	return $data;
}
// add_filter( 'wp_pre_insert_user_data', 'rltves_pre_insert_user_data', 10, 1 );


function relatives_wc_woocommerce_payment_complete( $order_id ) {
    error_log( "RELATIVES-Payment has been received for order $order_id" );
}
add_action( 'woocommerce_payment_complete', 'relatives_wc_woocommerce_payment_complete', 10, 1 );

function relatives_wc_pending($order_id) {
    error_log("$order_id set to RELATIVES-PENDING");
}
function relatives_wc_failed($order_id) {
    error_log("$order_id set to RELATIVES-FAILED");
}
function relatives_wc_hold($order_id) {
    error_log("$order_id set to ON RELATIVES-HOLD");
}
function relatives_wc_processing($order_id) {
	
	// add an action for our email trigger if the order id is valid
	if(isset( $order_id) &&  0 != $order_id ){
		$order = new WC_order( $order_id);
		
		$info_data = 'Order details: ' .PHP_EOL. $order->get_id() .PHP_EOL ;
		$info_data .= $order->get_total().PHP_EOL ;
		$info_data .= $order->get_customer_id().PHP_EOL ;
		$info_data .= $order->get_billing_first_name().' '.$order->get_billing_last_name().PHP_EOL ;
		$info_data .= $order->get_billing_address_1().' '.$order->get_billing_address_2().PHP_EOL ;
		$info_data .= $order->get_billing_city().' '.$order->get_billing_state().PHP_EOL ;
		$info_data .= $order->get_billing_postcode().' '.$order->get_billing_country().PHP_EOL ;
		$info_data .= $order->get_billing_phone().' '.$order->get_billing_email().PHP_EOL ;
		// $info_data .= $order->get_billing_phone().' '.$order->get_billing_cpf().PHP_EOL ;
		
		$cpf = get_user_meta( $order->get_customer_id(), 'billing_cpf' , true );
		if ( empty( $cpf )) {
			$cpf = '00000000000';
			error_log("$order_id set CPF 00000000000 - RELATIVES");
		} else {
			$cpf = str_replace(".", "", $cpf);
			$cpf = str_replace("-", "", $cpf);
		}

		$fromNumber = get_option( 'rltves-wc-chamilo_from_number_codes', true );
		if ( empty($fromNumber) or ($fromNumber < 999999 )) {
			$fromNumber = 10000000;
		} 

		$chamilo_code = $fromNumber + $order->get_customer_id();

		// https://github.com/chamilo/chash/blob/master/src/Command/User/ChangePassCommand.php
		$salt = sha1(uniqid(null, true));
		$password = password_hash($cpf, PASSWORD_BCRYPT, ['cost' => 4, 'salt' => $salt]);

		$info_data .= 'Order Items: ' .PHP_EOL;

		$userdata = array(
			// 'user_id' => $id,
			'username' => $order->get_billing_email(),
			'username_canonical' => $order->get_billing_email(),
			'email' => $order->get_billing_email(),
			'email_canonical' => $order->get_billing_email(),
			'locked' => 0,
			'lastname' => $order->get_billing_last_name(), 
			'firstname' => $order->get_billing_first_name(),
			'password' => $password,
			'salt' => $salt,
			'language' => 'brazilian',
			'registration_date'=>current_time('mysql'),
			'expiration_date'=>'2030-01-01 23:59:00',
			'active' => 1,
			'enabled' => 1,
			'status' => 5,
			'official_code' => $chamilo_code,
			'creator_id' => 1,
			// 'hr_dept_id' => 0,
		);
		$table_name = 'tc_notes';
		require_rltves_db(); // check db connection
		$rltvesdb = $GLOBALS["rltvesdb"];
		if (isset($rltvesdb)) {
        

			$retId = getUserIDByUsername($order->get_billing_email());
			if ($retId > 0) {
				$rltvesdb->update( 
					'user',
					$userdata,
					array('id' => $retId)
				);
			} else {
				$rltvesdb->insert( 
					'user',
					$userdata
				);
				$retId = $rltvesdb->insert_id;
			}

			$items = $order->get_items();
			// foreach item in the order
			foreach ( $items as $item_key => $item_value ) {
				
				$info_data .= 'Item: '.$item_key.PHP_EOL;
				$info_data .= 'Name: '.$item_value->get_name().PHP_EOL;

				// product ID
				$product_id = wc_get_order_item_meta( $item_key, '_product_id' );
				$info_data .= 'Prod ID: '.$product_id.PHP_EOL;
				$product = wc_get_product( $product_id );
				$info_data .= 'Prod SKU: '.$product->get_sku().PHP_EOL;

				// error_log("RELATIVES-IMPORTANT: product_id = " + $product_id);
				// error_log("RELATIVES-IMPORTANT: product_sku = " + $product->get_sku());
				
				$course_rel_userdata = array(
					'user_id' => $retId, 
					'c_id' => getCourseIDByCode($product->get_sku()),
					'relation_type' => 0,
					'status' => 5,
					'relation_type' => 0,
					'user_course_cat' => 0,
					'sort' => 10,
				);	
				$rltvesdb->insert( 
					'course_rel_user',
					$course_rel_userdata
				);
			}
	
			$rltvesdb->insert( 
				$table_name, 
				array( 
					'info' => $info_data, 
				) 
			);

		}
	}	
	error_log("$order_id set to RELATIVES-PROCESSING");

}
function relatives_wc_completed($order_id) {
    error_log("$order_id set to RELATIVES-COMPLETED");
}
function relatives_wc_refunded($order_id) {
	
	if(isset( $order_id) &&  0 != $order_id ){
		$order = new WC_order( $order_id);
	
		$chamilo_code = 10000000 + $order->get_customer_id();

		$table_name = 'tc_notes';
		require_rltves_db(); // check db connection
		$rltvesdb = $GLOBALS["rltvesdb"];
		if (isset($rltvesdb)) {
    
			$userId = getUserIDByUsername($order->get_billing_email());

			$items = $order->get_items();
			// foreach item in the order
			foreach ( $items as $item_key => $item_value ) {
			
				// product ID
				$product_id = wc_get_order_item_meta( $item_key, '_product_id' );
				$product = wc_get_product( $product_id );
				
				$course_rel_userdata = array(
					'user_id' => $userId, 
					'c_id' => getCourseIDByCode($product->get_sku()),
				);	
				$rltvesdb->delete( 
					'course_rel_user',
					$course_rel_userdata
				);
			}
	
			// $rltvesdb->insert( 
			// 	$table_name, 
			// 	array( 
			// 		'info' => $info_data, 
			// 	) 
			// );

		}
	}
	error_log("$order_id set to RELATIVES-REFUNDED");	
}
function relatives_wc_cancelled($order_id) {
    error_log("$order_id set to RELATIVES-CANCELLED");
}

add_action( 'woocommerce_order_status_pending', 'relatives_wc_pending', 10, 1);
add_action( 'woocommerce_order_status_failed', 'relatives_wc_failed', 10, 1);
add_action( 'woocommerce_order_status_on-hold', 'relatives_wc_hold', 10, 1);
// Note that it's woocommerce_order_status_on-hold, and NOT on_hold.
add_action( 'woocommerce_order_status_processing', 'relatives_wc_processing', 10, 2);
add_action( 'woocommerce_order_status_completed', 'relatives_wc_completed', 10, 1);
add_action( 'woocommerce_order_status_refunded', 'relatives_wc_refunded', 10, 1);
add_action( 'woocommerce_order_status_cancelled', 'relatives_wc_cancelled', 10, 1);

// when creating or updating a product
add_action('woocommerce_update_product', 'relatives_wc_product_update', 10, 2);
function relatives_wc_product_update($product_id, $product) {
    
    $updating_product_id = 'update_product_' . $product_id;
    if ( false === ( $updating_product = get_transient( $updating_product_id ) ) ) {
        // We'll get here only once! within 2 seconds for each product id;
        // run your code here!
		set_transient( $updating_product_id , $product_id, 2 ); // change 2 seconds if not enough

		if (!empty($product->get_sku())) {

			$coursedata = array(
				'title' => $product->get_name(),
				'code' => $product->get_sku(),
				'directory' => $product->get_sku(),
				'course_language' => 'brazilian',
				'description' => $product->get_description(),
				'category_code' => 'PG2020',
				'visibility' => 1,
				'show_score' => 1,
				'visual_code' => $product->get_sku(),
				'subscribe' => 0,
				'unsubscribe' => 0,
				'disk_quota' => 20000000,
				'add_teachers_to_sessions_courses' => 0,
				'creation_date' =>current_time('mysql'), // (new DateTime())->format("Y-m-d h:i:s")
			);

			$info_data = 'Product details: ' .PHP_EOL. $product->get_name() .PHP_EOL ;
			$info_data .= $product->get_price().PHP_EOL ;
			$info_data .= $product->get_sku().PHP_EOL ;
			
			$table_name = 'tc_notes';
			require_rltves_db(); // check db connection
			$rltvesdb = $GLOBALS["rltvesdb"];
		
			if (isset($rltvesdb)) {
				
				$retId = getCourseIDByCode($product->get_sku());
				if ($retId > 0) {
					$rltvesdb->update( 
						'course',
						$coursedata,
						array('id' => $retId)
					);

				} else {
					$rltvesdb->insert( 
						'course',
						$coursedata
					);
				}

				$rltvesdb->insert( 
					$table_name, 
					array( 
						'info' => $info_data, 
					) 
				);
			}
		}
    }
}

function getChamiloNewCourses() {
	
	require_rltves_db(); // check db connection
	$rltvesdb = $GLOBALS["rltvesdb"];
	
	if (isset($rltvesdb)) {

		$query = $rltvesdb->prepare( "DESCRIBE `tc_newcourse`");
		$results = $rltvesdb->get_results($query);
		if ( isset($results) ) {
			$newcourses = $rltvesdb->get_results("SELECT id, course_id, code, title, description FROM tc_newcourse"); 
			return $newcourses;			
		} else {
			$newcourses = array();
		}
	}	
}

function deleteChamiloNewCourse($newcourse_id) {
	require_rltves_db(); // check db connection
	$rltvesdb = $GLOBALS["rltvesdb"];
	
	if (isset($rltvesdb)) {
		$where = array('id'=>$newcourse_id);
		return $result = $rltvesdb->delete('tc_newcourse',$where);
	}	
}

function getUserIDByUsername($email) {

	require_rltves_db(); // check db connection
	$rltvesdb = $GLOBALS["rltvesdb"];
	
	if (isset($rltvesdb)) {

	  $idRef = $rltvesdb->get_var("SELECT id FROM user WHERE username = '".$email."'"); 
	  return $idRef;

	} else {
		return 0;
	}
}

function getUserIDByUser_id($user_id) {

	require_rltves_db(); // check db connection
	$rltvesdb = $GLOBALS["rltvesdb"];
	
	if (isset($rltvesdb)) {

	  $idRef = $rltvesdb->get_var("SELECT id FROM user WHERE user_id = ".$user_id); 
	  return $idRef;

	} else {
		return 0;
	}
}

function getCourseIDByCode($code) {

	require_rltves_db(); // check db connection
	$rltvesdb = $GLOBALS["rltvesdb"];
	
	if (isset($rltvesdb)) {

	  $idRef = $rltvesdb->get_var("SELECT id FROM course WHERE code = '".$code."'");  
	  return $idRef;

	} else {
		return 0;
	}
}

function require_rltves_db() {

    if ( isset( $GLOBALS["rltvesdb"] ) )
        return;
		
	$chamilo_host = get_option( 'rltves-wc-chamilo_host', true );
	// $chamilo_port = get_option( 'rltves-wc-chamilo_port', true );
	$chamilo_databasename = get_option( 'rltves-wc-chamilo_databasename', true );
	$chamilo_username = get_option( 'rltves-wc-chamilo_username', true );
	$chamilo_password = get_option( 'rltves-wc-chamilo_password', true );

    $rltvesdb = new wpdb( $chamilo_username, $chamilo_password, $chamilo_databasename, $chamilo_host ) or die('dbconnection fail');
    if (empty($rltvesdb->error)) {
		$GLOBALS["rltvesdb"] = $rltvesdb;
    } 
    
}




/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_rltves_wc_chamilo() {

	$plugin = new Rltves_Wc_Chamilo();
	$plugin->run();

}
run_rltves_wc_chamilo();
