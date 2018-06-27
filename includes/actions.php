<?php

function lsx_get_landing_page_elements( $elements ){

	$these_elements = array(
		'widget_area' => array(
			'name'		=> 'Widget Area',
			'template'	=> LSXLDPG_PATH . 'includes/templates/modules/widget-area/widget_area.php',
			'config_template'	=> LSXLDPG_PATH . 'includes/templates/modules/widget-area/config-widget_area.php'
		),
		'content_box' => array(
			'name'		=> 'Content Box',
			'template'	=> LSXLDPG_PATH . 'includes/templates/modules/content-box/content_box.php',
			'config_template'	=> LSXLDPG_PATH . 'includes/templates/modules/content-box/config-content_box.php'
		),
		'comparison_table' => array(
			'name'		=> 'Comparison Table',
			'template'	=> LSXLDPG_PATH . 'includes/templates/modules/comparison-table/comparison_table.php',
			'config_template'	=> LSXLDPG_PATH . 'includes/templates/modules/comparison-table/config-comparison_table.php'
		),
		'pricing_table' => array(
			'name'		=> 'Pricing Table',
			'template'	=> LSXLDPG_PATH . 'includes/templates/modules/pricing-table/pricing_table.php',
			'config_template'	=> LSXLDPG_PATH . 'includes/templates/modules/pricing-table/config-pricing_table.php',
			'styles'	=> array(
				'pricing-table' => LSXLDPG_URL . 'includes/templates/modules/pricing-table/pricing.css',
				'wp-color-picker'
			),
			'scripts' => array(
				'wp-color-picker'
			)
		)		
	);
	return $these_elements;

}
add_filter( 'lsx_landing_pages_elements', 'lsx_get_landing_page_elements');


add_action( 'lsx_landing_page_element_widget_area', 'lsx_landing_page_do_widget' );
function lsx_landing_page_do_widget( $item ){
	if ( is_active_sidebar( $item['_id'] ) ){
		dynamic_sidebar( $item['_id'] );
	}
}


add_action( 'lsx_landing_page_element_content_box', 'lsx_landing_page_do_content_box' );
function lsx_landing_page_do_content_box( $item ){
	$content = apply_filters( 'the_content', $item['config']['content'] );
	echo '<div class="landing-page-content-box">';
	echo do_shortcode( $content );
	echo '<div style="clear:both;"></div></div>';

}

add_action( 'wp_print_styles', 'lsx_set_landing_pages_module_scripts' );
function lsx_set_landing_pages_module_scripts(){
	if( lsx_check_landing_page() ){
		$elements = apply_filters( 'lsx_landing_pages_elements', array() );

		if ( is_admin() ) {
			$uix = \lsx\ui\uix::get_instance( 'lsx' );
			foreach ( $elements as $element_slug => $element ) {
				$uix->enqueue_set( $element, 'lsx-landing' );
			}
		}
		//wp_enqueue_style( 'pricing-tables', LSXLDPG_URL . 'includes/templates/modules/pricing-table/pricing.css' );
		wp_enqueue_script( 'js', LSXLDPG_URL . 'includes/templates/js/main.js' );
		wp_enqueue_script( 'wow', get_stylesheet_directory_uri() . '/assets/js/wow.min.js', 20);
		wp_enqueue_style( 'wow_styles', get_stylesheet_directory_uri() . '/assets/css/animate.css', 20);
		wp_enqueue_style( 'styles', get_stylesheet_directory_uri() . '/assets/css/custom.css', 20);
	}
}


add_action( 'lsx_landing_page_element_pricing_table', 'lsx_landing_page_do_pricing_table' );
function lsx_landing_page_do_pricing_table( $item ){
	?>
	<div style="margin-bottom: 12px;">
	    <?php if( !empty( $item['config']['recommended'] ) ){ ?><div id="banner_<?php echo $item['_id']; ?>" class="recommended" style="background-color: <?php echo $item['config']['banner_color']; ?>;"><strong><?php echo $item['config']['banner_text']; ?></strong></div><?php } ?>
	    <div class="price_table_container">
	        <div class="price_table_heading"><?php echo $item['config']['label']; ?></div>
	        <div class="price_table_body">
	            <div class="price_table_row cost" id="bg_color_<?php echo $item['_id']; ?>" style="background-color: <?php echo $item['config']['bg_color']; ?>;color: <?php echo $item['config']['text_color']; ?>;">
	                <strong><?php echo $item['config']['price']; ?></strong> <span><?php echo $item['config']['rate']; ?></span>
	            </div>
	            <?php
	            if( !empty( $item['config']['option'] ) ){
	             foreach( $item['config']['option'] as $option ) { ?>
	                <div class="price_table_row" id="option_row_<?php echo $option['_id']; ?>" style="position:relative;">
	                    <?php echo $option['label']; ?>
	                </div>
	            <?php }
	        	}
	             ?>
	        </div>
	        <?php if( $item['config']['area_type'] == 'shortcode' ){
	        	echo do_shortcode( $item['config']['signup_text'] );
	        	}else{ ?>
	        <a href="#" id="signup_bg_<?php echo $item['_id']; ?>" class="price_table_signup btn btn-lg btn-block" style="background-color: <?php echo $item['config']['signup_bg']; ?>;color: <?php echo $item['config']['signup_color']; ?>;"><?php if( !empty( $item['config']['signup_text'] ) ){ ?><?php echo $item['config']['signup_text']; ?><?php }else{ ?>Sign Up<?php } ?></a>
	        	<?php } ?>
	    </div>
	</div>
	<?php
}





