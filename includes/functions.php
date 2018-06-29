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



function lsx_landing_pages_actions( $actions, $post ) {
	//check for your post type
	if ( 'lsx_landing_page' == $post->post_type ) {

		$url = get_permalink( $post->ID );
		$link = admin_url( 'customize.php?url=' . urlencode( $url ) );
		$actions['customize'] = '<a href="' . $link . '" title="' . esc_attr__( 'Customize', 'lsx-landing-pages' ) . '">' . esc_html__( 'Customize', 'lsx-landing-pages' ) . '</a>';
	}
	return $actions;
}
add_filter( 'post_row_actions', 'lsx_landing_pages_actions', 10, 2 );

//add_filter( 'stylesheet_directory_uri', 'lsx_check_landing_page_bind', 9);
add_filter( 'stylesheet_directory', 'lsx_landing_page_style_check', 9 );
function lsx_check_landing_page_bind( $uri ) {

	$url = $_SERVER['REQUEST_URI'];
	if ( lsx_check_landing_page() ) {
		return LSXLDPG_URL . 'framework';
	}
	return $uri;
}
function lsx_landing_page_style_check( $dir ) {
	$url = $_SERVER['REQUEST_URI'];
	if ( lsx_check_landing_page() ) {
		add_filter( 'customize_loaded_components', 'lsx_setup_components', 100 );
		return LSXLDPG_PATH . 'framework';
	}
	return $dir;
}

function lsx_check_landing_page() {
	$url = $_SERVER['REQUEST_URI'];
	if ( basename( $_SERVER['SCRIPT_FILENAME'] ) === 'customize.php' ) {		
		parse_str( $_SERVER['QUERY_STRING'], $query );
		if ( ! empty( $query['url'] ) ) {
			$url = $query['url'];
		}
		// if saving
	}
	if ( ! empty( $_POST['action'] ) && 'customize_save' == $_POST['action'] ) {
		$url = wp_get_referer();
		$parsed = parse_url( $url );
		if ( 'customize.php' == basename( $parsed['path'] ) && ! empty( $parsed['query'] ) ) {
			$query = urldecode( $parsed['query'] );
			parse_str( $query, $ref );
			if ( ! empty( $ref['url'] ) ) {
				$url = $ref['url'];
			}
		}
	}
	if ( false !== strpos( $url, '/go/' ) ) {
		// $landing_page = url_to_postid( $url );
		$landing_page = $url;
		// echo $landing_page;
		if ( ! empty( $landing_page ) ) {
			return $landing_page;
		}
		return true;
	}
	return false;
}

function lsx_check_landing_page_url() {
	$landing_url = lsx_check_landing_page();
	$landing_url = url_to_postid( $landing_url );
	return $landing_url;
}

