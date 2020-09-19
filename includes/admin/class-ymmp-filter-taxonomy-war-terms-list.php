<?php
/**
 * Taxonomy Terms List Template: Show only one level terms with CHILD TERMS link
 *
 * @class YMMP_FILTER_Taxonomy_war_Terms_List
 */
if (!defined('ABSPATH')) {
    exit;
}

/**
 * YMMP_FILTER_Taxonomy_war_Terms_List Class
 */
class YMMP_FILTER_Taxonomy_war_Terms_List {

    /**
     * @var var
     */
    public $current_term_level = '';

    /**
     * @var array
     */
    public $parent_terms = NULL;

    /**
     * @var labels
     */
    public $label_width = '';
    public $label_aspect = '';
    public $label_rim = '';

    /**
     * Constructor
     *
     * @access public
     */
    public function __construct($level = '') {
        add_action('admin_init', array($this, 'init'));
    }

    /**
     * Load core functions before hooks
     */
    public function init() {
        global $pagenow;

        // Assign Labels
        $this->label_width = WAR_FILTER_Functions::get_width_label();
        $this->label_aspect = WAR_FILTER_Functions::get_aspect_label();
        $this->label_rim = WAR_FILTER_Functions::get_rim_label();

        // Delete Child Terms While Deleting Parent Term
        add_action('pre_delete_term', array($this, 'delete_child_terms'), 10, 2);

        if (!(
                ( defined('DOING_AJAX') && DOING_AJAX && isset($_REQUEST['action']) && ( $_REQUEST['action'] == 'add-tag' || $_REQUEST['action'] == 'delete-tag' || $_REQUEST['action'] == 'inline-save-tax' ) && isset($_REQUEST['taxonomy']) && $_REQUEST['taxonomy'] == 'product_war' ) ||
                ( ( $pagenow == 'edit-tags.php' || $pagenow == 'term.php' ) && isset($_GET['taxonomy']) && $_GET['taxonomy'] == 'product_war' )
                )) {
            return;
        }

        if ($pagenow != 'term.php') {
            // Set Parents Term Hierarchy
            if (isset($_REQUEST['parent']) && $_REQUEST['parent'] > 0) {
                $this->parent_terms = WAR_FILTER_Functions::get_parent_terms_hierarchy($_REQUEST['parent']);
            }

            // Set Current Term Level
            $this->current_term_level();

            // Manage Terms List Columns
            add_filter('manage_edit-product_war_columns', array($this, 'terms_list_columns'), 10, 3);
            add_filter('manage_product_war_custom_column', array($this, 'terms_list_columns_content'), 10, 3);

            // Add CHILD TERMS link in Quick Links
            add_filter('tag_row_actions', array($this, 'terms_list_row_actions'), 10, 2);

            // Fix: Quick Edit Section Issues
            add_action('quick_edit_custom_box', array($this, 'term_quick_edit_box_custom_fields'), 10, 2);
            add_filter('term_name', array($this, 'remove_term_name_pre_pad'), 10, 2);

            if ($pagenow == 'edit-tags.php' || ( defined('DOING_AJAX') && DOING_AJAX )) {
                if ($pagenow == 'edit-tags.php') {
                    // Limit Terms List to One Depth Level
                    add_filter('get_terms_args', array($this, 'terms_list_parent_arg'), 10, 2);

                    // Fix WPML Admin Bar Language Switcher Links
                    if (WAR_FILTER_Functions::is_wpml_activated()) {
                        add_filter('wpml_admin_language_switcher_items', array($this, 'language_switcher_links'), 10);
                    }
                }
            }
        }

        if ($pagenow == 'edit-tags.php' || $pagenow == 'term.php') {

            // Before Terms List Actions
            if ($pagenow == 'edit-tags.php') {
                add_filter('product_war_pre_add_form', array($this, 'terms_delete_form'));
                add_filter('product_war_pre_add_form', array($this, 'terms_search_form'));
            }

            // Filter Form Dropdown Terms
            if (!( WAR_FILTER_Functions::is_wpml_activated() && isset($_REQUEST['trid']) )) {
                add_filter('taxonomy_parent_dropdown_args', array($this, 'term_form_dropdown_terms_args'), 10, 2);
            }
        }
    }

    /**
     * Set VPF ( YMM ) current term level
     */
    public function current_term_level() {

        // All Term Levels
        $this->term_levels = array(
            array(
                'slug' => 'width',
                'menu_slug' => 'widths',
                'label' => $this->label_width,
                'level' => 1
            ),
            array(
                'slug' => 'aspect',
                'menu_slug' => 'aspects',
                'label' => $this->label_aspect,
                'level' => 2
            ),
            array(
                'slug' => 'rim',
                'menu_slug' => 'rims',
                'label' => $this->label_rim,
                'level' => 3
            ),
        );

        // Current Level
        $level = 1;

        if ($this->parent_terms !== NULL) {
            if (isset($this->parent_terms['aspect'])) {
                $level = 3;
            } else if (isset($this->parent_terms['width'])) {
                $level = 2;
            }
        }

        $this->current_term_level = apply_filters('woo_vpf_ymm_current_term_level', $this->term_levels[$level - 1]);
    }

