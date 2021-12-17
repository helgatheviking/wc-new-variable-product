<?php
/**
 * New Variable Product
 *
 * The WooCommerce product class handles individual product data.
 *
 * @package WC New Variable\Classes
 */

defined( 'ABSPATH' ) || exit;

class WC_Product_New_Variable extends WC_Product_Variable {

	/**
	 * __construct function.
	 *
	 * @param  mixed $product
	 */
	public function __construct( $product ) {
		parent::__construct( $product );
	}

	/**
	 * Get internal type.
	 *
	 * @return string
	 */
	public function get_type() {
		return 'new-variable';
	}

	/**
	 * Get the add to cart button text.
	 *
	 * @access public
	 * @return string
	 */
	public function add_to_cart_text() {
		return apply_filters( 'woocommerce_product_add_to_cart_text', __( 'Pick options', 'your-plugin' ), $this );
	}

}
