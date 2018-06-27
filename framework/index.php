<?php

global $post, $wp_filter, $wp_styles;
$structure = $post->_lsx_layout;
wp_enqueue_script( 'jquery' );

get_header();
?>
<body>
	<?php if( !empty( $structure ) ){ ?>
		<?php foreach( $structure as $location => $struct ){ ?>		
			<?php
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
						
							echo render_lsx_landing_pages_grid( $row );
						
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
	<?php } ?>

<?php wp_footer();?>

</body>
</html>
