<?php
/**
 * Taxonomy Metabox Templates on Post Edit Screen
 *
 * @class YMMP_FILTER_Taxonomy_Metabox_Template
 */
if (!defined('ABSPATH')) {
    exit;
}

/**
 * YMMP_FILTER_Taxonomy_Metabox_Template Class
 */
class YMMP_FILTER_Taxonomy_Metabox_Template {

    /**
     * Constructor
     *
     * @access public
     */
    public function __construct() {
        add_action('admin_init', array($this, 'init'));
    }

    /**
     * Load core functions before hooks
     */
    public function init() {
        $woo_vpf_ymm_taxonomy_metabox_template = WC_Admin_Settings::get_option('woo_vpf_ymm_taxonomy_metabox_template');

        // Taxonomy Terms Hierarchy
        if ($woo_vpf_ymm_taxonomy_metabox_template == 'default' || $woo_vpf_ymm_taxonomy_metabox_template == 'tree_view') {
            add_filter('wp_terms_checklist_args', array($this, 'terms_hierarchy'));
        }

        // Taxonomy Template
        if ($woo_vpf_ymm_taxonomy_metabox_template == 'tree_view') {
            add_action('admin_head-post-new.php', array($this, 'template_tree'));
            add_action('admin_head-post.php', array($this, 'template_tree'));
        } else if ($woo_vpf_ymm_taxonomy_metabox_template == 'ajaxify_meta_box') {
            add_action('add_meta_boxes', array($this, 'add_taxonomy_meta_box'));
            add_action('save_post', array($this, 'save_taxonomy_meta_box'));
        }
    }

    /**
     * Template: Default / Tree View ( Taxonomy Terms Hierarchy )
     */
    public function terms_hierarchy($args) {
        if (is_admin()) {
            if (isset($args['taxonomy']) && $args['taxonomy'] == 'product_ymm') {
                $args['checked_ontop'] = false;
            }
        }

        return $args;
    }

    /**
     * Template: Tree View
     */
    public function template_tree() {
        global $post;

        if ($post->post_type != 'product') {
            return;
        }
        ?><style type="text/css">
            #product_ymm-tabs .hide-if-no-js {
                display:none;
            }

