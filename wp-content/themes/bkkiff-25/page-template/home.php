<?php
/**
* Template Name: Home
*/
get_header();

$html  = "";

$html .= "<main>";
$html .= "<article class=\"slide-scroll\">";

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
$html .= "<img src=\"".get_template_directory_uri()."/assets/img/home-1.png\" />";
$html .= "</div>";//stage
$html .= "<a href=\"/overview\" class=\"btn btn-lg btn-primary placed vertical-center horizontal-right\">View Info</a>";
$html .= "</div>";//item

$html .= "<div class=\"carousel-item\" style=\"background-color: #E7DECD;\">";
$html .= "<div class=\"stage container\">";
$html .= "<img src=\"".get_template_directory_uri()."/assets/img/home-2.png\" />";

$form_url = "https://forms.gle/vurGKewyq15EwtqX6";

$html .= "<div class=\"btns\">";
$html .= "<a href=\"{$form_url}\" class=\"btn btn-lg btn-primary\" target=\"_blank\">Submit</a>";
$html .= "<a href=\"".home_url("/asian-short-film-competition/")."\" class=\"btn btn-lg btn-outline-primary\">View Info</a>";
$html .= "</div>";

$html .= "</div>";//stage
$html .= "</div>";//item

$html .= "<div class=\"carousel-item\" style=\"background-color: #D94423\">";
$html .= "<div class=\"stage container\">";
$html .= "<img src=\"".get_template_directory_uri()."/assets/img/home-3.png\" />";

$form_url = "https://forms.gle/3Hv6ZtoKNKxsJRhN6";

$html .= "<div class=\"btns\">";
$html .= "<a href=\"{$form_url}\" class=\"btn btn-lg btn-primary\" target=\"_blank\">Submit</a>";
$html .= "<a href=\"".home_url("/asian-project-pitching/")."\" class=\"btn btn-lg btn-outline-primary\">View Info</a>";
$html .= "</div>";

$html .= "</div>";//stage
$html .= "</div>";//item

$html .= "<div class=\"carousel-item\" style=\"background-color: #D9C27E;\">";
$html .= "<div class=\"stage container\">";
$html .= "<img src=\"".get_template_directory_uri()."/assets/img/home-4.png?c=12\" />";

$form_url = "https://forms.gle/aTFLhxtcyieZ6HVUA";

$html .= "<div class=\"btns\">";
$html .= "<a href=\"{$form_url}\" class=\"btn btn-lg btn-primary\" target=\"_blank\">Submit</a>";
$html .= "<a href=\"".home_url("/thai-project-pitching/")."\" class=\"btn btn-lg btn-outline-primary\">View Info</a>";
$html .= "</div>";

$html .= "</div>";//stage
$html .= "</div>";//item


$html .= "</div>";//inner
$html .= "</div>";//carousel

$html .= "</article>";
$html .= "</main>";

echo $html;

get_footer();
?>