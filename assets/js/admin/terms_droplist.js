jQuery(document).ready(function ($) {

    // Dynamic DropDowns
    if ($('.woo-vpf-ymm-form-container').find('.woo_vpf_ymm_make').length) {
        $('.woo-vpf-ymm-form-container').on('change', '.woo_vpf_ymm_year', function () {

            if ($(this).closest('.woo-vpf-ymm-terms-row').length) {
                var $elem = $(this).closest('.woo-vpf-ymm-terms-row');
            } else {
                var $elem = $(this).closest('.woo-vpf-ymm-form-container');
            }

            $elem.addClass('woo-vpf-ymm-processing');

            $term_id = $(this).val();
            console.log(woo_vpf_ymm_tdl_params.ajax_url);
            console.log('woo_vpf_ymm_tdl_params.ajax_url');
            $.post(woo_vpf_ymm_tdl_params.ajax_url, {'parent': $term_id, 'action': 'woo_vpf_ymm_get_terms'}, function (response) {
                $elem.removeClass('woo-vpf-ymm-processing');
                $elem.find('.woo_vpf_ymm_make option:not(:first)').remove();
                $elem.find('.woo_vpf_ymm_make').append(response);

                $elem.find('.woo_vpf_ymm_model option:not(:first)').remove();

                if ($elem.find('.woo_vpf_ymm_engine').length) {
                    $elem.find('.woo_vpf_ymm_engine option:not(:first)').remove();
                }

                $(document.body).trigger('woo_vpf_ymm_makes_updated', [$elem, $elem.find('.woo_vpf_ymm_make')]);
            });
        });
    }

    if ($('.woo-vpf-ymm-form-container').find('.woo_vpf_ymm_model').length) {
        $('.woo-vpf-ymm-form-container').on('change', '.woo_vpf_ymm_make', function () {

            if ($(this).closest('.woo-vpf-ymm-terms-row').length) {
                var $elem = $(this).closest('.woo-vpf-ymm-terms-row');
            } else {
                var $elem = $(this).closest('.woo-vpf-ymm-form-container');
            }

            $elem.addClass('woo-vpf-ymm-processing');

            $term_id = $(this).val();
            $.post(woo_vpf_ymm_tdl_params.ajax_url, {'parent': $term_id, 'action': 'woo_vpf_ymm_get_terms'}, function (response) {
                $elem.removeClass('woo-vpf-ymm-processing');
                $elem.find('.woo_vpf_ymm_model option:not(:first)').remove();
                $elem.find('.woo_vpf_ymm_model').append(response);

                if ($elem.find('.woo_vpf_ymm_engine').length) {
                    $elem.find('.woo_vpf_ymm_engine option:not(:first)').remove();
                }

                $(document.body).trigger('woo_vpf_ymm_models_updated', [$elem, $elem.find('.woo_vpf_ymm_model')]);
            });
        });
    }

    if ($('.woo-vpf-ymm-form-container').find('.woo_vpf_ymm_engine').length) {
        $('.woo-vpf-ymm-form-container').on('change', '.woo_vpf_ymm_model', function () {

            if ($(this).closest('.woo-vpf-ymm-terms-row').length) {
                var $elem = $(this).closest('.woo-vpf-ymm-terms-row');
            } else {
                var $elem = $(this).closest('.woo-vpf-ymm-form-container');
            }

            $elem.addClass('woo-vpf-ymm-processing');

            $term_id = $(this).val();
            $.post(woo_vpf_ymm_tdl_params.ajax_url, {'parent': $term_id, 'action': 'woo_vpf_ymm_get_terms'}, function (response) {
                $elem.removeClass('woo-vpf-ymm-processing');
                $elem.find('.woo_vpf_ymm_engine option:not(:first)').remove();
                $elem.find('.woo_vpf_ymm_engine').append(response);

                $(document.body).trigger('woo_vpf_ymm_engines_updated', [$elem, $elem.find('.woo_vpf_ymm_engine')]);
            });
        });
    }
});