            #product_ymmchecklist .expand {
                font-weight:bold;
                margin:0 0 0 5px;
                padding:0 3px;
            }
        </style>

        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#product_ymmchecklist ul.children').hide().parent().children('label').append('<a class="expand">+</a>');

                $('#product_ymmchecklist .expand').click(function (e) {
                    e.preventDefault();

                    $elem_expand = $(this);
                    $elem_expand.closest('li').children('ul.children').slideToggle(function () {
                        if ($(this).is(':visible')) {
                            $elem_expand.text('-');
                        } else {
                            $elem_expand.text('+');
                        }
                    });
                });
            });
        </script><?php
    }

    /**
     * Template: Remove Taxonomy Meta Box
     */
    public function remove_taxonomy_meta_box() {
        remove_meta_box('product_ymmdiv', 'product', 'side');
    }

    /**
     * Template: Replace Default Taxonomy Meta Box with Custom Meta Box
     */
    public function add_taxonomy_meta_box() {
        $this->remove_taxonomy_meta_box();

        add_meta_box('product_custom_ymmdiv', sprintf('%s  <a href="javascript:" class="woo-vpf-ymm-add-term-row">' . __('Add', YMMP_FILTER_TEXT_DOMAIN) . '</a>', __('Year/make/model/option Terms', YMMP_FILTER_TEXT_DOMAIN)), array($this, 'taxonomy_meta_box'), 'product', 'normal');
    }

    /**
     * Template: Add Custom Meta Box with Ajaxify Rows
     */
    public function taxonomy_meta_box($post) {
        wp_nonce_field('taxonomy_product_ymm', 'product_ymm_taxonomy_noncename');

        $label_year = YMMP_FILTER_Functions::get_year_label();
        $label_make = YMMP_FILTER_Functions::get_make_label();
        $label_model = YMMP_FILTER_Functions::get_model_label();
        $label_engine = YMMP_FILTER_Functions::get_engine_label();

        // Is Universal?
        $_universal = false;
        if (!empty($post) && isset($post->ID)) {
            $woo_vpf_ymm_taxonomy_metabox_excluded_products = WC_Admin_Settings::get_option('woo_vpf_ymm_taxonomy_metabox_excluded_products');
            if (!empty($woo_vpf_ymm_taxonomy_metabox_excluded_products)) {

                if (!is_array($woo_vpf_ymm_taxonomy_metabox_excluded_products)) {
                    $woo_vpf_ymm_taxonomy_metabox_excluded_products = explode(',', $woo_vpf_ymm_taxonomy_metabox_excluded_products);
                }

                if (in_array($post->ID, $woo_vpf_ymm_taxonomy_metabox_excluded_products)) {
                    $_universal = true;
                }
            }
        }
        ?><div class="woo-vpf-ymm-form-container">

            <table class="form-table">
                <tbody>
                    <tr>
                        <td>
<!--                            <label for="vpf_ymm_universal"><?php _e('Universal Product?', YMMP_FILTER_TEXT_DOMAIN); ?></label>
                            <input type="checkbox" name="vpf_ymm_universal" class="vpf_ymm_universal" id="vpf_ymm_universal" value="1" <?php
                            if ($_universal) {
                                echo 'checked';
                            }
                            ?> />-->
                        </td>

                        <td>
                            <a href="javascript:" class="button button-primary woo-vpf-ymm-remove-term-rows"><?php _e('Delete All Terms', YMMP_FILTER_TEXT_DOMAIN); ?></a>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="woo-vpf-ymm-terms-table-container">
                <table class="form-table woo-vpf-ymm-form-table">
                    <thead>
                        <tr>
                            <th scope="row"><?php echo $label_year; ?></th>
                            <th scope="row"><?php echo $label_make; ?></th>
                            <th scope="row"><?php echo $label_model; ?></th>
                            <th scope="row"><?php echo $label_engine; ?></th>
                            <th scope="row" class="woo-vpf-ymm-text-center"><?php _e('Actions', YMMP_FILTER_TEXT_DOMAIN); ?></th>
                        </tr>
                    </thead>

                    <tfoot>
                        <tr>
                            <th scope="row"><?php echo $label_year; ?></th>
                            <th scope="row"><?php echo $label_make; ?></th>
                            <th scope="row"><?php echo $label_model; ?></th>
                            <th scope="row"><?php echo $label_engine; ?></th>
                            <th scope="row" class="woo-vpf-ymm-text-center"><?php _e('Actions', YMMP_FILTER_TEXT_DOMAIN); ?></th>
                        </tr>
                    </tfoot>

                    <tbody>
                        <?php
                        $has_rows = false;

                        if (!empty($post) && isset($post->ID)) {

                            list( $post_terms, $years, $makes, $models, $engines ) = YMMP_FILTER_Functions::wp_get_post_terms_hierarchy($post->ID);

                            if (!empty($post_terms)) {

                                $terms = array();
                                $i = 0;

                                if (isset($post_terms['years']) && !empty($post_terms['years'])) {
                                    $vpf_years = $post_terms['years'];
                                    foreach ($vpf_years as $vpf_year) {
                                        $year_id = $vpf_year['term']->term_id;
                                        $year_name = $vpf_year['term']->name;

                                        if (isset($vpf_year['makes']) && !empty($vpf_year['makes'])) {
                                            $vpf_makes = $vpf_year['makes'];
                                            foreach ($vpf_makes as $vpf_make) {
                                                $make_id = $vpf_make['term']->term_id;
                                                $make_name = $vpf_make['term']->name;

                                                if (isset($vpf_make['models']) && !empty($vpf_make['models'])) {
                                                    $vpf_models = $vpf_make['models'];
                                                    foreach ($vpf_models as $vpf_model) {
                                                        $model_id = $vpf_model['term']->term_id;
                                                        $model_name = $vpf_model['term']->name;

                                                        if (isset($vpf_model['engines']) && !empty($vpf_model['engines'])) {
                                                            $vpf_engines = $vpf_model['engines'];
                                                            foreach ($vpf_engines as $vpf_engine) {
                                                                $engine_id = $vpf_engine['term']->term_id;
                                                                $engine_name = $vpf_engine['term']->name;

                                                                $terms[$i]['year'] = array('id' => $year_id, 'name' => $year_name);
                                                                $terms[$i]['make'] = array('id' => $make_id, 'name' => $make_name);
                                                                $terms[$i]['model'] = array('id' => $model_id, 'name' => $model_name);
                                                                $terms[$i]['engine'] = array('id' => $engine_id, 'name' => $engine_name);

                                                                $i++;
                                                            }
                                                        } else {
                                                            $terms[$i]['year'] = array('id' => $year_id, 'name' => $year_name);
                                                            $terms[$i]['make'] = array('id' => $make_id, 'name' => $make_name);
                                                            $terms[$i]['model'] = array('id' => $model_id, 'name' => $model_name);
                                                            $terms[$i]['engine'] = '';

                                                            $i++;
                                                        }
                                                    }
                                                } else {
                                                    $terms[$i]['year'] = array('id' => $year_id, 'name' => $year_name);
                                                    $terms[$i]['make'] = array('id' => $make_id, 'name' => $make_name);
                                                    $terms[$i]['model'] = '';
                                                    $terms[$i]['engine'] = '';

                                                    $i++;
                                                }
                                            }
                                        } else {
                                            $terms[$i]['year'] = array('id' => $year_id, 'name' => $year_name);
                                            $terms[$i]['make'] = '';
                                            $terms[$i]['model'] = '';
                                            $terms[$i]['engine'] = '';

                                            $i++;
                                        }
                                    }
                                }

                                $terms = YMMP_FILTER_Functions::sort_terms($terms);
                                $post_terms = $terms;

                                if (!empty($post_terms)) {
                                    $has_rows = true;

                                    foreach ($post_terms as $post_term) {
                                        $this->taxonomy_meta_box_row($post_term);
                                    }
                                }
                            }

                            if (!$has_rows) {
                                $this->taxonomy_meta_box_row();
                            }

                            $this->taxonomy_meta_box_row('', array('woo-vpf-ymm-hidden'));
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div><?php
    }

    /**
     * Template: Custom Meta Box Row with Ajaxify Rows
     */
    public function taxonomy_meta_box_row($post_term = array(), $classes = array()) {
        ?><tr class="woo-vpf-ymm-terms-row <?php echo ( empty($post_term) ? 'woo-vpf-ymm-terms-row-add' : 'woo-vpf-ymm-terms-row-edit' ); ?> <?php
        if (!empty($classes)) {
            echo implode(' ', $classes);
        }
        ?>">
            <td>
                <select name="woo_vpf_ymm[]" class="woo_vpf_ymm_year">
                    <option value=""><?php _e('-- Select --', YMMP_FILTER_TEXT_DOMAIN); ?></option>

                    <?php YMMP_FILTER_Functions::get_terms_list(0, (!empty($post_term) && isset($post_term['year']['id']) ) ? $post_term['year']['id'] : '' ); ?>
                </select>
            </td>

            <td>
                <select name="woo_vpf_ymm[]" class="woo_vpf_ymm_make">
                    <option value=""><?php _e('-- Select --', YMMP_FILTER_TEXT_DOMAIN); ?></option>

                    <?php
                    if (!empty($post_term) && isset($post_term['year']['id'])) {
                        YMMP_FILTER_Functions::get_terms_list($post_term['year']['id'], isset($post_term['make']['id']) ? $post_term['make']['id'] : '' );
                    }
                    ?>
                </select>
            </td>

            <td>
                <select name="woo_vpf_ymm[]" class="woo_vpf_ymm_model">
                    <option value=""><?php _e('-- Select --', YMMP_FILTER_TEXT_DOMAIN); ?></option>

                    <?php
                    if (!empty($post_term) && isset($post_term['make']['id'])) {
                        YMMP_FILTER_Functions::get_terms_list($post_term['make']['id'], isset($post_term['model']['id']) ? $post_term['model']['id'] : '' );
                    }
                    ?>
                </select>
            </td>

            <td>
                <select name="woo_vpf_ymm[]" class="woo_vpf_ymm_engine" <?php
                if (empty($post_term)) {
                    echo 'multiple';
                }
                ?> >
                    <option value=""><?php _e('-- Select --', YMMP_FILTER_TEXT_DOMAIN); ?></option>

                    <?php
                    if (!empty($post_term) && isset($post_term['model']['id'])) {
                        YMMP_FILTER_Functions::get_terms_list($post_term['model']['id'], isset($post_term['engine']['id']) ? $post_term['engine']['id'] : '' );
                    }
                    ?>
                </select>
            </td>

            <td class="woo-vpf-ymm-term-actions woo-vpf-ymm-text-center">
                <a href="javascript:" class="woo-vpf-ymm-remove-term-row"><?php _e('Delete', YMMP_FILTER_TEXT_DOMAIN); ?></a>
            </td>
        </tr><?php
    }

    /**
     * Template: Save Custom Meta Box with Ajaxify Rows
     */
    public function save_taxonomy_meta_box($post_id) {

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            return;

        if (wp_is_post_revision($post_id))
            return;

        if (!isset($_POST['product_ymm_taxonomy_noncename']) || !wp_verify_nonce($_POST['product_ymm_taxonomy_noncename'], 'taxonomy_product_ymm'))
            return;

        // Save Field: Universal Product
        $_universal = '';

        if (isset($_REQUEST['vpf_ymm_universal'])) {
            $_universal = 'yes';
        } else {
            $_universal = 'no';
        }

        $woo_vpf_ymm_taxonomy_metabox_excluded_products = WC_Admin_Settings::get_option('woo_vpf_ymm_taxonomy_metabox_excluded_products');

        if (!empty($woo_vpf_ymm_taxonomy_metabox_excluded_products)) {
            if (!is_array($woo_vpf_ymm_taxonomy_metabox_excluded_products)) {
                $woo_vpf_ymm_taxonomy_metabox_excluded_products = explode(',', $woo_vpf_ymm_taxonomy_metabox_excluded_products);
            }
        } else {
            $woo_vpf_ymm_taxonomy_metabox_excluded_products = array();
        }

        if ($_universal == 'yes') {
            $woo_vpf_ymm_taxonomy_metabox_excluded_products[] = $post_id;
        } else {
            if (!empty($woo_vpf_ymm_taxonomy_metabox_excluded_products)) {
                if (( $key = array_search($post_id, $woo_vpf_ymm_taxonomy_metabox_excluded_products) ) !== false) {
                    unset($woo_vpf_ymm_taxonomy_metabox_excluded_products[$key]);
                }
            }
        }

        if (!empty($woo_vpf_ymm_taxonomy_metabox_excluded_products)) {
            $woo_vpf_ymm_taxonomy_metabox_excluded_products = array_unique($woo_vpf_ymm_taxonomy_metabox_excluded_products);
        }

        update_option('woo_vpf_ymm_taxonomy_metabox_excluded_products', $woo_vpf_ymm_taxonomy_metabox_excluded_products);

        // Save Field: Post Terms
        $terms = array();

        if (!empty($_POST['woo_vpf_ymm'])) {
            $terms = $_POST['woo_vpf_ymm'];

            $terms = array_map('intval', $terms);
            $terms = array_unique($terms);
        }

        wp_set_object_terms($post_id, $terms, 'product_ymm', false);
    }

}

new YMMP_FILTER_Taxonomy_Metabox_Template();