    /**
     * Add/Remove terms list columns
     */
    public function terms_list_columns($columns) {
        $columns = array();

        $columns['cb'] = '<input type="checkbox" />';

        if ($this->current_term_level['level'] > 1) {
            $columns['width'] = $this->label_width;
        }

        if ($this->current_term_level['level'] > 2) {
            $columns['aspect'] = $this->label_aspect;
        }

        if ($this->current_term_level['level'] > 3) {
            $columns['rim'] = $this->label_rim;
        }

        $columns['name'] = $this->current_term_level['label'];
        $columns['posts'] = __('Count', YMMP_FILTER_TEXT_DOMAIN);

        return $columns;
    }

    /**
     * Manage terms list column values
     */
    public function terms_list_columns_content($content, $column_name, $term_id) {
        switch ($column_name) {
            case 'width':
            case 'aspect':
            case 'rim':
                $content = $this->parent_terms[$column_name]['name'];

            default:
                break;
        }

        return $content;
    }

    /**
     * Add CHILD TERMS link in Quick Links
     */
    public function terms_list_row_actions($actions, $term) {
        // Add custom quick actions
        if (isset($this->term_levels[$this->current_term_level['level']])) {
            // View child terms link
            $view_child_terms_link = 'edit-tags.php';

            if (isset($_REQUEST['taxonomy'])) {
                $view_child_terms_link = add_query_arg('taxonomy', $_REQUEST['taxonomy'], $view_child_terms_link);
            }

            if (isset($_REQUEST['post_type'])) {
                $view_child_terms_link = add_query_arg('post_type', $_REQUEST['post_type'], $view_child_terms_link);
            }

            if (isset($_REQUEST['orderby'])) {
                $view_child_terms_link = add_query_arg('orderby', $_REQUEST['orderby'], $view_child_terms_link);
            } else {
                $view_child_terms_link = add_query_arg('orderby', 'name', $view_child_terms_link);
            }

            if (isset($_REQUEST['order'])) {
                $view_child_terms_link = add_query_arg('order', $_REQUEST['order'], $view_child_terms_link);
            } else {
                $view_child_terms_link = add_query_arg('order', 'asc', $view_child_terms_link);
            }

            $view_child_terms_link = add_query_arg('parent', $term->term_id, $view_child_terms_link);
            $next_level = $this->term_levels[$this->current_term_level['level']];

            $actions['view_child_terms'] = sprintf('<a href="%s" aria-label="%s">%s</a>', $view_child_terms_link, sprintf(__('View %s', YMMP_FILTER_TEXT_DOMAIN), $next_level['label']), sprintf(__('View %s', YMMP_FILTER_TEXT_DOMAIN), $next_level['label']));
        }

        return $actions;
    }

    /**
     * Delete Child Terms While Deleting Parent Term
     */
    public function delete_child_terms($term, $taxonomy) {
        if ($taxonomy != 'product_war') {
            return;
        }

        if (!$term) {
            return;
        }

        $terms = get_terms('product_war', array(
            'parent' => $term,
            'fields' => 'ids',
            'hide_empty' => false,
            'ignore_parent' => true
        ));

        if (!empty($terms)) {
            foreach ($terms as $term) {
                wp_delete_term($term, $taxonomy);
            }
        }
    }

    /**
     * Quick Edit: Add PARENT Value as Hidden
     */
    public function term_quick_edit_box_custom_fields($actions, $term) {
        if (isset($_REQUEST['parent']) && $_REQUEST['parent'] > 0) {
            ?><input type="hidden" name="parent" value="<?php echo $_REQUEST['parent']; ?>" /><?php
        }
    }

    /**
     * Quick Edit: Remove '--' before Term Name
     */
    public function remove_term_name_pre_pad($term_name) {
        if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'inline-save-tax') {
            $term_name = str_replace('&#8212; ', '', $term_name);
        }

