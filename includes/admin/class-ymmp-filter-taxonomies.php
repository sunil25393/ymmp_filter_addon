<?php

/**
 * Taxonomies
 *
 * Registers Taxonomies
 *
 * @class YMMP_FILTER_Taxonomies
 */
if (!defined('ABSPATH')) {
    exit;
}

/**
 * YMMP_FILTER_Taxonomies Class
 */
class YMMP_FILTER_Taxonomies {

    /**
     * Constructor
     *
     * @access public
     */
    public function __construct() {
        // Init based hooks
        add_action('init', array(__CLASS__, 'register_taxonomies'), 7);
    }

    /**
     * Register Taxonomies
     */
    public static function register_taxonomies() {
        if (taxonomy_exists('product_ymm')) {
            return;
        }

        $woo_vpf_ymm_activate_quick_edit_terms = WC_Admin_Settings::get_option('woo_vpf_ymm_activate_quick_edit_terms');
        $woo_vpf_ymm_taxonomy_metabox_template = WC_Admin_Settings::get_option('woo_vpf_ymm_taxonomy_metabox_template');

        $args = array(
            'hierarchical' => true,
            'label' => __('YMMa  Term', YMMP_FILTER_TEXT_DOMAIN),
            'labels' => array(
                'name' => __('Year/Make/Model/Option Terms', YMMP_FILTER_TEXT_DOMAIN),
                'singular_name' => __('YMM Term', YMMP_FILTER_TEXT_DOMAIN),
                'menu_name' => _x('YMM Terms', 'Admin menu name', YMMP_FILTER_TEXT_DOMAIN),
                'search_items' => __('Search Terms', YMMP_FILTER_TEXT_DOMAIN),
                'all_items' => __('All Terms', YMMP_FILTER_TEXT_DOMAIN),
                'parent_item' => __('Parent Term', YMMP_FILTER_TEXT_DOMAIN),
                'parent_item_colon' => __('Parent Term:', YMMP_FILTER_TEXT_DOMAIN),
                'edit_item' => __('Edit Term', YMMP_FILTER_TEXT_DOMAIN),
                'update_item' => __('Update Term', YMMP_FILTER_TEXT_DOMAIN),
                'add_new_item' => __('Add New Term', YMMP_FILTER_TEXT_DOMAIN),
                'new_item_name' => __('New Term', YMMP_FILTER_TEXT_DOMAIN),
                'not_found' => __('No terms found.', YMMP_FILTER_TEXT_DOMAIN),
            ),
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => false,
            'show_in_quick_edit' => ( $woo_vpf_ymm_activate_quick_edit_terms == 'yes' ? true : false ),
            'query_var' => true,
            'capabilities' => array(
                'manage_terms' => 'manage_product_terms',
                'edit_terms' => 'edit_product_terms',
                'delete_terms' => 'delete_product_terms',
                'assign_terms' => 'assign_product_terms',
            ),
            'rewrite' => false,
        );

        if ($woo_vpf_ymm_taxonomy_metabox_template != 'default' && $woo_vpf_ymm_taxonomy_metabox_template != 'tree_view') {
            $args['meta_box_cb'] = false;
        }

        register_taxonomy('product_ymm', 'product', apply_filters('woo_vpf_ymm_register_taxonomy_args', $args));

        $args = array(
            'hierarchical' => true,
            'label' => __('WAR Term', YMMP_FILTER_TEXT_DOMAIN),
            'labels' => array(
                'name' => __('Widht/Aspect/Rim Terms', YMMP_FILTER_TEXT_DOMAIN),
                'singular_name' => __('WAR Term', YMMP_FILTER_TEXT_DOMAIN),
                'menu_name' => _x('WAR Terms', 'Admin menu name', YMMP_FILTER_TEXT_DOMAIN),
                'search_items' => __('Search Terms', YMMP_FILTER_TEXT_DOMAIN),
                'all_items' => __('All Terms', YMMP_FILTER_TEXT_DOMAIN),
                'parent_item' => __('Parent Term', YMMP_FILTER_TEXT_DOMAIN),
                'parent_item_colon' => __('Parent Term:', YMMP_FILTER_TEXT_DOMAIN),
                'edit_item' => __('Edit Term', YMMP_FILTER_TEXT_DOMAIN),
                'update_item' => __('Update Term', YMMP_FILTER_TEXT_DOMAIN),
                'add_new_item' => __('Add New Term', YMMP_FILTER_TEXT_DOMAIN),
                'new_item_name' => __('New Term', YMMP_FILTER_TEXT_DOMAIN),
                'not_found' => __('No terms found.', YMMP_FILTER_TEXT_DOMAIN),
            ),
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => false,
            'show_in_quick_edit' => ( $woo_vpf_ymm_activate_quick_edit_terms == 'yes' ? true : false ),
            'query_var' => true,
            'capabilities' => array(
                'manage_terms' => 'manage_product_terms',
                'edit_terms' => 'edit_product_terms',
                'delete_terms' => 'delete_product_terms',
                'assign_terms' => 'assign_product_terms',
            ),
            'rewrite' => false,
        );

        if ($woo_vpf_ymm_taxonomy_metabox_template != 'default' && $woo_vpf_ymm_taxonomy_metabox_template != 'tree_view') {
            $args['meta_box_cb'] = false;
        }

        register_taxonomy('product_war', 'product', apply_filters('woo_vpf_ymm_register_taxonomy_args', $args));
    }

}

new YMMP_FILTER_Taxonomies();
