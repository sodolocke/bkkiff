<?php
function convertToThaiNumbers($input){
	$arabic_number = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
	$thai_number = array('๐', '๑', '๒', '๓', '๔', '๕', '๖', '๗', '๘', '๙');
	$input = strval($input);

	return str_replace($arabic_number, $thai_number, $input);
}
function convert_month_th($m, $abbr = false){
	$months_th = array("มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
	if ($abbr) $months_th = [ "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค.", ];

	foreach($months_th as $index => $month){
		if ($m == $index) return $month;
	}
	return false;
}
function n4d_paging_nav($echo = true, $attr = []) {
	global $wp_query;
	$big = 999999999; // need an unlikely integer
	$pages = paginate_links( array(
		'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
		'format' => '?paged=%#%',
		'current' => max( 1, get_query_var('paged') ),
		'total' => $wp_query->max_num_pages,
		'prev_next' => false,
		'type'  => 'array',
		'prev_next'   => TRUE,
	));

	if( is_array( $pages ) ) {
		$paged = ( get_query_var('paged') == 0 ) ? 1 : get_query_var('paged');
		$ul = '<ul id="pagination-if" class="pagination my-5">';

		foreach ( $pages as $index => $page ) {
			$active = (strpos($page, 'aria-current') !== false) ? " active" : "";
			$page = str_replace("page-numbers", "page-link", $page);

			if (strpos($page, 'next page-link') !== false) {
				$alink = $page;
				if (isset($attr["url"])){

					$a_html = explode("href=\"", $page);
					if (sizeof($a_html) == 2){
						$path = explode("page/", $a_html[1]);
						if (sizeof($path) == 2){
							$path[0]   = $attr["url"];
							$a_html[1] = implode("page/", $path);
						}
						$alink = implode("href=\"", $a_html);

					}
				}
				$ul .= "<li class=\"page-item{$active}\">{$alink}</li>";
			}
		}
		$ul .= '</ul>';

		if ($echo){
			echo $ul;
		} else {
			return $ul;
		}
	}
}
function n4d_pagniation($url, $paged, $posts_per_page, $total, $qstr = ""){
	if ($total > $posts_per_page){
		$steps = ceil($total/$posts_per_page);
		$li    = "";
		$diff = 5 - ($steps - $paged) ;
		if ($diff <= 2){
			$diff = 3;
		}

		$start = ($paged > ($steps - 5)) ?  $paged - $diff : $paged - 3;
		$diff = 6 - $paged;
		if ($diff <= 2){
			$diff = 3;
		}
		$end   = ($paged < 5 ) ? $paged + $diff : $paged + 3;

		if ($paged > 1){
			$li .= "<li class=\"page-item\"><a class=\"page-link prev\" href=\"{$url}".($paged - 1 )."{$qstr}\"> <i class=\"icon-arrow-left\"></i></a></li>";
		} else {
			$li .= "<li class=\"page-item\"><a class=\"page-link prev disabled\" href=\"{$url}".($paged - 1 )."{$qstr}\"> <i class=\"icon-arrow-left\"></i></a></li>";
		}
		for ($i = 1; $i <= $steps; $i++){
			if ($i > $start && $i < $end){
				$active = ($i == $paged || $paged == 0 && $i == 1) ? " active" : "";
				$li .= "<li class=\"page-item\"><a class=\"page-link{$active}\" href=\"{$url}{$i}{$qstr}\">{$i}</a></li>";
			}
		}
		if ($total > 1 && $posts_per_page <= ($total - 1)){
			if ($paged == 0) $paged = 1;
			$li .= "<li class=\"page-item\"><a class=\"page-link next\" href=\"{$url}".($paged + 1 )."{$qstr}\"> <i class=\"icon-arrow-right\"></i></a></li>";
		}

		return "<ul class=\"pagination pagination-sm\">{$li}</ul>";
	};
	return "";
}
function get_marquee($id = false, $filters = "", $nav = false, $title = false  ){
	$pagename   = ( is_page() ) ? get_query_var("pagename") : false;
	$id         = (!$id) ? get_the_ID() : $id;
	$img_id     = false;
	$hasMarquee = false;
	$html       = "";
	$bg_color   = get_post_meta($id, "_bg_color", true);
	$split      = get_post_meta($id, "_title_split", true);
	$overlap    = (get_post_type() == "project" && is_single()) ? true : get_post_meta($id, "_overlap_hide", true);
	$nomargin   = get_post_meta($id, "_margin_none", true);
	$bannerM_id = false;
	$style      = "";

	if ($bg_color){
		$style .= "background-color: {$bg_color};";
	}

	if (function_exists('kdmfi_has_featured_image')) {
		$bannerM_id = kdmfi_has_featured_image('banner-image-m', $id);
	}

	if (function_exists('kdmfi_has_featured_image')) {
		$banner_id = kdmfi_has_featured_image('banner-image', $id);
		$img_id    = ($banner_id) ? $banner_id : $img_id;
	}
	$title      = ($title) ? $title : get_the_title($id);

	if ($bannerM_id) {
		$bannerM = wp_get_attachment_image_src($bannerM_id, "full");


		$image  = "<picture class=\"banner-m-image\">";
		$image .= "<source media=\"(max-width:768px)\" srcset=\"{$bannerM[0]}\">";
		$image .= wp_get_attachment_image($img_id, "full", false, array());
		$image .= "</picture>";

	}
	else {
		$image      = ($img_id) ? wp_get_attachment_image($img_id, "full", false, array("class" => "banner-image")) : false;
	}


	$reverse  = ($filters !== "" || $nav) ? " reverse" : "";
	$m_class .= ($overlap) ? " no-overlap" : "";
	$m_class .= ($nomargin) ? " mb-0" : "";

	$filters = ($pagename == "our-work") ? render_filters() : "";

	if ($style !== ""){
		$style = " style=\"{$style}\"";
	}

	if ($image){
		$html .= "<div class=\"marquee{$reverse}{$m_class}\">";
		$html .= $image;
		$caption = wp_get_attachment_caption($img_id);
		$caption = ($caption !== "") ? $caption : false;
		$title_class  = "marquee-title";
		$title_class .= ($filters !== "" && $nav) ? " px-lg-5" : "";

		if ($img_id && $caption){
			$caption = "<blockquote class=\"large-quote\">".apply_filters("the_content", $caption)."</blockquote>";
			$title_class = "fade";
		}

		if ($split){
			$title_obj = explode(" ", $title);
			if (sizeof($title_obj) > 1){
				$title_obj[0] = "<small>{$title_obj[0]}</small>";
			}
			$title = implode(" ", $title_obj);
		}

//		$title = "<h1 class=\"{$title_class}\">{$title}</h1>";

		$html .= "<div class=\"entry\">";

//		if ($nav) $html .= $title;
		$control_class = ($nav) ? " hasNav" : "";
		if ($nav){
			$html .= "<nav class=\"mobile-slide\">{$nav}</nav>";
		}
		else {
		};
		$html .= "</div>";
		$html .= $filters;//"<div class=\"filters\">{$filters}</div>";


		$html .= "</div>";


		$GLOBALS["bodyTop"] = true;
		$hasMarquee         = true;
	}
	else {
		$html .= "<div class=\"marquee blank\"{$style}>";
		$html .= "<h2 class=\"page-title uc px-5\">{$title}</h2>";
		$html .= "</div>";
	}

	return $html;
}
function get_marquee_single($id = false, $parent_id = false, $subtitle = false){
	$id         = (!$id) ? get_the_ID() : $id;
	$parent_id  = ($parent_id) ? $parent_id : $id;
	$img_id     = false;
	$hasMarquee = false;
	$html       = "";

	if (function_exists('kdmfi_has_featured_image')) {
		$banner_id = kdmfi_has_featured_image('banner-image', $parent_id);
		$img_id    = ($banner_id) ? $banner_id : $img_id;
	}
	$image      = ($img_id) ? wp_get_attachment_image($img_id, "full", false, array("class" => "banner-image")) : false;

	if ($image){
		$html .= "<div class=\"marquee reverse\">";
		$html .= $image;
		$title_class = "marquee-title";
		$title = "<h1 class=\"{$title_class} center\">".get_the_title($id)."</h1>";

		$html .= "<div class=\"entry\">";
		$html .= "<div class=\"container\">";
		if ($subtitle) $html .= "<small class=\"subtitle\">{$subtitle}</small>";
		$html .= $title;

		$html .= "</div>";
		$html .= "</div>";

		$html .= "</div>";


		$GLOBALS["bodyTop"] = true;
		$hasMarquee         = true;
	}

	return $html;
}
function n4d_get_share($id){
	$encoded_url = urlencode(get_the_permalink($id));

	$html  = "";
	$html .= "<ul class=\"nav nav-share\">";
//Label
	$html .= "<li class=\"nav-item title\">".__("Share", "sls")."</li>";
//Facebook
	$html .= "<li class=\"nav-item\"><a class=\"nav-link\" href=\"https://www.facebook.com/sharer/sharer.php?u={$encoded_url}\"><i class=\"fa-brands fa-facebook-f\"></i></li>";
//Twitter
	$html .= "<li class=\"nav-item\"><a class=\"nav-link\" href=\"http://twitter.com/share?url={$encoded_url}\"><i class=\"fa-brands fa-square-x-twitter\"></i></a></li>";

//Email
	$subject = get_the_title($id).' at '.get_bloginfo();
	$content = 'Hi, %0D%0A%0D%0Acheck out this at '.get_bloginfo().' for '.get_the_title($id).'. Visit '.get_the_permalink($id);
	$url = 'mailto:?body='.$content.'&subject='.$subject;
	$html .= "<li class=\"nav-item\"><a class=\"nav-link\" href=\"{$url}\"><i class=\"fa-solid fa-envelope\"></i></a></li>";

	$html .= "</ul>";

	return $html;
}

function n4d_get_sidebar($slug, $project_id = false){
	$timeline    = get_query_var("timeline");
//	$project_id  = get_query_var("project");
	$first       = false;
	$content     = "";
	$content    .= "<div class=\"scroll-box\">";

	$projects = get_posts(array(
		"post_type"      => "project",
		"posts_per_page" => -1,
		"tax_query"      => array(
			array(
				"taxonomy"   => "projects",
				"field"      => "slug",
				"terms"      => $slug
			)
		),
		"fields"         => "ids",
		'suppress_filters' => false
	));

	$sort  = [];

	foreach($projects as $id){
		$years = wp_get_post_terms($id, "timeline", array( "fields" => "names"));
		$year  = ($years) ? current($years) : false;
		if ($year && $year !== "N/A"){
			if (!isset($sort[$year])) $sort[$year] = [];

			$sort[$year][get_the_title($id)] = $id;

			if (!$first) $first = $id;
		}
	}
	$li = "";

	krsort($sort);

	foreach($sort as $year => $ids){

		krsort($ids);
		$c = 0;

		foreach($ids as $title => $id){
			$url      = get_permalink($id);
			$active   = ($project_id == $id || $timeline == $year && $c == 0) ? " active" : "";
			$li      .= "<li class=\"nav-item\"><a href=\"{$url}\" class=\"nav-link{$active} d-flex justify-content-between align-items-start\">{$title} <span class=\"year\">{$year}</span></a></li>";
			$first   = ($project_id == $id || $timeline == $year && $c == 0) ? $id : $first;

			$c++;
		}
	}

	$content .= ($li !== "") ? "<ul class=\"nav flex-column\">{$li}</ul>" : "";

	$content .= "</div>";


	$btn  = "<a class=\"btn btn-primary hamburger hamburger--spin d-lg-none\" data-bs-toggle=\"offcanvas\" href=\"#offcanvas-timeline\" role=\"button\" aria-controls=\"offcanvas-timeline\" aria-expanded=\"false\">";
	$btn .= "<span class=\"hamburger-box\">";
	$btn .= "<span class=\"hamburger-inner\"></span>";
	$btn .= "</span>";
	$btn .= "ผลงาน";
	$btn .= "</a>";

	$offcanvas  = "";
	$offcanvas .= "<div class=\"offcanvas-lg offcanvas-start\" tabindex=\"-1\" id=\"offcanvas-timeline\" aria-labelledby=\"offcanvas-timelineLabel\">";


	$offcanvas .= "<div class=\"offcanvas-body\">{$content}";

	$offcanvas .= "</div>";
	$offcanvas .= "</div>";



	return array(
		"html"  => $btn.$offcanvas,
		"first" => $first
	);
}
function n4d_get_timeline($slug, $year = false){
	$sort      = [];
	$html      = "";
	$li        = "";
	$current   = ($year) ? $year : get_query_var("timeline");
	$current   = ($current) ? $current : false;
	$next      = false;
	$prev      = false;
	$url_base  = home_url("/projects/{$slug}/timeline");

	$check     = substr($url_base, -1);
	if ($check == "/"){
		$url_base = rtrim($url_base, "/");
	}
	$projects = get_posts(array(
		"post_type"      => "project",
		"posts_per_page" => -1,
		"tax_query"      => array(
			array(
				"taxonomy"   => "projects",
				"field"      => "slug",
				"terms"      => $slug
			)
		),
		"fields"           => "ids",
		'suppress_filters' => false
	));

	$precurrent    = false;
	$postcurrent   = false;
	$found_current = false;

	foreach($projects as $id){
		$years = wp_get_post_terms($id, "timeline", array( "fields" => "names"));
		$year  = ($years) ? current($years) : false;
		if ($year && $year !== "N/A"){
			if (!isset($sort[$year])) $sort[$year] = [];

			$sort[$year][get_the_title($id)] = $id;
		}
	}

	ksort($sort);


	$year_first =  array_key_first($sort);
	$year_last  =  array_key_last($sort);

	foreach($sort as $year => $data){
		$active   = ($year == $current) ? " active" : "";
		$li      .= "<li class=\"page-item{$active}\"><a class=\"page-link\" href=\"{$url_base}/{$year}/\">{$year}</a></li>";

		if (!$postcurrent && $found_current){
			$postcurrent = $year;
		}
		if (!$found_current && $current != $year){
			$precurrent = $year;


		}

		if (!$found_current && $current == $year){
			$found_current = true;
		}
	}

	$url_prev  = ($precurrent) ? "{$url_base}/{$precurrent}" : false;
	$url_next  = ($postcurrent) ? "{$url_base}/{$postcurrent}" : false;
	$url_first = "{$url_base}/{$year_first}";
	$url_last  = "{$url_base}/{$year_last}";



	$html .= "<nav class=\"nav timeline\" aria-label=\"project timeline\">";

	$html .= "<li class=\"nav-item\">";
	$html .= "<a href=\"$url_first\" class=\"nav-link\">".__("ปีแรกสุด", "chu")."</a>";
	$html .= "</li>";

	$html .= "<li class=\"nav-item fill\">";
	$html .= "<ul class=\"pagination\">";
	$html .= "<li class=\"page-item prev\">";
	$html .= ($url_prev) ? "<a class=\"page-link prev\" href=\"{$url_prev}\"><i class=\"icon arrow-left\"></i></a>" : "<a class=\"page-link prev disabled\" href=\"#\" disabled><i class=\"icon arrow-left\"></i></a>";
	$html .= "</li>";


	$html .= $li;
	$html .= "<li class=\"page-item next\">";
	$html .= ($url_next) ? "<a class=\"page-link next\" href=\"{$url_next}\"><i class=\"icon arrow-right\"></i></a>" : "<a class=\"page-link next disabled\" href=\"#\" disabled><i class=\"icon arrow-right\"></i></a>";
	$html .= "</li>";
	$html .= "</ul>";
	$html .= "</li>";

	$html .= "<li class=\"nav-item\">";
	$html .= "<a href=\"{$url_last}\" class=\"nav-link\">".__("ปีล่าสุด", "chu")."</a>";
	$html .= "</li>";

	$html .= "</nav>";



	return $html;
}
function n4d_get_project($id){
	$lang        = apply_filters( 'wpml_current_language', NULL );


	$content     = "";
	$exclude     = [];
//TITLE
	$title       = get_the_title($id);
//YEARS
	$years       = wp_get_post_terms($id, "timeline");
	$year        = ($years) ? current($years) : false;
	$year        = ($year) ? $year->name : false;
//VENUES
	$venues      = wp_get_post_terms($id, "venues");

	$content    .= "<div class=\"content\">";
	$content    .= ($title) ? "<h6 class=\"project-title\">{$title}</h6>" : "";
	$content    .= apply_filters("the_content", get_post_field("post_content", $id) );
	$content    .= "</div>";

	if ($lang !== "th"){
		$id = apply_filters( 'wpml_object_id', $id, "project", FALSE, "th"  );
	}

	//VENUES
	if ($venues){
		$content .= "<h6>".__("venue", "chm")."</h6>";

		$content .= "<table class=\"table data\">";
		$content .= "<tbody>";

		foreach($venues as $venue){
			$content .= "<tr>";
			$content .= "<td>{$venue->name}</td>";
			$content .= "<td class=\"text-right\"></td>";//{$year}
			$content .= "</tr>";
		}

		$content .= "</tbody>";
		$content .= "</table>";
	}

//GALLERIES
	$galleries   = get_post_meta($id, "_gallery_ids", false);
//	$galleries   = array_merge($galleries, get_post_meta($id, "_photo_1_ids", false));
	$galleries   = array_merge($galleries, get_post_meta($id, "_photo_2_ids", false));
	$galleries   = array_merge($galleries, get_post_meta($id, "_photo_3_ids", false));
	$galleries   = array_merge($galleries, get_post_meta($id, "_photo_4_ids", false));
/*
	$images = get_posts(array(
		"post_type" => "attachment",
		"post_parent" => $id,
		"posts_per_page" => -1,
		"fields"         => "ids",
		"order"          => "ASC"
	));



//update_post_meta($id, "_gallery_ids", $images);

print_r($galleries);

//	echo sizeof($images);

	print_r($galleries);
*/
	$content    .= "<div class=\"galleries\">";
	foreach($galleries as $gallery){
		$exclude     = array_merge($exclude, $gallery);

		$ids         = implode(",", $gallery);
		$content    .= apply_filters("the_content", "[n4d_carousel ids=\"{$ids}\" indicators=\"1\" indicatorsThumbnails=\"1\" ratio4x3=\"1\" cover=\"0\" modal=\"1\"]");
	}
	$content    .= "</div>";

	$videos     = get_post_meta($id, "_video_ids", true);

	if  ($videos){
		$exclude  = array_merge($exclude, $videos);

		$first    = current($videos);
		$src      = wp_get_attachment_url($first);
//		$content .= "<div class=\"video-js w-100\">";
		$content .= "<div class=\"ratio ratio-4x3\">";
		$content .= "<video id=\"video-player\" controls>";
		$content .= "<source src=\"{$src}\" type=\"video/mp4\">";
		$content .= "</video>";
		$content .= "</div>";//ratio
//		$content .= "</div>";//video.js

		$content .= "<div class=\"video-playlist\" data-target=\"#video-player\">";
		$content .= "<table class=\"table data\">";
		$content .= "<tbody>";

		foreach($videos as $vid){
			$src      = wp_get_attachment_url($vid);

			$content .= "<tr>";
			$content .= "<td><a class=\"playlist\" data-src=\"{$src}\">".get_the_title($vid)."</a></td>";
			$content .= "</tr>";
		}

		$content .= "</tbody>";
		$content .= "</table>";
		$content .= "</div>";

	}


//DOCUMENTS
	$attachments = get_posts(array(
		"post_type"      => "attachment",
		"post_parent"    => $id,
		"posts_per_page" => -1,
		"fields"         => "ids",
		"exclude"        => $exclude,
		'suppress_filters' => false
	));

	if ($attachments){
		$tr = "";

		foreach( $attachments as $attach_id ){
			$name = get_the_title($attach_id);
			$mime = get_post_mime_type($attach_id);
			$url  = wp_get_attachment_url($attach_id);
			$type = get_post_meta($attach_id, "_type", true);

			$file = wp_check_filetype($name);
			$ext  = strtoupper($file['ext']);

			$icon = "";

			switch($mime){
				case "application/pdf":
					$ext  = "PDF";
					$icon = "<i class=\"icon download\"></i>";
				break;
				case "image/jpeg":
					$ext  = "JPG";
					$icon = "<i class=\"icon view\"></i>";
				break;
				case "application/msword":
				case "application/vnd.openxmlformats-officedocument.wordprocessingml.document":
					$ext  = "DOC";
					$icon = "<i class=\"icon download\"></i>";
				break;
			}

			if ($icon){
				$path_parts = pathinfo( $url );
				$tr .= "<tr>";
				$tr .= "<td class=\"small\">{$ext}</td>";
				$tr .= "<td class=\"action\">{$icon}</td>";
				$tr .= "<td><a href=\"{$url}\" target=\"_blank\">{$name}</a></td>";// {$mime} {$attach_id}
				$tr .= "<td class=\"text-right\"></td>";//{$year}
				$tr .= "</tr>";
			} else {
				file_put_contents(ABSPATH.'wp-content/delete-video-'.$attach_id.'.txt', print_r($name, true));
			}

		}
		if ($tr){
			$content    .= "<h6>".__("Others", "chm")."</h6>";
			$content    .= "<div class=\"table-responsive\">";
			$content    .= "<table class=\"table data\">";
			$content    .= "<tbody>{$tr}</tbody>";
			$content    .= "</table>";
			$content    .= "</div>";
		}
	}


	return $content;
}

function n4d_get_attachment_id_from_url( $url ){
	if ( empty( $url ) ) {
		return 0;
	}

	$id         = 0;
	$upload_dir = wp_upload_dir( null, false );
	$base_url   = $upload_dir['baseurl'] . '/';

	// Check first if attachment is inside the WordPress uploads directory, or we're given a filename only.
	if ( false !== strpos( $url, $base_url ) || false === strpos( $url, '://' ) ) {
		// Search for yyyy/mm/slug.extension or slug.extension - remove the base URL.
		$file = str_replace( $base_url, '', $url );
		$args = array(
			'post_type'   => 'attachment',
			'post_status' => 'any',
			'fields'      => 'ids',
			'meta_query'  => array(
				'relation' => 'OR',
				array(
					'key'     => '_wp_attached_file',
					'value'   => '^' . $file,
					'compare' => 'REGEXP',
				),
				array(
					'key'     => '_wp_attached_file',
					'value'   => '/' . $file,
					'compare' => 'LIKE',
				),
				array(
					'key'     => '_wc_attachment_source',
					'value'   => '/' . $file,
					'compare' => 'LIKE',
				),
			),
		);
	}
	else {
		// This is an external URL, so compare to source.
		$args = array(
			'post_type'   => 'attachment',
			'post_status' => 'any',
			'fields'      => 'ids',
			'meta_query'  => array(
				array(
					'value' => $url,
					'key'   => '_wc_attachment_source',
				),
			),
		);
	}

	if ( $ids = get_posts( $args ) ) {
		$id = current( $ids );
	}
	return $id;
}
function n4d_process_attachment( $post, $url ) {
	if ( ! function_exists( 'wp_crop_image' ) ) {
	  include( ABSPATH . 'wp-admin/includes/image.php' );
	}
	$upload = n4d_fetch_remote_file( $url, $post );

	if ( is_wp_error( $upload ) )
		return $upload;
	if ( $info = wp_check_filetype( $upload['file'] ) )
		$post['post_mime_type'] = $info['type'];
	else
		return new WP_Error( 'attachment_processing_error', __('Invalid file type', 'bacc') );

	$post['guid'] = $upload['url'];

	// as per wp-admin/includes/upload.php
	$post_id = wp_insert_attachment( $post, $upload['file'] );
	wp_update_attachment_metadata( $post_id, wp_generate_attachment_metadata( $post_id, $upload['file'] ) );

	// remap resized image URLs, works by stripping the extension and remapping the URL stub.
	if ( preg_match( '!^image/!', $info['type'] ) ) {
		$parts = pathinfo( $url );
		$name = basename( $parts['basename'], ".{$parts['extension']}" ); // PATHINFO_FILENAME in PHP 5.2

		$parts_new = pathinfo( $upload['url'] );
		$name_new = basename( $parts_new['basename'], ".{$parts_new['extension']}" );
	}

	return $post_id;
}
function n4d_fetch_remote_file( $url, $post ) {
	// extract the file name and extension from the url
	$file_name = basename( $url );
	// get placeholder file in the upload dir with a unique, sanitized filename
	$upload = wp_upload_bits( $file_name, 0, '', $post['upload_date'] );
	if ( $upload['error'] )
		return new WP_Error( 'upload_dir_error', $upload['error'] );

	$sslverify = ( str_contains($url, ":8890") ) ? false : true;

	$request = new WP_Http();
	$result = $request->request($url, array(
		'sslverify' => $sslverify
	));


	// request failed
	if ( is_wp_error($result) ) {
		//@unlink( $upload['file'] );
		return new WP_Error( 'import_file_error', __('Remote server did not respond', 'bacc') );
	}



// make sure the fetch was successful
	if ( 200 !== $result['response']['code'] ) {
		//@unlink( $upload['file'] );
//		return new WP_Error( 'import_file_error', sprintf( __('Remote server returned error response %1$d %2$s', 'bacc'), esc_html($headers['response']), get_status_header_desc($headers['response']) ) );
	}

	if ( false === file_put_contents($upload['file'],$result['body']) ) {
		//@unlink( $upload['file'] );
		return new WP_Error( 'import_file_error', __('Could not save image contents', 'bacc') );
	}

	$filesize = strlen( $result['body'] );
/*
	if ( isset( $result['headers']['content-length'] ) && $filesize != $result['headers']['content-length'] ) {
		//@unlink( $upload['file'] );
		return new WP_Error( 'import_file_error', __('Remote file is incorrect size', 'bacc') );
	}
*/

	if ( 0 == $filesize ) {
		//@unlink( $upload['file'] );
		return new WP_Error( 'import_file_error', __('Zero size file downloaded', 'bacc') );
	}

	$max_size = (int) apply_filters( 'import_attachment_size_limit', 0 );
	if ( ! empty( $max_size ) && $filesize > $max_size ) {
		//@unlink( $upload['file'] );
		return new WP_Error( 'import_file_error', sprintf(__('Remote file is too large, limit is %s', 'bacc'), size_format($max_size) ) );
	}

	return $upload;
}
function n4d_attach_image($image, $id){ //$images is an array of image urls, $id is the ID of the post I want the images to be attached to
	require_once(ABSPATH . '/wp-admin/includes/file.php');
	require_once(ABSPATH . '/wp-admin/includes/media.php');
	require_once(ABSPATH . '/wp-admin/includes/image.php');

//	foreach($images as $image){

		$array = array( //array to mimic $_FILES
			'name' => basename($image), //isolates and outputs the file name from its absolute path
			'type' => wp_check_filetype($image), // get mime type of image file
			'tmp_name' => $image, //this field passes the actual path to the image
			'error' => 0, //normally, this is used to store an error, should the upload fail. but since this isnt actually an instance of $_FILES we can default it to zero here
			'size' => filesize($image) //returns image filesize in bytes
		);


		return media_handle_sideload($array, $id);
//	}
}
function n4d_get_memory_limit() {
	if ( function_exists( 'ini_get' ) ) {
		$memory_limit = ini_get( 'memory_limit' );
	} else {
		// Sensible default.
		$memory_limit = '128M';
	}

	if ( ! $memory_limit || -1 === intval( $memory_limit ) ) {
		// Unlimited, set to 32GB.
		$memory_limit = '32000M';
	}
	return intval( $memory_limit ) * 1024 * 1024;
}
function n4d_memory_exceeded() {
	$memory_limit   = n4d_get_memory_limit() * 0.8; // 90% of max memory
	$current_memory = memory_get_usage( true );
	$return         = false;
	if ( $current_memory >= $memory_limit ) {
		$return = true;
	}
	return $return;
}
function n4d_time_exceeded($start_time) {
	$finish = $start_time + apply_filters( 'woocommerce_product_importer_default_time_limit', 20 ); // 20 seconds
	$return = false;
	if ( time() >= $finish ) {
		$return = true;
	}
	return $return;
}