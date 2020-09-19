<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://sunilprajapati.com/
 * @since      1.0.0
 *
 * @package    Ymmp_Filter
 * @subpackage Ymmp_Filter/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Ymmp_Filter
 * @subpackage Ymmp_Filter/admin
 * @author     sunil prajapati <sdprajapati999@gmail.com>
 */
class Ymmp_Filter_Admin {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Ymmp_Filter_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Ymmp_Filter_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/ymmp-filter-admin.css', array(), $this->version, 'all');

        wp_register_style('woo_vpf_ymm_admin_style', YMMP_FILTER_PLUGIN_URL . '/assets/css/admin/style.css');

        // Register Scripts
        wp_register_script('woo_vpf_ymm_admin_settings_script', YMMP_FILTER_PLUGIN_URL . '/assets/js/admin/settings.js', array('jquery'));

        wp_register_script('woo_vpf_ymm_admin_terms_droplist_script', YMMP_FILTER_PLUGIN_URL . '/assets/js/admin/terms_droplist.js', array('jquery'));
        wp_localize_script('woo_vpf_ymm_admin_terms_droplist_script', 'woo_vpf_ymm_tdl_params', array(
            'ajax_url' => admin_url('admin-ajax.php')
        ));


        wp_register_script('woo_vpf_ymm_admin_terms_droplist_script_war', YMMP_FILTER_PLUGIN_URL . '/assets/js/admin/terms_droplist_war.js', array('jquery'));
        wp_localize_script('woo_vpf_ymm_admin_terms_droplist_script_war', 'woo_vpf_ymm_tdl_params', array(
            'ajax_url' => admin_url('admin-ajax.php')
        ));

        wp_register_script('woo_vpf_ymm_admin_terms_meta_box_script', YMMP_FILTER_PLUGIN_URL . '/assets/js/admin/terms_meta_box.js', array('jquery'));
        wp_localize_script('woo_vpf_ymm_admin_terms_meta_box_script', 'woo_vpf_ymm_tmb_params', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'msg_confirm' => __('Are you sure?', YMMP_FILTER_TEXT_DOMAIN),
        ));

        wp_register_script('woo_vpf_ymm_admin_terms_list_script', YMMP_FILTER_PLUGIN_URL . '/assets/js/admin/terms_list.js', array('jquery'));
        wp_localize_script('woo_vpf_ymm_admin_terms_list_script', 'woo_vpf_ymm_tl_params', array(
            'msg_delete_all_confirm' => __('Warning! Are you sure you want to delete all VPF ( YMM ) Terms?', YMMP_FILTER_TEXT_DOMAIN),
        ));

        global $pagenow;

        if ($pagenow == 'admin.php') {
            if (( isset($_REQUEST['page']) && $_REQUEST['page'] == 'wc-settings' ) && ( isset($_REQUEST['tab']) && $_REQUEST['tab'] == 'woo_vpf_ymm' )) {
                wp_enqueue_style('woo_vpf_ymm_admin_style');
                wp_enqueue_script('woo_vpf_ymm_admin_settings_script');
            }
        }

        if ($pagenow == 'post.php' || $pagenow == 'post-new.php') {
            $woo_vpf_ymm_taxonomy_metabox_template = WC_Admin_Settings::get_option('woo_vpf_ymm_taxonomy_metabox_template');
            if ($woo_vpf_ymm_taxonomy_metabox_template == 'ajaxify_meta_box') {
                wp_enqueue_style('woo_vpf_ymm_admin_style');
                wp_enqueue_script('woo_vpf_ymm_admin_terms_droplist_script');
                wp_enqueue_script('woo_vpf_ymm_admin_terms_droplist_script_war');
                wp_enqueue_script('woo_vpf_ymm_admin_terms_meta_box_script');
            }
        }

        if ($pagenow == 'edit-tags.php' || $pagenow == 'term.php') {
            if (isset($_REQUEST['taxonomy']) && $_REQUEST['taxonomy'] == 'product_ymm') {
                wp_enqueue_style('woo_vpf_ymm_admin_style');
                wp_enqueue_script('woo_vpf_ymm_admin_terms_droplist_script');
                wp_enqueue_script('woo_vpf_ymm_admin_terms_droplist_script_war');
                wp_enqueue_script('woo_vpf_ymm_admin_terms_list_script');
            }
        }

        // Product Quick Edit

        $screen = get_current_screen();
        $screen_id = $screen ? $screen->id : '';

        if (in_array($screen_id, array('edit-product'))) {
            wp_register_script('woo_vpf_ymm_admin_quick_edit', YMMP_FILTER_PLUGIN_URL . '/assets/js/admin/quick_edit.js', array('jquery'));
            wp_enqueue_script('woo_vpf_ymm_admin_quick_edit');
        }
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Ymmp_Filter_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Ymmp_Filter_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/ymmp-filter-admin.js', array('jquery'), $this->version, false);
    }

}
