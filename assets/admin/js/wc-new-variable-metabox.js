jQuery( function($) {

	$( '.enable_variation' ).addClass( 'show_if_new-variable' );


});

/*
jQuery( function( $ ) {
	$( ".variations_tab" ).addClass( "show_if_new-variable" );

	$( document.body ).on( "woocommerce_added_attribute", function() {
		$( ".enable_variation" ).addClass( "show_if_new-variable" );

		if ( "new-variable" === $( "select#product-type" ).val() ) {
			$( ".enable_variation" ).show();
		}
	});
} );
*/