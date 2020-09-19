<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://sunilprajapati.com/
 * @since      1.0.0
 *
 * @package    Ymmp_filter
 * @subpackage Ymmp_filter/includes
 */
/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Ymmp_filter
 * @subpackage Ymmp_filter/includes
 * @author     sunil prajapati <sdprajapati999@gmail.com>
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class YMMP_FILTER_Admin_Menu {

    /**
     * @var: ymmp_menu
     */
    public $ymmp_menu;

    /**
     * Constructor
     *
     * @access public
     */
    public function __construct() {
        // Admin Main Menu: VPF ( YMM )
        add_action('admin_menu', array($this, 'terms_main_menu'), 5);
    }

    /**
     * Admin Main Menu: VPF ( YMM )
     */
    public function terms_main_menu() {
//        add_menu_page(__('ymmp Filter', YMMP_FILTER_TEXT_DOMAIN), __('ymmp Filter', YMMP_FILTER_TEXT_DOMAIN), 'manage_woocommerce', 'ymmp_menu', array($this, 'ymmp_menu'), '', 58);

        $this->ymmp_menu = add_submenu_page('ymmp_menu', __('Terms', YMMP_FILTER_TEXT_DOMAIN), __('Terms', YMMP_FILTER_TEXT_DOMAIN), 'manage_woocommerce', 'ymmp_menu', array($this, 'ymmp_menu'));

        add_action('admin_menu', array($this, 'vpf_ymm_direct_menus'));
    }

    /**
     * Admin Sub Menu: Years
     */
    public function ymmp_menu() {
        return;
    }

    /**
     * Admin Sub Menu: Direct Menus ( Export AND Settings )
     */
    public function vpf_ymm_direct_menus() {
        global $submenu;

        if (!isset($submenu['ymmp_menu'])) {
            return;
        }

        unset($submenu['ymmp_menu'][0]);

        $submenu['ymmp_menu'][] = array(__('Terms', YMMP_FILTER_TEXT_DOMAIN), 'manage_woocommerce', 'edit-tags.php?taxonomy=product_ymm&post_type=product');

        $submenu['ymmp_menu'][] = array(__('Import CSV', YMMP_FILTER_TEXT_DOMAIN), 'manage_woocommerce', 'admin.php?import=woo_product_ymm_importer');

        $submenu['ymmp_menu'][] = array(__('Settings', YMMP_FILTER_TEXT_DOMAIN), 'manage_woocommerce', 'admin.php?page=wc-settings&tab=woo_vpf_ymm');
    }

}

new YMMP_FILTER_Admin_Menu();
