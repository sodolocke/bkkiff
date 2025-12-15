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
	$card .= "<div class=\"card post\">";


	$card .= "<a href=\"{$url}\" class=\"card-image\">";
	$card .= ($img_id) ? wp_get_attachment_image( $img_id, 'card', false, array()) : "";
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

	$card .= "<h4 class=\"card-title\"><a href=\"{$url}\">{$title}</a></h4>";
	$card .= "<div class=\"card-excerpt\">";
	$card .= get_the_excerpt($id);
	$card .= "</div>";//excerpt
	$card .= "</div>";//body

	$card .= "</div>";//card

	return $card;
}
function get_gallery_card($id, $hide = false){
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
	$card .= "<div class=\"card gallery\">";


	$card .= "<a data-id=\"{$id}\" data-bs-toggle=\"modal\" data-bs-target=\"#popup-modal\" data-mode=\"gallery\" class=\"card-image no-icon\">";
	$card .= ($img_id) ? wp_get_attachment_image( $img_id, 'card', false, array()) : "";
	$card .= "</a>";
	if (!$hide){
		$card .= "<div class=\"card-body\">";
		$card .= "<div class=\"meta\">";
		$card .= "<div class=\"card-date\">{$date}</div>";
		$card .= "</div>";

		$card .= "<h4 class=\"card-title\"><a data-bs-toggle=\"modal\" data-bs-target=\"#popup-modal\" data-mode=\"gallery\">{$title}</a></h4>";
		$card .= "<div class=\"card-excerpt\">";
		$card .= get_the_excerpt($id);
		$card .= "</div>";//excerpt
		$card .= "</div>";//body
	}

	$card .= "</div>";//card

	return $card;
}
function get_news_marquee_card($id, $hide_cat = false){
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
	$card .= "<div class=\"card post\">";
	$card .= "<div class=\"row\">";


	$card .= "<a href=\"{$url}\" class=\"card-image col-12 col-lg-6\">";
	$card .= ($img_id) ? wp_get_attachment_image( $img_id, 'card', false, array()) : "";
	$card .= "</a>";

	$card .= "<div class=\"card-body col-12 col-lg-6\">";
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

	$card .= "<h3 class=\"card-title\"><a href=\"{$url}\">{$title}</a></h3>";
	$card .= "<div class=\"card-excerpt\">";
	$card .= get_the_excerpt($id);
	$card .= "</div>";//excerpt
	$card .= "<a href=\"{$url}\" class=\"btn btn-dark btn-lg px-5\">View</a>";
	$card .= "</div>";//body

	$card .= "</div>";//row
	$card .= "</div>";//card

	return $card;
}
function get_film_card($id, $classes = "", $index = false){
	//https://vp.eventival.com/bkkiff/2025/film/1168025

	$vp_id     = get_post_meta($id, "_id", true);
	$path      = "https://vp.eventival.com/bkkiff/2025/film/";

	$title     = get_the_title($id);

	$img_id    = get_post_thumbnail_id($id);
	$url       = ($vp_id) ? "{$path}/{$vp_id}" :get_permalink($id);
	$excerpt   = get_the_excerpt($id);

	$taxonomy  = "category";
	$terms     = wp_get_post_terms($id, $taxonomy, array());

	$data_index = ($index) ? " data-index=\"{$index}\"" : "";

	$card  = "";
	$card .= "<a href=\"{$url}\" class=\"card film{$classes}\" target=\"_blank\"{$data_index}>";


	$card .= "<div class=\"card-image\">";
	$card .= ($img_id) ? wp_get_attachment_image( $img_id, 'large', false, array()) : "";
	$card .= "</div>";

	$card .= "<div class=\"card-body\">";
	$card .= "<h4 class=\"card-title\">{$title}</h4>";
	$card .= "<div class=\"card-excerpt\">";
	$card .= get_the_excerpt($id);


	$card .= "</div>";//excerpt
	$card .= "<div class=\"btn btn-light\">View Info</div>";
	$card .= "</div>";//body


	$card .= "</a>";//card

	return $card;
}
