<?php
$id          = get_the_ID();
$html        = "";
$content     = "";
$container   = "container-fluid";

$terms       = wp_get_post_terms($id, "projects");
$term        = ($terms) ? current($terms) : false;
$slug        = ($term) ? $term->slug : false;

$years       = wp_get_post_terms($id, "timeline");
$year        = ($years) ? current($years) : false;
$year        = ($year) ? $year->slug : false;

$content .= "<div class=\"text-center\">";
$content .= "<div class=\"title-group\">";
$content .= "<h4 class=\"uc artwork\">{$term->name}</h4>";
//$content .= "<h6>{$term->name}</h6>";
$content .= "</div>";//title-group


$content .= (isset($term) && $term->description) ? "<div class=\"entry term-description\"><div class=\"row\"><div class=\"col-lg-8 offset-lg-2\">".apply_filters("the_content", $term->description)."</div></div></div>" : "";


$content .= n4d_get_timeline($slug, $year);

$content .= "</div>";//container


$content .= "<div class=\"row\">";

$content .= "<div class=\"col-12 col-lg-4 sidebar\">";

$sidebar  = n4d_get_sidebar($slug, $id);

$content .= $sidebar['html'];

$content .= "</div>";//col

$content .= "<div class=\"col-12 col-lg-6 offset-lg-1\">";


$content .= "<div class=\"entry\">";
$content    .= n4d_get_project($id);
$content .= "</div>";


$content .= "</div>";//col
$content .= "</div>";//row


$html .= "<main class=\"{$container}\">";
$html .= "<article class=\"content-area page\">";
$html .= $content;
$html .= "</article>";
$html .= "</main>";

get_header();
echo $html;
get_footer();
?>