<?php
add_shortcode( 'n4d_projects', 'render_projects' );
add_shortcode('n4d_carousel', 'n4d_carousel_shortcode');

function render_projects($attributes = null, $content = null){
	global $wp_query, $exclude;
	$paged        = get_query_var('paged');
	$qa           = [];
	$prefix       = "";

	$slug         = get_query_var("projects");
	$slug         = ($slug) ? "{$slug}/" : "";

	$default_attributes = array(
		"ids"        => false,
		"exclude"    => ($exclude) ? $exclude : [],
		"page"       => ($paged) ? $paged : 0,
		"home"       => "{$prefix}/our-work/{$slug}",
		"limit"      => get_option( 'posts_per_page' ),
		"pagniation" => false,
		"slugs"      => false,
		"s"          => false,
		"parent"     => false,
		"dark_mode"  => false,
		"column"     => 2,
	);

	$attributes = shortcode_atts( $default_attributes, $attributes );

	$args       = array(
		"taxonomy"      => "projects",
		"number"        => $attributes["limit"],
		"hide_empty"    => false,
		'orderby' => 'meta_value_num',
		'order' => 'ASC',
		'meta_query' => array(
			'relation' => 'OR',
			array(
				'key' => 'project_order',
				'compare' => 'NOT EXISTS'
			),
			array(
				'key' => 'project_order',
				'value' => 0,
				'compare' => '>='
			)
		),
	);

	if ($attributes["parent"]){
		$parent = get_term_by("slug", $attributes["parent"], "projects");
		if ($parent){
			$args["parent"] = $parent->term_id;
		}
	}

	$items = get_terms( $args );


	$row_class = ($attributes["limit"] == 6 || $attributes["column"] == 3) ? "project-list bordered" : "project-list";

	$html  = "";
	$html .= "<div class=\"row g-0 {$row_class}\">";

	$c = 0;

	foreach($items as $id){
		$c++;
		$col   = ($attributes["limit"] == 6 || $attributes["column"] == 3) ? " col-12 col-lg-4 project-grid" : " col-12 col-lg-6";
		$card    = get_projects_card($id,  $attributes["dark_mode"]);
		$url     = get_permalink($id);

		if (sizeof($items) == 5 && $c < 3){
			$col = " col-12 col-lg-6 project-grid";
		}

		$html .= "<div class=\"grid-item{$col}\">";
		$html .= $card;
		$html .= "</div>";//col

	}
	$html .= "</div>";
	if ( $attributes["pagniation"]  === true ) {
		$html .= n4d_pagniation($attributes["home"]."page/", $attributes["page"], intval($attributes["limit"]), $wp_query->found_posts, "", $attributes["home"]);
	}

	return $html;
}

function n4d_carousel_shortcode($atts, $content) {
	$digits = 5;
	$ran = rand(pow(10, $digits-1), pow(10, $digits)-1);


	extract( shortcode_atts( array(
		'id'         		           => $ran,
		'ids'        		           => '',
		'orderby'       		       => 'post__in',
		'spill'         		       => false,
		'fill'           		       => false,
		"classname"       		       => '',
		"autoplay"         		       => false,
		"indicators"        		   => false,
		"indicatorsthumbnails"		   => false,
		"indicatorsthumbnailscontain"  => false,
		"ratio4x3"                     => false,
		"cover"         		       => false,
		"modal"     		           => false
	), $atts ));


	$ids = explode(",", $ids);
	$gallery_id = "n4d-gallery-{$id}";
	$html       = "";
	$gallery    = "";
	$gClass     = "";
	$gClass    .= ($classname) ? " {$classname}" : "";
	$gClass    .= ($fill) ? " fill" : "";
	$gClass    .= ($spill) ? " spill" : "";
	$gClass    .= ($cover) ? " cover" : "";

	$autoplay_att = ($autoplay) ? " data-bs-ride=\"carousel\"" : "";

	$gallery .= "<div id=\"{$gallery_id}\" class=\"carousel slide gallery-carousel{$gClass}\"{$autoplay_att}>";
	$gallery .= "<div class=\"carousel-inner\">";

	$indicators_html  = "";
	$indicators_class = ($indicatorsthumbnails) ? " thumbnails" : "";
	$indicators_class .= ($indicatorsthumbnailscontain) ? " contain" : "";


	foreach($ids as $key => $id){
		$img     = wp_get_attachment_image( $id, "full");
		$title   = get_the_title($id);
		$active  = ($key == 0) ? " active" : "";
		$current = ($key == 0) ? true : false;

		$caption = wp_get_attachment_caption($id);
		$caption = ($caption) ? $caption : "";

		$img = ($modal) ? "<a data-bs-toggle=\"modal\" data-bs-target=\"#popup-modal\" data-mode=\"gallery\">{$img}</a>" : $img;

		$img = ($ratio4x3) ? "<div class=\"ratio ratio-4x3\">{$img}</div>" : $img;
		$img = "<figure>{$img}<figcaption>{$caption}</figcaption></figure>";
		$img = ($ratio4x3) ? "<div class=\"ratio-wrap\">{$img}</div>" : $img;


		if ($indicators){
			$thumbnail = ($indicatorsthumbnails) ? "<div class=\"image\">".wp_get_attachment_image( $id, "thumbnail")."</div>" : "";
			$indicators_html .= "<li data-bs-target=\"#{$gallery_id}\" data-bs-slide-to=\"{$key}\" class=\"{$active}\" aria-current=\"{$current}\" aria-label=\"Slide {$key}\">{$thumbnail}</li>";
		}

		$gallery .= "<div class=\"carousel-item{$active}\">{$img}</div>";
	}
	$gallery .= "</div>";//inner
	$gallery .= ($indicators) ? "<ul class=\"carousel-indicators{$indicators_class}\">{$indicators_html}</ul>" : "";

	if (sizeof($ids) > 1) {
		$gallery .= "<a class=\"carousel-control-prev\" data-bs-target=\"#{$gallery_id}\" role=\"button\" data-bs-slide=\"prev\">";
		$gallery .= "<span class=\"carousel-control-prev-icon\" aria-hidden=\"true\"></span>";
		$gallery .= "<span class=\"sr-only\">Previous</span>";
		$gallery .= "</a>";
		$gallery .= "<a class=\"carousel-control-next\" data-bs-target=\"#{$gallery_id}\" role=\"button\" data-bs-slide=\"next\">";
		$gallery .= "<span class=\"carousel-control-next-icon\" aria-hidden=\"true\"></span>";
		$gallery .= "<span class=\"sr-only\">Next</span>";
		$gallery .= "</a>";
	}
	$gallery .= "</div>";
	$html .= $gallery;

	return $html;
}