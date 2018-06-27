		{{#if config.name}}<div class="lsx-header-group">{{config.name}}</div>{{/if}}
		<div class="grid-magic" id="item-wrapper-{{_id}}">
			<a class="lsx-wrapper-remover" data-remove-element="#item-wrapper-{{_id}}" data-confirm="Remove this row and all it's content?"><span class="dashicons dashicons-no-alt"></span></a>
			
			{{:node_point}}
			<div 
			class="lsx-wrapper-remover"
			style="top: 30px;"
			data-title="<?php echo esc_attr( 'Configure Row' ); ?>"
			data-height="550"
			data-width="700"

			
			data-modal="{{_node_point}}.config"
			data-template="row_config"
			data-focus="true"
			data-buttons="save delete"
			data-footer="conduitModalFooter"


		><span class="dashicons dashicons-edit"></span></div>	

			
			<input name="{{:name}}[struct]" value="{{json struct}}" type="hidden">
			<input name="{{:name}}[config]" value="{{#if config}}{{json config}}{{/if}}" type="hidden">
			<div class="row">
				{{#each struct.column}}
				<div id="element-{{../_id}}-col-{{width}}" class="col-xs-{{width}}" style="padding:6px;">
					<div class="column-content" data-column="{{id}}" style="padding:12px; text-align: center; background: #fff;">
						
						{{#each ../element}}
							{{#is column value=../id}}
							<div class="lsx-landing-element-wrapper" id="element-wrapper-{{_id}}">
								
								{{:node_point}}

								<input type="hidden" name="{{:name}}[column]" class="column_input" value="{{column}}">
								<input type="hidden" name="{{:name}}[element]" value="{{element}}">
								<input type="hidden" name="{{:name}}[config]" value="{{#if config}}{{json config}}{{/if}}">
							
								<a class="lsx-wrapper-remover" data-remove-element="#element-wrapper-{{_id}}" data-confirm="Remove this element?" style="left: unset; right: -9px;"><span class="dashicons dashicons-no-alt"></span></a>
								{{{load_partial element this}}}


								<a data-add-node="{{../../_node_point}}.element" data-node-default='{  "element" : "{{element}}", "column" : "{{column}}",  "config" : {{json config}} }' 
								class="lsx-wrapper-remover" style="left: unset; left: -9px; top: 25px;"><span class="dashicons dashicons-admin-page"></a>
								
							</div>
							{{/is}}
						{{/each}}

					</div>
					<div style="margin-top:6px; width: 100%; text-align: center;">
							<div 
							class="lsx-add-element"
							data-title="<?php echo esc_attr( 'Select Element' ); ?>"
							data-height="780"
							data-width="700"

							
							data-modal="{{../_node_point}}.element"
							data-template="element"
							data-focus="true"
							data-buttons="create"
							data-footer="conduitModalFooter"
							data-default='{"column": "{{id}}" }'

						><span class="dashicons dashicons-plus"></span><?php _e( 'Add Element', 'lsx-landing-pages' ); ?></div>					
					</div>
				</div>
				{{/each}}
				<span style="clear:both; display:block;"></span>
			</div>

		</div>
		{{#script type="text/javascript"}}
			jQuery( "#item-wrapper-{{_id}} .column-content" ).sortable({
				connectWith: "#item-wrapper-{{_id}} .column-content",
				items: '.lsx-landing-element-wrapper',
				update: function( event, ui ) {
					var column = ui.item.parent().data('column'),
						input = ui.item.find( '.column_input' );
					input.val( column );
				}
			});
		{{/script}}