<?php
/*
add_filter( 'kdmfi_featured_images', function( $featured_images ) {
	$args = array(
		'id' => 'banner-image',
		'desc' => '',
		'label_name' => 'Banner',
		'label_set' => 'Set Banner',
		'label_remove' => 'Remove Banner',
		'label_use' => 'Set Banner',
		'post_type' => array( 'project', 'page', 'post' ),
	);
	$featured_images[] = $args;

	return $featured_images;
});
*/
add_filter( 'kdmfi_featured_images', function( $featured_images ) {
	$args = array(
		'id' => 'banner-image',
		'desc' => '',
		'label_name' => 'Banner',
		'label_set' => 'Set Banner',
		'label_remove' => 'Remove Banner',
		'label_use' => 'Set Banner',
		'post_type' => array( 'project', 'page', 'post', 'product' ),
	);
	$featured_images[] = $args;

	return $featured_images;
});
add_filter( 'kdmfi_featured_images', function( $featured_images ) {
	$args = array(
		'id' => 'banner-image-m',
		'desc' => '',
		'label_name' => 'Mobile Banner',
		'label_set' => 'Set Mobile Banner',
		'label_remove' => 'Remove Mobile Banner',
		'label_use' => 'Set Mobile Banner',
		'post_type' => array( 'project', 'page', 'post', 'product' ),
	);
	$featured_images[] = $args;

	return $featured_images;
});
/*
add_filter( 'kdmfi_featured_images', function( $featured_images ) {
	$args = array(
		'id' => 'bg-image',
		'desc' => '',
		'label_name' => 'Background',
		'label_set' => 'Set Background',
		'label_remove' => 'Remove Background',
		'label_use' => 'Set Background',
		'post_type' => array( 'page' ),
	);
	$featured_images[] = $args;

	return $featured_images;
});
add_filter( 'kdmfi_featured_images', function( $featured_images ) {
	$args = array(
		'id' => 'profile-image',
		'desc' => '',
		'label_name' => 'Board Image',
		'label_set' => 'Set Board Image',
		'label_remove' => 'Remove Board Image',
		'label_use' => 'Set Board Image',
		'post_type' => array( 'member' ),
	);
	$featured_images[] = $args;

	return $featured_images;
});
*/