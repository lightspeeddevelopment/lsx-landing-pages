var lsx_content_box_bind;

jQuery( function( $ ){

	lsx_content_box_bind = function(){
		tinyMCE.remove();
		
	}
	

	$( window ).on('uix.init modal.open', function(){


		$('.color-field').wpColorPicker({
			change: function(obj){
				
				var trigger = $(this);
				if( trigger.data('target') ){
					$( trigger.data('target') ).css( trigger.data('style'), trigger.val() );
				}

			}
		});



	});
	$( window ).on('close.modal', function(){
		lsx_content_box_bind();
	});
});
