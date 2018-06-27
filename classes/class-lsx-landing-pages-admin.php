<?php
/**
 * LSX Landing Pages Admin Class
 *
 * @package   uix_example
 * @author    LightSpeed
 * @license   GPL-2.0+
 * @link
 * @copyright 2018 LightSpeed
 */

class LSX_Landing_Pages_Admin {

	public function __construct() {
		if ( ! class_exists( 'CMB_Meta_Box' ) ) {
			require_once( LSXLDPG_PATH . '/vendor/Custom-Meta-Boxes/custom-meta-boxes.php' );
		}

		if ( function_exists( 'tour_operator' ) ) {
			$this->options = get_option( '_lsx-to_settings', false );
		} else {
			$this->options = get_option( '_lsx_settings', false );

			if ( false === $this->options ) {
				$this->options = get_option( '_lsx_lsx-settings', false );
			}
		}

		add_action( 'init', array( $this, 'lsx_landing_page_post_type' ) );
		// add_filter( 'cmb_meta_boxes', array( $this, 'field_setup' ) );
		// add_action( 'cmb_save_custom', array( $this, 'post_relations' ), 3, 20 );
		// add_action( 'admin_enqueue_scripts', array( $this, 'assets' ) );

		add_action( 'init', array( $this, 'create_settings_page' ), 100 );
		add_filter( 'lsx_framework_settings_tabs', array( $this, 'register_tabs' ), 100, 1 );

		// add_filter( 'type_url_form_media', array( $this, 'change_attachment_field_button' ), 20, 1 );
		// add_filter( 'enter_title_here', array( $this, 'change_title_text' ) );
	}

	// Register Custom Post Type
	function lsx_landing_page_post_type() {

		$labels = array(
			'name'                  => _x( 'Landing Pages', 'Post Type General Name', 'text_domain' ),
			'singular_name'         => _x( 'Landing Page', 'Post Type Singular Name', 'text_domain' ),
			'menu_name'             => __( 'Landing Pages', 'text_domain' ),
			'name_admin_bar'        => __( 'Landing Page', 'text_domain' ),
			'archives'              => __( 'Item Archives', 'text_domain' ),
			'parent_item_colon'     => __( 'Parent Item:', 'text_domain' ),
			'all_items'             => __( 'All Landing Pages', 'text_domain' ),
			'add_new_item'          => __( 'Add New Landing Page', 'text_domain' ),
			'add_new'               => __( 'Add New', 'text_domain' ),
			'new_item'              => __( 'New Landing Page', 'text_domain' ),
			'edit_item'             => __( 'Edit Landing Page', 'text_domain' ),
			'update_item'           => __( 'Update Landing Page', 'text_domain' ),
			'view_item'             => __( 'View Landing Page', 'text_domain' ),
			'search_items'          => __( 'Search Landing Page', 'text_domain' ),
			'not_found'             => __( 'Not found', 'text_domain' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
			'featured_image'        => __( 'Featured Image', 'text_domain' ),
			'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
			'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
			'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
			'insert_into_item'      => __( 'Insert into Landing Page', 'text_domain' ),
			'uploaded_to_this_item' => __( 'Uploaded to this Landing Page', 'text_domain' ),
			'items_list'            => __( 'Landing Pages list', 'text_domain' ),
			'items_list_navigation' => __( 'Landing Pages list navigation', 'text_domain' ),
			'filter_items_list'     => __( 'Filter Landing Page list', 'text_domain' ),
		);
		$rewrite = array(
			'slug'                  => 'go',
			'with_front'            => true,
			'pages'                 => false,
			'feeds'                 => false,
		);
		$args = array(
			'label'                 => __( 'Landing Page', 'text_domain' ),
			'description'           => __( 'Landing Page Definition', 'text_domain' ),
			'labels'                => $labels,
			'supports'              => array( 'title' ),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 5,
			'menu_icon'             => 'dashicons-welcome-widgets-menus',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => '',
			'exclude_from_search'   => true,
			'publicly_queryable'    => true,
			'rewrite'               => $rewrite,
			'capability_type'       => 'post',
		);
		register_post_type( 'lsx_landing_page', $args );

	}
	// add_action( 'init', 'lsx_landing_page_post_type', 0 );

	/**
	 * Returns the array of settings to the UIX Class
	 */
	public function create_settings_page() {
		if ( is_admin() ) {
			if ( ! class_exists( '\lsx\ui\uix' ) && ! function_exists( 'tour_operator' ) ) {
				include_once LSXLDPG_PATH . 'uix/uix.php';
				$pages = $this->settings_page_array();
				$uix = \lsx\ui\uix::get_instance( 'lsx' );
				$uix->register_pages( $pages );
			} else {
				$uix = \lsx\ui\uix::get_instance( 'lsx' );
			}

			// include the meta boxes
			if ( null !== $uix ) {
				$metaboxes = include LSXLDPG_PATH . 'includes/metaboxes.php';
				$uix->register_metaboxes( $metaboxes );
			}
			/*if ( function_exists( 'tour_operator' ) ) {
				add_action( 'lsx_to_framework_display_tab_content', array( $this, 'display_settings' ), 11 );
				} else {
				add_action( 'lsx_framework_display_tab_content', array( $this, 'display_settings' ), 11 );
			 }*/
		}
	}

	/**
	 * Returns the array of settings to the UIX Class
	 */
	public function settings_page_array() {
		$tabs = apply_filters( 'lsx_framework_settings_tabs', array() );

		return array(
			'settings'  => array(
				'page_title'  => esc_html__( 'Theme Options', 'text_domain' ),
				'menu_title'  => esc_html__( 'Theme Options', 'text_domain' ),
				'capability'  => 'manage_options',
				'icon'        => 'dashicons-book-alt',
				'parent'      => 'themes.php',
				'save_button' => esc_html__( 'Save Changes', 'text_domain' ),
				'tabs'        => $tabs,
			),
		);
	}

	/**
	 * Register tabs
	 */
	public function register_tabs( $tabs ) {
		$default = true;

		if ( false !== $tabs && is_array( $tabs ) && count( $tabs ) > 0 ) {
			$default = false;
		}

		if ( ! function_exists( 'tour_operator' ) ) {
			if ( ! array_key_exists( 'display', $tabs ) ) {
				$tabs['display'] = array(
					'page_title'       => '',
					'page_description' => '',
					'menu_title'       => esc_html__( 'Display', 'text_domain' ),
					'template'         => LSXLDPG_PATH . 'includes/settings/display.php',
					'default'          => $default,
				);

				$default = false;
			}
		}

		return $tabs;
	}

	/**
	 * Outputs the display tabs settings
	 *
	 * @param $tab string
	 * @return null
	 */
	public function display_settings( $tab = 'general' ) {
		if ( 'landing-pages' === $tab ) {
			echo ' ';
			/*$this->disable_single_post_field();
			$this->group_by_role_checkbox();
			$this->placeholder_field();
			$this->careers_cta_post_fields();*/
		}
	}

}

$lsx_landing_pages_admin = new LSX_Landing_Pages_Admin();
