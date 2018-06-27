<?php



	// setup theme stuff
	add_action( 'widgets_init', 'lsx_landing_pages_widgets_init' );
	function lsx_landing_pages_widgets_init() {

		$args = array(
			'posts_per_page'   => -1,
			'post_type'        => 'lsx_landing_page',
			'post_status'      => 'publish',
		);
		$landing_pages = get_posts( $args );
		
		$widgets = 1;
		foreach( $landing_pages as $page ){
			if( empty( $page->_lsx_layout ) ){
				continue;
			}
			$structure = $page->_lsx_layout;
			foreach( $structure as $location=>$area ){
				foreach( $area as $row ){
					if( empty( $row['column'] ) ){
						continue;
					}

					foreach( $row['column'] as $column ){
						if( empty( $column['element'] ) ){
							continue;
						}
						foreach( $column['element'] as $element ){
							if( $element['element'] == 'widget_area' ){
								$name = ucwords( $location ) . ' Area: ' . $widgets++;
								if( !empty( $element['config']['name'] ) ){
									$name = $element['config']['name'];
								}
								// dont forget the extra settings
								register_sidebar( array(
									'name' => $name,
									'id' => $element['_id'],
									'description' => $page->post_title . ': ' . ucwords( $location ),
									'before_widget' => '<div id="%1$s" class="landing-page-widget %2$s">',
									'after_widget' => '</div>',
									'before_title' => '<h3 class="landing-page-widget-title">',
									'after_title' => '</h3>',
								) );
							}
						}
					}
				}
			}
		}

	}
