jQuery(document).ready(function ($) {

    // Reset Form on Page Load
    $('.widget-woo-vpf-ymm-filter form').each(function () {
        $(this)[0].reset();
    });

    $('.widget-woo-vpf-ymm-filter .nav-item').addClass('hidden');
    $('.widget-woo-vpf-ymm-filter .nav-item.active ').removeClass('hidden');
    $('.woo-vpf-ymm-field-submit').addClass('disabled');
    $('.modal-header .selection span').empty();

    $(document.body).on('woo_vpf_ymm_makes_updated', function (event, elem_container, elem_select) {
        elem_container.find('#make-tab').parent().removeClass('hidden');
        elem_container.find('#make-tab').tab('show');
        elem_container.find('#model-tab').parent().addClass('hidden');
        elem_container.find('#option-tab').parent().addClass('hidden');
//        elem_container.find('#zip-tab').parent().addClass('hidden');
        elem_container.find('.woo-vpf-ymm-field-submit').addClass('disabled');

    });

    $(document.body).on('woo_vpf_ymm_models_updated', function (event, elem_container, elem_select) {
        elem_container.find('#model-tab').parent().removeClass('hidden');
        elem_container.find('#model-tab').tab('show');

        elem_container.find('#option-tab').parent().addClass('hidden');
//        elem_container.find('#zip-tab').parent().addClass('hidden');
        elem_container.find('.woo-vpf-ymm-field-submit').addClass('disabled');
    });

    $(document.body).on('woo_vpf_ymm_engines_updated', function (event, elem_container, elem_select) {
        elem_container.find('#option-tab').parent().removeClass('hidden');
        elem_container.find('#option-tab').tab('show');

//        elem_container.find('#zip-tab').parent().addClass('hidden');
        elem_container.find('.woo-vpf-ymm-field-submit').addClass('disabled');
    });

    $(document.body).on('woo_vpf_ymm_zipcode_updated', function (event, elem_container, elem_select) {
//        elem_container.find('#zip-tab').parent().removeClass('hidden');
//        elem_container.find('#zip-tab').tab('show');
        elem_container.removeClass('woo-vpf-ymm-processing');
        elem_container.find('.woo-vpf-ymm-field-submit').removeClass('disabled');
    });

    // Dynamic DropDowns
    if ($('.widget-woo-vpf-ymm-filter .woo-vpf-ymm-field-make').length) {
        $(document.body).on('change', '.widget-woo-vpf-ymm-filter input:radio[name=year_id]', function () {
    
            var $elem = $(this).closest('form');
            $elem.addClass('woo-vpf-ymm-processing');

            $('#year').find('input').parent().removeClass('active');
            $(this).parent().addClass('active'); 

            $term_id = $(this).val();
            $term_text = $(this).attr('data-name');

            $.post(woo_vpf_ymm_params.ajax_url, {'parent': $term_id, 'input_name': 'make', 'action': 'woo_vpf_ymm_get_terms_frontend'}, function (response) {
                $elem.find('.woo-vpf-ymm-field-make').empty();
                $elem.removeClass('woo-vpf-ymm-processing');

                if ($elem.find('.woo-vpf-ymm-field-make').length && response !== '') {
                    $elem.find('#option .filter p').addClass('hidden');
                    $elem.find('.woo-vpf-ymm-field-make').append(response);
                    if ($('.modal-header .selection .year').length) {
                        $('.modal-header .selection span').empty();
                        $('.modal-header .selection .year').html($term_text);
                    }

                } else {
                    $elem.find('#make .filter p').removeClass('hidden');
                }

                $(document.body).trigger('woo_vpf_ymm_makes_updated', [$elem, $elem.find('input:radio[name="make"]')]);
            });
        });
    }

    if ($('.widget-woo-vpf-ymm-filter .woo-vpf-ymm-field-model').length) {
        $(document.body).on('change', '.widget-woo-vpf-ymm-filter input:radio[name="make"]', function () {
            var $elem = $(this).closest('form');
            $elem.find('.woo-vpf-ymm-field-model').empty();
            $elem.addClass('woo-vpf-ymm-processing');

            $('#make').find('input').parent().removeClass('active');
            $(this).parent().addClass('active'); 
            
            $term_id = $(this).val();
            $term_text = $(this).attr('data-name');

            $.post(woo_vpf_ymm_params.ajax_url, {'parent': $term_id, 'input_name': 'model', 'action': 'woo_vpf_ymm_get_terms_frontend'}, function (response) {
                $elem.removeClass('woo-vpf-ymm-processing');

                if ($elem.find('.woo-vpf-ymm-field-model').length && response !== '') {
                    $elem.find('#option .filter p').addClass('hidden');
                    $elem.find('.woo-vpf-ymm-field-model').append(response);
                    if ($('.modal-header .selection .make').length) {
                        $('.modal-header .selection .model').empty();
                        $('.modal-header .selection .option').empty();
                        $('.modal-header .selection .make').html($term_text);
                    }
                } else {
                    $elem.find('#model .filter p').removeClass('hidden');
                }

                $(document.body).trigger('woo_vpf_ymm_models_updated', [$elem, $elem.find('input:radio[name="model"]')]);
            });
        });
    }

    if ($('.widget-woo-vpf-ymm-filter .woo-vpf-ymm-field-engine').length) {
        $(document.body).on('change', '.widget-woo-vpf-ymm-filter input:radio[name="model"]', function () {
            var $elem = $(this).closest('form');
            $elem.find('.woo-vpf-ymm-field-engine').empty();
            $elem.addClass('woo-vpf-ymm-processing');

            $('#model').find('input').parent().removeClass('active');
            $(this).parent().addClass('active'); 
            
            $term_id = $(this).val();
            $term_text = $(this).attr('data-name');

            $.post(woo_vpf_ymm_params.ajax_url, {'parent': $term_id, 'input_name': 'engine', 'action': 'woo_vpf_ymm_get_terms_frontend'}, function (response) {
                $elem.removeClass('woo-vpf-ymm-processing');

                if ($elem.find('.woo-vpf-ymm-field-engine').length && response !== '') {
                    $elem.find('#option .filter p').addClass('hidden');
                    $elem.find('.woo-vpf-ymm-field-engine').append(response);
                    if ($('.modal-header .selection .model').length) {
                        $('.modal-header .selection .option').empty();
                        $('.modal-header .selection .model').html($term_text);
                    }
                } else {
                    $elem.find('#option .filter p').removeClass('hidden');
                }

                $(document.body).trigger('woo_vpf_ymm_engines_updated', [$elem, $elem.find('input:radio[name="engine"]')]);
            });
        });
    }


    if ($('.widget-woo-vpf-ymm-filter .woo-vpf-ymm-field-submit').length) {
        $(document.body).on('change', '.widget-woo-vpf-ymm-filter input:radio[name="engine"]', function () {
            var $elem = $(this).closest('form');
            $elem.addClass('woo-vpf-ymm-processing');

            $('#engine').find('input').parent().removeClass('active');
            $(this).parent().addClass('active'); 
            
            $term_id = $(this).val();
            $term_text = $(this).attr('data-name');

            if ($term_id != '') {
                if ($('.modal-header .selection .option').length) {
                    $('.modal-header .selection .option').html($term_text);
                }
            }
            $(document.body).trigger('woo_vpf_ymm_zipcode_updated', [$elem, $elem.find('input:radio[name="engine"]')]);
        });
    }
 
});
