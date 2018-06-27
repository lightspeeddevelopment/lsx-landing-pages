<?php
/**
 * Panel template for Create Your Element
 *
 * @package   Elements
 * @author    David Cramer
 * @license   GPL-2.0+
 * @link
 * @copyright 2015 David Cramer
 */

$sets = array(
	array(
		12
	),
	array(
		6,6
	),
	array(
		4,4,4
	),
	array(
		3,3,3,3
	),
	array(
		8,4
	),
	array(
		3,6,3
	),
	array(
		4,8
	),
	array(
		2,2,2,2,2,2
	),
	array(
		1,1,1,1,1,1,1,1,1,1,1,1
	),
	array(
		2,10
	),
	array(
		3,9
	),

);



?>

<div class="grid-magic">


<?php foreach( $sets as $set_name=>$set ){ 

	$struct = array(
		'set' => 'set_' . $set_name,
		'column' => array()
	);

	foreach( $set as $index=>$column ){
		$struct['column']['col_' . $index] = array(
			'id' => 'col_' . $index,
			'width' => $column
		);
	}

	?>
	<div class="row" style="cursor: pointer;{{#is struct.set value="<?php echo $struct['set'];?>"}}background: <?php echo $uix['base_color']; ?>{{/is}}">
	<label><input type="radio" style="display:none;" data-live-sync="true" name="struct" value="<?php echo esc_attr( json_encode( $struct ) ); ?>" {{#is struct.set value="<?php echo $struct['set'];?>"}}checked="checked"{{/is}}>
	<?php foreach( $set as $col ) { ?>
		
		<div id="element-{{_id}}" class="col-xs-<?php echo $col; ?>" style="padding:6px;">
			<div class="column-content" style="padding:12px; text-align: center; background: #fff;">
				<?php echo $col; ?>
			</div>
		</div>	
		
		<?php } ?>

		<span style="clear:both; display:block;"></span>
		</label>
	</div>
<?php } ?>
</div>