function lsx_landing_pages_customize_register( $wp_customize ) {
	$landing_id = lsx_check_landing_page_url();
	// 	echo $landing_id;
	if ( ! lsx_check_landing_page() ) {
		return;
	}

	$structure = get_post_meta( $landing_id, '_lsx_layout', true );

	$remove = array(
		'display_header_text',
		'title_tagline',
		'colors',
		'header_image',
		'background_image',
		'static_front_page',
		'themes',
	);

	$sections = $wp_customize->sections();
	foreach ( $sections as $section_id => $section ) {
		if ( false !== strpos( $section_id, 'sidebar-widgets-nd' ) ) {
			continue;
		}
		$wp_customize->remove_section( $section_id );
	}

	foreach ( $remove as $section ) {
		$wp_customize->remove_section( $section );
	}

	// Main Panels
	$wp_customize->add_panel(
		'lsx_landing_pages_panel_areas',
		array(
			'title'     => 'Areas',
		)
	);
	$wp_customize->add_panel(
		'lsx_landing_pages_panel_rows',
		array(
			'title'     => 'Rows',
		)
	);
	$wp_customize->add_panel(
		'lsx_landing_pages_panel_columns',
		array(
			'title'     => 'Columns',
		)
	);

	foreach ( $structure as $location => $struct ) {
		// Group Area
		if ( ! empty( $struct ) ) {

			$area_num = 1;
			foreach ( $struct as $node_id => $area ) {

				// Main area
				$wp_customize->add_section(
					'lsx_landing_pages_' . $location . '_' . $node_id . '_main',
					array(
						'panel'     => 'lsx_landing_pages_panel_areas',
						'title'     => ucwords( $location ) . ' ' . $area_num,
						'priority'  => 20,
					)
				);

				$key = $landing_id . '_' . $node_id;
				$section = 'lsx_landing_pages_' . $location . '_' . $node_id . '_main';
				lsx_landing_pages_set_controls( $key, $section, $wp_customize );

				$row_num = 1;
				foreach ( $area['column'] as $row_id => $row ) {

					// inner row
					// Section
					$title = ( ! empty( $row['config']['name'] ) ? $row['config']['name'] : ucwords( $location ) . ' ' . $area_num . ' row ' . $row_num );
					$wp_customize->add_section(
						'lsx_landing_pages_' . $location . '_' . $node_id . '_' . $row_id,
						array(
							'panel'     => 'lsx_landing_pages_panel_rows',
							'title'     => $title,
							'priority'  => 20,
						)
					);

					$key = $landing_id . '_' . $node_id . '_' . $row_id;
					$section = 'lsx_landing_pages_' . $location . '_' . $node_id . '_' . $row_id;
					lsx_landing_pages_set_controls( $key, $section, $wp_customize );

					// columns
					$column_no = 1;
					foreach ( $row['struct']['column'] as $column ) {

						$title = ( ! empty( $row['config']['name'] ) ? $row['config']['name'] : ucwords( $location ) . ' ' . $area_num . ' row ' . $row_num . ' column ' . $column_no );
						$wp_customize->add_section(
							'lsx_landing_pages_' . $landing_id . '_' . $node_id . '_' . $row_id . '_' . $column['id'],
							array(
								'panel'     => 'lsx_landing_pages_panel_columns',
								'title'     => $title,
								'priority'  => 20,
							)
						);

						$key = $landing_id . '_' . $node_id . '_' . $row_id . '_' . $column['id'];
						$section = 'lsx_landing_pages_' . $landing_id . '_' . $node_id . '_' . $row_id . '_' . $column['id'];
						lsx_landing_pages_set_controls( $key, $section, $wp_customize );

						// inc columns
						$column_no++;

					}

					// inc rows
					$row_num++;

				}

				// inc areas
				$area_num++;
			}
		}
	}

	add_filter( 'pre_option_blogname', 'lsx_set_title', 10, 2 );

}


function lsx_set_title( $a ) {
	$landing_page = lsx_check_landing_page_url();
	if ( false === $landing_page ) {
		return $a;
	}
	$page = get_post( $landing_page );
	return $page->post_title;

}
add_action( 'customize_register', 'lsx_landing_pages_customize_register', 100 );


function lsx_setup_components( $wp_customize ) {
	return array( 'widgets' );
}

function lsx_landing_pages_get_registered_customizer_settings() {
		$registered_controls = array(
			'margin'                => array(
				'property'  => 'margin',
				'value'     => 'to',
			),
			'padding'               => array(
				'property'  => 'padding',
				'value'     => 'to',
			),
			'border_radius'         => array(
				'property'  => 'border-radius',
				'value'     => 'to',
			),
			'background_color'      => array(
				'property'  => 'background-color',
				'value'    => 'to',
			),
			'text_color'           => array(
				'property'  => 'color',
				'value'     => 'to',
			),
			'background_image'     => array(
				'property'  => 'background-image',
				'value'     => "'url(' + to + ')'",
			),
			'background_attachment'  => array(
				'property'  => 'background-attachment',
				'value'     => 'to',
			),
			'background_position'    => array(
				'property'  => 'background-position',
				'value'     => 'to',
			),
			'background_size'        => array(
				'property'  => 'background-size',
				'value'     => 'to',
			),
			'background_repeat'      => array(
				'property'  => 'background-repeat',
				'value'     => 'to',
			),

		);

	return $registered_controls;
}