add_action( 'lsx_landing_page_element_comparison_table', 'lsx_landing_page_do_comparison_table' );
function lsx_landing_page_do_comparison_table( $item ){
	add_filter( 'wqc_shortcode_classes', 'lsx_landing_pages_set_qc_class' );
	?>


<div class="row">
	<div class="col-sm-12">
		<div class="db-wrapper">
			<div class="db-pricing-nine">
				<div class="table-responsive">
					<table class="table table-hover" cellpadding="0" cellspacing="0">
						<thead>
							<tr class="price_table_row">
								<th></th>
								<?php
								if( !empty( $item['config']['options'] ) ){
								foreach( $item['config']['options'] as $option ) { ?>

								<th class="db-bk-color-one option-id"  style="text-align: center;">
									<div class="option-label"><?php echo $option['label']; ?></div>
									<div class="option-price"><?php echo $option['price']; ?></div>
								</th>
								<?php } } ?>
							</tr>
						</thead>
						<tbody>
						<?php
						if( !empty( $item['config']['feature'] ) ){
						foreach( $item['config']['feature'] as $feature ) { ?>
							<tr class="price_table_row feature-id">
								<td class="db-width-perticular">
									<?php echo $feature['label']; ?>
								</td>
								<?php
								if( !empty( $item['config']['options'] ) ){
								foreach( $item['config']['options'] as $option ) { ?>
								<td class="option-id" style="text-align: center;">
									<?php 
									if( !empty( $option['feature'][ $feature['_id'] ] ) ){
										echo do_shortcode( $option['feature'][ $feature['_id'] ] );	
									} ?>
								</td>
								<?php } } ?>

							</tr>
						<?php } } ?>
						</tbody>
						<tfoot>
							<tr class="price_table_row">
								<th></th>
								<?php
								if( !empty( $item['config']['options'] ) ){
								foreach( $item['config']['options'] as $option ) { ?>
								<th class="db-bk-color-one option-id">
									<?php 
									if( !empty( $option['product'] ) ){
										echo do_shortcode( '[reveal_quick_checkout clear_cart="true" id="' . $option['product'] . '" quantity="1" checkout_text="Choose Package"]' );
									} ?>
								</th>
								<?php } } ?>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
remove_filter( 'wqc_shortcode_classes', 'lsx_landing_pages_set_qc_class' );
}



function lsx_landing_pages_set_qc_class( $class ){
	return 'btn ' . $class; 
}

// unregister
add_action( 'admin_enqueue_scripts', 'lsx_landing_pages_remove_legacy_scripts' );
function lsx_landing_pages_remove_legacy_scripts(){

	$screen = get_current_screen();
	if( !is_object( $screen ) || $screen->base != 'post' || $screen->post_type !== 'lsx_landing_page' ){
		return;
	}
	wp_dequeue_style( 'lsx-core-style' );
	wp_dequeue_style( 'lsx-baldrick-modals' );
	wp_dequeue_script( 'lsx-wp-baldrick' );
	wp_dequeue_script( 'lsx-core-script' );
}



add_filter( 'manage_edit-lsx_landing_page_columns', 'lsx_landing_pages_custom_columns' ) ;

function lsx_landing_pages_custom_columns( $columns ) {

	$new_columns = array(
		'cb'		=> $columns['cb'],
		'title'		=> $columns['title'],
		'shortcode'	=> __( 'Shortcode' ),
		'date'		=> $columns['date'],
	);

	return $new_columns;
}

add_action( 'manage_lsx_landing_page_posts_custom_column', 'lsx_landing_pages_custom_column_data', 10, 2 );

function lsx_landing_pages_custom_column_data( $column, $post_id ) {
	global $post;
	if( $column === 'shortcode' ){
		echo '[landing_page id="' . $post_id . '"]';
	}
}

