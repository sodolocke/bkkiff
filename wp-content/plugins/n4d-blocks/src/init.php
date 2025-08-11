<?php
/**
 * Initializer
 *
 * Enqueue CSS/JS of all the blocks.
 *
 * @since   1.0.0
 * @package n4d
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'enqueue_block_editor_assets', 'n4d_block_assets' );
add_action( 'init', 'n4d_blocks' );
add_filter( 'block_categories_all', 'n4d_block_category', 10, 2);

function n4d_block_assets() {
// Scripts.
	wp_enqueue_script(
		'n4d-block-js', // Handle.
		plugins_url( 'assets/js/core.min.js?c=3', dirname( __FILE__ ) ),
		array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ),
		true
	);
// Styles.
	wp_enqueue_style(
		'n4d-block-editor-css', // Handle.
		plugins_url( 'assets/css/editor.css?c=1', dirname( __FILE__ ) ), // Block editor CSS.
		array( 'wp-edit-blocks' )
	);
}
function n4d_block_category( $categories, $post ) {
	return array_merge(
		$categories,
		array(
			array(
				'slug' => 'n4d-blocks',
				'title' => __( 'N4D', 'n4d-blocks' ),
			),
		)
	);
}
function n4d_blocks() {
	register_block_type( 'n4d/gallery', array(
	    'render_callback' => 'n4d_block_render_gallery',
	));
	register_block_type( 'n4d/carousel', array(
		'render_callback' => 'n4d_block_render_carousel',
	));
}
function n4d_block_render_gallery( $attributes, $content ) {
	$html  = "";
	$html .= "[gallery ids=\"".implode(",", $attributes['ids'])."\" columns=\"".$attributes["columns"]."\" id=\"".$attributes['id']."\"]";

    return $html;
}
function n4d_block_render_carousel( $attributes, $content ) {
	$ss_code = [];
	foreach($attributes as $type => $attr){
		$value = (is_array($attr)) ? implode(",", $attr) : $attr;
		array_push($ss_code, " {$type}=\"{$value}\"");
	}
	$html = "[n4d_carousel ".implode(" ", $ss_code)."]";

	return $html;
}