        return $term_name;
    }

    /**
     * Add: Filter get_terms args to show only one level terms
     */
    public function terms_list_parent_arg($args, $taxonomies) {
        if (!( isset($args['ignore_parent']) && $args['ignore_parent'] )) {
            if (isset($_REQUEST['parent']) && $_REQUEST['parent'] > 0) {
                $args['parent'] = $_REQUEST['parent'];
            } else {
                $args['parent'] = 0;
            }
        }

        return $args;
    }

    /**
     * Add: Fix WPML Admin Bar Language Switcher Links to Ignore Parent Field
     */
    public function language_switcher_links($languages_links) {
        if (isset($_REQUEST['parent'])) {
            if (!empty($languages_links)) {
                foreach ($languages_links as $lang_code => $lang) {
                    if (strstr($lang['url'], '&parent=' . $_REQUEST['parent']) !== false) {
                        $languages_links[$lang_code]['url'] = str_replace('&parent=' . $_REQUEST['parent'], '', $lang['url']);
                    }
                }
            }
        }

        return $languages_links;
    }

    /**
     * Terms Delete Form
     */
    public function terms_delete_form() {
        ?><form class="woo-vpf-ymm-terms-delete-form" method="post">
            <input type="hidden" name="taxonomy" value="<?php echo $_REQUEST['taxonomy']; ?>" />
            <input type="hidden" name="post_type" value="<?php echo $_REQUEST['post_type']; ?>" />
            <input type="hidden" name="vpf_action" value="woo_vpf_ymm_delete_terms" />

            <input type="submit" value="<?php _e('Delete All Terms', YMMP_FILTER_TEXT_DOMAIN); ?>" class="button button-primary" />
        </form><?php
    }

    /**
     * Terms Search Form
     */
    public function terms_search_form() {
        ?><div class="form-wrap woo-vpf-ymm-form-container woo-vpf-ymm-search-form-container">
            <h2><?php _e('Search Terms', YMMP_FILTER_TEXT_DOMAIN); ?></h2>

            <form method="get">
                <input type="hidden" name="parent" class="woo_vpf_ymm_term_id" value="" />

                <?php
                if (!empty($_REQUEST)) {
                    foreach ($_REQUEST as $key => $value) {
                        if ($key == 's' || $key == 'parent') {
                            continue;
                        }
                        ?><input type="hidden" name="<?php echo $key; ?>" value="<?php echo $value; ?>" /><?php
                    }
                }

                $parent_terms = array();

                if (isset($_REQUEST['parent']) && $_REQUEST['parent'] > 0) {
                    $parent_terms = WAR_FILTER_Functions::get_parent_terms_hierarchy($_REQUEST['parent']);
                }

                if ($this->current_term_level['level'] > 1) {
                    ?><div class="form-field">
                        <select class="woo_vpf_ymm_width">
                            <option value=""><?php printf(__('-- Select %s --', YMMP_FILTER_TEXT_DOMAIN), $this->label_width); ?></option>

                            <?php WAR_FILTER_Functions::get_terms_list(0, isset($parent_terms['width']['id']) ? $parent_terms['width']['id'] : '' ); ?>
                        </select>
                    </div><?php
                }

                if ($this->current_term_level['level'] > 2) {
                    ?><div class="form-field">
                        <select class="woo_vpf_ymm_aspect">
                            <option value=""><?php printf(__('-- Select %s --', YMMP_FILTER_TEXT_DOMAIN), $this->label_aspect); ?></option>

                            <?php WAR_FILTER_Functions::get_terms_list(isset($parent_terms['width']['id']) ? $parent_terms['width']['id'] : '', isset($parent_terms['aspect']['id']) ? $parent_terms['aspect']['id'] : '' ); ?>
                        </select>
                    </div><?php
                }

                if ($this->current_term_level['level'] > 3) {
                    ?><div class="form-field">
                        <select class="woo_vpf_ymm_rim">
                            <option value=""><?php printf(__('-- Select %s --', YMMP_FILTER_TEXT_DOMAIN), $this->label_rim); ?></option>

                            <?php WAR_FILTER_Functions::get_terms_list(isset($parent_terms['aspect']['id']) ? $parent_terms['aspect']['id'] : '', isset($parent_terms['rim']['id']) ? $parent_terms['rim']['id'] : '' ); ?>
                        </select>
                    </div><?php
                }
                ?>

                <div class="form-field">
                    <input type="text" name="s" placeholder="<?php _e('Keyword', YMMP_FILTER_TEXT_DOMAIN); ?>" value="<?php echo ( isset($_REQUEST['s']) ? $_REQUEST['s'] : '' ); ?>" />
                </div>

                <p class="submit">
                    <input type="submit" value="<?php _e('Submit', YMMP_FILTER_TEXT_DOMAIN); ?>" class="button button-primary" />
                </p>
            </form>
        </div><?php
    }

    /**
     * Remove from FORM dropdown: Filter get_terms args to show only one level terms
     */
    public function term_form_dropdown_terms_args($args, $taxonomies) {

        $parent = false;
        if (isset($_REQUEST['parent']) && $_REQUEST['parent'] > 0) {
            $parent = $_REQUEST['parent'];
        } else if (isset($_REQUEST['tag_ID']) && $_REQUEST['tag_ID'] > 0) {
            $term = get_term_by('id', $_REQUEST['tag_ID'], 'product_war');
            if (!empty($term)) {
                $parent = $term->parent;
            }
        }

        $args['ignore_parent'] = true;

        if ($parent) {

            $args['include'] = array($parent);
            $args['show_option_none'] = false;
        } else {
            $args['include'] = array('-1');
        }

        return $args;
    }

}

new YMMP_FILTER_Taxonomy_war_Terms_List();
