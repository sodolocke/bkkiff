<?php
/**
* Template Name: Slides
*/
get_header();

$html  = "";

$html .= "<main>";
$html .= "<article class=\"slide-scroll\" data-bs-spy=\"scroll\" data-bs-target=\"#menu-bar\">";
//$html .= get_the_title();
$html .= apply_filters( "the_content", get_the_content() );

$html .= "<a class=\"scrolldown\"><small>SCROLL DOWN</small></a>";
$html .= "</article>";
$html .= "</main>";

echo $html;

get_footer();
?>