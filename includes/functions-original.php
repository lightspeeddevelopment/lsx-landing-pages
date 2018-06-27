<?php
/**
 * Functions for this plugin
 *
 * @package   Lsx_Landing_Pages
 * @author    David Cramer
 * @license   GPL-2.0+
 * @link
 * @copyright 2015 David Cramer & CalderaWP LLC
 */
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
		'supports'              => array( 'title', ),
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
		'capability_type'       => 'page',
	);
	register_post_type( 'lsx_landing_page', $args );

}
add_action( 'init', 'lsx_landing_page_post_type', 0 );


function lsx_landing_pages_actions($actions, $post){
    //check for your post type
    if ($post->post_type =="lsx_landing_page"){
    	
    	$url = get_permalink( $post->ID );
    	$link = admin_url( 'customize.php?url=' . urlencode( $url ) );
    	$actions['customize'] =	'<a href="' . $link . '" title="' . esc_attr__( 'Customize', 'lsx-landing-pages' ) . '">' . esc_html__( 'Customize', 'lsx-landing-pages' ) . '</a>';
    }
    return $actions;
}
add_filter('post_row_actions','lsx_landing_pages_actions', 10, 2);

//add_filter( 'stylesheet_directory_uri', 'lsx_check_landing_page_bind', 9);
add_filter( 'stylesheet_directory', 'lsx_landing_page_style_check', 9 );
function lsx_check_landing_page_bind( $uri ){

	$url = $_SERVER['REQUEST_URI'];
	if( lsx_check_landing_page() ){
		return LSXLDPG_URL . 'framework';
	}
	return $uri;	
}
function lsx_landing_page_style_check( $dir ){
	$url = $_SERVER['REQUEST_URI'];
	if( lsx_check_landing_page() ){
		add_filter( 'customize_loaded_components', 'lsx_setup_components', 100 );
		return LSXLDPG_PATH . 'framework';
	}
	return $dir;
}

function lsx_check_landing_page(){
	$url = $_SERVER['REQUEST_URI'];
	if( basename( $_SERVER['SCRIPT_FILENAME'] ) === 'customize.php' ){		
		parse_str( $_SERVER['QUERY_STRING'], $query );
		if( !empty( $query['url'] ) ){
			$url = $query['url'];
		}
		// if saving
	}
	if( !empty( $_POST['action'] ) && $_POST['action'] == 'customize_save' ){
		$url = wp_get_referer();
		$parsed = parse_url( $url );
		if( 'customize.php' == basename( $parsed['path'] ) && !empty( $parsed['query'] ) ){
			$query = urldecode( $parsed['query'] );
			parse_str( $query, $ref );
			if( !empty( $ref['url'] ) ){
				$url = $ref['url'];
			}
		}
	}	
	if( false !== strpos( $url, '/go/' ) ){
		$landing_page = url_to_postid( $url );
		if( !empty( $landing_page ) ){
			return $landing_page;
		}
		return true;
	}	
	return false;
}

