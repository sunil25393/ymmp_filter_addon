jQuery( document ).ready(function($) {

	// Universal Product?
	$('.woo-vpf-ymm-form-container').on('change', '.vpf_ymm_universal', function(e) {
		e.preventDefault();
		
		if( $(this).is(':checked') ) {
			$('.woo-vpf-ymm-form-container .woo-vpf-ymm-terms-table-container').addClass('woo-vpf-ymm-hidden');
			$('.woo-vpf-ymm-form-container .woo-vpf-ymm-remove-term-rows').addClass('woo-vpf-ymm-hidden');
			$('.woo-vpf-ymm-add-term-row').addClass('woo-vpf-ymm-hidden');
		} else {
			$('.woo-vpf-ymm-form-container .woo-vpf-ymm-terms-table-container').removeClass('woo-vpf-ymm-hidden');
			$('.woo-vpf-ymm-form-container .woo-vpf-ymm-remove-term-rows').removeClass('woo-vpf-ymm-hidden');
			$('.woo-vpf-ymm-add-term-row').removeClass('woo-vpf-ymm-hidden');
		}
	});
	$('.woo-vpf-ymm-form-container .vpf_ymm_universal').trigger('change');
	
	// Add New Row
	$('.woo-vpf-ymm-add-term-row').on('click', function(e) {
		e.preventDefault();
		
		var $elem = $(this).closest('.postbox').find('tbody tr:last');
				
		var $elem_clone_row = $elem.clone();		
		$elem_clone_row.find('select').val('');
		$elem_clone_row.find('select:not(:first)').each(function() {
			$(this).find('option:not(:first)').remove();
		});
		$elem_clone_row.removeClass('woo-vpf-ymm-hidden');
		
		$elem_clone_row.insertBefore( $elem );
	});
	
	// Remove Single Row
	$('.woo-vpf-ymm-form-table').on('click', '.woo-vpf-ymm-remove-term-row', function(e) {
		e.preventDefault();
		
		if( confirm( woo_vpf_ymm_tmb_params.msg_confirm ) ) {
			$(this).closest('tr').remove();
		}
	});
	
	// Remove All Row
	$('.woo-vpf-ymm-form-container').on('click', '.woo-vpf-ymm-remove-term-rows', function(e) {
		e.preventDefault();
		
		if( confirm( woo_vpf_ymm_tmb_params.msg_confirm ) ) {
			var $elem_table = $(this).closest('.woo-vpf-ymm-form-container').find('.woo-vpf-ymm-form-table');;
			
			$elem_table.find('tbody').find('tr:not(:last)').remove();
			$elem_table.find('tbody').find('tr select').val('');
		}
	});
	
	// Reset Form on Page Load
	$('.woo-vpf-ymm-form-table .woo-vpf-ymm-terms-row-add select').val('');
});
