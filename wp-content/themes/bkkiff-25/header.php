<?php
global $bodyTop;

$body_class    = ($bodyTop) ? " hasBanner" : "";
$wrapper_class = (isset($args["wrapper_class"])) ? " ".$args["wrapper_class"] : "";
$html_class    = (isset($args["h_100"]) && $args["h_100"] == false) ? "no-js h-100" : "no-js h-100";
$img_id        = false;

if (function_exists('kdmfi_has_featured_image')) {
	$bg_id = kdmfi_has_featured_image('bg-image', $id);
	$img_id    = ($bg_id) ? $bg_id : false;
};
$image         = ($img_id) ? wp_get_attachment_image_src($img_id, "full") : false;
$style         = ($image) ? "background-image:url({$image[0]});" : "";
$bgFixed       = get_post_meta(get_the_ID(), "_bg_fixed", true);
$style        .= ($bgFixed) ? "background-attachment:fixed;" : "";
?>

<!DOCTYPE html>
<html <?php language_attributes(); ?> class="<?php echo $html_class; ?>">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="theme-color" content="#ffffff">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php if ( is_singular() && pings_open( get_queried_object() ) ) : ?>
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php endif; ?>
	<link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_template_directory_uri(); ?>/assets/icons/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="<?php echo get_template_directory_uri(); ?>/assets/icons/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="<?php echo get_template_directory_uri(); ?>/assets/icons/favicon-16x16.png">
	<link rel="manifest" href="<?php echo get_template_directory_uri(); ?>/assets/icons/site.webmanifest">
	<meta name="theme-color" content="#FFFFFF">
	<link rel="stylesheet" href="https://use.typekit.net/txc2wwe.css">
	<?php wp_head(); ?>
</head>

<body <?php body_class("d-flex flex-column h-100".$body_class); ?>>
	<?php get_template_part("template-parts/navigation/navigation"); ?>
	<div class="wrapper fade<?php echo $wrapper_class; ?>" style="<?php echo $style; ?>">