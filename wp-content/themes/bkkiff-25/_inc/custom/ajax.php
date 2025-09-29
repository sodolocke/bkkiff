<?php
add_action( 'wp_ajax_n4d_import_ajax', 'n4d_import_ajax' );

function n4d_import_ajax() {
	$args      = array();
	$added  = $_POST['added'];
	$offset = $_POST['offset'];

	$results = n4d_import_films($added, $offset);

	echo wp_json_encode( $results );
	echo "\n";
	wp_die();
}

function n4d_import_films($added, $offset){
	global $sitepress;

	$labels        = [];
	$debug         = [];
	$processed     = ($added) ? $added : -1;
	$row           = -1;
	$limit         = 300;
	$total         = -1;
	$now           = strtotime("now");
	$start_time    = time();

	$films  = get_option("n4d_films");
	$update = true;
	$n      = 0;

	$skip = [];//array(1168073);//1161381,

	foreach($films as $row => $film){
		$film_id = false;

		if ($row > $processed && $row < $limit && !in_array($film['id'], $skip) ) {
			$processed++;


			$find = get_posts(array(
				"post_type"      => "film",
				"post_status"    => array("publish","pending"),
				"posts_per_page" => 1,
				"fields"         => "ids",
				"meta_query"     => array(
					array(
						"key" => "_id",
						"value" => $film['id']
					)
				)
			));

			$selected = (in_array("VP", $film['tags'])) ? true : false;


			if (!$find){

				$args = array(
					'post_type'    => 'film',
					'post_title'   => $film['title_english'],
					'post_content' => (isset($film['synopsis_long'])) ? $film['synopsis_long']  : "",
					'post_excerpt' => (isset($film['synopsis_short'])) ? $film['synopsis_short']  : "",
					'post_status'  => ($selected) ? 'publish' : 'pending',
					'post_author'  => 0,
				);

				$film_id = wp_insert_post($args);
				if ($film_id){
					update_post_meta($film_id, "_id", $film['id']);
					update_post_meta($film_id, "_data", $film);

					$update = true;
				}

			}
			else  {
				$film_id = current($find);
			}

			if ($update && $film_id && $selected){
				$update_args = array(
					'ID'           => $film_id,
					'post_title'   => $film['title_english'],
					'post_content' => (isset($film['synopsis_long'])) ? $film['synopsis_long']  : "",
					'post_excerpt' => (isset($film['synopsis_short'])) ? $film['synopsis_short']  : "",
					'post_status'  => ($selected) ? 'publish' : 'pending',
				);

//				wp_update_post( $update_args );
/**/
				$url    = (isset($film['poster']['normal'])) ? $film['poster']['normal'] : false;
				$img_id = n4d_get_attachment_id_from_url( $url );

				if (!$img_id && $url){
					$postdata = array(
						'post_title'  => $url,
						'post_status' => "publish",
						'post_parent' => $film_id,
						'upload_date' => date("Y-m-d H:i:s")
					);


					$img_id = n4d_process_attachment( $postdata, $url );
					if ($img_id && !is_wp_error($img_id)){
						update_post_meta($img_id, "_wc_attachment_source", $url);
						update_post_meta($film_id, "_thumbnail_id", $img_id);
					}
				}
				else {
					if ($url) update_post_meta($film_id, "_thumbnail_id", $img_id);
				}
/*
				if ($film["sections"]){
					foreach($film["sections"] as $section){
						$term = (isset($types_indexes[$section['id']])) ?  $types_indexes[$section['id']] : false;

						if ( ! $term ) {
							$term = wp_insert_term( $section['name'], 'films', array( 'slug' => $section['id'] ) );

							if (!is_wp_error($term)){
								$term = $term['term_id'];
							}
						}

						if($term && !is_wp_error($term)) wp_set_post_terms( $film_id, $term, "films" );
					}
				}
*/
				if ( n4d_time_exceeded($start_time) || n4d_memory_exceeded() )  {
					return array(
						"status"    => false,
						"processed" => $row,
						"added"     => $processed,
						"total"     => $total,
						"offset"    => $offset,
						"pid"       => $pid
					);
				}
			}
		}
	}





/**/
	return array(
		"status"    => true,//($processed == ($total)) ? true : false,
		"processed" => $row,
		"added"     => $processed,
		"total"     => $total,
		"offset"    => $offset,
		"pid"       => (isset($pid)) ? $pid : false
	);
}