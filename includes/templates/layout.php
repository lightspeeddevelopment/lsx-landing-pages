<?php
/**
 * Layout Grid metabox template
 *
 * @package   templates
 * @author    David Cramer
 * @license   GPL-2.0+
 * @link      
 * @copyright 2016 David Cramer
 */
?>
<div class="lsx-element-wrapper-title">
	<?php _e( 'Header', 'lsx-landing-pages' ); ?>	
</div>
{{#unless header}}
<div class="lsx-element-wrapper" style="box-shadow: none;"><p class="description"><?php _e( 'No Header Groups Setup', 'lsx-landing-pages' ); ?></p></div>
{{/unless}}
{{#each header}}
	{{>component_area}}
{{/each}}
<div style="padding: 12px 0px 0px 12px; cursor: pointer; display: inline-block;"><span class="dashicons dashicons-plus-alt"></span> <a style="" data-add-node="header"><?php _e( 'Add Header Group', 'lsx-landing-pages' ); ?></a></div>

<hr>
<div class="lsx-element-wrapper-title">
	<?php _e( 'Body', 'lsx-landing-pages' ); ?>	
</div>
{{#unless body}}
<div class="lsx-element-wrapper" style="box-shadow: none;"><p class="description"><?php _e( 'No Body Groups Setup', 'lsx-landing-pages' ); ?></p></div>
{{/unless}}
{{#each body}}
	{{>component_area}}
{{/each}}
<div style="padding: 12px 0px 0px 12px; cursor: pointer; display: inline-block;"><span class="dashicons dashicons-plus-alt"></span> <a style="" data-add-node="body"><?php _e( 'Add Body Group', 'lsx-landing-pages' ); ?></a></div>
<hr>
<div class="lsx-element-wrapper-title">
	<?php _e( 'Footer', 'lsx-landing-pages' ); ?>	
</div>
{{#unless footer}}
<div class="lsx-element-wrapper" style="box-shadow: none;"><p class="description"><?php _e( 'No Footer Groups Setup', 'lsx-landing-pages' ); ?></p></div>
{{/unless}}
{{#each footer}}
	{{>component_area}}
{{/each}}
<div style="padding: 12px 0px 0px 12px; cursor: pointer; display: inline-block;"><span class="dashicons dashicons-plus-alt"></span> <a style="" data-add-node="footer"><?php _e( 'Add Footer Group', 'lsx-landing-pages' ); ?></a></div>

