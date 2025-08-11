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
$html .= render_projects($attr);
$html .= "</article>";
$html .= "</main>";

get_header();
echo $html;
get_footer();



?>