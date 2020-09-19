<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://sunilprajapati.com/
 * @since      1.0.0
 *
 * @package    Ymmp_Filter
 * @subpackage Ymmp_Filter/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Ymmp_Filter
 * @subpackage Ymmp_Filter/public
 * @author     sunil prajapati <sdprajapati999@gmail.com>
 */
class Ymmp_Filter_Public {

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
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
        add_shortcode('ymmp_filter', array($this, 'reder_widget_filter'));
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
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
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/ymmp-filter-public.css', array(), $this->version, 'all');
        wp_enqueue_style('bootstrap-modal-css', plugin_dir_url(__FILE__) . 'css/bootstrap.min.css', array(), $this->version, 'all');
        wp_enqueue_style('bootstrap-theme-css', plugin_dir_url(__FILE__) . 'css/bootstrap-theme.min.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
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
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/ymmp-filter-public.js', array('jquery'), $this->version, false);
        wp_enqueue_script('bootstrap-modal-js', plugin_dir_url(__FILE__) . 'js/bootstrap.min.js', array('jquery'), $this->version, false);
        wp_enqueue_script('jquery-ui', plugin_dir_url(__FILE__) . 'js/jquery-ui.min.js', array('jquery'), false, true);



//        $ymmp_activate_chosen = WC_Admin_Settings::get_option('woo_vpf_ymm_activate_chosen');
//        if ($ymmp_activate_chosen == 'yes') {
//            wp_enqueue_style('woo_vpf_ymm_chosen_style', YMMP_FILTER_PLUGIN_URL . '/assets/css/chosen.min.css');
//            wp_enqueue_script('woo_vpf_ymm_chosen_script', YMMP_FILTER_PLUGIN_URL . '/assets/js/chosen.jquery.min.js', array('jquery'));
//        }

        wp_enqueue_style('woo_vpf_ymm_colorbox_style', YMMP_FILTER_PLUGIN_URL . '/assets/css/colorbox.css');
        wp_enqueue_script('woo_vpf_ymm_colorbox_script', YMMP_FILTER_PLUGIN_URL . '/assets/js/jquery.colorbox-min.js', array('jquery'));

        wp_enqueue_style('woo_vpf_ymm_front_style', YMMP_FILTER_PLUGIN_URL . '/assets/css/style.css');
        wp_enqueue_script('woo_vpf_ymm_front_script', YMMP_FILTER_PLUGIN_URL . '/assets/js/scripts.js', array('jquery'));
        wp_enqueue_script('woo_vpf_ymm_front_script_war', YMMP_FILTER_PLUGIN_URL . '/assets/js/scripts_war.js', array('jquery'));


        // Localize JS Params
        $woo_vpf_js_args = array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'is_chosen' => false
        );

        $js_args = array('disable_dependent_fields', 'activate_validation', 'validation_alert', 'validation_style', 'validate_year', 'validate_year_text', 'validate_make', 'validate_make_text', 'validate_model', 'validate_model_text', 'validate_engine', 'validate_engine_text', 'validate_category', 'validate_category_text', 'validate_keyword', 'validate_keyword_text');

        if (!empty($js_args)) {
            foreach ($js_args as $js_arg) {
                $woo_vpf_js_args[$js_arg] = WC_Admin_Settings::get_option('woo_vpf_ymm_' . $js_arg);
            }
        }

