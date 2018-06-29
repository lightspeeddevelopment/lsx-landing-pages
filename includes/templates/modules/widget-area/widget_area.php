	<a class="lsx-wrapper-remover" style="left: -9px;"

	class="lsx-add-element"
	data-title="<?php echo esc_attr( 'Configure Widget Area' ); ?>"
	data-height="360"
	data-width="510"


	data-modal="{{_node_point}}.config"
	data-template="config_widget_area"
	data-focus="true"
	data-buttons="save delete"
	data-footer="conduitModalFooter"

	><span class="dashicons dashicons-edit"></span></a>
	<div style="text-align: center;"><?php _e( 'Widget Area', 'lsx-landing-pages' ); ?>{{#if config.name}}: {{config.name}}{{/if}}</div>
