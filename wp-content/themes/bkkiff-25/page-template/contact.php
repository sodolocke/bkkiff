<?php
/**
* Template Name: Contact
*/
get_header();

$html  = "";

$html .= get_marquee_page(get_the_ID(), true);
$html .= "<main class=\"container\">";
$html .= "<article class=\"content-area page pb-5\">";

$html .= apply_filters( "the_content", get_the_content() );

$html .= "</article>";
$html .= "</main>";

echo $html;

get_footer();
?>