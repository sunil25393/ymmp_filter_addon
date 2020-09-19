<?php

/**
 * Main YMMP_FILTER_Ajax Class
 *
 * @class YMMP_FILTER_Ajax
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class YMMP_FILTER_Ajax {

    /**
     * Hook in ajax handlers
     */
    public static function init() {
        self::add_ajax_events();
    }

    /**
     * Hook in methods - uses WordPress ajax handlers (admin-ajax)
     */
    public static function add_ajax_events() {
        // YMMP_FILTER_EVENT => nopriv
        $ajax_events = array(
            'get_terms' => true,
            'get_terms_frontend' => true,
            'get_terms_war' => true,
            'get_terms_war_frontend' => true,
            'reset_search' => true
        );

        foreach ($ajax_events as $ajax_event => $nopriv) {
            add_action('wp_ajax_woo_vpf_ymm_' . $ajax_event, array(__CLASS__, $ajax_event));

            if ($nopriv) {
                add_action('wp_ajax_nopriv_woo_vpf_ymm_' . $ajax_event, array(__CLASS__, $ajax_event));
            }
        }
    }

    /**
     * AJAX - Get Terms
     */
    public static function get_terms() {
        if (isset($_REQUEST['parent']) && $_REQUEST['parent'] > 0) {
            ob_start();
            YMMP_FILTER_Functions::get_terms_list($_REQUEST['parent'], '', $_REQUEST['input_name']);
            echo ob_get_clean();
        }

        die();
    }

    /**
     * AJAX - Get Terms
     */
    public static function get_terms_frontend() {
        if (isset($_REQUEST['parent']) && $_REQUEST['parent'] > 0) {
            ob_start();
            YMMP_FILTER_Functions::get_terms_list_frontend($_REQUEST['parent'], '', $_REQUEST['input_name']);
            echo ob_get_clean();
        }

        die();
    }

    /**
     * AJAX - Get Terms
     */
    public static function get_terms_war() {
        if (isset($_REQUEST['parent']) && $_REQUEST['parent'] > 0) {
            ob_start();
            WAR_FILTER_Functions::get_terms_list($_REQUEST['parent'], '', $_REQUEST['input_name']);
            echo ob_get_clean();
        }

        die();
    }

    /**
     * AJAX - Get Terms
     */
    public static function get_terms_war_frontend() {
        if (isset($_REQUEST['parent']) && $_REQUEST['parent'] > 0) {
            ob_start();
            WAR_FILTER_Functions::get_terms_list_frontend($_REQUEST['parent'], '', $_REQUEST['input_name']);
            echo ob_get_clean();
        }

        die();
    }

    /**
     * AJAX - Clear/Reset User Search if Rememberd
     */
    public static function reset_search() {
        if (!session_id()) {
            session_start();
        }

        if (isset($_SESSION['vpf_ymm']['search'])) {
            unset($_SESSION['vpf_ymm']['search']);
        }

        die();
    }

}

YMMP_FILTER_Ajax::init();
