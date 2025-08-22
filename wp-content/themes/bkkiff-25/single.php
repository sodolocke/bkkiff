<?php
$html        = "";
$id          = get_the_ID();
$taxonomy    = "category";
$terms       = wp_get_post_terms($id, $taxonomy, array());
$term        = current($terms);

$cat_name    = ($term) ? $term->name : "";
$date        = get_post_field("post_date", $id);
$lang        = apply_filters( 'wpml_current_language', NULL );

if ($lang == "th"){
	$d     = date("d", strtotime($date));
	$m     = date("n", strtotime($date));
	$m     = convert_month_th($m-1);
	$y     = date("Y", strtotime($date)) + 543;
	$date  = "{$d} {$m} {$y}";
} else {
	$date  = date("d F Y", strtotime($date));
}
$img_id      = get_post_thumbnail_id($id);
$url         = get_permalink($id);
$src         = wp_get_attachment_image_src( $img_id, 'full');

get_header();


$html .= "<main class=\"container\">";
$html .= "<h1 class=\"page-title-parent\">{$cat_name}</h1>";
$html .= "<article class=\"content-area single mb-5\">";

$html .= "<div class=\"row\">";
$html .= "<div class=\"col-12 col-lg-8 offset-lg-2\">";

$html .= "<h1 class=\"page-title\">".get_the_title()."</h1>";
$html .= "<div class=\"meta mb-4\">";
$html .= "<ul class=\"nav nav-pills\">";
if ($terms){
	foreach($terms as $term){
		$term_url = get_term_link($term->slug, $taxonomy);
		$html .= "<li class=\"nav-item\"><div class=\"nav-link\">{$term->name}</div></li>";
	}
}
$html .= "</ul>";

$html .= "<div class=\"card-date mb-4\">{$date}</div>";

$html .= ($img_id) ? wp_get_attachment_image( $img_id, 'large', false, array( "class" => "mb-4")) : "";

$html .= n4d_get_share($id);

$html .= "</div>";



$html .= apply_filters( "the_content", get_the_content() );

$html .= "</div>";
$html .= "</div>";


$html .= "</article>";

$html .= "</main>";

echo $html;
get_footer();
?>