function lsx_landing_pages_customize_register( $wp_customize ) {
	$landing_id = lsx_check_landing_page();
	if( false === $landing_id ){
		return;
	}
	
	$structure = get_post_meta( $landing_id, '_lsx_layout', true );

	$remove = array(
		"display_header_text",
		"title_tagline",
		"colors",
		"header_image",
		"background_image",
		"static_front_page",
		"themes"
	);

	$sections = $wp_customize->sections();
	foreach( $sections as $section_id => $section ){
		if( false !== strpos( $section_id, 'sidebar-widgets-nd') ){
			continue;
		}
		$wp_customize->remove_section( $section_id );
	}

	foreach( $remove as $section ){
		$wp_customize->remove_section( $section );
	}


	foreach( $structure as $location=>$struct ){
		// Group Area
		if( !empty( $struct ) ){

			$index = 1;
			foreach( $struct as $node_id=>$area ){
				// rows / section
				$wp_customize->add_panel(
					'lsx_landing_pages_' . $location . '_' . $node_id,
					array(
						'title'     => ucwords( $location ) . ' ' . $index++,
						'priority'  => 20
					)
				);
				
				// Main area
				$wp_customize->add_section(
					'lsx_landing_pages_' . $location . '_' . $node_id . '_main',
					array(
						'panel'		=> 'lsx_landing_pages_' . $location . '_' . $node_id,
						'title'     => 'Area Settings',
						'priority'  => 20
					)
				);
				// setting
				$wp_customize->add_setting(
					'margin_' . $landing_id . '_' . $node_id,
					array(						
						'transport'  =>  'postMessage',
						'type'		 =>  'option',
						'capability' =>  'edit_theme_options'
					)
				);
				$wp_customize->add_setting(
					'padding_' . $landing_id . '_' . $node_id,
					array(						
						'transport'  =>  'postMessage',
						'type'		 =>  'option',
						'capability' =>  'edit_theme_options'
					)
				);
				$wp_customize->add_setting(
					'background_color_' . $landing_id . '_' . $node_id,
					array(						
						'transport'  =>  'postMessage',
						'type'		 =>  'option',
						'capability' =>  'edit_theme_options'
					)
				);

				$wp_customize->add_setting(
					'text_color_' . $landing_id . '_' . $node_id,
					array(						
						'transport'  =>  'postMessage',
						'type'		 =>  'option',
						'capability' =>  'edit_theme_options'
					)
				);
				$wp_customize->add_setting(
					'background_image_' . $landing_id . '_' . $node_id,
					array(
						'transport'  =>  'postMessage',
						'type'		 =>  'option',
						'capability' =>  'edit_theme_options'
					)
				);

				// controll
				$wp_customize->add_control( 
					new WP_Customize_Control( 
						$wp_customize, 
						'margin_' . $landing_id . '_' . $node_id,
						array(
							'label'      	=> __( 'Margin', 'lsx-landing-pages' ),
							'description'   => __( 'i.e "5px" or "5px 8px" etc.', 'lsx-landing-pages' ),
							'default'	 => '0',
							'type'		 => 'text',
							'section'    => 'lsx_landing_pages_' . $location . '_' . $node_id . '_main',
							'settings'   => 'margin_' . $landing_id . '_' . $node_id,
						)
					) 
				);
				$wp_customize->add_control( 
					new WP_Customize_Control( 
						$wp_customize, 
						'padding_' . $landing_id . '_' . $node_id,
						array(
							'label'      	=> __( 'Padding', 'lsx-landing-pages' ),
							'description'   => __( 'i.e "5px" or "5px 8px" etc.', 'lsx-landing-pages' ),
							'default'	 => '0',
							'type'		 => 'text',
							'section'    => 'lsx_landing_pages_' . $location . '_' . $node_id . '_main',
							'settings'   => 'padding_' . $landing_id . '_' . $node_id,
						)
					) 
				);
				$wp_customize->add_control( 
					new WP_Customize_Color_Control( 
						$wp_customize, 
						'background_color_' . $landing_id . '_' . $node_id,
						array(
							'label'      => __( 'Background Color', 'lsx-landing-pages' ),
							'section'    => 'lsx_landing_pages_' . $location . '_' . $node_id . '_main',
							'settings'   => 'background_color_' . $landing_id . '_' . $node_id,
						)
					) 
				);
				$wp_customize->add_control( 
					new WP_Customize_Color_Control( 
						$wp_customize, 
						'text_color_' . $landing_id . '_' . $node_id,
						array(
							'label'      => __( 'Text Color', 'lsx-landing-pages' ),
							'section'    => 'lsx_landing_pages_' . $location . '_' . $node_id . '_main',
							'settings'   => 'text_color_' . $landing_id . '_' . $node_id,
						)
					) 
				);
				$wp_customize->add_control(
					new WP_Customize_Image_Control(
						$wp_customize,
						'background_image_' . $landing_id . '_' . $node_id,
						array(
							'label'      => __( 'Background Image', 'lsx-landing-pages' ),
							'section'    => 'lsx_landing_pages_' . $location . '_' . $node_id . '_main',
							'settings'   => 'background_image_' . $landing_id . '_' . $node_id,
						)
					)
				);	
				$row_num = 1;
				foreach( $area['column'] as $row_id => $row ){

					// inner row
					// rows / section
					$title = ( !empty( $row['config']['name'] ) ? $row['config']['name'] : 'Row: '. $row_num );
					$wp_customize->add_section(
						'lsx_landing_pages_' . $location . '_' . $node_id . '_' . $row_id,
						array(
							'panel'		=> 'lsx_landing_pages_' . $location . '_' . $node_id,
							'title'     => $title,
							'priority'  => 20
						)
					);
					// inc
					$row_num++;

					$wp_customize->add_setting(
						'background_color_' . $landing_id . '_' . $node_id . '_' . $row_id,
						array(							
							'transport'  =>  'postMessage',
							'type'		 =>  'option',
							'capability' =>  'edit_theme_options'
						)
					);
					$wp_customize->add_setting(
						'text_color_' . $landing_id . '_' . $node_id . '_' . $row_id,
						array(							
							'transport'  =>  'postMessage',
							'type'		 =>  'option',
							'capability' =>  'edit_theme_options'
						)
					);
					
					$wp_customize->add_setting(
						'padding_' . $landing_id . '_' . $node_id . '_' . $row_id,
						array(							
							'transport'  =>  'postMessage',
							'type'		 =>  'option',
							'capability' =>  'edit_theme_options'
						)
					);
					$wp_customize->add_setting(
						'margin_' . $landing_id . '_' . $node_id . '_' . $row_id,
						array(							
							'transport'  =>  'postMessage',
							'type'		 =>  'option',
							'capability' =>  'edit_theme_options'
						)
					);

					$wp_customize->add_setting(
						'background_image_' . $landing_id . '_' . $node_id . '_' . $row_id,
						array(
							'transport'  =>  'postMessage',
							'type'		 =>  'option',
							'capability' =>  'edit_theme_options'
						)
					);
					$wp_customize->add_setting(
						'separator_' . $landing_id . '_' . $node_id . '_' . $row_id,
						array(
							'capability' =>  'edit_theme_options'
						)
					);



					// controll
					$wp_customize->add_control( 
						new LSX_Landing_Pages_Seperator_Control( 
							$wp_customize, 
							'separator_' . $landing_id . '_' . $node_id . '_' . $row_id,
							array(
								'label'      => __( 'Row Settings', 'lsx-landing-pages' ),
								'section'    => 'lsx_landing_pages_' . $location . '_' . $node_id . '_' . $row_id,
							)
						) 
					);

					$wp_customize->add_control( 
						new WP_Customize_Control( 
							$wp_customize, 
							'padding_' . $landing_id . '_' . $node_id . '_' . $row_id,
							array(
								'label'      	=> __( 'Padding', 'lsx-landing-pages' ),
								'description'   => __( 'i.e "5px" or "5px 8px" etc.', 'lsx-landing-pages' ),
								'default'	 => '0',
								'type'		 => 'text',
								'section'    => 'lsx_landing_pages_' . $location . '_' . $node_id . '_' . $row_id,
								'settings'   => 'padding_' . $landing_id . '_' . $node_id . '_' . $row_id,
							)
						) 
					);
					$wp_customize->add_control( 
						new WP_Customize_Control( 
							$wp_customize, 
							'margin_' . $landing_id . '_' . $node_id . '_' . $row_id,
							array(
								'label'      	=> __( 'Margin', 'lsx-landing-pages' ),
								'description'   => __( 'i.e "5px" or "5px 8px" etc.', 'lsx-landing-pages' ),
								'default'	 => '0 15px',
								'section'    => 'lsx_landing_pages_' . $location . '_' . $node_id . '_' . $row_id,
								'type'		 => 'text',
								'settings'   => 'margin_' . $landing_id . '_' . $node_id . '_' . $row_id,
							)
						) 
					);

					$wp_customize->add_control( 
						new WP_Customize_Color_Control( 
							$wp_customize, 
							'background_color_' . $landing_id . '_' . $node_id . '_' . $row_id,
							array(
								'label'      => __( 'Background Color', 'lsx-landing-pages' ),
								'section'    => 'lsx_landing_pages_' . $location . '_' . $node_id . '_' . $row_id,
								'settings'   => 'background_color_' . $landing_id . '_' . $node_id . '_' . $row_id,
							)
						) 
					);
					$wp_customize->add_control( 
						new WP_Customize_Color_Control( 
							$wp_customize, 
							'text_color_' . $landing_id . '_' . $node_id . '_' . $row_id,
							array(
								'label'      => __( 'Text Color', 'lsx-landing-pages' ),
								'section'    => 'lsx_landing_pages_' . $location . '_' . $node_id . '_' . $row_id,
								'settings'   => 'text_color_' . $landing_id . '_' . $node_id . '_' . $row_id,
							)
						) 
					);
					$wp_customize->add_control(
						new WP_Customize_Image_Control(
							$wp_customize,
							'background_image_' . $landing_id . '_' . $node_id . '_' . $row_id,
							array(
								'label'      => __( 'Background Image', 'lsx-landing-pages' ),
								'section'    => 'lsx_landing_pages_' . $location . '_' . $node_id . '_' . $row_id,
								'settings'   => 'background_image_' . $landing_id . '_' . $node_id . '_' . $row_id,
							)
						)
					);	



					// columns
					$column_no = 1;
					foreach( $row['struct']['column'] as $column ){

						$wp_customize->add_setting(
							'separator_' . $landing_id . '_' . $node_id . '_' . $row_id . '_' . $column['id'],
							array(
								'capability' =>  'edit_theme_options'
							)
						);
						$wp_customize->add_setting(
							'background_color_' . $landing_id . '_' . $node_id . '_' . $row_id . '_' . $column['id'],
							array(								
								'transport'  =>  'postMessage',
								'type'		 =>  'option',
								'capability' =>  'edit_theme_options'
							)
						);
						$wp_customize->add_setting(
							'margin_' . $landing_id . '_' . $node_id . '_' . $row_id . '_' . $column['id'],
							array(								
								'transport'  =>  'postMessage',
								'type'		 =>  'option',
								'capability' =>  'edit_theme_options'
							)
						);
						$wp_customize->add_setting(
							'padding_' . $landing_id . '_' . $node_id . '_' . $row_id . '_' . $column['id'],
							array(								
								'transport'  =>  'postMessage',
								'type'		 =>  'option',
								'capability' =>  'edit_theme_options'
							)
						);
						$wp_customize->add_setting(
							'text_color_' . $landing_id . '_' . $node_id . '_' . $row_id . '_' . $column['id'],
							array(								
								'transport'  =>  'postMessage',
								'type'		 =>  'option',
								'capability' =>  'edit_theme_options'
							)
						);
						$wp_customize->add_setting(
							'background_image_' . $landing_id . '_' . $node_id . '_' . $row_id . '_' . $column['id'],
							array(
								'transport'  =>  'postMessage',
								'type'		 =>  'option',
								'capability' =>  'edit_theme_options'
							)
						);


						// controll
						$wp_customize->add_control( 
							new LSX_Landing_Pages_Seperator_Control( 
								$wp_customize, 
								'separator_' . $landing_id . '_' . $node_id . '_' . $row_id . '_' . $column['id'],
								array(
									'label'      => __('Column', 'lsx-landing-pages') .' '. $column_no++,
									'section'    => 'lsx_landing_pages_' . $location . '_' . $node_id . '_' . $row_id,
								)
							) 
						);


						$wp_customize->add_control( 
							new WP_Customize_Control( 
								$wp_customize, 
								'margin_' . $landing_id . '_' . $node_id . '_' . $row_id . '_' . $column['id'],
								array(
									'label'      => __( 'Margin', 'lsx-landing-pages' ),
									'description'   => __( 'i.e "5px" or "5px 8px" etc.', 'lsx-landing-pages' ),
									'default'	 => '0',
									'type'		 => 'text',
									'section'    => 'lsx_landing_pages_' . $location . '_' . $node_id . '_' . $row_id,
									'settings'   => 'margin_' . $landing_id . '_' . $node_id . '_' . $row_id . '_' . $column['id'],
								)
							) 
						);

						$wp_customize->add_control( 
							new WP_Customize_Control( 
								$wp_customize, 
								'padding_' . $landing_id . '_' . $node_id . '_' . $row_id . '_' . $column['id'],
								array(
									'label'      => __( 'Padding', 'lsx-landing-pages' ),
									'description'   => __( 'i.e "5px" or "5px 8px" etc.', 'lsx-landing-pages' ),
									'default'	 => '0',
									'type'		 => 'text',
									'section'    => 'lsx_landing_pages_' . $location . '_' . $node_id . '_' . $row_id,
									'settings'   => 'padding_' . $landing_id . '_' . $node_id . '_' . $row_id . '_' . $column['id'],
								)
							) 
						);

						$wp_customize->add_control( 
							new WP_Customize_Color_Control( 
								$wp_customize, 
								'background_color_' . $landing_id . '_' . $node_id . '_' . $row_id . '_' . $column['id'],
								array(
									'label'      => __( 'Background Color', 'lsx-landing-pages' ),
									'section'    => 'lsx_landing_pages_' . $location . '_' . $node_id . '_' . $row_id,
									'settings'   => 'background_color_' . $landing_id . '_' . $node_id . '_' . $row_id . '_' . $column['id'],
								)
							) 
						);
						$wp_customize->add_control( 
							new WP_Customize_Color_Control( 
								$wp_customize, 
								'text_color_' . $landing_id . '_' . $node_id . '_' . $row_id . '_' . $column['id'],
								array(
									'label'      => __( 'Background Color', 'lsx-landing-pages' ),
									'section'    => 'lsx_landing_pages_' . $location . '_' . $node_id . '_' . $row_id,
									'settings'   => 'text_color_' . $landing_id . '_' . $node_id . '_' . $row_id . '_' . $column['id'],
								)
							) 
						);
						$wp_customize->add_control(
							new WP_Customize_Image_Control(
								$wp_customize,
								'background_image_' . $landing_id . '_' . $node_id . '_' . $row_id . '_' . $column['id'],
								array(
									'label'      => __( 'Background Image', 'lsx-landing-pages' ),
									'section'    => 'lsx_landing_pages_' . $location . '_' . $node_id . '_' . $row_id,
									'settings'   => 'background_image_' . $landing_id . '_' . $node_id . '_' . $row_id . '_' . $column['id'],
								)
							)
						);	


					}




				}
		


			}
		}
		

	}

	add_filter( 'pre_option_blogname', 'lsx_set_title', 10, 2 );

}


