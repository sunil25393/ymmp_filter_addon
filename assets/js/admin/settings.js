jQuery( document ).ready(function($) {
	
	// Collect all the parent fields
	var parent_elems = [];								
	$('input, textarea, select').each(function() {
		var elem_id = $(this).attr('id');
		if( $('.'+elem_id+'_child').length ) {
			if( typeof elem_id != 'undefined' && $.inArray(elem_id, parent_elems) == -1 ) {
				parent_elems.push(elem_id);
			}
		}
	});
	
	// Trigger hook when changed parent field
	if( parent_elems.length > 0 ) {
		parent_elems.forEach(function(parent_elem) {
			$('#'+parent_elem).change(function() {
				woo_vpf_ymm_setting_fields(parent_elems);
			});
		});
		
		woo_vpf_ymm_setting_fields(parent_elems);
	}
	
	// Show/Hide child fields based on parent field values
	function woo_vpf_ymm_setting_fields(parent_elems) {
		if( parent_elems.length > 0 ) {
			parent_elems.forEach(function(parent_elem) {
				parent_elem_val = $('#'+parent_elem).val();
				
				parent_elem_type = $('#'+parent_elem).attr('type');
				if( typeof parent_elem_type != 'undefined' && parent_elem_type == 'checkbox' ) {
					if( $('#'+parent_elem).is(':checked') ) {
						parent_elem_val = 'yes';
					}
				}
				
				$('.'+parent_elem+'_child').closest('tr').hide();
				
				if( $('#'+parent_elem).is(':visible') ) {
					$('.'+parent_elem+'_'+parent_elem_val).closest('tr').show();
				}
			});
		}
	}
	
	// Custom Accordion
	if( $('.woo-vpf-ymm-section-title').length ) {
		$('.woo-vpf-ymm-section-title').each(function() {
			$elem = $(this).closest('tr');
			$elem.nextUntil('tr:has(.woo-vpf-ymm-section-title)').addClass('woo-vpf-ymm-hidden');
			$elem.addClass('closed');
		});
		
		$('.woo-vpf-ymm-section-title').click(function() {
			$elem = $(this).closest('tr');
			
			if( ! $elem.hasClass('closed') ) {
				$elem.nextUntil('tr:has(.woo-vpf-ymm-section-title)').addClass('woo-vpf-ymm-hidden');
				$elem.addClass('closed');
			} else {
				$elem.nextUntil('tr:has(.woo-vpf-ymm-section-title)').removeClass('woo-vpf-ymm-hidden');
				$elem.removeClass('closed');
			}
		});
	}
});
