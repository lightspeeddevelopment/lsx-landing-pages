<?php
/**
 * Plugin metabox Structures
 *
 * @package   uix_example
 * @author    David Cramer
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 David Cramer
 */

$metabox = array(
	'layout'   => array(
		'post_type'         => array( 'lsx_landing_page' ), // array of post types this should be in
		'name'              => __( 'Layout Builder', 'lsx-landing-pages' ), // the label/name of the metabox
		'context'           => 'normal', // metabox type ( core , advanced, side )
		'priority'          => 'high', // priority of the box in editor
		'base_color'        => '#e06000',
		'chromeless'        => true,
		'template'          => LSXLDPG_PATH . 'includes/templates/layout.php',
		'modals'            => array(
			'row'           => LSXLDPG_PATH . 'includes/templates/metabox-row.php',
			'row_config'    => LSXLDPG_PATH . 'includes/templates/config-row.php',
			'element'       => LSXLDPG_PATH . 'includes/templates/metabox-elements.php',
		),
		'scripts'           => array(
			'jquery-ui-sortable',
			'sort'          => LSXLDPG_URL . 'includes/templates/js/sort.js',
		),
		'styles'            => array(
			'layout'        => LSXLDPG_URL . 'includes/templates/css/metabox.css',
			'grid'          => LSXLDPG_URL . 'includes/templates/css/metabox-grid.css',
		),
		'partials'           => array(
			'row_item'       => LSXLDPG_PATH . 'includes/templates/row-item.php',
			'component_area' => LSXLDPG_PATH . 'includes/templates/component-area.php',
		),
	),
	'page_template'   => array(
		'post_type'         => array( 'lsx_landing_page' ), // array of post types this should be in
		'name'              => __( 'Template', 'lsx-landing-pages' ), // the label/name of the metabox
		'context'           => 'side', // metabox type ( core , advanced, side )
		'priority'          => 'default', // priority of the box in editor
		'template'          => LSXLDPG_PATH . 'includes/templates/template.php',
	),
);

// get elements
$elements = apply_filters( 'lsx_landing_pages_elements', array() );
foreach ( $elements as $element_slug => $element ) {

	$metabox['layout']['partials'][ $element_slug ] = $element['template'];
	if ( ! empty( $element['config_template'] ) ) {
		$metabox['layout']['partials'][ 'config_' . $element_slug ] = $element['config_template'];
	}
	if ( ! empty( $element['scripts'] ) ) {
		$metabox['layout']['scripts'] = array_merge( $metabox['layout']['scripts'], $element['scripts'] );
	}
	if ( ! empty( $element['styles'] ) ) {
		$metabox['layout']['styles'] = array_merge( $metabox['layout']['styles'], $element['styles'] );
	}
}


return $metabox;