function lsx_set_title( $a ){
	$landing_page = lsx_check_landing_page();
	if( false === $landing_page ){
		return $a;
	}
	$page = get_post( $landing_page );
	return $page->post_title;

}
add_action( 'customize_register', 'lsx_landing_pages_customize_register', 100 );


function lsx_setup_components( $wp_customize ) {
	return array( 'widgets' );
}

function lsx_landing_pages_print_script(){
	$landing_page = lsx_check_landing_page();
	$structure = get_post_meta( $landing_page, '_lsx_layout', true );

	?>
<script type="text/javascript">

!(function( $ ) {
	"use strict";
	
	<?php
		$structure = get_post_meta( $landing_page, '_lsx_layout', true );

		foreach( $structure as $location=>$struct ){
			// Group Area
			if( !empty( $struct ) ){

				$index = 1;
				foreach( $struct as $node_id=>$area ){
					?>
					wp.customize( 'margin_<?php echo $landing_page; ?>_<?php echo $node_id; ?>', function( value ) {
						value.bind( function( to ) {
							$( '#landing-page-<?php echo $location; ?>-<?php echo $node_id; ?>' ).css( 'margin', to );
						} );
					});	
					wp.customize( 'padding_<?php echo $landing_page; ?>_<?php echo $node_id; ?>', function( value ) {
						value.bind( function( to ) {
							$( '#landing-page-<?php echo $location; ?>-<?php echo $node_id; ?>' ).css( 'padding', to );
						} );
					});	
					wp.customize( 'background_color_<?php echo $landing_page; ?>_<?php echo $node_id; ?>', function( value ) {
						value.bind( function( to ) {
							$( '#landing-page-<?php echo $location; ?>-<?php echo $node_id; ?>' ).css( 'background-color', to );
						} );
					});	
					wp.customize( 'text_color_<?php echo $landing_page; ?>_<?php echo $node_id; ?>', function( value ) {
						value.bind( function( to ) {
							$( '#landing-page-<?php echo $location; ?>-<?php echo $node_id; ?>' ).css( 'color', to );
						} );
					});	
					wp.customize( 'background_image_<?php echo $landing_page; ?>_<?php echo $node_id; ?>', function( value ) {
						value.bind( function( to ) {
							$( '#landing-page-<?php echo $location; ?>-<?php echo $node_id; ?>' ).css( 'background-image', 'url(' + to + ')');
						} );
					});
					<?php
					foreach( $area['column'] as $row_id => $row ){

							?>							
							wp.customize( 'padding_<?php echo $landing_page; ?>_<?php echo $node_id; ?>_<?php echo $row_id; ?>', function( value ) {								
								value.bind( function( to ) {
									$( '#landing-page-row-<?php echo $row_id; ?>' ).css( 'padding', to );
								} );
							});	
							wp.customize( 'margin_<?php echo $landing_page; ?>_<?php echo $node_id; ?>_<?php echo $row_id; ?>', function( value ) {								
								value.bind( function( to ) {
									$( '#landing-page-row-<?php echo $row_id; ?>' ).css( 'margin', to );
								} );
							});	
							wp.customize( 'background_color_<?php echo $landing_page; ?>_<?php echo $node_id; ?>_<?php echo $row_id; ?>', function( value ) {								
								value.bind( function( to ) {
									$( '#landing-page-row-<?php echo $row_id; ?>' ).css( 'background-color', to );
								} );
							});	
							wp.customize( 'text_color_<?php echo $landing_page; ?>_<?php echo $node_id; ?>_<?php echo $row_id; ?>', function( value ) {
								value.bind( function( to ) {
									$( '#landing-page-row-<?php echo $row_id; ?>' ).css( 'color', to );
								} );
							});	
							wp.customize( 'background_image_<?php echo $landing_page; ?>_<?php echo $node_id; ?>_<?php echo $row_id; ?>', function( value ) {
								value.bind( function( to ) {
									$( '#landing-page-row-<?php echo $row_id; ?>' ).css( 'background-image', 'url(' + to + ')');
								} );
							});
							<?php

						foreach( $row['struct']['column'] as $column_id=>$column ){
							?>							
							wp.customize( 'background_color_<?php echo $landing_page; ?>_<?php echo $node_id; ?>_<?php echo $row_id; ?>_<?php echo $column_id; ?>', function( value ) {								
								value.bind( function( to ) {
									$( '#landing-page-<?php echo $row_id; ?>-column-<?php echo $column_id; ?>' ).css( 'background-color', to );
								} );
							});	
							wp.customize( 'margin_<?php echo $landing_page; ?>_<?php echo $node_id; ?>_<?php echo $row_id; ?>_<?php echo $column_id; ?>', function( value ) {								
								value.bind( function( to ) {
									$( '#landing-page-<?php echo $row_id; ?>-column-<?php echo $column_id; ?>' ).css( 'margin', to );
								} );
							});	
							wp.customize( 'padding_<?php echo $landing_page; ?>_<?php echo $node_id; ?>_<?php echo $row_id; ?>_<?php echo $column_id; ?>', function( value ) {								
								value.bind( function( to ) {
									$( '#landing-page-<?php echo $row_id; ?>-column-<?php echo $column_id; ?>' ).css( 'padding', to );
								} );
							});	
							wp.customize( 'text_color_<?php echo $landing_page; ?>_<?php echo $node_id; ?>_<?php echo $row_id; ?>_<?php echo $column_id; ?>', function( value ) {
								value.bind( function( to ) {
									$( '#landing-page-<?php echo $row_id; ?>-column-<?php echo $column_id; ?>' ).css( 'color', to );
								} );
							});	
							wp.customize( 'background_image_<?php echo $landing_page; ?>_<?php echo $node_id; ?>_<?php echo $row_id; ?>_<?php echo $column_id; ?>', function( value ) {
								value.bind( function( to ) {
									$( '#landing-page-<?php echo $row_id; ?>-column-<?php echo $column_id; ?>' ).css( 'background-image', 'url(' + to + ')');
								} );
							});
							<?php
						}
					}

				}

			}

		}


	?>	

})( jQuery );

</script>
	<?php

}

