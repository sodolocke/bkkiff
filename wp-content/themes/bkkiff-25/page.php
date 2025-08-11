<?php
$html       = "";
$html      .= get_marquee();

get_header();

$html .= "<main class=\"container\">";
$html .= "<article class=\"content-area page\">";

$html .= "<div class=\"row\">";
$html .= "<div class=\"col-12\">";

//$html .= "<h1 class=\"page-title sm\">".get_the_title()."</h1>";
$html .= apply_filters( "the_content", get_the_content() );

$html .= "</div>";
$html .= "</div>";


$html .= "</article>";
$html .= "</main>";

echo $html;
get_footer();
?>