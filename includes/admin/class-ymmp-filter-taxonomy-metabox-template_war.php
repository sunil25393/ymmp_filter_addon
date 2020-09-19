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
class YMMP_FILTER_Taxonomy_Metabox_Template_war {

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
        $woo_vpf_war_taxonomy_metabox_template = 'default';

        // Taxonomy Terms Hierarchy
        if ($woo_vpf_war_taxonomy_metabox_template == 'default' || $woo_vpf_war_taxonomy_metabox_template == 'tree_view') {
            add_filter('wp_terms_checklist_args', array($this, 'terms_hierarchy'));
        }

        // Taxonomy Template
//        if ($woo_vpf_war_taxonomy_metabox_template == 'tree_view') {
//            add_action('admin_head-post-new.php', array($this, 'template_tree'));
//            add_action('admin_head-post.php', array($this, 'template_tree'));
//        } else if ($woo_vpf_war_taxonomy_metabox_template == 'ajaxify_meta_box') {
        add_action('add_meta_boxes', array($this, 'add_taxonomy_meta_box'));
        add_action('save_post', array($this, 'save_taxonomy_meta_box'));
//        }
    }

    /**
     * Template: Default / Tree View ( Taxonomy Terms Hierarchy )
     */
    public function terms_hierarchy($args) {
        if (is_admin()) {
            if (isset($args['taxonomy']) && $args['taxonomy'] == 'product_war') {
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
            #product_war-tabs .hide-if-no-js {
                display:none;
            }

            #product_warchecklist .expand {
                font-weight:bold;
                margin:0 0 0 5px;
                padding:0 3px;
            }
        </style>

        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#product_warchecklist ul.children').hide().parent().children('label').append('<a class="expand">+</a>');

                $('#product_warchecklist .expand').click(function (e) {
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
        remove_meta_box('product_war', 'product', 'side');
    }

    /**
     * Template: Replace Default Taxonomy Meta Box with Custom Meta Box
     */
    public function add_taxonomy_meta_box() {
        $this->remove_taxonomy_meta_box();

        add_meta_box('product_custom_wardiv', sprintf('%s  <a href="javascript:" class="woo-vpf-ymm-add-term-row">' . __('Add', YMMP_FILTER_TEXT_DOMAIN) . '</a>', __('War/Aspect/Rim Terms', YMMP_FILTER_TEXT_DOMAIN)), array($this, 'taxonomy_meta_box'), 'product', 'normal');
    }

    /**
     * Template: Add Custom Meta Box with Ajaxify Rows
     */
    public function taxonomy_meta_box($post) {
        wp_nonce_field('taxonomy_product_war', 'product_war_taxonomy_noncename');

        $label_width = WAR_FILTER_Functions::get_width_label();
        $label_aspect = WAR_FILTER_Functions::get_aspect_label();
        $label_rim = WAR_FILTER_Functions::get_rim_label();


        // Is Universal?
        $_universal = false;
        if (!empty($post) && isset($post->ID)) {
            $woo_vpf_war_taxonomy_metabox_excluded_products = WC_Admin_Settings::get_option('woo_vpf_war_taxonomy_metabox_excluded_products');
            if (!empty($woo_vpf_war_taxonomy_metabox_excluded_products)) {

                if (!is_array($woo_vpf_war_taxonomy_metabox_excluded_products)) {
                    $woo_vpf_war_taxonomy_metabox_excluded_products = explode(',', $woo_vpf_war_taxonomy_metabox_excluded_products);
                }

                if (in_array($post->ID, $woo_vpf_war_taxonomy_metabox_excluded_products)) {
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
                            <th scope="row"><?php echo $label_width; ?></th>
                            <th scope="row"><?php echo $label_aspect; ?></th>
                            <th scope="row"><?php echo $label_rim; ?></th>

                            <th scope="row" class="woo-vpf-ymm-text-center"><?php _e('Actions', YMMP_FILTER_TEXT_DOMAIN); ?></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th scope="row"><?php echo $label_width; ?></th>
                            <th scope="row"><?php echo $label_aspect; ?></th>
                            <th scope="row"><?php echo $label_rim; ?></th>

                            <th scope="row" class="woo-vpf-ymm-text-center"><?php _e('Actions', YMMP_FILTER_TEXT_DOMAIN); ?></th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php
                        $has_rows = false;

                        if (!empty($post) && isset($post->ID)) {

                            list( $post_terms, $widths, $aspects, $rims, $engines ) = WAR_FILTER_Functions::wp_get_post_terms_hierarchy($post->ID);

                            if (!empty($post_terms)) {

                                $terms = array();
                                $i = 0;

                                if (isset($post_terms['widths']) && !empty($post_terms['widths'])) {
                                    $vpf_widths = $post_terms['widths'];
                                    foreach ($vpf_widths as $vpf_width) {
                                        $width_id = $vpf_width['term']->term_id;
                                        $width_name = $vpf_width['term']->name;

                                        if (isset($vpf_width['aspects']) && !empty($vpf_width['aspects'])) {
                                            $vpf_aspects = $vpf_width['aspects'];
                                            foreach ($vpf_aspects as $vpf_aspect) {
                                                $aspect_id = $vpf_aspect['term']->term_id;
                                                $aspect_name = $vpf_aspect['term']->name;

                                                if (isset($vpf_aspect['rims']) && !empty($vpf_aspect['rims'])) {
                                                    $vpf_rims = $vpf_aspect['rims'];
                                                    foreach ($vpf_rims as $vpf_rim) {
                                                        $rim_id = $vpf_rim['term']->term_id;
                                                        $rim_name = $vpf_rim['term']->name;

                                                        if (isset($vpf_rim['engines']) && !empty($vpf_rim['engines'])) {
                                                            $vpf_engines = $vpf_rim['engines'];
                                                            foreach ($vpf_engines as $vpf_engine) {
                                                                $engine_id = $vpf_engine['term']->term_id;
                                                                $engine_name = $vpf_engine['term']->name;

                                                                $terms[$i]['width'] = array('id' => $width_id, 'name' => $width_name);
                                                                $terms[$i]['aspect'] = array('id' => $aspect_id, 'name' => $aspect_name);
                                                                $terms[$i]['rim'] = array('id' => $rim_id, 'name' => $rim_name);
                                                                $terms[$i]['engine'] = array('id' => $engine_id, 'name' => $engine_name);

                                                                $i++;
                                                            }
                                                        } else {
                                                            $terms[$i]['width'] = array('id' => $width_id, 'name' => $width_name);
                                                            $terms[$i]['aspect'] = array('id' => $aspect_id, 'name' => $aspect_name);
                                                            $terms[$i]['rim'] = array('id' => $rim_id, 'name' => $rim_name);
                                                            $terms[$i]['engine'] = '';

                                                            $i++;
                                                        }
                                                    }
                                                } else {
                                                    $terms[$i]['width'] = array('id' => $width_id, 'name' => $width_name);
                                                    $terms[$i]['aspect'] = array('id' => $aspect_id, 'name' => $aspect_name);
                                                    $terms[$i]['rim'] = '';
                                                    $terms[$i]['engine'] = '';

                                                    $i++;
                                                }
                                            }
                                        } else {
                                            $terms[$i]['width'] = array('id' => $width_id, 'name' => $width_name);
                                            $terms[$i]['aspect'] = '';
                                            $terms[$i]['rim'] = '';
                                            $terms[$i]['engine'] = '';

                                            $i++;
                                        }
                                    }
                                }

                                $terms = WAR_FILTER_Functions::sort_terms($terms);
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
                <select name="woo_vpf_war[]" class="woo_vpf_war_width">
                    <option value=""><?php _e('-- Select --', YMMP_FILTER_TEXT_DOMAIN); ?></option>

                    <?php WAR_FILTER_Functions::get_terms_list(0, (!empty($post_term) && isset($post_term['width']['id']) ) ? $post_term['width']['id'] : '' ); ?>
                </select>
            </td>

            <td>
                <select name="woo_vpf_war[]" class="woo_vpf_war_aspect">
                    <option value=""><?php _e('-- Select --', YMMP_FILTER_TEXT_DOMAIN); ?></option>

                    <?php
                    if (!empty($post_term) && isset($post_term['width']['id'])) {
                        WAR_FILTER_Functions::get_terms_list($post_term['width']['id'], isset($post_term['aspect']['id']) ? $post_term['aspect']['id'] : '' );
                    }
                    ?>
                </select>
            </td>

            <td>
                <select name="woo_vpf_war[]" class="woo_vpf_war_rim">
                    <option value=""><?php _e('-- Select --', YMMP_FILTER_TEXT_DOMAIN); ?></option>

                    <?php
                    if (!empty($post_term) && isset($post_term['aspect']['id'])) {
                        WAR_FILTER_Functions::get_terms_list($post_term['aspect']['id'], isset($post_term['rim']['id']) ? $post_term['rim']['id'] : '' );
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

        if (!isset($_POST['product_war_taxonomy_noncename']) || !wp_verify_nonce($_POST['product_war_taxonomy_noncename'], 'taxonomy_product_war'))
            return;

        // Save Field: Universal Product
        $_universal = '';

        if (isset($_REQUEST['vpf_ymm_universal'])) {
            $_universal = 'yes';
        } else {
            $_universal = 'no';
        }

        $woo_vpf_war_taxonomy_metabox_excluded_products = WC_Admin_Settings::get_option('woo_vpf_war_taxonomy_metabox_excluded_products');

        if (!empty($woo_vpf_war_taxonomy_metabox_excluded_products)) {
            if (!is_array($woo_vpf_war_taxonomy_metabox_excluded_products)) {
                $woo_vpf_war_taxonomy_metabox_excluded_products = explode(',', $woo_vpf_war_taxonomy_metabox_excluded_products);
            }
        } else {
            $woo_vpf_war_taxonomy_metabox_excluded_products = array();
        }

        if ($_universal == 'yes') {
            $woo_vpf_war_taxonomy_metabox_excluded_products[] = $post_id;
        } else {
            if (!empty($woo_vpf_war_taxonomy_metabox_excluded_products)) {
                if (( $key = array_search($post_id, $woo_vpf_war_taxonomy_metabox_excluded_products) ) !== false) {
                    unset($woo_vpf_war_taxonomy_metabox_excluded_products[$key]);
                }
            }
        }

        if (!empty($woo_vpf_war_taxonomy_metabox_excluded_products)) {
            $woo_vpf_war_taxonomy_metabox_excluded_products = array_unique($woo_vpf_war_taxonomy_metabox_excluded_products);
        }

        update_option('woo_vpf_war_taxonomy_metabox_excluded_products', $woo_vpf_war_taxonomy_metabox_excluded_products);

        // Save Field: Post Terms
        $terms = array();

        if (!empty($_POST['woo_vpf_war'])) {
            $terms = $_POST['woo_vpf_war'];

            $terms = array_map('intval', $terms);
            $terms = array_unique($terms);
        }

        wp_set_object_terms($post_id, $terms, 'product_war', false);
    }

}

new YMMP_FILTER_Taxonomy_Metabox_Template_war();
