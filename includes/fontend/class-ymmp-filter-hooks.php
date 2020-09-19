<?php
/**
 * YMMP_FILTER Hooks
 *
 * @class YMMP_FILTER_Hooks
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class YMMP_FILTER_Hooks {

	/**
	 * @var page_title
	 */
	public $page_title = '';

	/**
	 * @var searched_term_id
	 */
	public $searched_term_id = '';
	
	/**
	 * @var parent_term_id
	 */
	public $parent_term_id = '';

	/**
	 * Constructor
	 *
	 * @access public
	 */
	public function __construct() {
		// Start Session
		add_action( 'init', array($this, 'session_init') );
		
		// Filter query hook to include YMMP_FILTER search criteria
		add_action( 'woocommerce_product_query', array($this, 'filter_posts') );
		add_filter( 'posts_where', array($this, 'filter_posts_where'), 10, 2 );
		
		// Speed Hack - Filter query not to create a cache for plugin custom taxonomy
		add_action( 'pre_get_posts', array($this, 'disable_post_term_cache') );
		add_action( 'get_terms_args', array($this, 'disable_get_terms_cache') );
		
		// Override search page title
		//add_filter( 'get_search_query', array($this, 'search_page_html_esc_title') );
		add_filter( 'woocommerce_page_title', array($this, 'search_page_title') );
		
		// Override WooCommerce Product Archive Template Option
		add_action( 'woocommerce_before_main_content', array($this, 'override_shop_page_display_option') );
		add_action( 'woocommerce_after_main_content', array($this, 'revert_shop_page_display_option') );
		
		// Add additional tab on product detail page
		add_filter( 'woocommerce_product_tabs', array($this, 'product_tab') );
		
		// Enable/Disable single result redirection to product details page
		add_filter( 'woocommerce_redirect_single_search_result', array($this, 'disable_woocommerce_single_result_redirection'), 99 );
		add_action ( 'template_redirect', array($this, 'redirect_single_search_result') );
		
		// Fix: Duplicate Products doesn't duplicate VPF Term Relations
		add_action ( 'woocommerce_product_duplicate', array( $this, 'duplicate_product_terms' ), 99, 2 );
		
		// Yoast SEO Plugin Hack: Speed issue conflict with Yoast SEO Plugin
		add_filter( 'wpseo_primary_term_taxonomies', array($this, 'wpseo_taxonomies') );
		
		// WooCommerce Layered Nav Filters: Conflict as This Widget doesn't Maintain VPF Search Results
		add_filter( 'woocommerce_layered_nav_link', array($this, 'woocommerce_layered_nav_link') );
		
		// Fix No Child Term Conflicts when having Parent Arg with get_terms
		add_filter( 'get_terms_args', array( $this, 'get_queried_parent_term_id' ), 99, 2 );
		
		if( YMMP_FILTER_Functions::is_wpml_activated() ) {
			$languages = icl_get_languages();
			
			if( ! empty( $languages ) ) {
				foreach( $languages as $language ) {
					add_filter( 'default_option_product_ymm_children_' . $language['code'], array( $this, 'fix_no_child_terms_conflict' ), 10, 2 );
					add_filter( 'option_product_ymm_children_' . $language['code'], array( $this, 'fix_no_child_terms_conflict' ), 10, 2 );
				}
			}
		}
		add_filter( 'default_option_product_ymm_children', array( $this, 'fix_no_child_terms_conflict' ), 10, 2 );
		add_filter( 'option_product_ymm_children', array( $this, 'fix_no_child_terms_conflict' ), 10, 2 );
	}
	
	/**
	 * Start Session
	 */
	function session_init() {
		if( !session_id() ) {
			session_start();
		}
		
		// Declare VPF Session
		if( ! isset( $_SESSION['vpf_ymm'] ) ) {
			$_SESSION['vpf_ymm'] = array();
		}
		
		// Get Current Language
		if( YMMP_FILTER_Functions::is_wpml_activated() ) {
			if( isset( $_SESSION['vpf_ymm']['current_lang'] ) && $_SESSION['vpf_ymm']['current_lang'] != ICL_LANGUAGE_CODE ) {
				unset( $_SESSION['vpf_ymm']['search'] );
			}
			
			$_SESSION['vpf_ymm']['current_lang'] = ICL_LANGUAGE_CODE;
		}
	}
	
	/**
	 * Include YMMP_FILTER query params in the query
	 */
	function filter_posts( $query ) {
		if( ! is_admin() && $query->is_main_query() ) {
		
			$is_filter_enable = false;
			$term_id = ''; $category_id = ''; $s = '';
			$woo_vpf_ymm_activate_remember_search = WC_Admin_Settings::get_option( 'woo_vpf_ymm_activate_remember_search' );
			
			if( YMMP_FILTER_Functions::is_search() ) {
				$is_filter_enable = true;
				
				$year = 0;
				if( isset( $_REQUEST['year_id'] ) ) {
					$year = $_REQUEST['year_id'];
				}
				
				$make = 0;
				if( isset( $_REQUEST['make'] ) ) {
					$make = $_REQUEST['make'];
				}
				
				$model = 0;
				if( isset( $_REQUEST['model'] ) ) {
					$model = $_REQUEST['model'];
				}
				
				$engine = 0;
				if( isset( $_REQUEST['engine'] ) ) {
					$engine = $_REQUEST['engine'];
				}
				
				$category_id = '';
				if( isset($_REQUEST['category']) ) {
					$category_id = $_REQUEST['category'];
				}
				
				$s = '';
				if( isset($_REQUEST['s']) ) {
					$s = esc_sql( $_REQUEST['s'] );
				}
				
				// Validate requested IDs
				$_error = false;
				
				if( ! $_error ) {
					if( $engine > 0 ) {
						$term_engine = get_term( $engine, 'product_ymm' );
						if( $term_engine && $term_engine->parent != $model ) {
							$_error = true;
						}
					}
				}
				
				if( ! $_error ) {
					if( $model > 0 ) {
						$term_model = get_term( $model, 'product_ymm' );
						if( $term_model && $term_model->parent != $make ) {
							$_error = true;
						}
					}
				}
				
				if( ! $_error ) {
					if( $make > 0 ) {
						$term_make = get_term( $make, 'product_ymm' );
						if( $term_make && $term_make->parent != $year ) {
							$_error = true;
						}
					}
				}
				
				$term_id = '';
				if( $_error ) {
					$term_id = -1;
				} else {
					if( $engine > 0 ) {
						$term_id = $engine;
					} else if( $model > 0 ) {
						$term_id = $model;
					} else if( $make > 0 ) {
						$term_id = $make;
					} else if( $year > 0 ) {
						$term_id = $year;
					}
				}
				
				// Remember User Search
				if( $woo_vpf_ymm_activate_remember_search == 'yes' ) {
					$_SESSION['vpf_ymm']['search']['year_id'] = $year;
					$_SESSION['vpf_ymm']['search']['make'] = $make;
					$_SESSION['vpf_ymm']['search']['model'] = $model;
					$_SESSION['vpf_ymm']['search']['engine'] = $engine;
					
					$_SESSION['vpf_ymm']['search']['term_id'] = $term_id;
				}
				
			} else if( $woo_vpf_ymm_activate_remember_search == 'yes' ) {
				if( isset( $_SESSION['vpf_ymm']['search'] ) && isset( $_SESSION['vpf_ymm']['search']['term_id'] ) ) {
					$is_filter_enable = true;
					$term_id = $_SESSION['vpf_ymm']['search']['term_id'];
				
					$year = isset( $_SESSION['vpf_ymm']['search']['year_id'] ) ? $_SESSION['vpf_ymm']['search']['year_id'] : 0;
					$make = isset( $_SESSION['vpf_ymm']['search']['make'] ) ? $_SESSION['vpf_ymm']['search']['make'] : 0;
					$model = isset( $_SESSION['vpf_ymm']['search']['model'] ) ? $_SESSION['vpf_ymm']['search']['model'] : 0;
					$engine = isset( $_SESSION['vpf_ymm']['search']['engine'] ) ? $_SESSION['vpf_ymm']['search']['engine'] : 0;
				}
			}
			
			if( $is_filter_enable ) {
			
				do_action( 'woo_vpf_ymm_before_search_query', array(
					'year_id'	=> $year,
					'make'		=> $make,
					'model'		=> $model,
					'engine'	=> $engine,
					'category'	=> $category_id
				) );
							
				// Set Post Type
				$query->set('post_type', 'product');
				
				// Set Taxonomies Queries
				$tax_query = $query->get('tax_query');
				
				if( empty( $tax_query ) ) {
					$tax_query = array();
				}
				
				if( $term_id != '' ) {
					$tax_query[] = array(
						'taxonomy'			=> 'product_ymm',
						'field'				=> 'id',
						'terms'				=> array($term_id),
						'include_children'	=> false
					);
				}
				
				if( $category_id != '' ) {					
					$tax_query[] = array(
						'taxonomy'			=> 'product_cat',
						'field'				=> 'id',
						'terms'				=> array($category_id),
						'include_children'	=> false
					);
				}
				
				$query->set('tax_query', $tax_query);
				
				// Set Keyword Query
				if( $s != '' ) {					
					$query->set('s', $s);
				}
				
				$this->searched_term_id = $term_id;
				
				do_action( 'woo_vpf_ymm_after_search_query', array(
					'year_id'	=> $year,
					'make'		=> $make,
					'model'		=> $model,
					'engine'	=> $engine,
					'category'	=> $category_id
				) );
			}
		}
	}
	
	/**
	 * Inclulde Universal Products Always
	 */
	function filter_posts_where( $where, $query ) {
		if( ! is_admin() && $query->is_main_query() ) {
			if( $this->searched_term_id ) {
				global $wpdb;
				$term_taxonomy_id = $wpdb->get_var( "SELECT `term_taxonomy_id` FROM $wpdb->term_taxonomy WHERE `term_id`='{$this->searched_term_id}'" );
				if( $term_taxonomy_id ) {
					$woo_vpf_ymm_taxonomy_metabox_excluded_products = WC_Admin_Settings::get_option( 'woo_vpf_ymm_taxonomy_metabox_excluded_products' );
					if( ! empty( $woo_vpf_ymm_taxonomy_metabox_excluded_products ) ) {
						if( is_array( $woo_vpf_ymm_taxonomy_metabox_excluded_products ) ) {
							$woo_vpf_ymm_taxonomy_metabox_excluded_products = implode( ',', $woo_vpf_ymm_taxonomy_metabox_excluded_products );
						}
						
						$where = preg_replace( '!\s+!', ' ', $where );
						$where = str_replace( "$wpdb->term_relationships.term_taxonomy_id IN ($term_taxonomy_id)", "( $wpdb->posts.ID IN($woo_vpf_ymm_taxonomy_metabox_excluded_products) OR $wpdb->term_relationships.term_taxonomy_id IN ($term_taxonomy_id) )", $where );
					}
				}
			}
		}
		
		return $where;
	}
	
	/**
	 * Fix - Speed up the taxonomy related queries by disabling post terms cache
	 */
	function disable_post_term_cache( $query ) {
		$post_types = $query->get('post_type');
		if( ! empty( $post_types ) ) {
			if( ! is_array( $post_types ) ) {
				$post_types = array( $post_types );
			}
			
			if( in_array( 'product', $post_types ) ) {
				$query->set('update_post_term_cache', false);
			}
		}
	}
	
	/**
	 * Fix - Speed up the taxonomy related queries by disabling post terms cache
	 */
	function disable_get_terms_cache( $args ) {
		if( isset( $args['taxonomy'] ) && ! empty( $args['taxonomy'] ) ) {
			if( in_array( 'product_ymm', $args['taxonomy'] ) ) {
				$args['update_term_meta_cache'] = false;
			}
		}

		return $args;
	}
	
	/**
	 * Override search page title with YMMP_FILTER title ( Breadcrumbs / Page Meta Title )
	 */
	function search_page_html_esc_title( $title ) {
		if( YMMP_FILTER_Functions::is_search() ) {
			$title = YMMP_FILTER_Hooks::search_page_title( $title );
			$title = wp_strip_all_tags( $title );
			
			$title_search_results_label = YMMP_FILTER_Functions::get_search_results_label();
			if( ! empty( $title_search_results_label ) ) {
				$title = trim( str_replace( $title_search_results_label, '', $title ) );
			}
		}
		
		return $title;
	}
	
	/**
	 * Override search page title with YMMP_FILTER title
	 */
	function search_page_title( $title ) {
		if( YMMP_FILTER_Functions::is_search() ) {
		
			// If Title Already Generated
			if( $this->page_title != '' ) {
				return $this->page_title;
			}
		
			// Year Title
			$title_year = '';
			if( isset( $_REQUEST['year_id'] ) ) {
				$year = $_REQUEST['year_id'];
				if( $year > 0 ) {
					$term_year = get_term( $year, 'product_ymm' );
					if( !empty( $term_year ) ) {
						$title_year = sprintf( __( '<span class="search_col search_col_year">%s: <span class="search_col_val">%s</span></span>,', YMMP_FILTER_TEXT_DOMAIN ), YMMP_FILTER_Functions::get_year_label(), $term_year->name );
					}
				}
			}
			
			// Make Title
			$title_make = '';
			if( isset( $_REQUEST['make'] ) ) {
				$make = $_REQUEST['make'];
				if( $make > 0 ) {
					$term_make = get_term( $make, 'product_ymm' );
					if( !empty( $term_make ) ) {
						$title_make = sprintf( __( '<span class="search_col search_col_make">%s: <span class="search_col_val">%s</span></span>,', YMMP_FILTER_TEXT_DOMAIN ), YMMP_FILTER_Functions::get_make_label(), $term_make->name );
					}
				}
			}
			
			// Model Title
			$title_model = '';
			if( isset( $_REQUEST['model'] ) ) {
				$model = $_REQUEST['model'];
				if( $model > 0 ) {
					$term_model = get_term( $model, 'product_ymm' );
					if( !empty( $term_model ) ) {
						$title_model = sprintf( __( '<span class="search_col search_col_model">%s: <span class="search_col_val">%s</span></span>,', YMMP_FILTER_TEXT_DOMAIN ), YMMP_FILTER_Functions::get_model_label(), $term_model->name );
					}
				}
			}
			
			// Engine Title
			$title_engine = '';
			if( isset( $_REQUEST['engine'] ) ) {
				$engine = $_REQUEST['engine'];
				if( $engine > 0 ) {
					$term_engine = get_term( $engine, 'product_ymm' );
					if( !empty( $term_engine ) ) {
						$title_engine = sprintf( __( '<span class="search_col search_col_engine">%s: <span class="search_col_val">%s</span></span>,', YMMP_FILTER_TEXT_DOMAIN ), YMMP_FILTER_Functions::get_engine_label(), $term_engine->name );
					}
				}
			}
			
			// Category Title
			$title_category = '';
			if( isset( $_REQUEST['category'] ) ) {
				$category = $_REQUEST['category'];
				if( $category > 0 ) {
					$term_category = get_term( $category, 'product_cat' );
					if( !empty( $term_category ) ) {
						$title_category = sprintf( __( '<span class="search_col search_col_category">%s: <span class="search_col_val">%s</span></span>,', YMMP_FILTER_TEXT_DOMAIN ), YMMP_FILTER_Functions::get_category_label(), $term_category->name );
					}
				}
			}
			
			// Keyword Title
			$title_s = '';
			if( isset( $_REQUEST['s'] ) ) {
				$s = trim( stripslashes( esc_sql( $_REQUEST['s'] ) ) );
				if( $s != '' ) {
					$title_s = sprintf( __( '<span class="search_col search_col_keyword">%s: <span class="search_col_val">%s</span></span>,', YMMP_FILTER_TEXT_DOMAIN ), YMMP_FILTER_Functions::get_keyword_label(), $s );
				}
			}
			
			// Complete Page Title
			$title_search_results_label = YMMP_FILTER_Functions::get_search_results_label();
			
			$title = sprintf( __( '%s %s %s %s %s %s %s', YMMP_FILTER_TEXT_DOMAIN ), $title_search_results_label, $title_year, $title_make, $title_model, $title_engine, $title_category, $title_s );
			
			$title = trim( trim( trim( $title, ' ' ), ',' ), ' ' );
			$title = '<span class="woo-vpf-ymm-search-title">' . $title . '</span>';
			
			$title = apply_filters( 'woo_vpf_ymm_search_page_title', $title, $title_search_results_label, $title_year, $title_make, $title_model, $title_engine, $title_category, $title_s );
			
			$this->page_title = $title;
		}
		
		return $title;
	}
	
	/**
	 * Override WooCommerce Product Archive Template Option
	 */
	function override_shop_page_display_option() {
		if( YMMP_FILTER_Functions::is_search() ) {
			add_filter( 'default_option_woocommerce_shop_page_display', array( $this, 'override_shop_page_template' ), 10, 2 );
			add_filter( 'option_woocommerce_shop_page_display', array( $this, 'override_shop_page_template' ), 10, 2 );
		}
	}
	
	/**
	 * Revert WooCommerce Categories List Template Option
	 */
	function revert_shop_page_display_option() {
		if( YMMP_FILTER_Functions::is_search() ) {
			remove_filter( 'default_option_woocommerce_shop_page_display', array( $this, 'override_shop_page_template' ), 10, 2 );
			remove_filter( 'option_woocommerce_shop_page_display', array( $this, 'override_shop_page_template' ), 10, 2 );
		}
	}
	
	/**
	 * Archive Template to Show Only Products Even When No Search Keyword
	 */
	function override_shop_page_template( $value, $option ) {
		if( YMMP_FILTER_Functions::is_search() ) {
			$value = '';
		}
		
		return $value;
	}
	
	/**
	 * Additional VPF tab on product details page
	 */
	function product_tab( $tabs ) {
		$woo_vpf_ymm_activate_tab = WC_Admin_Settings::get_option( 'woo_vpf_ymm_activate_tab' );
		if( $woo_vpf_ymm_activate_tab == 'yes' ) {
			global $post;
			$terms = wp_get_post_terms( $post->ID, 'product_ymm', array( 'fields' => 'ids', 'orderby' => 'parent', 'order' => 'ASC', 'parent' => 0 ) );
			if( ! empty( $terms ) ) {
				$woo_vpf_ymm_tab_title = WC_Admin_Settings::get_option( 'woo_vpf_ymm_tab_title' );
				if( $woo_vpf_ymm_tab_title == '' ) {
					$woo_vpf_ymm_tab_title = __( 'Fit for the Following Vehicles', YMMP_FILTER_TEXT_DOMAIN );
				}
				
				$tabs['vpf_ymm']		= array(
					'title'			=> apply_filters( 'woo_vpf_ymm_tab_title', $woo_vpf_ymm_tab_title ),
					'priority'		=> 50,
					'callback'		=> array($this, 'product_tab_content')
				);
			}
		}
		
		return $tabs;
	}
	
	/**
	 * Additional VPF tab callback
	 */
	function product_tab_content() {
		global $post;
		
		list( $post_terms, $years, $makes, $models, $engines ) = YMMP_FILTER_Functions::wp_get_post_terms_hierarchy( $post->ID );
		
		if( empty( $post_terms ) ) {
			return;
		}
		
		$has_engines = false;
		if( ! empty( $engines ) ) {
			$has_engines = true;
		}
		
		$year_label = YMMP_FILTER_Functions::get_year_label();
		$make_label = YMMP_FILTER_Functions::get_make_label();
		$model_label = YMMP_FILTER_Functions::get_model_label();
		$engine_label = YMMP_FILTER_Functions::get_engine_label();
		
		?><div class="woo-vpf-ymm-product-tab">
		
			<?php
				do_action( 'woo_vpf_ymm_product_tab_before_content' );
				
					$woo_vpf_ymm_tab_heading = WC_Admin_Settings::get_option( 'woo_vpf_ymm_tab_heading' );
					if( $woo_vpf_ymm_tab_heading != '' ) {
						echo apply_filters( 'woo_vpf_ymm_tab_heading_before', '<h2>' ) . $woo_vpf_ymm_tab_heading . apply_filters( 'woo_vpf_ymm_tab_heading_after', '</h2>' );
					}
				
					$woo_vpf_ymm_tab_description = WC_Admin_Settings::get_option( 'woo_vpf_ymm_tab_description' );
					if( $woo_vpf_ymm_tab_description != '' ) {
						echo apply_filters( 'woo_vpf_ymm_tab_description_before', '<p>' ) . $woo_vpf_ymm_tab_description . apply_filters( 'woo_vpf_ymm_tab_description_after', '</p>' );
					}
					
				do_action( 'woo_vpf_ymm_product_tab_after_content' );
			?>
			
			<?php do_action( 'woo_vpf_ymm_product_tab_before_list' ); ?>
			
				<table border="0">
					<thead>
						<tr>
							<th><?php echo $year_label; ?></th>
							<th><?php echo $make_label; ?></th>
							<th><?php echo $model_label; ?></th>
							
							<?php
								if( $has_engines ) {
									?><th><?php echo $engine_label; ?></th><?php
								}
							?>
						</tr>
					</thead>
					
					<tfoot>
						<tr>
							<th><?php echo $year_label; ?></th>
							<th><?php echo $make_label; ?></th>
							<th><?php echo $model_label; ?></th>
							
							<?php
								if( $has_engines ) {
									?><th><?php echo $engine_label; ?></th><?php
								}
							?>
						</tr>
					</tfoot>
					
					<?php
						if( isset( $post_terms['years'] ) && ! empty( $post_terms['years'] ) ) {
							?><tbody><?php
								$terms = array();
								$i = 0;
									
								$vpf_years = $post_terms['years'];
								foreach( $vpf_years as $vpf_year ) {
									$year_name = $vpf_year['term']->name;
									
									if( isset( $vpf_year['makes'] ) && !empty( $vpf_year['makes'] ) ) {
										$vpf_makes = $vpf_year['makes'];
										foreach( $vpf_makes as $vpf_make ) {
											$make_name = $vpf_make['term']->name;
											
											if( isset( $vpf_make['models'] ) && !empty( $vpf_make['models'] ) ) {
												$vpf_models = $vpf_make['models'];
												foreach( $vpf_models as $vpf_model ) {
													$model_name = $vpf_model['term']->name;
													
													if( isset( $vpf_model['engines'] ) && !empty( $vpf_model['engines'] ) ) {
														$vpf_engines = $vpf_model['engines'];
														foreach( $vpf_engines as $vpf_engine ) {
															$engine_name = $vpf_engine['term']->name;
															
															$terms[$i]['year'] = $year_name;
															$terms[$i]['make'] = $make_name;
															$terms[$i]['model'] = $model_name;
															$terms[$i]['engine'] = $engine_name;
															
															$i++;
														}
													} else {
														$terms[$i]['year'] = $year_name;
														$terms[$i]['make'] = $make_name;
														$terms[$i]['model'] = $model_name;
														$terms[$i]['engine'] = '';
														
														$i++;
													}
												}
											} else {
												$terms[$i]['year'] = $year_name;
												$terms[$i]['make'] = $make_name;
												$terms[$i]['model'] = '';
												$terms[$i]['engine'] = '';
												
												$i++;
											}
										}
									} else {
										$terms[$i]['year'] = $year_name;
										$terms[$i]['make'] = '';
										$terms[$i]['model'] = '';
										$terms[$i]['engine'] = '';
										
										$i++;
									}
								}
																
								if( ! empty( $terms ) ) {
									$woo_vpf_ymm_activate_tab_year_ranges = WC_Admin_Settings::get_option( 'woo_vpf_ymm_activate_tab_year_ranges' );
									if( $woo_vpf_ymm_activate_tab_year_ranges == 'yes' ) {
										$terms = YMMP_FILTER_Functions::arrange_terms_by_year_range( $terms );
									}
									
									$terms = YMMP_FILTER_Functions::sort_terms( $terms );
									$terms = apply_filters( 'woo_vpf_ymm_sorted_tab_terms', $terms );
									
									foreach( $terms as $term ) {
										?><tr>
											<td><?php echo $term['year']; ?></td>
											<td><?php echo $term['make']; ?></td>
											<td><?php echo $term['model']; ?></td>
											
											<?php
												if( $has_engines ) {
													?><td><?php echo $term['engine']; ?></td><?php
												}
											?>
										</tr><?php
									}
								}
							?></tbody><?php
						}
					?>
				</table>
			
			<?php do_action( 'woo_vpf_ymm_product_tab_after_list' ); ?>
			
		</div><?php
	}
	
	/**
	 * Disable WooCommerce Single Result Redirection
	 */
	function disable_woocommerce_single_result_redirection( $true ) {
		if( YMMP_FILTER_Functions::is_search() ) {
			$true = false;
		}
		
		return $true;
	}
	
	/**
	 * Enable/Disable Single Result Redirection to Product Details Page
	 */
	function redirect_single_search_result() {
		if( YMMP_FILTER_Functions::is_search() ) {
			$woo_vpf_ymm_disable_redirect_single_search_result = WC_Admin_Settings::get_option( 'woo_vpf_ymm_disable_redirect_single_search_result' );
			if( $woo_vpf_ymm_disable_redirect_single_search_result != 'yes' ) {
				global $wp_query;
				
				if( 1 === absint( $wp_query->found_posts ) ) {
					$product = wc_get_product( $wp_query->post );
				   
					if ( $product && $product->is_visible() ) {
						wp_safe_redirect( get_permalink( $product->id ), 302 );
						exit;
					}
				}
			}
		}
	}
	
	/**
	 * Enable/Disable Single Result Redirection to Product Details Page
	 */
	function duplicate_product_terms( $duplicate, $product ) {
		if( empty( $product ) ) {
			return;
		}
	   
		if( empty( $duplicate ) ) {
			return;
		}
	   
		$duplicate_product_term_ids = wp_get_post_terms( $product->get_id(), 'product_ymm', array( 'fields' => 'ids' ) );
		if( empty( $duplicate_product_term_ids ) ) {
			$term_ids = wp_get_post_terms( $product->get_id(), 'product_ymm', array( 'fields' => 'ids' ) );
			if( ! empty( $term_ids ) ) {
				$term_ids = array_map( 'intval', $term_ids );
				$term_ids = array_unique( $term_ids );
				wp_set_object_terms( $duplicate->get_id(), $term_ids, 'product_ymm', false );
			}
		}
	}
	
	/**
	 * Yoast SEO Plugin Hack: Speed issue conflict with Yoast SEO Plugin
	 */
	function wpseo_taxonomies( $taxonomies ) {
		if( isset( $taxonomies['product_ymm'] ) ) {
			unset( $taxonomies['product_ymm'] );
		}
		
		return $taxonomies;
	}
	
	/**
	 * WooCommerce Layered Nav Filters: Conflict as This Widget doesn't Maintain VPF Search Results
	 */
	function woocommerce_layered_nav_link( $link ) {
		if( YMMP_FILTER_Functions::is_search() ) {
			if( isset( $_REQUEST['year_id'] ) ) {
				$link = add_query_arg( 'year_id', $_REQUEST['year_id'], $link );
			}
			
			if( isset( $_REQUEST['make'] ) ) {
				$link = add_query_arg( 'make', $_REQUEST['make'], $link );
			}
			
			if( isset( $_REQUEST['model'] ) ) {
				$link = add_query_arg( 'model', $_REQUEST['model'], $link );
			}
		  
			if( isset( $_REQUEST['engine'] ) ) {
				$link = add_query_arg( 'engine', $_REQUEST['engine'], $link );
			}
		  
			if( isset( $_REQUEST['category'] ) ) {
				$link = add_query_arg( 'category', $_REQUEST['category'], $link );
			}
			
			if( isset( $_REQUEST['s'] ) ) {
				$link = add_query_arg( 's', esc_sql( $_REQUEST['s'] ), $link );
			}
		  
			if( isset( $_REQUEST['post_type'] ) ) {
				$link = add_query_arg( 'post_type', $_REQUEST['post_type'], $link );
			}
			  
			if( isset( $_REQUEST['action'] ) ) {
				$link = add_query_arg( 'action', $_REQUEST['action'], $link );
			}
		}
	   
		return $link;
	}
	
	/**
	 * Set Queried Parent Term ID
	 */
	function get_queried_parent_term_id( $args, $taxonomies ) {
		$taxonomies = $args['taxonomy'];
		
		if ( ! empty( $taxonomies ) && in_array( 'product_ymm', $taxonomies ) ) {
			if( $args['parent'] ) {
				$this->parent_term_id = $args['parent'];
			}
		}
		
		return $args;
	}
	
	/**
	 * Fix Admin - Fixed Error Saying "Unsupported operand types when no children"
	 * Fix Global - When "No Child" term with get_terms
	 */
	public function fix_no_child_terms_conflict( $value, $option ) {
		if( $this->parent_term_id > 0 ) {
			$value[ $this->parent_term_id ] = '';
		}
		
		return $value;
	}
	
}

new YMMP_FILTER_Hooks();
