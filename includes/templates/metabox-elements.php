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

$elements = apply_filters( 'lsx_landing_pages_elements', array() );
?>

<input name="column" value="{{column}}" type="hidden">
<div class="grid-magic">
	<div class="row" style="background-color: #fff;">
		<?php
		foreach ( $elements as $element_slug => $element ) {
		?>

			<div id="insert-element-<?php echo esc_attr( $element_slug ); ?>" class="col-xs-4" style="padding:6px;">
				<label class="column-content" style="display:block; padding:20px 12px; text-align: center; background: {{#is element value="<?php echo esc_attr( $element_slug ); ?>"}}<?php echo esc_attr( $uix['base_color'] ); ?>;color:#fff;{{else}}#efefef;color:#444;{{/is}}">
					<?php echo esc_attr( $element['name'] ); ?>
					<input style="display:none;" type="radio" name="element" value="<?php echo esc_attr( $element_slug ); ?>" data-live-sync="true" {{#is element value="<?php echo esc_attr( $element_slug ); ?>"}}checked="checked"{{/is}}>
				</label>
			</div>	


		<?php
		}
		?>
		<span style="clear:both; display:block;"></span>
	</div>
</div>
