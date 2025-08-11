<?php
get_header();

$taxonomy  = "projects";
$container = "container";


$slug      = get_query_var($taxonomy);
$term      = get_term_by("slug", $slug, $taxonomy);
$ancestors = ($term) ? get_ancestors($term->term_id, $taxonomy) : false;

$content   = "";
$parent = get_query_var("projects");
if (sizeof($ancestors) == 0){
	$attr     = array(
		"limit"     => 100,
		"dark_mode" => "auto",
		"column"    => ($slug == "personal-practices") ? 3 : 2,
	);
	$attr["parent"] = $slug;


	$container = ($attr["column"] == 2) ? "container-fluid" : $container;


	$content .= "<div class=\"text-center\">";

	$content .= (isset($term) && $term->description) ? "<div class=\"entry term-description mt-4 mb-5\"><div class=\"row\"><div class=\"col-lg-8 offset-lg-2\">".apply_filters("the_content", $term->description)."</div></div></div>" : "";

	$content .= "</div>";//container


	$content .= render_projects($attr);
}
else {
	$container = "container-fluid";

	$content .= "<div class=\"text-center\">";
	$content .= "<div class=\"title-group\">";
	$content .= "<h4 class=\"uc artwork\">{$term->name}</h4>";
	$content .= "<h6>{$term->name}</h6>";
	$content .= "</div>";//title-group


	$content .= (isset($term) && $term->description) ? "<div class=\"entry term-description\"><div class=\"row\"><div class=\"col-lg-8 offset-lg-2\">".apply_filters("the_content", $term->description)."</div></div></div>" : "";

	$content .= n4d_get_timeline($slug);

	$content .= "</div>";//container


	$content .= "<div class=\"row\">";

	$content .= "<div class=\"col-12 col-lg-4 sidebar\">";

	$sidebar  = n4d_get_sidebar($slug);

	$content .= $sidebar['html'];

	$content .= "</div>";//col

	$content .= "<div class=\"col-12 col-lg-6 offset-lg-1\">";

	$content .= "<div class=\"entry\">";
	if (isset($sidebar['first'])){
		$id          = $sidebar['first'];
		$content    .= n4d_get_project($id);
	}

	$content .= "</div>";

	$content .= "</div>";//col
	$content .= "</div>";//row
}


$html  = "";
$html .= "<main class=\"{$container}\">";
$html .= "<article class=\"content-area page\">";
$html .= $content;
$html .= "</article>";
$html .= "</main>";

echo $html;
get_footer();

?>