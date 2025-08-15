<?php
/**
* Template Name: Home
*/
get_header();

$html  = "";

$html .= "<main>";
$html .= "<article class=\"slide-scroll\">";

/*
$html .= "<div id=\"marquee-indicators\" class=\"carousel slide\">";
$html .= "<div class=\"carousel-indicators\">";
$html .= "<button type=\"button\" data-bs-target=\"#marquee-indicators\" data-bs-slide-to=\"0\" class=\"active\" aria-current=\"true\" aria-label=\"Slide 1\"></button>";
$html .= "<button type=\"button\" data-bs-target=\"#marquee-indicators\" data-bs-slide-to=\"1\" aria-label=\"Slide 2\"></button>";
$html .= "<button type=\"button\" data-bs-target=\"#marquee-indicators\" data-bs-slide-to=\"2\" aria-label=\"Slide 3\"></button>";
$html .= "<button type=\"button\" data-bs-target=\"#marquee-indicators\" data-bs-slide-to=\"3\" aria-label=\"Slide 4\"></button>";
$html .= "</div>";//indicators
$html .= "<div class=\"carousel-inner\">";

$html .= "<div class=\"carousel-item active\">";
$html .= "<div class=\"stage container\">";
$html .= "<img src=\"".get_template_directory_uri()."/assets/img/home-1-2.png\" />";
$html .= "</div>";//stage
$html .= "<a href=\"/festival\" class=\"btn btn-lg btn-dark placed vertical-center horizontal-right\">View Info</a>";
$html .= "</div>";//item

$html .= "<div class=\"carousel-item\" style=\"background-color: #FEE9E2;\">";
$html .= "<div class=\"stage container\">";
$html .= "<img src=\"".get_template_directory_uri()."/assets/img/home-2-2.png\" />";

$form_url = "https://forms.gle/vurGKewyq15EwtqX6";

$html .= "<div class=\"btns\">";
$html .= "<a href=\"{$form_url}\" class=\"btn btn-lg btn-dark\" target=\"_blank\">Apply</a>";
$html .= "<a href=\"".home_url("/asian-short-film-competition/")."\" class=\"btn btn-lg btn-outline-dark\">View Info</a>";
$html .= "</div>";

$html .= "</div>";//stage
$html .= "</div>";//item

$html .= "<div class=\"carousel-item\" style=\"background-color: #FA845C\">";
$html .= "<div class=\"stage container\">";
$html .= "<img src=\"".get_template_directory_uri()."/assets/img/home-3-2.png\" />";

$form_url = "https://forms.gle/3Hv6ZtoKNKxsJRhN6";

$html .= "<div class=\"btns\">";
$html .= "<a href=\"{$form_url}\" class=\"btn btn-lg btn-dark\" target=\"_blank\">Apply</a>";
$html .= "<a href=\"".home_url("/asian-project-pitching/")."\" class=\"btn btn-lg btn-outline-dark\">View Info</a>";
$html .= "</div>";

$html .= "</div>";//stage
$html .= "</div>";//item

$html .= "<div class=\"carousel-item\" style=\"background-color: #B1DED5;\">";
$html .= "<div class=\"stage container\">";
$html .= "<img src=\"".get_template_directory_uri()."/assets/img/home-4-2.png?c=2\" />";

$form_url = "https://forms.gle/aTFLhxtcyieZ6HVUA";

$html .= "<div class=\"btns\">";
$html .= "<a href=\"{$form_url}\" class=\"btn btn-lg btn-dark\" target=\"_blank\">Apply</a>";
$html .= "<a href=\"".home_url("/thai-project-pitching/")."\" class=\"btn btn-lg btn-outline-dark\">View Info</a>";
$html .= "</div>";

$html .= "</div>";//stage
$html .= "</div>";//item


$html .= "</div>";//inner
$html .= "</div>";//carousel
*/

$html .= apply_filters("the_content", $post->post_content);

$html .= "</article>";
$html .= "</main>";

echo $html;

get_footer();
?>