        $woo_vpf_ymm_args = apply_filters('woo_vpf_ymm_js_args', $woo_vpf_js_args);
        wp_localize_script('woo_vpf_ymm_front_script', 'woo_vpf_ymm_params', $woo_vpf_ymm_args);
    }

    public function get_years_list() {
        
    }

    /**
     * Output the html at the start of shortcode
     *
     * @param  array $args
     * @return string
     */
    public function shortcode_start($args) {
        echo apply_filters('woo_vpf_ymm_shortcode_filter_wrapper_start', '<div class="woo_vpf_ymm_filter_wrapper">');

        if (isset($args['title']) && $args['title'] != '') {
            echo apply_filters('woo_vpf_ymm_shortcode_filter_before_title', '<h2 class="woo_vpf_ymm_filter_title">') . $args['title'] . apply_filters('woo_vpf_ymm_shortcode_filter_after_title', '</h2>');
        }
    }

    /**
     * Output the html at the end of shortcode
     *
     * @param  array $args
     * @return string
     */
    public function shortcode_end($args) {
        echo apply_filters('woo_vpf_ymm_shortcode_filter_wrapper_end', '</div>');
    }

    public function reder_widget_filter($args) {
        ob_start();

        $this->shortcode_start($args);

        $args = shortcode_atts(array(
            'title' => __('Vehicle Parts Filter', YMMP_FILTER_TEXT_DOMAIN),
            'view' => 'V',
            'label_year' => __('Select Year', YMMP_FILTER_TEXT_DOMAIN),
            'label_make' => __('Select Make', YMMP_FILTER_TEXT_DOMAIN),
            'show_model' => '1',
            'label_model' => __('Select Model', YMMP_FILTER_TEXT_DOMAIN),
            'show_engine' => '',
            'label_engine' => __('Select Option', YMMP_FILTER_TEXT_DOMAIN),
            'show_category' => '',
            'label_category' => __('Select Category', YMMP_FILTER_TEXT_DOMAIN),
            'show_keyword' => '1',
            'label_keyword' => __('Product Name', YMMP_FILTER_TEXT_DOMAIN),
            'show_my_vehicles' => '',
            'label_search' => __('Search', YMMP_FILTER_TEXT_DOMAIN),
            'label_reset_search' => __('Reset Search', YMMP_FILTER_TEXT_DOMAIN),
                ), $args);

        $this->shortcode_end($args);
        ?> 
 
        <div id="search-widgets">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item active">
                    <a class="nav-link" id="vehicle-tab" data-toggle="tab" href="#search-by-vehicle" role="tab" aria-controls="vehicle" aria-selected="true">Shop Tires By <span>Vehicle</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="size-tab" data-toggle="tab" href="#search-by-size" role="tab" aria-controls="size" aria-selected="false">Shop Tires By <span>Size</span></a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="search-by-vehicle" role="tabpanel" aria-labelledby="vehicle-tab">
                    <div id="vehicle-widget">
                        <button type="button" class="btn btn-primary params" data-toggle="modal" data-target="#vehicle-search">
                            Year
                        </button>
                        <button type="button" class="btn btn-primary params disabled" data-toggle="modal" data-target="#vehicle-search"  disabled="">
                            Make
                        </button>
                        <button type="button" class="btn btn-primary params disabled" data-toggle="modal" data-target="#vehicle-search" disabled="">
                            Model
                        </button>
                        <button type="button" class="btn btn-primary params disabled" data-toggle="modal" data-target="#vehicle-search"  disabled="">
                            Option
                        </button> 

                        <div class="zips"> 
                            <a href="">Shop</a>
                        </div>
                        <div class="modal fade" id="vehicle-search" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
                            <div class="modal-dialog modal-dialog-scrollable" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">
                                            <span>Vehicle:</span>
                                            <span class="selection"> 
                                                <span class="year"></span>
                                                <span class="make"></span>
                                                <span class="model"></span>
                                                <span class="option"></span>
                                                <span class="zipcode"></span>
                                            </span>
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="tab-pane active" role="tabpanel" aria-labelledby="vehicle-tab">
                                            <div id="vehicle-parameters">


                                                <div class="widget-woo-vpf-ymm-filter <?php echo ( ( $args['view'] == 'H' ) ? 'woo-vpf-ymm-filter-horizontal' : 'woo-vpf-ymm-filter-vertical'); ?>">

                                                    <?php do_action('woo_vpf_ymm_before_filter_widget', $args); ?>

                                                    <form action="<?php echo esc_url(home_url('/')); ?>" method="get">
                                                        <?php do_action('woo_vpf_ymm_after_filter_form_start', $args); ?>
                                                        <ul class="nav nav-tabs" role="tablist">
                                                            <li class="nav-item active">
                                                                <a class="nav-link active" id="year-tab" data-toggle="tab" href="#year" >Year</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a  class="nav-link" id="make-tab" data-toggle="tab" href="#make">Make</a>
                                                            </li>
                                                            <li class="nav-item" >
                                                                <a  class="nav-link" id="model-tab" data-toggle="tab" href="#model">Model</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a  class="nav-link" id="option-tab" data-toggle="tab" href="#option">Option</a>
                                                            </li>
                                                            <li class="nav-item" >
                                                                <a class="nav-link" id="zip-tab" data-toggle="tab" href="#zip">Zip
                                                                    Code
                                                                </a>
                                                            </li>
                                                        </ul>
                                                        <div class="tab-content" id="vehicle-search-content">
                                                            <div class="tab-pane active" id="year" role="tabpanel" aria-labelledby="year-tab">
                                                                <div class="filter">
                                                                    <input  type="text" placeholder="Filter" id="yearfilter" onkeyup="filter_input_text('yearfilter', 'woo-vpf-ymm-field-year')">
                                                                    <p class="hidden">No results</p>
                                                                </div>
                                                                <div id="woo-vpf-ymm-field-year" class="woo-vpf-ymm-field woo-vpf-ymm-field-year">
                                                                    <?php $label_year = apply_filters('widget_woo_vpf_ymm_label_year', $args['label_year'], $args); ?>


                                                                    <?php
                                                                    $year_id = '';
                                                                    if (isset($_REQUEST['year_id'])) {
                                                                        $year_id = $_REQUEST['year_id'];
                                                                    } else if (isset($_SESSION['vpf_ymm']['search']) && isset($_SESSION['vpf_ymm']['search']['year_id'])) {
                                                                        $year_id = $_SESSION['vpf_ymm']['search']['year_id'];
                                                                    }

                                                                    YMMP_FILTER_Functions::get_terms_list_frontend(0, $year_id, 'year_id');
                                                                    ?>

                                                                </div>

                                                            </div>    
                                                            <div  class="tab-pane" id="make" role="tabpanel" aria-labelledby="make-tab">
                                                                <div class="filter">
                                                                    <input type="text" placeholder="Filter" id="makefilter" onkeyup="filter_input_text('makefilter', 'woo-vpf-ymm-field-make')">
                                                                    <p class="hidden">No results</p>
                                                                </div>
                                                                <div id="woo-vpf-ymm-field-make" class="woo-vpf-ymm-field woo-vpf-ymm-field-make">
                                                                    <?php $label_make = apply_filters('widget_woo_vpf_ymm_label_make', $args['label_make'], $args); ?>


                                                                    <?php
                                                                    if (isset($year_id) && $year_id > 0) {

                                                                        $make = '';
                                                                        if (isset($_REQUEST['make'])) {
                                                                            $make = $_REQUEST['make'];
                                                                        } else if (isset($_SESSION['vpf_ymm']['search']) && isset($_SESSION['vpf_ymm']['search']['make'])) {
                                                                            $make = $_SESSION['vpf_ymm']['search']['make'];
                                                                        }

                                                                        YMMP_FILTER_Functions::get_terms_list_frontend($year_id, $make, 'make');
                                                                    }
                                                                    ?>

                                                                </div>

                                                            </div>
                                                            <div class="tab-pane" id="model" role="tabpanel" aria-labelledby="model-tab">
                                                                <div class="filter">
                                                                    <input type="text" placeholder="Filter" id="modelfilter" onkeyup="filter_input_text('modelfilter', 'woo-vpf-ymm-field-model')">
                                                                    <p class="hidden">No results</p>
                                                                </div>
                                                                <div id="woo-vpf-ymm-field-model" class="woo-vpf-ymm-field woo-vpf-ymm-field-model">
                                                                    <?php $label_model = apply_filters('widget_woo_vpf_ymm_label_model', $args['label_model'], $args); ?>


                                                                    <?php
                                                                    if (isset($make) && $make > 0) {

                                                                        $model = '';
                                                                        if (isset($_REQUEST['model'])) {
                                                                            $model = $_REQUEST['model'];
                                                                        } else if (isset($_SESSION['vpf_ymm']['search']) && isset($_SESSION['vpf_ymm']['search']['model'])) {
                                                                            $model = $_SESSION['vpf_ymm']['search']['model'];
                                                                        }

                                                                        YMMP_FILTER_Functions::get_terms_list_frontend($make, $model, 'model');
                                                                    }
                                                                    ?>

                                                                </div>
                                                            </div>
                                                            <div class="tab-pane" id="option" role="tabpanel" aria-labelledby="option-tab">
                                                                <div class="filter">
                                                                    <input  type="text" placeholder="Filter" id="enginefilter" onkeyup="filter_input_text('enginefilter', 'woo-vpf-ymm-field-engine')">
                                                                    <p class="hidden">No results</p>
                                                                </div>
                                                                <div id="woo-vpf-ymm-field-engine" class="woo-vpf-ymm-field woo-vpf-ymm-field-engine">
                                                                    <?php $label_engine = apply_filters('widget_woo_vpf_ymm_label_engine', $args['label_engine'], $args); ?>

                                                                    <?php
                                                                    if (isset($model) && $model > 0) {

                                                                        $engine = '';
                                                                        if (isset($_REQUEST['engine'])) {
                                                                            $engine = $_REQUEST['engine'];
                                                                        } else if (isset($_SESSION['vpf_ymm']['search']) && isset($_SESSION['vpf_ymm']['search']['engine'])) {
                                                                            $engine = $_SESSION['vpf_ymm']['search']['engine'];
                                                                        }

                                                                        YMMP_FILTER_Functions::get_terms_list_frontend($model, $engine, 'engine');
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="woo-vpf-ymm-field woo-vpf-ymm-field-submit">
                                                            <input type="hidden" name="post_type" value="product" />
                                                            <input type="hidden" name="action" value="vpf-ymm-search" /> 
                                                        
                                                            <?php do_action('wpml_add_language_form_field'); ?>

                                                            <?php
                                                            // Add Lang Param in URL
                                                            if (YMMP_FILTER_Functions::is_wpml_activated()) {
                                                                do_action('wpml_add_language_form_field');
                                                            }
                                                            ?>

                                                            <?php $label_search = apply_filters('widget_woo_vpf_ymm_label_search', $args['label_search'], $args); ?>
                                                            <input type="submit" value="Shop" />

                                                        </div>

                                                        <?php do_action('woo_vpf_ymm_before_filter_form_end', $args); ?>

                                                        <div class="woo-vpf-ymm-clearfix"></div>
                                                    </form>

                                                    <?php do_action('woo_vpf_ymm_after_filter_widget', $args); ?>

                                                </div> 

                                            </div>
                                        </div>
                                    </div> 
                                    <div class="modal-footer">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="search-by-size" role="tabpanel" aria-labelledby="size-tab">
                    <div id="vehicle-widget">
                        <button type="button" class="btn btn-primary params" data-toggle="modal" data-target="#war-search">
                            Width
                        </button>
                        <button type="button" class="btn btn-primary params disabled" data-toggle="modal" data-target="#war-search"  disabled="">
                            Aspect
                        </button>
                        <button type="button" class="btn btn-primary params disabled" data-toggle="modal" data-target="#war-search" disabled="">
                            Rim
                        </button> 
                        <div class="zips"> 
                            <a href="">Shop</a>
                        </div>
                        <div class="modal fade" id="war-search" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
                            <div class="modal-dialog modal-dialog-scrollable" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">
                                            <span>Vehicle:</span>
                                            <span class="selection"> 
                                                <span class="width"></span>
                                                <span class="aspect"></span>
                                                <span class="rim"></span> 
                                            </span>
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="tab-pane active" role="tabpanel" aria-labelledby="vehicle-tab">
                                            <div id="vehicle-parameters">
                                                <div class="widget-woo-vpf-ymm-filter <?php echo ( ( $args['view'] == 'H' ) ? 'woo-vpf-ymm-filter-horizontal' : 'woo-vpf-ymm-filter-vertical'); ?>">
                                                    <?php do_action('woo_vpf_ymm_before_filter_widget', $args); ?>

                                                    <form action="<?php echo esc_url(home_url('/')); ?>" method="get">
                                                        <?php do_action('woo_vpf_ymm_after_filter_form_start', $args); ?>
                                                        <ul class="nav nav-tabs" role="tablist">
                                                            <li class="nav-item active">
                                                                <a class="nav-link active" id="width-tab" data-toggle="tab" href="#width" >width</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a  class="nav-link" id="aspect-tab" data-toggle="tab" href="#aspect">aspect</a>
                                                            </li>
                                                            <li class="nav-item" >
                                                                <a  class="nav-link" id="rim-tab" data-toggle="tab" href="#rim">rim</a>
                                                            </li>

                                                        </ul>
                                                        <div class="tab-content" id="vehicle-search-content">
                                                            <div class="tab-pane active" id="width" role="tabpanel" aria-labelledby="width-tab">
                                                                <div class="filter">
                                                                    <input  type="text" placeholder="Filter" id="widthfilter" onkeyup="filter_input_text('widthfilter', 'woo-vpf-ymm-field-width')">
                                                                    <p class="hidden">No results</p>
                                                                </div>
                                                                <div id="woo-vpf-ymm-field-width" class="woo-vpf-ymm-field woo-vpf-ymm-field-width">


                                                                    <?php
                                                                    $width = '';
                                                                    if (isset($_REQUEST['width'])) {
                                                                        $width = $_REQUEST['width'];
                                                                    } else if (isset($_SESSION['vpf_ymm']['search']) && isset($_SESSION['vpf_ymm']['search']['width'])) {
                                                                        $width = $_SESSION['vpf_ymm']['search']['width'];
                                                                    }

                                                                    WAR_FILTER_Functions::get_terms_list_frontend(0, $width, 'width');
                                                                    ?>

                                                                </div>

                                                            </div>    
                                                            <div  class="tab-pane" id="aspect" role="tabpanel" aria-labelledby="aspect-tab">
                                                                <div class="filter">
                                                                    <input type="text" placeholder="Filter" id="aspectfilter" onkeyup="filter_input_text('aspectfilter', 'woo-vpf-ymm-field-aspect')">
                                                                    <p class="hidden">No results</p>
                                                                </div>
                                                                <div id="woo-vpf-ymm-field-aspect" class="woo-vpf-ymm-field woo-vpf-ymm-field-aspect">
                                                                    <?php $label_aspect = apply_filters('widget_woo_vpf_ymm_label_aspect', $args['label_aspect'], $args); ?>


                                                                    <?php
                                                                    if (isset($width_id) && $width_id > 0) {

                                                                        $aspect = '';
                                                                        if (isset($_REQUEST['aspect'])) {
                                                                            $aspect = $_REQUEST['aspect'];
                                                                        } else if (isset($_SESSION['vpf_ymm']['search']) && isset($_SESSION['vpf_ymm']['search']['aspect'])) {
                                                                            $aspect = $_SESSION['vpf_ymm']['search']['aspect'];
                                                                        }

                                                                        YMMP_FILTER_Functions::get_terms_list_frontend($width_id, $aspect, 'aspect');
                                                                    }
                                                                    ?>

                                                                </div>

                                                            </div>
                                                            <div class="tab-pane" id="rim" role="tabpanel" aria-labelledby="rim-tab">
                                                                <div class="filter">
                                                                    <input type="text" placeholder="Filter" id="rimfilter" onkeyup="filter_input_text('rimfilter', 'woo-vpf-ymm-field-rim')">
                                                                    <p class="hidden">No results</p>
                                                                </div>
                                                                <div id="woo-vpf-ymm-field-rim" class="woo-vpf-ymm-field woo-vpf-ymm-field-rim">
                                                                    <?php $label_rim = apply_filters('widget_woo_vpf_ymm_label_rim', $args['label_rim'], $args); ?>


                                                                    <?php
                                                                    if (isset($aspect) && $aspect > 0) {

                                                                        $rim = '';
                                                                        if (isset($_REQUEST['rim'])) {
                                                                            $rim = $_REQUEST['rim'];
                                                                        } else if (isset($_SESSION['vpf_ymm']['search']) && isset($_SESSION['vpf_ymm']['search']['rim'])) {
                                                                            $rim = $_SESSION['vpf_ymm']['search']['rim'];
                                                                        }

                                                                        YMMP_FILTER_Functions::get_terms_list_frontend($aspect, $rim, 'rim');
                                                                    }
                                                                    ?>

                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="woo-vpf-ymm-field woo-vpf-ymm-field-submit">
                                                            <input type="hidden" name="post_type" value="product" />
                                                            <input type="hidden" name="action" value="vpf-ymm-search" />  
                                                            <?php do_action('wpml_add_language_form_field'); ?>

                                                            <?php
                                                            // Add Lang Param in URL
                                                            if (YMMP_FILTER_Functions::is_wpml_activated()) {
                                                                do_action('wpml_add_language_form_field');
                                                            }
                                                            ?>

                                                            <?php $label_search = apply_filters('widget_woo_vpf_ymm_label_search', $args['label_search'], $args); ?>
                                                            <input type="submit" value="Shop" />
                                                        </div>
                                                        <?php do_action('woo_vpf_ymm_before_filter_form_end', $args); ?>
                                                        <div class="woo-vpf-ymm-clearfix"></div>
                                                    </form>
                                                    <?php do_action('woo_vpf_ymm_after_filter_widget', $args); ?>
                                                </div> 
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="modal-footer">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
           
        </script>
        <?php
        return ob_get_clean();
    }

}
