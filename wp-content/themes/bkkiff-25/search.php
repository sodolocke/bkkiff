<?php
$title    = false;
$attr     = array(
	"limit" => 20
);
$parent = get_query_var("projects");
if ($parent){
	$attr["parent"] = $parent;
}

$html  = "";
$html .= "<main class=\"container pb-5\">";
$html .= "<article class=\"content-area\">";

$html .= "<header class=\"page-header\">";
$html .= "<h1 class=\"page-title sm\">".sprintf( __( 'ผลการค้นหาสำหรับ <em>%s</em>', 'shape' ), '<span>' . get_search_query() . '</span>' )."</h1>";
$html .= "</header>";

 if ( have_posts() ) :

	while ( have_posts() ) : the_post();
		$id    = get_the_ID();
		$url   = get_permalink($id);
		$html .= "<div class=\"card search\">";

		$html .= "<div class=\"card-body\">";
		$html .= "<h5 class=\"card-title\">".get_the_title($id)."</h5>";
		$html .= "<p class=\"card-text\">".get_the_excerpt($id)."</p>";
		$html .= "</div>";
		$html .= "<div class=\"card-footer\">";
		$html .= "<a href=\"{$url}\" class=\"btn btn-primary\">อ่านเพิ่มเติม</a>";
		$html .= "</div>";


		$html .= "</div>";

	endwhile;

else :
endif;


$html .= "</article>";
$html .= "</main>";

get_header();
echo $html;
get_footer();



?>