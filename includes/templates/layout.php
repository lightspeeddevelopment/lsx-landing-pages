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
	<?php esc_html_e( 'Header', 'lsx-landing-pages' ); ?>	
</div>
{{#unless header}}
<div class="lsx-element-wrapper" style="box-shadow: none;"><p class="description"><?php esc_html_e( 'No Header Groups Setup', 'lsx-landing-pages' ); ?></p></div>
{{/unless}}
{{#each header}}
	{{>component_area}}
{{/each}}
<div style="padding: 12px 0px 0px 12px; cursor: pointer; display: inline-block;"><span class="dashicons dashicons-plus-alt"></span> <a style="" data-add-node="header"><?php esc_html_e( 'Add Header Group', 'lsx-landing-pages' ); ?></a></div>

<hr>
<div class="lsx-element-wrapper-title">
	<?php esc_html_e( 'Body', 'lsx-landing-pages' ); ?>	
</div>
{{#unless body}}
<div class="lsx-element-wrapper" style="box-shadow: none;"><p class="description"><?php esc_html_e( 'No Body Groups Setup', 'lsx-landing-pages' ); ?></p></div>
{{/unless}}
{{#each body}}
	{{>component_area}}
{{/each}}
<div style="padding: 12px 0px 0px 12px; cursor: pointer; display: inline-block;"><span class="dashicons dashicons-plus-alt"></span> <a style="" data-add-node="body"><?php esc_html_e( 'Add Body Group', 'lsx-landing-pages' ); ?></a></div>
<hr>
<div class="lsx-element-wrapper-title">
	<?php esc_html_e( 'Footer', 'lsx-landing-pages' ); ?>	
</div>
{{#unless footer}}
<div class="lsx-element-wrapper" style="box-shadow: none;"><p class="description"><?php esc_html_e( 'No Footer Groups Setup', 'lsx-landing-pages' ); ?></p></div>
{{/unless}}
{{#each footer}}
	{{>component_area}}
{{/each}}
<div style="padding: 12px 0px 0px 12px; cursor: pointer; display: inline-block;"><span class="dashicons dashicons-plus-alt"></span> <a style="" data-add-node="footer"><?php esc_html_e( 'Add Footer Group', 'lsx-landing-pages' ); ?></a></div>

