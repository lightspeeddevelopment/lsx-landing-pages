<!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) & !(IE 8)]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width" />
	<title><?php wp_title( ' ', true, 'right' ); ?></title>
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="stylesheet" href="<?php echo LSXLDPG_URL; ?>framework/css/bootstrap.min.css">
	<?php
	lsx_landing_page_render_page_styles( $post->ID );
	?>
	<link rel="stylesheet" href="<?php echo LSXLDPG_URL; ?>framework/css/bootstrap-theme.min.css">
	<script src="<?php echo LSXLDPG_URL; ?>framework/js/vendor/modernizr-2.8.3.min.js"></script>
	<?php wp_head(); ?>
</head>