// check if page has shortcode
add_action( 'wp_enqueue_scripts', 'lsx_landing_pages_detect_shortcode_use' );
function lsx_landing_pages_detect_shortcode_use(){
	global $post;
	if( empty( $post ) || empty( $post->ID ) ){
		return;
	}
	
    global $post;
    $pattern = get_shortcode_regex();

    if (   preg_match_all( '/'. $pattern .'/s', $post->post_content, $matches )
        && array_key_exists( 2, $matches )
        && in_array( 'landing_page', $matches[2] ) )
    {
        foreach( $matches[3] as $landing ){
        	
        	$atts = shortcode_parse_atts( $landing );
        	if( !empty( $atts['id'] ) ){
        		lsx_landing_page_render_page_styles( $atts['id'] );
				$elements = apply_filters( 'lsx_landing_pages_elements', array() );
		        if ( is_admin() ) {
			        $uix = \lsx\ui\uix::get_instance( 'lsx' );
			        foreach ( $elements as $element_slug => $element ) {
				        $uix->enqueue_set( $element, 'lsx-landing' );
			        }
		        }
        	}
        }
    }
}

add_shortcode( 'landing_page', 'lsx_landing_page_render_shortcode' );
function lsx_landing_page_render_shortcode( $atts ){
	if( empty( $atts['id'] ) ){
		return;
	}
	$structure = get_post_meta( $atts['id'], '_lsx_layout', true );
	if( !empty( $structure ) ){ ?>
		<?php foreach( $structure as $location => $struct ){

			if( $location !== 'body' ){ continue; }

			// put in AB loader testing thing
			// render_test_element( $structure['header'] );
			//for now I'm doing htis way

				$items = array();
				foreach( $struct as $node_id => $node ){
					if( empty( $node['column'] ) ){
						continue;
					}

					?>
					<div class="landing-page-area landing-page-<?php echo $location; ?>" id="landing-page-<?php echo $location . '-' . $node_id; ?>">
						
						<div class="container container-<?php echo $location; ?> ">
					<?php

					$tabs = array();
					foreach( $node['column'] as $row_id => $row ){
						if( !empty( $row['config']['name'] ) ){
							$tabs[$row_id] = $row['config']['name'];
						}
					}
					if( !empty( $tabs ) && count( $tabs) > 1 ){
						$has_active = false;
						?>
						<ul class="nav nav-tabs">
						<?php foreach( $tabs as $tab_id=>$tab ){
						
							$class = "";
							if( empty( $has_active ) ){
								$class = "active";
								$has_active = true;
							}
							?>
						  <li role="presentation" class="<?php echo $class; ?>">
						  	<a aria-controls="<?php echo $tab_id; ?>" role="tab" data-toggle="tab" href="#<?php echo $tab_id; ?>"><?php echo $tab; ?></a>
						  </li>
						 <?php } ?>
						</ul>
						<div class="tab-content">
						<?php					
					}
					$items[] = $node_id;
					$has_active_panel = false;
					foreach( $node['column'] as $row_id => $row ){

						// check tabs
						if( !empty( $tabs ) && count( $tabs) > 1 && !empty( $row['config']['name'] ) ){
							$class= '';
							if( empty( $has_active_panel ) ){
								$class = 'active';
								$has_active_panel = true;
							}
							echo '<div role="tabpanel" class="tab-pane ' . $class . '" id="' . $row_id . '">';
						}
						
							echo render_lsx_landing_pages_grid( $row, 'sm' );
						
						if( !empty( $tabs ) && count( $tabs) > 1 && !empty( $row['config']['name'] ) ){
							echo '</div>';
						}
					}
					if( !empty( $tabs ) && count( $tabs) > 1 ){
					?>
					</div>
					<?php
					}
					?>
					</div>
				</div>
			<?php
				}
				// random selector
				//$select = rand( 0, count( $items ) - 1);
				//foreach( $struct[ $items[ $select ] ]['column'] as $row ){
				//	echo render_lsx_landing_pages_grid( $row, 'row' );
				//}
				

			
			?>
		<?php } ?>
	<?php }


}

add_filter( 'post_updated_messages', 'lsx_landing_page_filter_messages');
function lsx_landing_page_filter_messages( $messages ){
	
	$screen = get_current_screen();
	if( !is_object( $screen ) || $screen->base != 'post' || $screen->post_type !== 'lsx_landing_page' ){
		return;
	}
	$messages[ 'lsx_landing_page' ] = $messages[ 'page' ];
	global $post;
	$url = get_permalink( $post->ID );
	$link = admin_url( 'customize.php?url=' . urlencode( $url ) );

	$messages[ 'lsx_landing_page' ][1] .=	' | <a href="' . $link . '" title="' . esc_attr__( 'Customize', 'lsx-landing-pages' ) . '">' . esc_html__( 'Customize', 'lsx-landing-pages' ) . '</a>';

	return $messages;
}