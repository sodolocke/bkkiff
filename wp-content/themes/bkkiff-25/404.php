<?php
get_header();

$html  = "";

$html .= "<main class=\"container\">";
$html .= "<article class=\"content-area page pb-5\">";

$html .= get_the_title();
$html .= apply_filters( "the_content", get_the_content() );

$html .= "</article>";
$html .= "</main>";

echo $html;
get_footer();
?>