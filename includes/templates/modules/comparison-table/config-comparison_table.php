<div class="grid-magic">
	<div class="col-sm-12">
		<div class="db-wrapper">
			<div class="db-pricing-nine">
				<div class="table-responsive">
					<table class="widefat" cellpadding="0" cellspacing="0">
						<thead>
							<tr class="price_table_row">
								<th>
									<button type="button" class="button button-small" data-add-node="feature">Add Feature</button>
									<button type="button" class="button button-small" data-add-node="options">Add Option</button>
								</th>
								{{#each @root/options}}
								<th class="db-bk-color-one option-id-{{_id}}"  style="text-align: center;">
									{{:node_point}}
									<input type="text" placeholder="label" name="{{:name}}[label]" value="{{label}}" style="width:80px;">
									<input type="text" placeholder="price" name="{{:name}}[price]" value="{{price}}" style="width:80px;">
									<a class="" style="color:red;cursor: pointer" data-remove-element=".option-id-{{_id}}">&times;</a>
								</th>
								{{/each}}
							</tr>
						</thead>
						<tbody>
						{{#each feature}}
							<tr class="price_table_row feature-id-{{_id}}">
								<td class="db-width-perticular">
									{{:node_point}}
									<input placeholder="Feature" type="text" name="{{:name}}[label]" value="{{label}}" style="width:80px;">
									<a class="" style="color:red;cursor: pointer" data-remove-element=".feature-id-{{_id}}">&times;</a>
								</td>
								{{#each @root/options}}
								<td class="option-id-{{_id}}" style="text-align: center;"><input type="text" style="width: 100%;" name="{{:name}}[feature][{{../_id}}]" value="{{#find feature ../_id}}{{this}}{{/find}}"></td>
								{{/each}}

							</tr>
						{{/each}}
						</tbody>
						<tfoot>
							<tr class="price_table_row">
								<th></th>
								{{#each @root/options}}
								<th class="db-bk-color-one option-id-{{_id}}">
									<input type="text" name="{{:name}}[product]" value="{{product}}" style="width:80px;" placeholder="Product ID">
								</th>
								{{/each}}
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>