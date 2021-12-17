<?php
/**
 * New Product Variation
 *
 * The WooCommerce product variation class handles product variation data.
 *
 * @package WC New Variation\Classes
 */

defined( 'ABSPATH' ) || exit;

class WC_Product_New_Variation extends WC_Product_Variation {

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
		return 'new-variation';
	}

	/**
	 * Get the add to cart button text.
	 *
	 * @return string
	 */
	public function add_to_cart_text() {
		$text = $this->is_purchasable() && $this->is_in_stock() ? __( 'New Add to cart', 'woocommerce' ) : __( 'Read more', 'woocommerce' );

		return apply_filters( 'woocommerce_product_add_to_cart_text', $text, $this );
	}
}


