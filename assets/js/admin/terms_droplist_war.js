jQuery(document).ready(function ($) {

    // Dynamic DropDowns
    if ($('.woo-vpf-ymm-form-container').find('.woo_vpf_war_aspect').length) {
        $('.woo-vpf-ymm-form-container').on('change', '.woo_vpf_war_width', function () {

            if ($(this).closest('.woo-vpf-ymm-terms-row').length) {
                var $elem = $(this).closest('.woo-vpf-ymm-terms-row');
            } else {
                var $elem = $(this).closest('.woo-vpf-ymm-form-container');
            }

            $elem.addClass('woo-vpf-ymm-processing');

            $term_id = $(this).val();

            $.post(woo_vpf_ymm_tdl_params.ajax_url, {'parent': $term_id, 'action': 'woo_vpf_ymm_get_terms_war'}, function (response) {
                $elem.removeClass('woo-vpf-ymm-processing');
                $elem.find('.woo_vpf_war_aspect option:not(:first)').remove();
                $elem.find('.woo_vpf_war_aspect').append(response);

                $elem.find('.woo_vpf_war_rim option:not(:first)').remove();



                $(document.body).trigger('woo_vpf_war_aspects_updated', [$elem, $elem.find('.woo_vpf_war_aspect')]);
            });
        });
    }

    if ($('.woo-vpf-ymm-form-container').find('.woo_vpf_war_rim').length) {
        $('.woo-vpf-ymm-form-container').on('change', '.woo_vpf_war_aspect', function () {

            if ($(this).closest('.woo-vpf-ymm-terms-row').length) {
                var $elem = $(this).closest('.woo-vpf-ymm-terms-row');
            } else {
                var $elem = $(this).closest('.woo-vpf-ymm-form-container');
            }

            $elem.addClass('woo-vpf-ymm-processing');

            $term_id = $(this).val();
            $.post(woo_vpf_ymm_tdl_params.ajax_url, {'parent': $term_id, 'action': 'woo_vpf_ymm_get_terms_war'}, function (response) {
                $elem.removeClass('woo-vpf-ymm-processing');
                $elem.find('.woo_vpf_war_rim option:not(:first)').remove();
                $elem.find('.woo_vpf_war_rim').append(response);


                $(document.body).trigger('woo_vpf_war_rims_updated', [$elem, $elem.find('.woo_vpf_war_rim')]);
            });
        });
    }

});
