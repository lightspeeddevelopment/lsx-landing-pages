<div class="lsx-element-wrapper" id="body-wrapper-{{_id}}">
	<a class="lsx-wrapper-remover" data-remove-element="#body-wrapper-{{_id}}" data-confirm="Remove this group and all its content?"><span class="dashicons dashicons-no-alt"></span></a>
	{{:node_point}}
	{{#each column}}
		
		{{>row_item}}

	{{/each}}
	<div 
	style="padding: 12px 0px 0px 12px; cursor: pointer; display: inline-block;" 
	data-title="<?php echo esc_attr( 'Select Structure' ); ?>"
	data-height="780"
	data-width="700"

	
	data-modal="{{_node_point}}.column"
	data-template="row"
	data-focus="true"
	data-buttons="create"
	data-footer="conduitModalFooter"
	data-default='{"struct": { "set" : "set_0" } }'

	><span class="dashicons dashicons-plus"></span><?php _e( 'Add Row', 'lsx-landing-pages' ); ?></div>
</div>