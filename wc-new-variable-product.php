<?php
/**
 * Plugin Name: New Variable Product
 * Plugin URI: http://stackoverflow.com/q/34271930/383847
 * Description: Sample new variable-type product
 * Version: 1.0.0-beta-1
 * Author: Kathy Darling
 * Author URI: http://kathyisawesome.com/
 * Text Domain: wc-new-variable
 * Domain Path: /languages
 *
 * Copyright: © 2021 Kathy Darling
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */
namespace KIA_New_Variable;

define( 'KIA_NEW_VARIABLE_VERSION', '1.0.0-beta-1' );

/**
 * Attach hooks and filters
 */
function init() {

	// Include additional files.
	include_once( 'includes/class-wc-product-new-variable.php' );
	include_once( 'includes/class-wc-product-new-variation.php' );	

	// Load translation files.
	add_action( 'init', __NAMESPACE__ . '\load_plugin_textdomain' );

	// Allows the selection of the new product type
	add_filter( 'product_type_selector', __NAMESPACE__ . '\product_selector_filter' );

	// Admin.
	add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\admin_scripts', 20 );
	add_filter( 'woocommerce_product_data_tabs', __NAMESPACE__ . '\product_data_tabs' );
	add_action( 'woocommerce_mnm_product_options', __NAMESPACE__ . '\display_meta_field' , 15, 2 );
	
	add_action( 'woocommerce_admin_process_product_object', __NAMESPACE__ . '\process_meta' );
	//add_action( 'woocommerce_ajax_save_product_variations', __NAMESPACE__ . '\process_product_meta_new_variable' );

	// Product data.
	add_filter( 'woocommerce_data_stores', __NAMESPACE__ . '\new_variable_data_store' );

	// Set the variation class to default.
	//add_filter( 'woocommerce_product_class', __NAMESPACE__ . '\set_variation_class', 10, 4 );

	// Use the existing add to cart template.
	add_action( 'woocommerce_new-variable_add_to_cart', 'woocommerce_variable_add_to_cart' );

}


/*-----------------------------------------------------------------------------------*/
/* Localization */
/*-----------------------------------------------------------------------------------*/


/**
 * Make the plugin translation ready
 */
function load_plugin_textdomain() {
	\load_plugin_textdomain( 'wc-new-variable' , false , dirname( plugin_basename( __FILE__ ) ) .  '/languages/' );
}


/*-----------------------------------------------------------------------------------*/
/* Admin */
/*-----------------------------------------------------------------------------------*/


/**
 * Register the new product type
 * 
 * @param array $product_types
 * @return array
 */ 
function product_selector_filter( $product_types ) {
	$product_types['new-variable'] = __( 'New Variable', 'wc-new-variable' );
	return $product_types;
}

/**
 * Assign classes to "Variations" tab to get it to show for our new tpe
 * 
 * @param array $tabs
 * @return array
 */ 
function product_data_tabs( $tabs ){
	$tabs['variations']['class']  = array( 'variations_tab', 'show_if_variable', 'show_if_new-variable' );
	return $tabs;
}

/**
 * Add custom javascripts to product page.
 */
function admin_scripts() {

	// Get admin screen id
	$screen = get_current_screen();

	// WooCommerce product admin page
	if ( 'product' == $screen->id && 'post' == $screen->base ) {
		\wp_enqueue_script( 'wc-new-variable-metabox', plugins_url( 'admin/js/wc-new-variable-metabox.js', __FILE__ ), array( 'wc-admin-variation-meta-boxes' ), KIA_NEW_VARIABLE_VERSION, true );
	}

}


/**
 * Adds the "sample" checkbox.
 *
 * @param int $post_id
 * @param  WC_Product_Mix_and_Match  $mnm_product_object
 */
function additional_container_option( $post_id, $mnm_product_object ) {
	\woocommerce_wp_checkbox( array(
		'id'            => '_wc_new_variable_sample',
		'label'       => __( 'Randomize for customers', 'wc-new-variable' )
	) );
}

/**
 * Saves the new meta field.
 *
 * @param  WC_Product_New_Variable  $product
 */
function process_meta( $product ) {

	if ( isset( $_POST[ '_wc_new_variable_sample' ] ) ) {
		$product->update_meta_data( '_wc_new_variable_sample', 'yes' );
	} else {
		$product->delete_meta_data( '_wc_new_variable_sample' );
	}
}


/*-----------------------------------------------------------------------------------*/
/* Product classes and data */
/*-----------------------------------------------------------------------------------*/

/**
 * Use regular Variable product data store.
 * 
 * @param array $stores key/value pairs of store name => class name
 * @return array
 */
function new_variable_data_store( $stores ) {
    $stores['product-new-variable'] = 'WC_Product_Variable_Data_Store_CPT';
    return $stores;
}

/**
 * Tells Woo to use the default variation class for our variation.
 *
 *@param  string $classname - The class for this product type.
 *@param  string $product_type Product type.
 *@param string $post_type
 *@param int $product_id

* @return string
*/
function set_variation_class( $classname, $product_type, $post_type, $product_id ) {

	if ( 'product_variation' === $post_type && 'variation' === $product_type ) {

		$terms = get_the_terms( get_post( $product_id )->post_parent, 'product_type' );

		$parent_product_type = ! empty( $terms ) && isset( current( $terms )->slug ) ? current( $terms )->slug : '';

		if ( 'new-variable' === $parent_product_type ) {
			$classname = 'WC_Product_Variation';
		}
	}

	return $classname;
}

/*-----------------------------------------------------------------------------------*/
/* Front End Display */
/*-----------------------------------------------------------------------------------*/



/*-----------------------------------------------------------------------------------*/
/* Cart  */
/*-----------------------------------------------------------------------------------*/



/*-----------------------------------------------------------------------------------*/
/* Launch the whole plugin. */
/*-----------------------------------------------------------------------------------*/
add_action( 'woocommerce_loaded', __NAMESPACE__ . '\init' );
