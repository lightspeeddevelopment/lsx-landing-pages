	<a class="lsx-wrapper-remover" style="left: -9px;"

	class="lsx-add-element"
	data-title="<?php echo esc_attr( 'Edit Content' ); ?>"
	data-height="600"
	data-width="800"

	data-before="lsx_content_box_bind"

	data-modal="{{_node_point}}.config"
	data-template="config_content_box"
	data-focus="true"
	data-buttons="save delete"
	data-footer="conduitModalFooter"

	><span class="dashicons dashicons-edit"></span></a>
	{{#unless config/content}}
	<div style="text-align: center;"><?php _e( 'Content Box', 'lsx-landing-pages' ); ?></div>
	{{else}}
	<div class="lsx-content-module">{{{config/content}}}</div>
	{{/unless}}