function lsx_landing_pages_live_preview() {
	$landing_page = lsx_check_landing_page();
	if( false === $landing_page ){
		return;
	}
	add_action('print_footer_scripts', 'lsx_landing_pages_print_script');
}
add_action( 'customize_preview_init', 'lsx_landing_pages_live_preview' );


// load the template 
add_filter( 'template_include', 'lsx_landing_page_load_template' );		
/**
* Checks for a landing page request and loads accrodingly
*
* @since 1.0.0
*
* @return    template url
*/
function lsx_landing_page_load_template( $template ) {
	global $wp_query;
	if( lsx_check_landing_page() ){
		return LSXLDPG_PATH . 'framework/index.php';	
	}
	return $template;
}

/**
 * Renders a Grid Layout
 *
 * @since 1.0.0
 *
 */
function render_lsx_landing_pages_grid( $grid, $extend = '' ){
	
	$global_break = 'md';
	
	$out = '<div class="lsx-landing-pages-grid">';
		$out .= '<div class="lsx-landing-pages-row row" id="landing-page-row-' . $grid['_id'] . '">';
			foreach( $grid['struct']['column'] as $column_id=>$column ){
				
				$break_point = $global_break;
				if( !empty( $column['break_point'] ) ){
					$break_point = $column['break_point'];
				}

				$out .= '<div class="lsx-landing-pages-column col-' . $break_point . '-' . $column['width'] . ' column-' . $column_id . '" id="landing-page-' . $grid['_id'] . '-column-' . $column_id . '">';
					if( !empty( $grid['element'] ) ){
						foreach( $grid['element'] as $item_inx=>$item ){

							if( $item['column'] !== $column['id'] ){
								continue;
							}
							ob_start();
							do_action( 'lsx_landing_page_element_' . $item['element'], $item );
							$out .= ob_get_clean();
								
						}
					}

				$out .= '</div>';
			}
	
		$out .= '</div>';
	
	$out .= '</div>';

	return $out;
}