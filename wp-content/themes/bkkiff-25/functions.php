<?php
/**
 * roc only works in WordPress 4.7 or later.
 */
if ( version_compare( $GLOBALS['wp_version'], '4.7-alpha', '<' ) ) {
	require get_template_directory() . '/inc/back-compat.php';
	return;
}
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function chm_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed at WordPress.org. See: https://translate.wordpress.org/projects/wp-themes/roc
	 * If you're building a theme based on roc, use a find and replace
	 * to change 'bkkiff' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'bkkiff' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );
//	add_image_size('card', 443, 330, true );
	// Set the default content width.
	$GLOBALS['content_width'] = 1600;

	// This theme uses wp_nav_menu() in two locations.
	register_nav_menus( array(
		'main'      => __( 'Main Menu', 'bkkiff' ),
		'social'    => __( 'Social Menu', 'bkkiff' ),
		'language'  => __( 'Language Menu', 'bkkiff' ),
		'footer-1'  => __( 'Footer Col1', 'bkkiff' ),
		'footer-2'  => __( 'Footer Col2', 'bkkiff' ),
		'footer-3'  => __( 'Footer Col3', 'bkkiff' ),
	) );


	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 *
	 * See: https://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside',
		'image',
		'video',
		'quote',
		'link',
		'gallery',
		'audio',
	) );

	// Add theme support for Custom Logo.
	add_theme_support( 'custom-logo', array(
		'width'       => 250,
		'height'      => 250,
		'flex-width'  => true,
	) );

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_filter('show_admin_bar', '__return_false');
}
add_action( 'after_setup_theme', 'chm_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function chm_content_width() {
	$content_width = 1600;
	$GLOBALS['content_width'] = apply_filters( 'chm_content_width', $content_width );
}
add_action( 'template_redirect', 'chm_content_width', 0 );
/**
 * Handles JavaScript detection.
 *
 * Adds a `js` class to the root `<html>` element when JavaScript is detected.
 *
 * @since roc 1.0
 */
function chm_javascript_detection() {
	echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
}
add_action( 'wp_head', 'chm_javascript_detection', 0 );
remove_action('wp_head', 'wp_generator');

function n4d_excerpt_more($more) {
	return "...";
}
function n4d_excerpt_length($length){
	return 40;
}
add_filter('excerpt_more', 'n4d_excerpt_more');
add_filter('excerpt_length', 'n4d_excerpt_length');


/**
 * Enqueue scripts and styles.
 */
function chm_scripts() {
	$version = '0.0.37';
//DEREGISTER
	wp_deregister_script( 'wp-embed' );
	wp_dequeue_script('google-recaptcha');
//SWITCH JQUERY to 3.3.1 || deregister jquery
	$no_jquery  = true;
	$page_theme = get_page_template_slug();
	global $wp_query;


	if ($no_jquery) wp_deregister_script('jquery');
//THEME STYLESHEET
	wp_enqueue_style( 'sls-style', get_stylesheet_uri(), array(), $version);
//SCRIPT
	if ( get_query_var('pagename') == "our-work" ) {
		wp_enqueue_script( 'imagesloaded', get_theme_file_uri( '/assets/js/imagesloaded.pkgd.min.js' ), array(), $version, true );
		wp_enqueue_script( 'masonry', get_theme_file_uri( '/assets/js/masonry.pkgd.min.js' ), array(), $version, true );
		wp_enqueue_script('infinite-scroll', get_template_directory_uri() . '/assets/js/infinite-scroll.pkgd.min.js', false, '3.0.6', true);
	}

	wp_enqueue_script( 'bkkiff', get_theme_file_uri( '/assets/js/scripts.min.js' ), array('wp-i18n'), $version, true );

	wp_localize_script( 'bkkiff', 'wp_ajax_object', array(
		'url'      => home_url(),
		'ajax_url' => admin_url( 'admin-ajax.php' ),
		'nonce'    => wp_create_nonce( 'wp_rest' )
	));
}
add_action( 'wp_enqueue_scripts', 'chm_scripts' );
function chm_admin_scripts($hook) {
	// Only add to the edit.php admin page.
	// See WP docs.
	wp_enqueue_script('chm_admin', get_theme_file_uri( '/assets/js/n4d-admin.min.js' ), array(), '0.1');

	if ('edit.php' !== $hook && 'post.php' !== $hook) return;

	wp_enqueue_script( 'jquery-ui-sortable' );

	wp_localize_script( 'bkkiff', 'wp_ajax_object', array(
		'url'      => home_url(),
		'ajax_url' => admin_url( 'admin-ajax.php' ),
	));
}
add_action('admin_enqueue_scripts', 'chm_admin_scripts');


// REMOVE WP EMOJI
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');

remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );

function custom_admin_css() {
	echo "<link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\"".get_template_directory_uri()."/style-admin.css\" />";
}
add_action('admin_head', 'custom_admin_css');


function n4d_add_featured_galleries( $postTypes ) {
	array_push($postTypes, 'project' );
	return $postTypes;
}
add_filter('fg_post_types', 'n4d_add_featured_galleries' );

add_filter('get_the_archive_title', function ($title) {
	if (is_category()) {
		$title = single_cat_title('', false);
	} elseif (is_tag()) {
		$title = single_tag_title('', false);
	} elseif (is_author()) {
		$title = '<span class="vcard">' . get_the_author() . '</span>';
	} elseif (is_tax()) { //for custom post types
		$title = sprintf(__('%1$s'), single_term_title('', false));
	} elseif (is_post_type_archive()) {
		$title = post_type_archive_title('', false);
	}
	return $title;
});



//INCLUDES
require get_template_directory() . '/_inc/custom/navigation.php';
require get_template_directory() . '/_inc/custom/shortcode.php';
require get_template_directory() . '/_inc/custom/cards.php';
require get_template_directory() . '/_inc/custom/template_functions.php';
require get_template_directory() . '/_inc/custom/admin.php';
require get_template_directory() . '/_inc/custom/admin-filters.php';
require get_template_directory() . '/_inc/custom/mb.php';
require get_template_directory() . '/_inc/custom/company-information.php';
require get_template_directory() . '/_inc/custom/uri.php';
require get_template_directory() . '/_inc/custom/ajax.php';
require get_template_directory() . '/_inc/custom/kdmfi.php';

require get_template_directory() . '/_inc/cpt/project.php';
require get_template_directory() . '/_inc/cpt/file.php';