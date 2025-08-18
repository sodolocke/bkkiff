<?php
$title    = false;
$attr     = array(
	"limit" => 6
);
$parent = get_query_var("projects");
if ($parent){
	$attr["parent"] = $parent;
}
$page_title = get_the_archive_title();

$html  = "";
$html .= "<main class=\"container pb-5\">";
$html .= "<h2 class=\"news-carousel-title\">{$page_title}</h2>";
$html .= render_news_marquee();
$html .= "<article class=\"content-area\">";
$html .= "<h2 class=\"news-carousel-title\">All {$page_title}</h2>";
$html .= render_news($attr);
$html .= "</article>";
$html .= "</main>";

get_header();
echo $html;
get_footer();



?>