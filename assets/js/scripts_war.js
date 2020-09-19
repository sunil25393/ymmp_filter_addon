jQuery(document).ready(function ($) {

    // Reset Form on Page Load
    $('.widget-woo-vpf-ymm-filter form').each(function () {
        $(this)[0].reset();
    });

    $('.widget-woo-vpf-ymm-filter .nav-item').addClass('hidden');
    $('.widget-woo-vpf-ymm-filter .nav-item.active ').removeClass('hidden');
    $('.woo-vpf-ymm-field-submit').addClass('disabled');
    $('.modal-header .selection span').empty();

    $(document.body).on('woo_vpf_ymm_aspects_updated', function (event, elem_container, elem_select) {
        elem_container.find('#aspect-tab').parent().removeClass('hidden');
        elem_container.find('#aspect-tab').tab('show');
        elem_container.find('#rim-tab').parent().addClass('hidden');
        elem_container.find('#option-tab').parent().addClass('hidden');
        elem_container.find('.woo-vpf-ymm-field-submit').addClass('disabled');

    });

    $(document.body).on('woo_vpf_ymm_rims_updated', function (event, elem_container, elem_select) {
        elem_container.find('#rim-tab').parent().removeClass('hidden');
        elem_container.find('#rim-tab').tab('show');

        elem_container.find('#option-tab').parent().addClass('hidden');
        elem_container.find('.woo-vpf-ymm-field-submit').addClass('disabled');
    });


    $(document.body).on('woo_vpf_ymm_zip_updated', function (event, elem_container, elem_select) {

        elem_container.removeClass('woo-vpf-ymm-processing');
        elem_container.find('.woo-vpf-ymm-field-submit').removeClass('disabled');
    });

    // Dynamic DropDowns
    if ($('.widget-woo-vpf-ymm-filter .woo-vpf-ymm-field-aspect').length) {
        $(document.body).on('change', '.widget-woo-vpf-ymm-filter input:radio[name=width]', function () {

            var $elem = $(this).closest('form');
            $elem.addClass('woo-vpf-ymm-processing');

            $('#width').find('input').parent().removeClass('active');
            $(this).parent().addClass('active');

            $term_id = $(this).val();
            $term_text = $(this).attr('data-name');

            $.post(woo_vpf_ymm_params.ajax_url, {'parent': $term_id, 'input_name': 'aspect', 'action': 'woo_vpf_ymm_get_terms_war_frontend'}, function (response) {
                $elem.find('.woo-vpf-ymm-field-aspect').empty();
                $elem.removeClass('woo-vpf-ymm-processing');

                if ($elem.find('.woo-vpf-ymm-field-aspect').length && response !== '') {
                    $elem.find('#aspect .filter p').addClass('hidden');
                    $elem.find('.woo-vpf-ymm-field-aspect').append(response);
                    if ($('.modal-header .selection .width').length) {
                        $('.modal-header .selection span').empty();
                        $('.modal-header .selection .width').html($term_text);
                    }

                } else {
                    $elem.find('#aspect .filter p').removeClass('hidden');
                }

                $(document.body).trigger('woo_vpf_ymm_aspects_updated', [$elem, $elem.find('input:radio[name="aspect"]')]);
            });
        });
    }

    if ($('.widget-woo-vpf-ymm-filter .woo-vpf-ymm-field-rim').length) {
        $(document.body).on('change', '.widget-woo-vpf-ymm-filter input:radio[name="aspect"]', function () {
            var $elem = $(this).closest('form');
            $elem.find('.woo-vpf-ymm-field-rim').empty();
            $elem.addClass('woo-vpf-ymm-processing');

            $('#aspect').find('input').parent().removeClass('active');
            $(this).parent().addClass('active');

            $term_id = $(this).val();
            $term_text = $(this).attr('data-name');

            $.post(woo_vpf_ymm_params.ajax_url, {'parent': $term_id, 'input_name': 'rim', 'action': 'woo_vpf_ymm_get_terms_war_frontend'}, function (response) {
                $elem.removeClass('woo-vpf-ymm-processing');

                if ($elem.find('.woo-vpf-ymm-field-rim').length && response !== '') {
                    $elem.find('#rim .filter p').addClass('hidden');
                    $elem.find('.woo-vpf-ymm-field-rim').append(response);
                    if ($('.modal-header .selection .aspect').length) {
                        $('.modal-header .selection .rim').empty();
                        $('.modal-header .selection .option').empty();
                        $('.modal-header .selection .aspect').html($term_text);
                    }
                } else {
                    $elem.find('#rim .filter p').removeClass('hidden');
                }

                $(document.body).trigger('woo_vpf_ymm_rims_updated', [$elem, $elem.find('input:radio[name="rim"]')]);
            });
        });
    }

    if ($('.widget-woo-vpf-ymm-filter .woo-vpf-ymm-field-submit').length) {
        $(document.body).on('change', '.widget-woo-vpf-ymm-filter input:radio[name="rim"]', function () {
            var $elem = $(this).closest('form');
            $elem.addClass('woo-vpf-ymm-processing');
            
            $('#rim').find('input').parent().removeClass('active');
            $(this).parent().addClass('active');

            $term_id = $(this).val();
            $term_text = $(this).attr('data-name');

            if ($term_id != '') {
                if ($('.modal-header .selection .rim').length) {
                    $('.modal-header .selection .rim').html($term_text);
                }
            }
            $(document.body).trigger('woo_vpf_ymm_zip_updated', [$elem, $elem.find('input:radio[name="rim"]')]);
        });
    }

});
