<?php
		wp_editor( '{{content}}', 'lsx_content_modal', array(
			'wpautop'       => true,
			'textarea_name' => 'content',
			'textarea_rows' => 16,
		) );

?>
{{#script}}


		( function() {
			var init, id, $wrap;

			setTimeout( function(){
				if ( typeof tinymce !== 'undefined' ) {
					for ( id in tinyMCEPreInit.mceInit ) {
						init = tinyMCEPreInit.mceInit[id];
						$wrap = tinymce.$( '#wp-' + id + '-wrap' );

						if ( ( $wrap.hasClass( 'tmce-active' ) || ! tinyMCEPreInit.qtInit.hasOwnProperty( id ) ) && ! init.wp_skip_init ) {
							tinymce.init( init );

							if ( ! window.wpActiveEditor ) {
								window.wpActiveEditor = id;
							}
						}
					}
				}

				
					for ( id in tinyMCEPreInit.qtInit ) {
						quicktags( tinyMCEPreInit.qtInit[id] );

						if ( ! window.wpActiveEditor ) {
							window.wpActiveEditor = id;
						}
					}
				
			}, 100 );
		}());


{{/script}}	