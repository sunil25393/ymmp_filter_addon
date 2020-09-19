<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://sunilprajapati.com/
 * @since             1.0.0
 * @package           Ymmp_Filter
 *
 * @wordpress-plugin
 * Plugin Name:       YMM Woocommerce Product Filter
 * Plugin URI:        http://sunilprajapati.com/
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            sunil prajapati
 * Author URI:        http://sunilprajapati.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ymmp-filter
 * Domain Path:       /languages
 */
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('YMMP_FILTER_VERSION', '1.0.0');
define('YMMP_FILTER_PLUGIN_FILE', __FILE__);
define('YMMP_FILTER_PLUGIN_BASENAME', plugin_basename(__FILE__));
define('YMMP_FILTER_TEXT_DOMAIN', 'woo_ymmp_filter');
define('YMMP_FILTER_PLUGIN_URL', untrailingslashit(plugins_url('/', __FILE__)));

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-ymmp-filter-activator.php
 */
function activate_ymmp_filter() {
    require_once plugin_dir_path(__FILE__) . 'includes/class-ymmp-filter-activator.php';
    Ymmp_Filter_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-ymmp-filter-deactivator.php
 */
function deactivate_ymmp_filter() {
    require_once plugin_dir_path(__FILE__) . 'includes/class-ymmp-filter-deactivator.php';
    Ymmp_Filter_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_ymmp_filter');
register_deactivation_hook(__FILE__, 'deactivate_ymmp_filter');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-ymmp-filter.php';


include_once('includes/fontend/class-ymmp-filter-functions.php');
include_once('includes/fontend/class-ymmp-filter-functions_war.php');
include_once('includes/admin/class-ymmp-filter-taxonomies.php');
include_once('includes/fontend/class-ymmp-filter-ajax.php');
include_once('includes/fontend/class-ymmp-filter-hooks.php');
include_once('includes/fontend/class-ymmp-filter-hooks_war.php');

if (is_admin()) {
    include_once( 'includes/admin/class-ymmp-filter-admin-menu.php' );
    include_once( 'includes/admin/class-ymmp-filter-taxonomy-terms-list.php' );
    include_once( 'includes/admin/class-ymmp-filter-taxonomy-war-terms-list.php' );
    include_once( 'includes/admin/class-ymmp-filter-taxonomy-metabox-template.php' );
    include_once( 'includes/admin/class-ymmp-filter-taxonomy-metabox-template_war.php' );
}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_ymmp_filter() {

    $plugin = new Ymmp_Filter();
    $plugin->run();
}

run_ymmp_filter();
