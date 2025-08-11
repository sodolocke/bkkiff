<?php
function get_project_card($id){
	$title       = get_the_title($id);
	$img_id      = get_post_thumbnail_id($id);
	$url         = get_permalink($id);
	$src         = wp_get_attachment_image_src( $img_id, 'full');


	$card  = "";
	$card .= "<div class=\"card project\">";
	$card .= "<a href=\"{$url}\" class=\"card-image\">";
	if ($img_id) {
		$card .= wp_get_attachment_image( $img_id, 'card', false, array());
	}

	$card .= "<span class=\"title-overlay\">";
	$card .= "<h3 class=\"uc artwork\">".get_the_title($id)."</h3>";
	$card .= "</span>";


	$card .= "</a>";
	$card .= "</div>";//card

	return $card;
}
function get_projects_card($term, $dark_mode = false){
	$taxonomy    = "projects";
	$title       = $term->name;
	$url         = get_term_link($term->slug, $taxonomy);
	$card_class  = "";
	$card_class .= (boolval($dark_mode) == true && $dark_mode !== "auto") ? " light" : $card_class;
	$image       = "";

	if (function_exists('z_taxonomy_image')) {
		$image = z_taxonomy_image($term->term_id, 'full', array(), false);
		if ($image && $dark_mode == "auto"){
			$card_class .= " light";
		}
	}

	$card  = "";
	$card .= "<div class=\"card project{$card_class}\">";


	$card .= "<a href=\"{$url}\" class=\"card-image\">";
	$card .= $image;


	$card .= "<span class=\"title-overlay\">";
	$card .= "<h3 class=\"uc artwork\">".$term->name."</h3>";
	$card .= "</span>";

	$card .= "</a>";


	$card .= "</div>";//card

	return $card;
}
function get_job_card($id, $show = false){
	$taxonomy  = "jobs";
	$title     = get_the_title($id);
	$img_id    = get_post_thumbnail_id($id);
	$url       = get_permalink($id);
	$terms     = wp_get_post_terms($id, $taxonomy, array());
	$lang     = apply_filters( 'wpml_current_language', NULL );


	$location  = get_post_meta($id, "_location", true);
	$days      = get_post_meta($id, "_days", true);
	$type      = get_post_meta($id, "_type", true);

	$card  = "";
	$card .= "<div class=\"card job\">";

	$expanded = ($show) ? true : false;
	$shown    = ($show) ? " show" : "";

	$card .= "<a class=\"card-trigger\" data-bs-toggle=\"collapse\" href=\"#job-{$id}-details\" role=\"button\" aria-expanded=\"{$expanded}\" aria-controls=\"job-{$id}-details\">{$title}<i class=\"fa-solid fa-chevron-down\"></i></a>";

	$card .= "<div class=\"collapse{$shown}\" id=\"job-{$id}-details\">";
	$card .= "<div class=\"card-body\">";

	$card .= "<div class=\"row mb-5\">";

	if ($location){
		$card .= "<div class=\"wp-block-n4d-col col-12 col-md-4 line-right\">";
		$card .= "<h6 class=\"wp-block-heading\"><strong>".__("WORK LOCATION", "sls")."</strong></h6>";
		$card .= $location;
		$card .= "</div>";
	}

	if ($days){
		$card .= "<div class=\"wp-block-n4d-col col-12 col-md-4 line-right\">";
		$card .= "<h6 class=\"wp-block-heading\"><strong>".__("WORKING DAY", "sls")."</strong></h6>";
		$card .= $days;
		$card .= "</div>";
	}

	if ($type){
		$card .= "<div class=\"wp-block-n4d-col col-12 col-md-4\">";
		$card .= "<h6 class=\"wp-block-heading\"><strong>".__("EMPLOYMENT TYPE", "sls")."</strong></h6>";
		$card .= $type;
		$card .= "</div>";
	}

	$card .= "</div>";//row





	$card .= apply_filters("the_content", get_post_field("post_content", $id));

	$card .= "<div class=\"row\">";

	$card .= "<div class=\"col-12 col-md-9 col-lg-8 offset-md-3 offset-lg-4\">";
	$prefix = ($lang == "th") ? "" : "/{$lang}";
	$card .= "<a href=\"{$prefix}/careers/join/apply?j={$id}\" class=\"btn btn-primary btn-icon arrow\">APPLY NOW</a>";
	$card .= "</div>";

	$card .= "</div>";

	$card .= "</div>";//body
	$card .= "</div>";//collapse

	$card .= "</div>";//card

	return $card;
}
function get_post_card($id, $hide_cat = false){
	$lang      = apply_filters( 'wpml_current_language', NULL );
	$title     = get_the_title($id);
	$date      = get_post_field("post_date", $id);

	if ($lang == "th"){
		$d     = date("d", strtotime($date));
		$m     = date("n", strtotime($date));
		$m     = convert_month_th($m-1);
		$y     = date("Y", strtotime($date)) + 543;
		$date  = "{$d} {$m} {$y}";
	} else {
		$date  = date("d F Y", strtotime($date));
	}

	$img_id    = get_post_thumbnail_id($id);
	$url       = get_permalink($id);
	$excerpt   = get_the_excerpt($id);

	$taxonomy  = "category";
	$terms     = wp_get_post_terms($id, $taxonomy, array());

	$card  = "";
	$card .= "<div class=\"card project\">";


	$card .= "<a href=\"{$url}\" class=\"card-image\">";
	if ($img_id) {
		$card .= wp_get_attachment_image( $img_id, 'card', false, array());
	}
	$card .= "</a>";


	$card .= "<div class=\"card-body\">";
	$card .= "<div class=\"meta\">";

	if (!$hide_cat){
		$card .= "<ul class=\"nav nav-pills\">";
		if ($terms){
			foreach($terms as $term){
				$term_url = get_term_link($term->slug, $taxonomy);
				$card .= "<li class=\"nav-item\"><div class=\"nav-link\">{$term->name}</div></li>";
			}
		}
		$card .= "</ul>";
	}
	$card .= "<div class=\"card-date\">{$date}</div>";
	$card .= "</div>";

	$card .= "<h5 class=\"card-title\"><a href=\"{$url}\">{$title}</a></h5>";
	$card .= "</div>";//body


	$card .= "<div class=\"card-footer\">";
	$card .= "<a href=\"{$url}\" class=\"btn btn-primary btn-icon arrow\">".__("More", "sls")."</a>";
	$card .= "</div>";//footer


	$card .= "</div>";//card

	return $card;
}
function get_board_card($id){
	$title     = get_the_title($id);
	$img_id    = get_post_thumbnail_id($id);
	$url       = get_permalink($id);
	$excerpt   = get_the_excerpt($id);
	$taxonomy  = "members";
	$position  = get_post_meta($id, "_board", true);
//	$terms     = wp_get_post_terms($id, $taxonomy, array());


	$format_t  = explode(" ", $title);
	if (sizeof($format_t) > 1){
		$end = array_key_last($format_t);
		$format_t[$end] = "<br />".$format_t[$end];
	}

	$title     = implode(" ", $format_t);

	$card  = "";
	$card .= "<div class=\"card board\">";
	$card .= "<div class=\"card-image\">";

	if (function_exists('kdmfi_has_featured_image')) {
		$profile_id = kdmfi_has_featured_image('profile-image', $id);
		$img_id     = ($profile_id) ? $profile_id : $img_id;
	};
	$image      = ($img_id) ? wp_get_attachment_image_src($img_id, "full") : false;
	$style      = ($image) ? "background-image:url({$image[0]})" : "";

	if ($img_id) {
		$card .= wp_get_attachment_image( $img_id, 'card', false, array());
	}
	$card .= "</div>";

	$card .= "<div class=\"card-body\">";
	$card .= "<h5 class=\"card-title\">{$title}</h5>";
	$card .= ($position) ? "<div class=\"position\">{$position}</div>" : "";

	$card .= "</div>";//body

	$card .= "</div>";//card

	return $card;
}
function get_member_card($id){
	$title     = get_the_title($id);
	$img_id    = get_post_thumbnail_id($id);
	$url       = get_permalink($id);
	$excerpt   = get_the_excerpt($id);
	$taxonomy  = "members";
	$position  = get_post_meta($id, "_positions", true);
//	$terms     = wp_get_post_terms($id, $taxonomy, array());


	$format_t  = explode(" ", $title);
	if (sizeof($format_t) > 1){
		$end = array_key_last($format_t);
		$format_t[$end] = "<br />".$format_t[$end];
	}

	$title     = implode(" ", $format_t);

	$card  = "";
	$card .= "<div class=\"card member\">";
	$card .= "<div class=\"card-image\">";
	$image      = ($img_id) ? wp_get_attachment_image_src($img_id, "full") : false;
	$style      = ($image) ? "background-image:url({$image[0]})" : "";

	if ($img_id) {
		$card .= wp_get_attachment_image( $img_id, 'card', false, array());
	}
	$card .= "</div>";

	$card .= "<div class=\"card-body\">";
	$card .= "<h5 class=\"card-title\">{$title}</h5>";
	$card .= ($position) ? "<div class=\"position\">{$position}</div>" : "";

	$card .= "</div>";//body

	$card .= "</div>";//card

	return $card;
}
function get_related_card($id){
	$title     = get_the_title($id);
	$date      = get_post_field("post_date", $id);

	$date      = date("d F Y", strtotime($date));

	$img_id    = get_post_thumbnail_id($id);
	$url       = get_permalink($id);

	$card  = "";
	$card .= "<div class=\"card related\">";


	$card .= "<div class=\"row g-0\">";
	$card .= "<div class=\"col-5 col-md-4\">";


	$card .= "<a href=\"{$url}\" class=\"card-image\">";
	if ($img_id) {
		$card .= wp_get_attachment_image( $img_id, 'thumbnail', false, array());

	}
	$card .= "</a>";
	$card .= "</div>";//col


	$card .= "<div class=\"col-7 col-md-8\">";
	$card .= "<div class=\"card-body\">";
	$card .= "<h6 class=\"card-title\"><a href=\"{$url}\">{$title}</a></h5>";
	$card .= "<small class=\"card-date\">{$date}</small>";
	$card .= "</div>";//body
	$card .= "</div>";//col

	$card .= "</div>";//row
	$card .= "</div>";//card

	return $card;
}
function get_award_card($id){
	$title     = get_the_title($id);

	$img_id    = get_post_thumbnail_id($id);
	$url       = get_permalink($id);
	$content   = get_post_field("post_content", $id);

	$taxonomy  = "awards";
	$terms     = wp_get_post_terms($id, $taxonomy, array());

	$card  = "";
	$card .= "<div class=\"card award\">";


	$card .= "<a href=\"{$url}\" class=\"card-image trigger\" data-bs-toggle=\"modal\" data-bs-target=\"#popup-modal\">";
	if ($img_id) {
		$card .= wp_get_attachment_image( $img_id, 'card', false, array());
	}
	$card .= "</a>";


	$card .= "<div class=\"card-body\">";

	$meta  = "<div class=\"meta\">";

	$meta .= "<ul class=\"nav nav-pills\">";
	if ($terms){
		foreach($terms as $term){
			$term_url = get_term_link($term->slug, $taxonomy);
			$meta .= "<li class=\"nav-item\"><a href=\"{$term_url}\" class=\"nav-link\">{$term->name}</a></li>";
		}
	}
	$meta .= "</ul>";

	$meta .= "</div>";

	$card .= $meta;
	$card .= "<h5 class=\"card-title\"><a href=\"{$url}\" data-bs-toggle=\"modal\" data-bs-target=\"#popup-modal\" class=\"trigger\">{$title}</a></h5>";
	$card .= "</div>";//body


	$card .= "<div class=\"card-footer\">";
	$card .= "<a href=\"{$url}\" class=\"btn btn-primary btn-icon arrow trigger\" data-bs-toggle=\"modal\" data-bs-target=\"#popup-modal\">".__("More", "sls")."</a>";
	$card .= "</div>";//footer


	$card .= "<div class=\"modal-content\">";
	$card .= "<div class=\"row\">";
	if ($img_id) {
		$card .= "<div class=\"col-12 col-lg-6\">";
		$card .= wp_get_attachment_image( $img_id, 'full', false, array());
		$card .= "</div>";//col
	}
	$card .= "<div class=\"col-12 col-lg-6\">";
	$card .= $meta;
	$card .= "<div class=\"entry\">";
	$card .= "<h5 class=\"title\">{$title}</h5>";
	$card .= apply_filters("the_content", $content);
	$card .= "</div>";//entry
	$card .= "</div>";//col
	$card .= "</div>";//row
	$card .= "</div>";//modal-content


	$card .= "</div>";//card

	return $card;

}
function get_page_card($id){
	$title     = get_the_title($id);

	if (strpos($title, 'Business') !== false) {
		$title = str_replace(" Business", "<br /> Business", $title);
	}

	$img_id    = get_post_thumbnail_id($id);
	$url       = get_permalink($id);
	$excerpt   = get_post_meta($id, "_description", true);

	$card  = "";
	$card .= "<div class=\"card page\">";
	$card .= "<a href=\"{$url}\" class=\"card-mask\"></a>";



	$card .= "<a href=\"{$url}\" class=\"card-image\">";
	if ($img_id) {
		$card .= wp_get_attachment_image( $img_id, 'large', false, array());

	}
	$card .= "</a>";
	$icon = "";
	$title_class = "";

	if (function_exists('kdmfi_has_featured_image')) {
		$icon_id = kdmfi_has_featured_image('icon-image', $id);
		$icon      = ($icon_id) ? wp_get_attachment_image($icon_id, "thumbnail") : $icon;
		$title_class = ($icon !== "") ? " hasIcon" : "";
	}

	$card .= "<div class=\"card-body{$title_class}\">";
	$card .= "<h4 class=\"card-title\">{$icon}<a href=\"{$url}\">{$title}</a></h4>";
	if ($excerpt) $card .= "<div class=\"excerpt\">{$excerpt}</div>";
	$card .= "</div>";//body
	$card .= "<i class=\"arrow fa-solid fa-arrow-right\"></i>";

	$card .= "</div>";//card

	return $card;
}
function get_product_card($id){
	$taxonomy  = "products";
	$title     = get_the_title($id);
	$img_id    = get_post_thumbnail_id($id);
	$url       = get_permalink($id);

	$card  = "";
	$card .= "<div class=\"card product\">";


	$card .= "<div class=\"card-image\">";
	if ($img_id) {
		$card .= wp_get_attachment_image( $img_id, 'card', false, array());
	}
	$card .= "</div>";


	$card .= "<div class=\"card-body\">";
	$card .= "<div class=\"card-icon\">";

	if (function_exists('kdmfi_has_featured_image')) {
		$banner_id = kdmfi_has_featured_image('banner-image', $id);
		$img_id    = ($banner_id) ? $banner_id : false;
		$card     .= ($img_id) ? wp_get_attachment_image($img_id, "full", false, array("class" => "banner-image")) : false;
	}

	$card .= "</div>";
	$card .= "<div class=\"card-title\">{$title}</div>";

	$card .= "</div>";//body


	$card .= "</div>";//card

	return $card;
}