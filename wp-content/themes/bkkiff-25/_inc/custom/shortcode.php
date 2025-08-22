<?php
add_shortcode( 'n4d_news', 'render_news' );
add_shortcode('n4d_news', 'n4d_news_carousel');
add_shortcode('n4d_carousel', 'n4d_carousel_shortcode');
add_shortcode('n4d_galleries', 'render_galleries');

function render_news($attributes = null, $content = null){
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
		"post_type"        => "post",
		"posts_per_page"   => $attributes["limit"],
		"fields"           => "ids",
		"suppress_filters" => false
	);

	$items = get_posts( $args );

	$html  = "";
	$html .= "<div class=\"row g-3\">";

	$c = 0;

	foreach($items as $id){
		$c++;
		$col   = " col-12 col-sm-6 col-lg-4";
		$card    = get_post_card($id);
		$url     = get_permalink($id);


		$html .= "<div class=\"{$col}\">";
		$html .= $card;
		$html .= "</div>";//col

	}
	$html .= "</div>"; // row
	if ( $attributes["pagniation"]  === true ) {
		$html .= n4d_pagniation($attributes["home"]."page/", $attributes["page"], intval($attributes["limit"]), $wp_query->found_posts, "", $attributes["home"]);
	}


	return $html;
}
function n4d_galleries_shortcode($attributes = null, $content = null){
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
		"post_type"        => "post",
		"posts_per_page"   => $attributes["limit"],
		"fields"           => "ids",
		"suppress_filters" => false
	);

	$items = get_posts( $args );

	$html  = "";
	$html .= "<div class=\"row g-3\">";

	$c = 0;

	foreach($items as $id){
		$c++;
		$col   = " col-12 col-sm-6 col-lg-3";
		$card    = get_gallery_card($id);
		$url     = get_permalink($id);


		$html .= "<div class=\"{$col}\">";
		$html .= $card;
		$html .= "</div>";//col

	}
	$html .= "</div>"; // row
	if ( $attributes["pagniation"]  === true ) {
		$html .= n4d_pagniation($attributes["home"]."page/", $attributes["page"], intval($attributes["limit"]), $wp_query->found_posts, "", $attributes["home"]);
	}


	return $html;
}



function render_galleries($attributes = null, $content = null){
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
		"post_type"        => "gallery",
		"posts_per_page"   => $attributes["limit"],
		"fields"           => "ids",
		"suppress_filters" => false
	);

	$items = get_posts( $args );

	$html  = "";
	$html .= "<div class=\"row g-3\">";

	$c = 0;

	foreach($items as $id){
		$c++;
		$col   = " col-12 col-sm-6 col-lg-3";
		$card    = get_gallery_card($id);
		$url     = get_permalink($id);


		$html .= "<div class=\"{$col}\">";
		$html .= $card;
		$html .= "</div>";//col

	}
	$html .= "</div>"; // row
	if ( $attributes["pagniation"]  === true ) {
		$html .= n4d_pagniation($attributes["home"]."page/", $attributes["page"], intval($attributes["limit"]), $wp_query->found_posts, "", $attributes["home"]);
	}


	return $html;
}


