<?php
/**
 * Common Functions
 *
 * @class YMMP_FILTER_Functions
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class YMMP_FILTER_Functions {

    /**
     * Check if VPF_YMM search results page
     *
     * @return void
     */
    public static function is_search() {
        if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'vpf-ymm-search') {
            return true;
        }

        return false;
    }

    /**
     * Get Year Label
     *
     * @return string/html
     */
    public static function get_year_label() {
        $woo_vpf_ymm_year_label = WC_Admin_Settings::get_option('woo_vpf_ymm_year_label');
        if ($woo_vpf_ymm_year_label == '') {
            $woo_vpf_ymm_year_label = __('Year', YMMP_FILTER_TEXT_DOMAIN);
        }

        return apply_filters('woo_vpf_ymm_year_label', $woo_vpf_ymm_year_label);
    }

    /**
     * Get Make Label
     *
     * @return string/html
     */
    public static function get_make_label() {
        $woo_vpf_ymm_make_label = WC_Admin_Settings::get_option('woo_vpf_ymm_make_label');
        if ($woo_vpf_ymm_make_label == '') {
            $woo_vpf_ymm_make_label = __('Make', YMMP_FILTER_TEXT_DOMAIN);
        }

        return apply_filters('woo_vpf_ymm_make_label', $woo_vpf_ymm_make_label);
    }

    /**
     * Get Model Label
     *
     * @return string/html
     */
    public static function get_model_label() {
        $woo_vpf_ymm_model_label = WC_Admin_Settings::get_option('woo_vpf_ymm_model_label');
        if ($woo_vpf_ymm_model_label == '') {
            $woo_vpf_ymm_model_label = __('Model', YMMP_FILTER_TEXT_DOMAIN);
        }

        return apply_filters('woo_vpf_ymm_model_label', $woo_vpf_ymm_model_label);
    }

    /**
     * Get Engine Label
     *
     * @return string/html
     */
    public static function get_engine_label() {
        $woo_vpf_ymm_engine_label = WC_Admin_Settings::get_option('woo_vpf_ymm_engine_label');
        if ($woo_vpf_ymm_engine_label == '') {
            $woo_vpf_ymm_engine_label = __('Option', YMMP_FILTER_TEXT_DOMAIN);
        }

        return apply_filters('woo_vpf_ymm_engine_label', $woo_vpf_ymm_engine_label);
    }

    /**
     * Get Category Label
     *
     * @return string/html
     */
    public static function get_category_label() {
        $woo_vpf_ymm_category_label = WC_Admin_Settings::get_option('woo_vpf_ymm_category_label');
        if ($woo_vpf_ymm_category_label == '') {
            $woo_vpf_ymm_category_label = __('Category', YMMP_FILTER_TEXT_DOMAIN);
        }

        return apply_filters('woo_vpf_ymm_category_label', $woo_vpf_ymm_category_label);
    }

    /**
     * Get Keyword Label
     *
     * @return string/html
     */
    public static function get_keyword_label() {
        $woo_vpf_ymm_keyword_label = WC_Admin_Settings::get_option('woo_vpf_ymm_keyword_label');
        if ($woo_vpf_ymm_keyword_label == '') {
            $woo_vpf_ymm_keyword_label = __('Keyword', YMMP_FILTER_TEXT_DOMAIN);
        }

        return apply_filters('woo_vpf_ymm_keyword_label', $woo_vpf_ymm_keyword_label);
    }

    /**
     * Get Search Results Label
     *
     * @return string/html
     */
    public static function get_search_results_label() {
        $woo_vpf_ymm_search_results_label = WC_Admin_Settings::get_option('woo_vpf_ymm_search_results_label');
        if ($woo_vpf_ymm_search_results_label == '') {
            $woo_vpf_ymm_search_results_label = __('Search Results For:', YMMP_FILTER_TEXT_DOMAIN);
        }

        return apply_filters('woo_vpf_ymm_search_results_label', $woo_vpf_ymm_search_results_label);
    }

    /**
     * My Vehicles - Is Activated?
     *
     * @return boolean
     */
    public static function is_activate_my_vehicles() {
        $woo_vpf_ymm_activate_my_vehicles = WC_Admin_Settings::get_option('woo_vpf_ymm_activate_my_vehicles');
        if ($woo_vpf_ymm_activate_my_vehicles != 'yes') {
            $woo_vpf_ymm_activate_my_vehicles = 'no';
        }

        return apply_filters('woo_vpf_ymm_activate_my_vehicles', $woo_vpf_ymm_activate_my_vehicles);
    }

    /**
     * My Vehicles - Title
     *
     * @return string/html
     */
    public static function get_my_vehicles_title() {
        $woo_vpf_ymm_my_vehicles_title = WC_Admin_Settings::get_option('woo_vpf_ymm_my_vehicles_title');
        return apply_filters('woo_vpf_ymm_my_vehicles_title', $woo_vpf_ymm_my_vehicles_title);
    }

    /**
     * My Vehicles - Saved Vehicles Limit
     *
     * @return number
     */
    public static function get_my_vehicles_save_limit() {
        $woo_vpf_ymm_my_vehicles_save_limit = WC_Admin_Settings::get_option('woo_vpf_ymm_my_vehicles_save_limit');
        $woo_vpf_ymm_my_vehicles_save_limit = (int) $woo_vpf_ymm_my_vehicles_save_limit;
        if (!$woo_vpf_ymm_my_vehicles_save_limit) {
            $woo_vpf_ymm_my_vehicles_save_limit = 5;
        }

        return apply_filters('woo_vpf_ymm_my_vehicles_save_limit', $woo_vpf_ymm_my_vehicles_save_limit);
    }

    /**
     * My Vehicles - Saved Vehicles Title
     *
     * @return string/html
     */
    public static function get_my_vehicles_save_title() {
        $woo_vpf_ymm_my_vehicles_save_title = WC_Admin_Settings::get_option('woo_vpf_ymm_my_vehicles_save_title');
        if ($woo_vpf_ymm_my_vehicles_save_title == '') {
            $woo_vpf_ymm_my_vehicles_save_title = __('My Saved Vehicles', YMMP_FILTER_TEXT_DOMAIN);
        }

        return apply_filters('woo_vpf_ymm_my_vehicles_save_title', $woo_vpf_ymm_my_vehicles_save_title);
    }

    /**
     * My Vehicles - Saved Vehicles Description
     *
     * @return string/html
     */
    public static function get_my_vehicles_save_description() {
        $woo_vpf_ymm_my_vehicles_save_description = WC_Admin_Settings::get_option('woo_vpf_ymm_my_vehicles_save_description');
        if ($woo_vpf_ymm_my_vehicles_save_description == '') {
            //$woo_vpf_ymm_my_vehicles_save_description = __( 'View, manage and find parts', YMMP_FILTER_TEXT_DOMAIN );
        }

        return apply_filters('woo_vpf_ymm_my_vehicles_save_description', $woo_vpf_ymm_my_vehicles_save_description);
    }

    /**
     * My Vehicles - Saved Vehicles No Items Text
     *
     * @return string/html
     */
    public static function get_my_vehicles_save_no_item_text() {
        $woo_vpf_ymm_my_vehicles_save_no_item_text = WC_Admin_Settings::get_option('woo_vpf_ymm_my_vehicles_save_no_item_text');
        if ($woo_vpf_ymm_my_vehicles_save_no_item_text == '') {
            //$woo_vpf_ymm_my_vehicles_save_no_item_text = __( 'You are yet to start saving search results', YMMP_FILTER_TEXT_DOMAIN );
        }

        return apply_filters('woo_vpf_ymm_my_vehicles_save_no_item_text', $woo_vpf_ymm_my_vehicles_save_no_item_text);
    }

    /**
     * My Vehicles - Saved Vehicles Clear History Text
     *
     * @return string/html
     */
    public static function get_my_vehicles_save_clear_history_text() {
        $woo_vpf_ymm_my_vehicles_save_clear_history_text = WC_Admin_Settings::get_option('woo_vpf_ymm_my_vehicles_save_clear_history_text');
        if ($woo_vpf_ymm_my_vehicles_save_clear_history_text == '') {
            $woo_vpf_ymm_my_vehicles_save_clear_history_text = __('Clear History', YMMP_FILTER_TEXT_DOMAIN);
        }

        return apply_filters('woo_vpf_ymm_my_vehicles_save_clear_history_text', $woo_vpf_ymm_my_vehicles_save_clear_history_text);
    }

    /**
     * My Vehicles - Vehicles History Limit
     *
     * @return number
     */
    public static function get_my_vehicles_history_limit() {
        $woo_vpf_ymm_my_vehicles_history_limit = WC_Admin_Settings::get_option('woo_vpf_ymm_my_vehicles_history_limit');
        $woo_vpf_ymm_my_vehicles_history_limit = (int) $woo_vpf_ymm_my_vehicles_history_limit;
        if (!$woo_vpf_ymm_my_vehicles_history_limit) {
            $woo_vpf_ymm_my_vehicles_history_limit = 5;
        }

        return apply_filters('woo_vpf_ymm_my_vehicles_history_limit', $woo_vpf_ymm_my_vehicles_history_limit);
    }

    /**
     * My Vehicles - Vehicles History Title
     *
     * @return string/html
     */
    public static function get_my_vehicles_history_title() {
        $woo_vpf_ymm_my_vehicles_history_title = WC_Admin_Settings::get_option('woo_vpf_ymm_my_vehicles_history_title');
        if ($woo_vpf_ymm_my_vehicles_history_title == '') {
            $woo_vpf_ymm_my_vehicles_history_title = __('Vehicles History', YMMP_FILTER_TEXT_DOMAIN);
        }

        return apply_filters('woo_vpf_ymm_my_vehicles_history_title', $woo_vpf_ymm_my_vehicles_history_title);
    }

    /**
     * My Vehicles - Vehicles History Description
     *
     * @return string/html
     */
    public static function get_my_vehicles_history_description() {
        $woo_vpf_ymm_my_vehicles_history_description = WC_Admin_Settings::get_option('woo_vpf_ymm_my_vehicles_history_description');
        if ($woo_vpf_ymm_my_vehicles_history_description == '') {
            //$woo_vpf_ymm_my_vehicles_history_description = __( 'Easily navigate the latest vehicles selected', YMMP_FILTER_TEXT_DOMAIN );
        }

        return apply_filters('woo_vpf_ymm_my_vehicles_history_description', $woo_vpf_ymm_my_vehicles_history_description);
    }

    /**
     * My Vehicles - Vehicles History No Items Text
     *
     * @return string/html
     */
    public static function get_my_vehicles_history_no_item_text() {
        $woo_vpf_ymm_my_vehicles_history_no_item_text = WC_Admin_Settings::get_option('woo_vpf_ymm_my_vehicles_history_no_item_text');
        if ($woo_vpf_ymm_my_vehicles_history_no_item_text == '') {
            //$woo_vpf_ymm_my_vehicles_history_no_item_text = __( 'You are yet to start searching vehicles', YMMP_FILTER_TEXT_DOMAIN );
        }

        return apply_filters('woo_vpf_ymm_my_vehicles_history_no_item_text', $woo_vpf_ymm_my_vehicles_history_no_item_text);
    }

    /**
     * My Vehicles - Vehicles History Clear History Text
     *
     * @return string/html
     */
    public static function get_my_vehicles_history_clear_history_text() {
        $woo_vpf_ymm_my_vehicles_history_clear_history_text = WC_Admin_Settings::get_option('woo_vpf_ymm_my_vehicles_history_clear_history_text');
        if ($woo_vpf_ymm_my_vehicles_history_clear_history_text == '') {
            $woo_vpf_ymm_my_vehicles_history_clear_history_text = __('Clear History', YMMP_FILTER_TEXT_DOMAIN);
        }

        return apply_filters('woo_vpf_ymm_my_vehicles_history_clear_history_text', $woo_vpf_ymm_my_vehicles_history_clear_history_text);
    }

    /**
     * My Vehicles - Add Vehicle Text
     *
     * @return string/html
     */
    public static function get_my_vehicles_add_vehicle_text() {
        $woo_vpf_ymm_my_vehicles_add_vehicle_text = WC_Admin_Settings::get_option('woo_vpf_ymm_my_vehicles_add_vehicle_text');
        if ($woo_vpf_ymm_my_vehicles_add_vehicle_text == '') {
            $woo_vpf_ymm_my_vehicles_add_vehicle_text = __('Add a Vehicle', YMMP_FILTER_TEXT_DOMAIN);
        }

        return apply_filters('woo_vpf_ymm_my_vehicles_add_vehicle_text', $woo_vpf_ymm_my_vehicles_add_vehicle_text);
    }

    /**
     * My Vehicles - Add Vehicle Heading
     *
     * @return string/html
     */
    public static function get_my_vehicles_add_vehicle_heading() {
        $woo_vpf_ymm_my_vehicles_add_vehicle_heading = WC_Admin_Settings::get_option('woo_vpf_ymm_my_vehicles_add_vehicle_heading');
        if ($woo_vpf_ymm_my_vehicles_add_vehicle_heading == '') {
            $woo_vpf_ymm_my_vehicles_add_vehicle_heading = __('Select Vehicle', YMMP_FILTER_TEXT_DOMAIN);
        }

        return apply_filters('woo_vpf_ymm_my_vehicles_add_vehicle_heading', $woo_vpf_ymm_my_vehicles_add_vehicle_heading);
    }

    /**
     * My Vehicles - Add Vehicle Description
     *
     * @return string/html
     */
    public static function get_my_vehicles_add_vehicle_description() {
        $woo_vpf_ymm_my_vehicles_add_vehicle_description = WC_Admin_Settings::get_option('woo_vpf_ymm_my_vehicles_add_vehicle_description');
        return apply_filters('woo_vpf_ymm_my_vehicles_add_vehicle_description', $woo_vpf_ymm_my_vehicles_add_vehicle_description);
    }

    /**
     * My Vehicles - Add Vehicle Shortcode
     *
     * @return string/html
     */
    public static function get_my_vehicles_add_vehicle_shortcode() {
        $woo_vpf_ymm_my_vehicles_add_vehicle_shortcode = WC_Admin_Settings::get_option('woo_vpf_ymm_my_vehicles_add_vehicle_shortcode');
        return apply_filters('woo_vpf_ymm_my_vehicles_add_vehicle_shortcode', $woo_vpf_ymm_my_vehicles_add_vehicle_shortcode);
    }

    /**
     * Get Post Terms Hierarchy
     *
     * @return array
     */
    public static function wp_get_post_terms_hierarchy($post_ID) {
        $vpf_terms = array();

        $years = array();
        $makes = array();
        $models = array();
        $engines = array();

        $terms = wp_get_post_terms($post_ID, 'product_ymm', array('fields' => 'all', 'orderby' => 'parent', 'order' => 'ASC'));
        if (!empty($terms)) {

            // Years
            foreach ($terms as $k => $term) {
                if ($term->parent != 0) {
                    break;
                }

                $year_id = $term->term_id;

                $years[$year_id] = $term;
                $vpf_terms['years'][$year_id]['term'] = $term;
                unset($terms[$k]);
            }

            // Makes
            foreach ($terms as $k => $term) {
                $year_id = $term->parent;
                if (isset($years[$year_id])) {
                    $make_id = $term->term_id;

                    $makes[$make_id] = $term;
                    $vpf_terms['years'][$year_id]['makes'][$make_id]['term'] = $term;
                    unset($terms[$k]);
                }
            }

            // Models
            foreach ($terms as $k => $term) {
                $make_id = $term->parent;
                if (isset($makes[$make_id])) {
                    $model_id = $term->term_id;
                    $year_id = $makes[$make_id]->parent;

                    $models[$model_id] = $term;
                    $vpf_terms['years'][$year_id]['makes'][$make_id]['models'][$model_id]['term'] = $term;
                    unset($terms[$k]);
                }
            }

            // Engines
            foreach ($terms as $k => $term) {
                $model_id = $term->parent;
                if (isset($models[$model_id])) {
                    $engine_id = $term->term_id;
                    $make_id = $models[$model_id]->parent;
                    $year_id = $makes[$make_id]->parent;

                    $engines[$engine_id] = $term;
                    $vpf_terms['years'][$year_id]['makes'][$make_id]['models'][$model_id]['engines'][$engine_id]['term'] = $term;
                    unset($terms[$k]);
                }
            }
        }

        return apply_filters('woo_vpf_ymm_post_terms_hierarchy', array($vpf_terms, $years, $makes, $models, $engines));
    }

    /**
     * Sort Terms
     *
     * @param  array $terms
     * @return array $terms
     */
    public static function sort_terms($terms = '') {
        if (empty($terms)) {
            return $terms;
        }

        $sort_years = array();
        $sort_makes = array();
        $sort_models = array();
        $sort_engines = array();

        foreach ($terms as $key => $term) {
            $sort_years[$key] = is_array($term['year']) ? $term['year']['name'] : $term['year'];
            $sort_makes[$key] = is_array($term['make']) ? $term['make']['name'] : $term['make'];
            $sort_models[$key] = is_array($term['model']) ? $term['model']['name'] : $term['model'];

            if (isset($term['engine'])) {
                $sort_engines[$key] = is_array($term['engine']) ? $term['engine']['name'] : $term['engine'];
            }
        }

        if (isset($term['engine'])) {
            array_multisort($sort_years, SORT_ASC, $sort_makes, SORT_ASC, $sort_models, SORT_ASC, $sort_engines, SORT_ASC, $terms);
        } else {
            array_multisort($sort_years, SORT_ASC, $sort_makes, SORT_ASC, $sort_models, SORT_ASC, $terms);
        }

        return $terms;
    }

    /**
     * Arrange Terms by Year Range
     *
     * @param  array $terms
     * @return array $terms
     */
    public static function arrange_terms_by_year_range($terms = '') {
        if (empty($terms)) {
            return $terms;
        }

        $yearly_terms = array();
        foreach ($terms as $term) {
            $term = array_filter($term);
            $year = $term['year'];
            unset($term['year']);

            $yearly_terms[$year][] = implode('{-VPF-SEPARATOR-}', $term);
        }
        ksort($yearly_terms);

        $terms = array();
        foreach ($yearly_terms as $year => &$year_terms) {
            if (!empty($year_terms)) {
                foreach ($year_terms as $key => $year_term) {
                    if (!empty($year_term)) {
                        $year_current = $year;
                        $year_start = $year_current;
                        $year_end = $year_start;

                        if ((int) $year_current == $year_current) { // Check if numeric year
                            while ($year_current) {
                                $year_current++;

                                $year_current_key = false;
                                if (isset($yearly_terms[$year_current])) {
                                    $year_current_key = array_search($year_term, $yearly_terms[$year_current]);
                                }

                                if ($year_current_key !== false) {
                                    $year_end = $year_current;
                                    unset($yearly_terms[$year_current][$year_current_key]);
                                } else {
                                    break;
                                }
                            }
                        }

                        $term = array();
                        $year_term = explode('{-VPF-SEPARATOR-}', $year_term);

                        if ($year_end > $year_start) {
                            $term['year'] = $year_start . ' - ' . $year_end;
                        } else {
                            $term['year'] = $year_start;
                        }

                        if (isset($year_term[0])) {
                            $term['make'] = $year_term[0];
                        } else {
                            $term['make'] = '';
                        }

                        if (isset($year_term[1])) {
                            $term['model'] = $year_term[1];
                        } else {
                            $term['model'] = '';
                        }

                        if (isset($year_term[2])) {
                            $term['engine'] = $year_term[2];
                        } else {
                            $term['engine'] = '';
                        }

                        $terms[] = $term;
                    }
                }
            }
        }

        return $terms;
    }

    /**
     * Get Terms List
     *
     * @param  int $parent
     * @return string/html
     */
    public static function get_terms_list($parent = 0, $selected = '', $input_name = '') {
        if ($parent === '') {
            return;
        }

        $order = 'ASC';

        if (!$parent) {
            $woo_vpf_ymm_years_sort_order = WC_Admin_Settings::get_option('woo_vpf_ymm_years_sort_order');
            if ($woo_vpf_ymm_years_sort_order == 'desc') {
                $order = 'DESC';
            }
        }

        $product_terms = get_terms('product_ymm', apply_filters('woo_vpf_ymm_terms_args', array(
            'orderby' => 'name',
            'order' => $order,
            'parent' => $parent,
            'hide_empty' => 0,
            'ignore_parent' => true
        )));

        if (!empty($product_terms)) {
            foreach ($product_terms as $product_term) {
                ?><option value="<?php echo $product_term->term_id; ?>" <?php
                if ($selected == $product_term->term_id) {
                    echo 'selected';
                }
                ?>><?php echo $product_term->name; ?></option><?php
                    }
                }
            }

            /**
             * Get Terms List
             *
             * @param  int $parent
             * @return string/html
             */
            public static function get_terms_list_frontend($parent = 0, $selected = '', $input_name = '') {
                if ($parent === '') {
                    return;
                }

                $order = 'ASC';

                if (!$parent) {
                    $woo_vpf_ymm_years_sort_order = WC_Admin_Settings::get_option('woo_vpf_ymm_years_sort_order');
                    if ($woo_vpf_ymm_years_sort_order == 'desc') {
                        $order = 'DESC';
                    }
                }

                $product_terms = get_terms('product_ymm', apply_filters('woo_vpf_ymm_terms_args', array(
                    'orderby' => 'name',
                    'order' => $order,
                    'parent' => $parent,
                    'hide_empty' => 0,
                    'ignore_parent' => true
                )));

                if (!empty($product_terms)) {
                    foreach ($product_terms as $product_term) {
                        ?>
                <label class="<?php
                if ($selected == $product_term->term_id) {
                    echo 'selected';
                }
                ?>"><input type="radio" name="<?php echo $input_name; ?>" data-name="<?php echo $product_term->name; ?>" id="<?php echo $product_term->term_id; ?>" value="<?php echo $product_term->term_id; ?>" <?php
                           if ($selected == $product_term->term_id) {
                               echo 'checked';
                           }
                           ?>><?php echo $product_term->name; ?></label>
                    <?php
                }
            }
        }

        /**
         * Get Categories
         *
         * @param  int $parent_id
         * @param  int $level
         * @return string/html
         */
        public static function get_categories_list($parent_id = 0, $level = 0, $selected = '') {
            $woo_vpf_ymm_included_categories = WC_Admin_Settings::get_option('woo_vpf_ymm_included_categories');

            if (!empty($woo_vpf_ymm_included_categories)) {
                $args = array(
                    'orderby' => 'name',
                    'order' => 'ASC',
                    'include' => $woo_vpf_ymm_included_categories,
                    'hide_empty' => 0
                );
            } else {
                $args = array(
                    'orderby' => 'name',
                    'order' => 'ASC',
                    'parent' => $parent_id,
                    'hide_empty' => 0
                );
            }

            $product_categories = get_terms('product_cat', apply_filters('woo_vpf_ymm_category_args', $args));

            if (!empty($product_categories)) {

                if (!empty($woo_vpf_ymm_included_categories)) {
                    foreach ($product_categories as $product_category) {
                        $child_prefix = '';

                        /* if( $product_category->parent > 0 ) {
                          $parent_terms = self::get_parent_terms( $product_category->term_id );
                          unset( $parent_terms[0] );

                          if( ! empty( $parent_terms ) ) {
                          $parent_terms = array_reverse( $parent_terms );

                          foreach( $parent_terms as $key => $parent_term ) {
                          $term = get_term_by( 'id', $parent_term, 'product_ymm' );
                          $child_prefix = $term->name . ' > ';
                          }
                          }
                          } */
                        ?><option value="<?php echo $product_category->term_id; ?>" <?php
                        if ($selected == $product_category->term_id) {
                            echo 'selected';
                        }
                        ?>><?php echo $child_prefix . $product_category->name; ?></option><?php
                    }
                } else {
                    $level = $level + 1;

                    $child_prefix = str_pad('', ( $level - 1), "-", STR_PAD_LEFT);
                    if (!empty($child_prefix)) {
                        $child_prefix = '&nbsp;' . $child_prefix . '&nbsp;';
                    }

                    foreach ($product_categories as $product_category) {
                        ?><option value="<?php echo $product_category->term_id; ?>" <?php
                    if ($selected == $product_category->term_id) {
                        echo 'selected';
                    }
                    ?>><?php echo $child_prefix . $product_category->name; ?></option><?php
                            self::get_categories_list($product_category->term_id, $level, $selected);
                        }
                    }
                }
            }

            /**
             * YMMP_FILTER Filter Box
             *
             * @param  array $args
             * @return string/html
             */
            public static function get_filter_widget_template($args) {
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
                ?><div class="widget-woo-vpf-ymm-filter <?php echo ( ( $args['view'] == 'H' ) ? 'woo-vpf-ymm-filter-horizontal' : 'woo-vpf-ymm-filter-vertical'); ?>">

            <?php do_action('woo_vpf_ymm_before_filter_widget', $args); ?>

            <form action="<?php echo esc_url(home_url('/')); ?>" method="get">

                <?php do_action('woo_vpf_ymm_after_filter_form_start', $args); ?>

                <div class="woo-vpf-ymm-field woo-vpf-ymm-field-year">
                    <?php $label_year = apply_filters('widget_woo_vpf_ymm_label_year', $args['label_year'], $args); ?>


                    <?php
                    $year_id = '';
                    if (isset($_REQUEST['year_id'])) {
                        $year_id = $_REQUEST['year_id'];
                    } else if (isset($_SESSION['vpf_ymm']['search']) && isset($_SESSION['vpf_ymm']['search']['year_id'])) {
                        $year_id = $_SESSION['vpf_ymm']['search']['year_id'];
                    }

                    self::get_terms_list(0, $year_id, 'year_id');
                    ?>

                </div>

                <div class="woo-vpf-ymm-field woo-vpf-ymm-field-make">
                    <?php $label_make = apply_filters('widget_woo_vpf_ymm_label_make', $args['label_make'], $args); ?>


                    <?php
                    if (isset($year_id) && $year_id > 0) {

                        $make = '';
                        if (isset($_REQUEST['make'])) {
                            $make = $_REQUEST['make'];
                        } else if (isset($_SESSION['vpf_ymm']['search']) && isset($_SESSION['vpf_ymm']['search']['make'])) {
                            $make = $_SESSION['vpf_ymm']['search']['make'];
                        }

                        self::get_terms_list($year_id, $make, 'make');
                    }
                    ?>

                </div>

                <?php
                if ($args['show_model'] == 1 || $args['show_model'] === "true") {
                    ?><div class="woo-vpf-ymm-field woo-vpf-ymm-field-model">
                    <?php $label_model = apply_filters('widget_woo_vpf_ymm_label_model', $args['label_model'], $args); ?>


                        <?php
                        if (isset($make) && $make > 0) {

                            $model = '';
                            if (isset($_REQUEST['model'])) {
                                $model = $_REQUEST['model'];
                            } else if (isset($_SESSION['vpf_ymm']['search']) && isset($_SESSION['vpf_ymm']['search']['model'])) {
                                $model = $_SESSION['vpf_ymm']['search']['model'];
                            }

                            self::get_terms_list($make, $model, 'model');
                        }
                        ?>

                    </div><?php ?><div class="woo-vpf-ymm-field woo-vpf-ymm-field-engine">
                        <?php $label_engine = apply_filters('widget_woo_vpf_ymm_label_engine', $args['label_engine'], $args); ?>

                        <?php
                        if (isset($model) && $model > 0) {

                            $engine = '';
                            if (isset($_REQUEST['engine'])) {
                                $engine = $_REQUEST['engine'];
                            } else if (isset($_SESSION['vpf_ymm']['search']) && isset($_SESSION['vpf_ymm']['search']['engine'])) {
                                $engine = $_SESSION['vpf_ymm']['search']['engine'];
                            }

                            self::get_terms_list($model, $engine, 'engine');
                        }
                        ?>
                    </div><?php
                }
                ?>

                <?php
                if ($args['show_category'] == 1 || $args['show_category'] === "true") {
                    ?><div class="woo-vpf-ymm-field woo-vpf-ymm-field-category">
                    <?php $label_category = apply_filters('widget_woo_vpf_ymm_label_category', $args['label_category'], $args); ?>
                        <select name="category">
                            <option value=""><?php echo $label_category; ?></option>
                            <?php
                            $category = '';
                            if (isset($_REQUEST['category'])) {
                                $category = $_REQUEST['category'];
                            } else if (isset($_SESSION['vpf_ymm']['search']) && isset($_SESSION['vpf_ymm']['search']['category'])) {
                                $category = $_SESSION['vpf_ymm']['search']['category'];
                            }

                            self::get_categories_list(0, 0, $category);
                            ?>
                        </select>
                    </div><?php
                }
                ?>

                <?php
                if ($args['show_keyword'] == 1 || $args['show_keyword'] === "true") {
                    ?><div class="woo-vpf-ymm-field woo-vpf-ymm-field-keyword">
                    <?php $label_keyword = apply_filters('widget_woo_vpf_ymm_label_keyword', $args['label_keyword'], $args); ?>
                        <input type="text" name="s" value="<?php echo ( isset($_REQUEST['s']) ? trim(stripslashes($_REQUEST['s'])) : '' ); ?>" placeholder="<?php echo $label_keyword; ?>" />
                    </div><?php
                }
                ?>

                <div class="woo-vpf-ymm-field woo-vpf-ymm-field-submit">
                    <input type="hidden" name="post_type" value="product" />
                    <input type="hidden" name="action" value="vpf-ymm-search" />
                    <?php do_action('wpml_add_language_form_field'); ?>

                    <?php
                    // Add Lang Param in URL
                    if (self::is_wpml_activated()) {
                        do_action('wpml_add_language_form_field');
                    }
                    ?>

                    <?php $label_search = apply_filters('widget_woo_vpf_ymm_label_search', $args['label_search'], $args); ?>
                    <input type="submit" value="<?php echo $label_search; ?>" />

                    <?php
                    $woo_vpf_ymm_activate_remember_search = WC_Admin_Settings::get_option('woo_vpf_ymm_activate_remember_search');
                    if ($woo_vpf_ymm_activate_remember_search == 'yes') {
                        if (isset($_SESSION['vpf_ymm']['search']) && isset($_SESSION['vpf_ymm']['search']['term_id'])) {
                            $label_reset_search = apply_filters('widget_woo_vpf_ymm_label_reset_search', $args['label_reset_search'], $args);

                            // On Reset, Go to URL
                            $refresh_url = '#';
                            if (self::is_search()) {
                                $refresh_url = home_url();
                            }

                            echo '<a class="woo-vpf-ymm-reset-search" href="' . $refresh_url . '">' . $label_reset_search . '</a>';
                        }
                    }
                    ?>
                </div>

                <?php do_action('woo_vpf_ymm_before_filter_form_end', $args); ?>

                <div class="woo-vpf-ymm-clearfix"></div>
            </form>

            <?php do_action('woo_vpf_ymm_after_filter_widget', $args); ?>

        </div><?php
    }

    /**
     * Get Ancestor Terms Hierarchy
     *
     * @param  int $term_id
     * @param  array $parent_terms

     * @return array
     */
    public static function get_parent_terms_hierarchy($term_id) {
        if ($term_id > 0) {
            $parent_terms = self::get_parent_terms($term_id);

            if (!empty($parent_terms)) {
                $parent_term_keys = array('year', 'make', 'model', 'engine');

                $parent_terms = array_reverse($parent_terms);
                $parent_terms = array_pad($parent_terms, sizeof($parent_term_keys), '');
                $parent_terms = array_combine($parent_term_keys, $parent_terms);
                $parent_terms = array_filter($parent_terms);

                foreach ($parent_terms as $key => $parent_term) {
                    $term = get_term_by('id', $parent_term, 'product_ymm');
                    $parent_terms[$key] = array(
                        'id' => $term->term_id,
                        'name' => $term->name
                    );
                }
            }

            return $parent_terms;
        }
    }

    /**
     * Get Ancestor Terms
     *
     * @param  int $term_id
     * @param  array $parent_terms
     * @return array
     */
    public static function get_parent_terms($term_id, $parent_terms = array()) {
        if ($term_id > 0) {
            $parent_terms[] = $term_id;

            $term = get_term_by('id', $term_id, 'product_ymm');
            if ($term->parent > 0) {
                $parent_terms = self::get_parent_terms($term->parent, $parent_terms);
            }
        }

        return $parent_terms;
    }

    /**
     * Format Name to Remove Extra Spaces
     *
     * @param  int $string
     * @return string
     */
    public static function format_string($string) {
        if (!empty($string)) {
            $string = utf8_encode($string);
            return trim(preg_replace('!\s+!', ' ', $string));
        }
    }

    /**
     * Check if term already exists
     *
     * @param  int $name
     * @param  int $parent
     * @return int $term_id
     */
    public static function term_exists($name, $parent = 0) {
        $term_id = '';

        if (!empty($name)) {
            $terms = get_terms('product_ymm', array(
                'name' => $name,
                'parent' => $parent,
                'fields' => 'ids',
                'hide_empty' => 0
            ));

            if (!empty($terms)) {
                $term_id = $terms[0];
            }
        }

        return $term_id;
    }

    /**
     * Insert term in database
     *
     * @param  int $name
     * @param  int $parent
     * @return int $term_id
     */
    public static function insert_term($name, $parent = 0) {
        if ('' == trim($name)) {
            return new WP_Error('empty_term_name', __('A name is required for this term', YMMP_FILTER_TEXT_DOMAIN));
        }

        $term = wp_insert_term($name, 'product_ymm', array('parent' => $parent));

        if (!is_wp_error($term)) {
            if ($term !== 0 && $term !== null && isset($term['term_id'])) {
                $term = $term['term_id'];
            }
        }

        return $term;
    }

    /**
     * Update term in database
     *
     * @param  int $term_id
     * @param  int $name
     * @param  int $parent
     * @return int $term_id
     */
    public static function update_term($term_id, $name, $parent = 0) {
        if ('' == trim($name)) {
            return new WP_Error('empty_term_name', __('A name is required for this term', YMMP_FILTER_TEXT_DOMAIN));
        }

        global $wpdb;

        $wpdb->update($wpdb->terms, array('name' => $name), array('term_id' => $term_id));
        $wpdb->update($wpdb->term_taxonomy, array('parent' => $parent), array('term_id' => $term_id));

        return $term_id;
    }

    /**
     * WPML: Is Activated?
     *
     * @return void
     */
    public static function is_wpml_activated() {
        if (function_exists('icl_object_id')) {
            return true;
        }

        return false;
    }

}
