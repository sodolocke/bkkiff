<?php
add_action( 'wp_ajax_n4d_import_ajax', 'n4d_import_ajax' );

function n4d_import_ajax() {
	$args      = array();
	$added  = $_POST['added'];
	$offset = $_POST['offset'];

	$results = n4d_import_projects($added, $offset);

	echo wp_json_encode( $results );
	echo "\n";
	wp_die();
}

function n4d_import_projects($added, $offset){
	global $sitepress;

	$labels        = [];
	$debug         = [];
	$processed     = ($added < 162) ? 162 : $added;//14
	$row           = -1;
	$limit         = 200;//15
	$total         = -1;
	$now           = strtotime("now");
	$start_time    = time();

	$theme         = "performance";//,artwork,tap-root-society,wethi-samai,concrete-house,asiatopia,performance,participation,writings,residency,references
	$img_path      = home_url("/wp-content/uploads/import/images/");
	$file          = ABSPATH."/wp-content/uploads/import/{$theme}.csv";

	$lang          = apply_filters( 'wpml_current_language', NULL );

	if ($lang == "th"){
		if (($handle = fopen($file, "r")) !== FALSE) {

			$fileContent = file_get_contents($file);

			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$total++;
			}
			fclose($handle);
		}
		if (($handle = fopen($file, "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$num = count($data);
				$row++;
//LABELS
				if ($row == 0){
					for ($c = 0; $c < $num; $c++) {
						array_push($labels, str_replace(" ", "_", strtolower($data[$c])));
					}
				}
				else if ($row > $processed && $row < $limit) {
					$processed++;

					$uid = array_search('id',  $labels);
					$uid = $data[$uid];

					array_push($debug, $uid);


					if ($uid && $uid !== ""){
						$exists = get_posts(array(
							"post_type"      => array("project"),
							"posts_per_page" => 1,
							"fields"         => "ids",
							'name'           => $uid,
						));

						$pid = ( $exists ) ? current($exists) : false;
/*
						wp_insert_post(array(
							'post_title'    => $uid,
							'post_type'     => 'project',
							'post_name'     => $uid,
							'post_status'   => 'publish',
						));
*/
						if ($pid){
/*
							$en_id = apply_filters( 'wpml_object_id', $pid, "project", FALSE, "en"  );
							if (!$en_id){
								$trid = $sitepress->get_element_trid($pid);
								$sitepress->set_element_language_details($pid, 'post_project', $trid, 'th');

								$en_id = wp_insert_post(array(
									'post_title'    => $uid,
									'post_name'     => "{$uid}-en",
									'post_type'     => 'project',
									'post_status'   => 'publish',
								));

								$sitepress->set_element_language_details($en_id, 'post_project', $trid, 'en');
							}
*/

/*
							$category = get_term_by('slug', $theme, 'projects');
							if ($category) {
								wp_set_post_terms($pid, array($category->term_id), "projects");
								if ($en_id){
									wp_set_post_terms($en_id, array($category->term_id), "projects");
								}
							}
*/
							$args    = [];
							$args_en = [];

							foreach($labels as $key => $label){
								if (isset($data[$key]) && $data[$key] !== ""){
//echo 'timeline';
									switch($label){
/*
										case "province/_country_th":
											$data[$key] = str_replace(" ", "", $data[$key]);
											$terms      = explode(",", $data[$key]);
											$term_ids   = [];

											foreach($terms as $term){
												$term_id = false;

												$location = term_exists( $term, 'location' );
												$term_id = (isset($location["term_id"])) ? $location["term_id"] : $term_id;
												if ($term_id) array_push($term_ids, $term_id);

											}
											if ($term_ids){
												wp_set_post_terms($pid, $term_ids, "location");
											}
										break;
										case "year":
											$data[$key] = str_replace(" ", "", $data[$key]);
											$years      = explode(",", $data[$key]);
											$term_ids   = [];

											foreach($years as $year){
												$term_id = false;
												if (strlen($year) == 4){
													$timeline = term_exists( $year, 'timeline' );
													if (!$timeline){
														$timeline = wp_insert_term(
															$year,   // the term
															'timeline', // the taxonomy
															array()
														);
													}
													$term_id = (isset($timeline["term_id"])) ? $timeline["term_id"] : $term_id;
													if ($term_id) array_push($term_ids, $term_id);
												}
											}

											if ($term_ids){
												wp_set_post_terms($pid, $term_ids, "timeline");

												if ($en_id){

													wp_set_post_terms($en_id, $term_ids, "timeline");
												}
											}
										break;
										case "exhibition_type":
											$term_id = false;
											$terms = term_exists( $data[$key], 'projects' );
											$term_id = (!is_wp_error($terms) && isset($terms["term_id"])) ? $terms["term_id"] : $term_id;
											if ($term_id){
												wp_set_post_terms($pid, array($term_id), "projects");
											}
											update_post_meta($pid, "_exhibition_type", $data[$key]);
										break;
										case "artwork_name_th":
										case "activity_name_th":
										case "activity_name_th_":
										case "workshop_name_th":
										case "project_name_th":
										case "writing_name_th":
										case "residency_name_th":
										case "reference_name_th":
											$args['post_title'] = $data[$key];
										break;
										case "artwork_name_en":
										case "activity_name_en":
										case "activity_name_en_":
										case "workshop_name_en":
										case "project_name_en":
										case "writing_name_en":
										case "residency_name_en":
										case "reference_name_en":
											$args_en['post_title'] = $data[$key];
										break;
										case "description_th":
											$text = explode("\n", $data[$key]);
											$text = array_map( function($value) {
												return "<p>{$value}</p>";
											} , $text);
											$args['post_content'] = implode("", $text);
										break;
										case "description_en":
											$text = explode("\n", $data[$key]);
											$text = array_map( function($value) {
												return "<p>{$value}</p>";
											} , $text);
											$args_en['post_content'] = implode("", $text);
										break;
										case "location_city_th_[t]":
											update_post_meta($pid, "_city", $data[$key]);
										break;
										case "country_th_[t]":
											update_post_meta($pid, "_country", $data[$key]);
										break;
										case "venue_th":
											$term_id = false;
											$terms = term_exists( $data[$key], 'venues' );
											if (!$terms){
												$terms = wp_insert_term(
													$data[$key],   // the term
													'venues', // the taxonomy
													array()
												);
											}
											$term_id = (!is_wp_error($terms) && isset($terms["term_id"])) ? $terms["term_id"] : $term_id;

											if ($term_id){
												wp_set_post_terms($pid, array($term_id), "venues");
											}
										break;
										case "venue_en":
											$venue = wp_get_post_terms($pid, array($term_id), "venues");
											if ($venue && !is_wp_error($venue)){
												$venue =  current($venue);
												update_term_meta($venue->term_id, "_name_en", $data[$key]);
											}
										break;
										case "catalogue":
										case "others":
											update_post_meta($pid, "_{$label}", $data[$key]);
										break;
*/
										case "image":
										case "photo":
										case "photo_1":
										case "photo_2":
										case "photo_3":
										case "photo_4":
										case "press_clipping":
										case "personal_document":
										case "personal_document_1":
										case "personal_document_2":
										case "personal_document_3":
										case "personal_document_4":
										case "personal_document_5":
										case "document_1":
										case "document_2":
										case "writing":
										case "info":
//										case "video_recording":
											$imgs = explode(",", $data[$key]);
											$gallery = array();
											$gallery_labels = array("photo","photo_1","photo_2","photo_3","photo_4");


											$ignore = [];//get_post_meta($pid, "_ignore", true);
											$skip   = ($ignore && sizeof($ignore) > 0) ? true : false;
											$ignore = [];//($ignore) ? $ignore : [];
											$errors = [];

											$ignore_urls = get_post_meta($pid, "_ignore_urls", true);
											$ignore_urls = ($ignore_urls) ? $ignore_urls : [];

//echo "{$label}: ".$pid;
											if (!$skip){
file_put_contents(ABSPATH.'wp-content/skip-'.$pid.'.txt', print_r($imgs, true));
												foreach($imgs as $k => $img){
													if ( !in_array($k, $ignore) ){
														$img        = trim($img);
//echo "url: {$img}<br />";
														$url        = $img_path.$img;
														$url        =  str_replace("|", "_", $url);
														$path_parts = pathinfo( $url );
														$name       = urldecode($path_parts["filename"]);
														$upload_id  = n4d_get_attachment_id_from_url($url);
file_put_contents(ABSPATH.'wp-content/last.txt', print_r($url, true));
														if ( !in_array($url, $ignore_urls) ){
															if (!$upload_id){
																$postdata = array(
																	'post_title' => $name,
																	'post_status' => "publish",
																	'post_parent' => $pid,
																);
																$postdata['upload_date'] = date("Y-m-d H:i:s");
																$upload_id = n4d_process_attachment($postdata, "$url");



																if ($upload_id && !is_wp_error( $upload_id )){
																	update_post_meta($upload_id,"_wc_attachment_source", $url);
																}
															}
														}


														if ($upload_id && !is_wp_error( $upload_id )){
															array_push($gallery, $upload_id);


															if ($k == 0 && $label == "photo"){
																update_post_meta($pid, "_thumbnail_id", $upload_id);
															}

															update_post_meta($upload_id, "_type", $label);

															$my_post = array(
																'ID'           => $upload_id,
																'post_title'   => $name,
															);
															wp_update_post( $my_post );

														}
														else if (is_wp_error( $upload_id )){
															array_push($errors, $k);
															array_push($ignore_urls, $url);
file_put_contents(ABSPATH.'wp-content/err-'.$label.'-'.$pid.'-'.$k.'.txt', print_r($url, true));
														}

														array_unique($gallery);


														if (sizeof($gallery) > 0 && in_array($label, $gallery_labels)){
															if ( $label == "photo" || $label == "photo_1" ){
																update_post_meta($pid, "_gallery_ids", $gallery);
															}
															else {
																update_post_meta($pid, "_{$label}_ids", $gallery);
															}
														}

														if ( $label == "video_recording" ){
															update_post_meta($pid, "_video_ids", $gallery);
														}

														if ($label == "personal_document_1"){
															update_post_meta($pid, "_personal1_ids", $gallery);
														}
														if ($label == "personal_document_2"){
															update_post_meta($pid, "_personal2_ids", $gallery);
														}
														if ($label == "personal_document_3"){
															update_post_meta($pid, "_personal3_ids", $gallery);
														}
														if ($label == "personal_document_4"){
															update_post_meta($pid, "_personal4_ids", $gallery);
														}
														if ($label == "personal_document_5"){
															update_post_meta($pid, "_personal5_ids", $gallery);
														}
														if ($label == "document_1"){
															update_post_meta($pid, "_document_1_ids", $gallery);
														}
														if ($label == "document_2"){
															update_post_meta($pid, "_document_2_ids", $gallery);
														}

													}

													if ( n4d_time_exceeded($start_time) || n4d_memory_exceeded() )  {
														return array(
															"status"    => false,
															"processed" => $row,
															"added"     => $processed,
															"total"     => $total,
															"offset"    => $offset,
															"pid"       => $pid,
															"k"         => $k
														);
													}
												}

												if ($errors && sizeof($errors) > 0) {
													update_post_meta($pid, "_ignore", $errors);
												} else {
													delete_post_meta($pid, "_ignore");
												}
												if ($ignore_urls && sizeof($ignore_urls) > 0) {
													update_post_meta($pid, "_ignore_urls", $ignore_urls);
file_put_contents(ABSPATH.'wp-content/ignore-'.$pid.'.txt', print_r($ignore_urls, true));
												} else {
													delete_post_meta($pid, "_ignore_urls");
												}
											}
										break;

									}
								}
							}
/*
							if ($args){
								$args['ID'] = $pid;
								wp_update_post( $args);
							}
							if ($args_en && $en_id){
								$args_en['ID'] = $en_id;
								wp_update_post( $args_en);
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
			}
			fclose($handle);
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