function n4d_news_carousel($attributes = null, $content = null) {
	$lang         = apply_filters( 'wpml_current_language', NULL );
	$prefix       = ($lang == "en") ? "" : $lang;


	$default_attributes = array(
		"ids"        => false,
		"exclude"    => ($exclude) ? $exclude : [],
		"page"       => ($paged) ? $paged : 0,
		"home"       => "{$prefix}/category/news",
		"limit"      => 9,
		"title"      => "News",
		"more"       => true
	);

	$attributes = shortcode_atts( $default_attributes, $attributes );

	extract($attributes);

	$page_title = $title;

	$ids        = (!$ids) ? get_posts(array(
		"post_type"        => "post",
		"posts_per_page"   => $limit,
		"fields"           => "ids",
		"suppress_filters" => false
	)) : explode(",", $ids);


	$gallery_id = "n4d-news";
	$html          = "";
	$gallery       = "";
	$gClass        = "";
	$gClass       .= ($classname) ? " {$classname}" : "";
	$autoplay_att  = ($autoplay) ? " data-bs-ride=\"carousel\"" : "";
	$indicators    = ( sizeof($ids) > 0 ) ? true : false;

	$gallery .= "<div id=\"{$gallery_id}\" class=\"carousel slide gallery-carousel{$gClass}\"{$autoplay_att}>";
	$gallery .= "<div class=\"carousel-inner\">";

	$indicators_html  = "";
	$indicators_class = ($indicatorsthumbnails) ? " thumbnails" : "";
	$indicators_class .= ($indicatorsthumbnailscontain) ? " contain" : "";

	$c = 0;
	$n = 0;

	foreach($ids as $key => $id){
		$title   = get_the_title($id);
		$active  = ($key == 0) ? " active" : "";
		$current = ($key == 0) ? true : false;

		$card = get_post_card($id);

		$gallery .= ( $c == 0 ) ? "<div class=\"carousel-item{$active}\">" : "";
		$gallery .= ( $c == 0 ) ? "<div class=\"container\"><div class=\"row g-3\">" : "";
		$gallery .= "<div class=\"col-12 col-lg-4\">";
		$gallery .= $card;
		$gallery .= "</div>";//col

		if ($indicators && $c == 0){
			$indicators_html .= "<li data-bs-target=\"#{$gallery_id}\" data-bs-slide-to=\"{$n}\" class=\"{$active}\" aria-current=\"{$current}\" aria-label=\"Set {$n}\"></li>";
		}

		$c++;

		if ($c == 3 || $key == (sizeof($ids) - 1)){
			$c = 0;
			$n++;
		}
		$gallery .= ( $c == 0 ) ? "</div></div>" : "";//container row
		$gallery .= ( $c == 0 ) ? "</div>" : "";//carousel-item
	}
	$gallery .= "</div>";//inner
	$gallery .= ($indicators) ? "<ul class=\"carousel-indicators{$indicators_class}\">{$indicators_html}</ul>" : "";

	if (sizeof($ids) > 3) {
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

	$view  = ($more) ? "<a href=\"{$home}\" class=\"btn btn-outline-dark\">".__("View All", "bkkiff")."</a>" : "";

	$html .= "<div>";
	$html .= ($title) ? "<div class=\"container\"><h2 class=\"news-carousel-title\">{$page_title}{$view}</h2></div>" : "";
	$html .= $gallery;
	$html .= "</div>";

	return $html;
}
function render_news_marquee($attributes = null, $content = null) {

	$default_attributes = array(
		"ids"        => false,
		"exclude"    => ($exclude) ? $exclude : [],
		"page"       => ($paged) ? $paged : 0,
		"home"       => "{$prefix}/news",
		"limit"      => 3,
		"title"      => "News",
		"more"       => true
	);

	$attributes = shortcode_atts( $default_attributes, $attributes );

	extract($attributes);

	$page_title = $title;

	$ids        = (!$ids) ? get_posts(array(
		"post_type"      => "post",
		"posts_per_page" => $limit,
		"fields"         => "ids",
		"suppress_filters" => false
	)) : explode(",", $ids);


	$gallery_id = "n4d-news-marquee";
	$html          = "";
	$gallery       = "";
	$gClass        = "";
	$gClass       .= ($classname) ? " {$classname}" : "";
	$autoplay_att  = ($autoplay) ? " data-bs-ride=\"carousel\"" : "";
	$indicators    = ( sizeof($ids) > 0 ) ? true : false;

	$gallery .= "<div id=\"{$gallery_id}\" class=\"carousel slide news-marquee{$gClass}\"{$autoplay_att}>";
	$gallery .= "<div class=\"carousel-inner\">";

	$indicators_html  = "";
	$indicators_class = ($indicatorsthumbnails) ? " thumbnails" : "";
	$indicators_class .= ($indicatorsthumbnailscontain) ? " contain" : "";

	$c = 0;
	$n = 0;

	foreach($ids as $key => $id){
		$title   = get_the_title($id);
		$active  = ($key == 0) ? " active" : "";
		$current = ($key == 0) ? true : false;

		$gallery .= ( $c == 0 ) ? "<div class=\"carousel-item{$active}\">" : "";
		$gallery .= get_news_marquee_card($id);

		if ($indicators && $c == 0){
			$indicators_html .= "<li data-bs-target=\"#{$gallery_id}\" data-bs-slide-to=\"{$key}\" class=\"{$active}\" aria-current=\"{$current}\" aria-label=\"Set {$key}\"></li>";
		}

		$gallery .= "</div>";//carousel-item
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

	$html .= "<div>";
	$html .= $gallery;
	$html .= "</div>";

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