function lsx_landing_pages_print_script() {
	$landing_page = lsx_check_landing_page_url();
	$structure = get_post_meta( $landing_page, '_lsx_layout', true );

	?>
<script type="text/javascript">

!(function( $ ) {
	"use strict";
	
	<?php
		$structure = get_post_meta( $landing_page, '_lsx_layout', true );

		$registered_controls = lsx_landing_pages_get_registered_customizer_settings();

	foreach ( $structure as $location  => $struct ) {
			// Group Area
		if ( ! empty( $struct ) ) {

				$index = 1;
			foreach ( $struct as $node_id => $area ) {
				foreach ( $registered_controls as $key => $control ) {
						$name = $key . '_' . $landing_page . '_' . $node_id;
						$selector = '#landing-page-' . $location . '-' . $node_id;

						?>
						wp.customize( '<?php echo wp_kses( $name ); ?>', function( value ) {
							value.bind( function( to ) {
								console.log( <?php echo wp_kses( $control['value'] ); ?> );
								$( '<?php echo wp_kses( $selector ); ?>' ).css( '<?php echo wp_kses( $control['property'] ); ?>', <?php echo wp_kses( $control['value'] ); ?> );
							} );
						});	
						<?php
				}
				foreach ( $area['column'] as $row_id => $row ) {

					foreach ( $registered_controls as $key => $control ) {
							$name = $key . '_' . $landing_page . '_' . $node_id . '_' . $row_id;
							$selector = '#landing-page-row-' . $row_id;

							?>
							wp.customize( '<?php echo wp_kses( $name ); ?>', function( value ) {
								value.bind( function( to ) {
									$( '<?php echo wp_kses( $selector ); ?>' ).css( '<?php echo wp_kses( $control['property'] ); ?>', <?php echo wp_kses( $control['value'] ); ?> );
								} );
							});	
							<?php
					}

					foreach ( $row['struct']['column'] as $column_id => $column ) {

						foreach ( $registered_controls as $key => $control ) {
								$name = $key . '_' . $landing_page . '_' . $node_id . '_' . $row_id . '_' . $column_id;
								$selector = '#landing-page-' . $row_id . '-column-' . $column_id;

								?>
								wp.customize( '<?php echo wp_kses( $name ); ?>', function( value ) {
									value.bind( function( to ) {
										$( '<?php echo wp_kses( $selector ); ?>' ).css( '<?php echo wp_kses( $control['property'] ); ?>', <?php echo wp_kses( $control['value'] ); ?> );
									} );
								});	
								<?php
						}
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
	$landing_page = lsx_check_landing_page_url();
	if ( false === $landing_page ) {
		return;
	}
	add_action( 'print_footer_scripts', 'lsx_landing_pages_print_script' );
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
	if ( lsx_check_landing_page() ) {
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
function render_lsx_landing_pages_grid( $grid, $global_break = 'md' ) {

	$out = '<div class="lsx-landing-pages-grid">';
		$out .= '<div class="lsx-landing-pages-row row" id="landing-page-row-' . $grid['_id'] . '">';
	foreach ( $grid['struct']['column'] as $column_id => $column ) {

				$break_point = $global_break;
		if ( ! empty( $column['break_point'] ) ) {
					$break_point = $column['break_point'];
		}

				$out .= '<div class="lsx-landing-pages-column col-' . $break_point . '-' . $column['width'] . ' column-' . $column_id . '" id="landing-page-' . $grid['_id'] . '-column-' . $column_id . '">';
		if ( ! empty( $grid['element'] ) ) {
			foreach ( $grid['element'] as $item_inx => $item ) {

				if ( $item['column'] !== $column['id'] ) {
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



function lsx_landing_pages_set_controls( $key, $section, $wp_customize ) {

	$wp_customize->add_setting(
		'background_color_' . $key,
		array(
			'transport'  => 'postMessage',
			'type'       => 'option',
			'capability' => 'edit_theme_options',
		)
	);
	$wp_customize->add_setting(
		'text_color_' . $key,
		array(
			'transport'  => 'postMessage',
			'type'       => 'option',
			'capability' => 'edit_theme_options',
		)
	);

	$wp_customize->add_setting(
		'padding_' . $key,
		array(
			'transport'  => 'postMessage',
			'type'       => 'option',
			'capability' => 'edit_theme_options',
		)
	);
	$wp_customize->add_setting(
		'margin_' . $key,
		array(
			'transport'  => 'postMessage',
			'type'       => 'option',
			'capability' => 'edit_theme_options',
		)
	);

	$wp_customize->add_setting(
		'background_image_' . $key,
		array(
			'transport'  => 'postMessage',
			'type'       => 'option',
			'capability' => 'edit_theme_options',
		)
	);

	$wp_customize->add_setting(
		'background_attachment_' . $key,
		array(
			'transport'  => 'postMessage',
			'type'       => 'option',
			'capability' => 'edit_theme_options',
		)
	);
	$wp_customize->add_setting(
		'background_position_' . $key,
		array(
			'transport'  => 'postMessage',
			'type'       => 'option',
			'capability' => 'edit_theme_options',
		)
	);
	$wp_customize->add_setting(
		'background_size_' . $key,
		array(
			'transport'  => 'postMessage',
			'type'       => 'option',
			'capability' => 'edit_theme_options',
		)
	);	
	$wp_customize->add_setting(
		'background_repeat_' . $key,
		array(
			'transport'  => 'postMessage',
			'type'       => 'option',
			'capability' => 'edit_theme_options',
		)
	);		
	$wp_customize->add_setting(
		'border_radius_' . $key,
		array(
			'transport'  => 'postMessage',
			'type'       => 'option',
			'capability' => 'edit_theme_options',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'padding_' . $key,
			array(
				'label'         => __( 'Padding', 'lsx-landing-pages' ),
				'description'   => __( 'i.e "5px" or "5px 8px" etc.', 'lsx-landing-pages' ),
				'default'    => '0',
				'type'       => 'text',
				'section'    => $section,
				'settings'   => 'padding_' . $key,
			)
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'margin_' . $key,
			array(
				'label'         => __( 'Margin', 'lsx-landing-pages' ),
				'description'   => __( 'i.e "5px" or "5px 8px" etc.', 'lsx-landing-pages' ),
				'default'    => '0 15px',
				'section'    => $section,
				'type'       => 'text',
				'settings'   => 'margin_' . $key,
			)
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'border_radius_' . $key,
			array(
				'label'         => __( 'Border Radius', 'lsx-landing-pages' ),
				'description'   => __( 'i.e "5px" or "5px 8px" etc.', 'lsx-landing-pages' ),
				'default'    => '0',
				'section'    => $section,
				'type'       => 'text',
				'settings'   => 'border_radius_' . $key,
			)
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'background_color_' . $key,
			array(
				'label'      => __( 'Background Color', 'lsx-landing-pages' ),
				'section'    => $section,
				'settings'   => 'background_color_' . $key,
			)
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'text_color_' . $key,
			array(
				'label'      => __( 'Text Color', 'lsx-landing-pages' ),
				'section'    => $section,
				'settings'   => 'text_color_' . $key,
			)
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'background_image_' . $key,
			array(
				'label'      => __( 'Background Image', 'lsx-landing-pages' ),
				'section'    => $section,
				'settings'   => 'background_image_' . $key,
			)
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'background_attachment_' . $key,
			array(
				'label'      => __( 'Background Attachment', 'lsx-landing-pages' ),
				'default'    => '0',
				'type'       => 'select',
				'section'    => $section,
				'settings'   => 'background_attachment_' . $key,
				'choices'    => array(
					'scroll' => 'Scroll',
					'fixed' => 'Fixed',
				),
			)
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'background_position_' . $key,
			array(
				'label'      => __( 'Background Position', 'lsx-landing-pages' ),
				'default'    => 'center',
				'type'       => 'select',
				'section'    => $section,
				'settings'   => 'background_position_' . $key,
				'choices'    => array(
					'center' => 'Center',
					'top' => 'Top',
					'right' => 'Right',
					'bottom' => 'Bottom',
					'left' => 'Left',
				),
			)
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'background_size_' . $key,
			array(
				'label'      => __( 'Background Size', 'lsx-landing-pages' ),
				'default'    => 'cover',
				'type'       => 'select',
				'section'    => $section,
				'settings'   => 'background_size_' . $key,
				'choices'    => array(
					'cover' => 'Cover',
					'contain' => 'Contain',
					'auto' => 'auto',
				),
			)
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'background_repeat_' . $key,
			array(
				'label'      => __( 'Background Repeat', 'lsx-landing-pages' ),
				'default'    => '',
				'type'       => 'select',
				'section'    => $section,
				'settings'   => 'background_repeat_' . $key,
				'choices'    => array(
					'repeat' => 'Repeat',
					'repeat-x' => 'Repeat X',
					'repeat-y' => 'Repeat Y',
					'no-repeat' => 'No Repeat',
				),
			)
		)
	);		
}


function lsx_landing_page_render_page_styles( $post_id ) {
	?>
	<style type="text/css">
	<?php

		$structure = get_post_meta( $post_id, '_lsx_layout', true );
		$registered_controls = lsx_landing_pages_get_registered_customizer_settings();

	foreach ( $structure as $location => $struct ) {
			// Group Area
		if ( ! empty( $struct ) ) {

				$index = 1;
			foreach ( $struct as $node_id => $area ) {

				foreach ( $registered_controls as $key => $control ) {

						$name = $key . '_' . $post_id . '_' . $node_id;
						$selector = '#landing-page-' . $location . '-' . $node_id;
						$value = get_option( $name );
					if ( ! empty( $value ) ) {
							$control['value'] = str_replace("' + to + '" , $value, $control['value'] );
							$control['value'] = str_replace('to' , $value, $control['value'] );
							echo $selector . '{ ' . $control['property'] . ' : ' . trim( $control['value'], "'" ) . '; }';
					}
				}

				foreach ( $area['column'] as $row_id => $row ) {


					foreach( $registered_controls as $key=>$control ) {

							$name = $key . '_' . $post_id . '_' . $node_id . '_' . $row_id;
							$selector = '#landing-page-row-' . $row_id;
							$value = get_option( $name );
							if ( ! empty( $value ) ) {
								$control['value'] = str_replace("' + to + '" , $value, $control['value'] );
								$control['value'] = str_replace('to' , $value, $control['value'] );
								echo $selector . '{ ' . $control['property'] . ' : ' . trim( $control['value'], "'" ) . '; }';
						}
					}

					foreach ( $row['struct']['column'] as $column_id => $column ) {

						foreach ( $registered_controls as $key => $control ) {

								$name = $key . '_' . $post_id . '_' . $node_id . '_' . $row_id . '_' . $column_id;
								$selector = '#landing-page-' . $row_id . '-column-' . $column_id;
								$value = get_option( $name );
								if ( ! empty( $value ) ) {
									$control['value'] = str_replace("' + to + '" , $value, $control['value'] );
									$control['value'] = str_replace('to' , $value, $control['value'] );
									echo $selector . '{ ' . $control['property'] . ' : ' . trim( $control['value'], "'" ) . '; }';
							}
						}
					}
				}
			}
		}
	}

	?>
	</style>
	<?php
}
