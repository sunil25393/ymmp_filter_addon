jQuery( document ).ready(function($) {

	// Reset Form on Page Load
	$('.woo-vpf-ymm-form-container').each(function() {
		$(this).find('form').each(function() {
			$(this)[0].reset();
		});
	});

	// Submit Search Term Form
	$('.woo-vpf-ymm-search-form-container').on('submit', function(e) {
	
		var $term_parent_id = 0;
		$(this).find('select').each(function() {
			if( $(this).val() != '' ) {
				$term_parent_id = $(this).val();
			}
		});
		
		if( $term_parent_id > 0 ) {
			$(this).find('input[name="parent"]').val( $term_parent_id );
		}
		
		return true;
	});
	
	// Delete All Terms
	$('.woo-vpf-ymm-terms-delete-form').on('submit', function(e) {
		if( confirm( woo_vpf_ymm_tl_params.msg_delete_all_confirm ) ) {
			return true;
		}
		
		return false;
	});
	
	// Hide SLUG from Quick Edit
	if( $('.inline-edit-row').length ) {
		$('.inline-edit-row input[name="slug"]').closest('label').addClass('woo-vpf-ymm-hidden');
	}
	
});
