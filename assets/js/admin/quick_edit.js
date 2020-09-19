jQuery( document ).ready(function($) {

	$( '#the-list' ).on( 'click', '.editinline', function() {
	
		inlineEditPost.revert();

		var post_id = $( this ).closest( 'tr' ).attr( 'id' );
		post_id = post_id.replace( 'post-', '' );

		var $wc_inline_data = $( '#woo_vpf_ymm_inline_' + post_id );
		var universal		= $wc_inline_data.find( '.universal' ).text();
		
		if ( 'yes' === universal ) {
			$( 'input[name="vpf_ymm_universal"]', '.inline-edit-row' ).attr( 'checked', 'checked' );
		} else {
			$( 'input[name="vpf_ymm_universal"]', '.inline-edit-row' ).removeAttr( 'checked' );
		}
		
	});
});
