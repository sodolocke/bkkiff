<?php
add_action( 'rest_api_init', 'n4d_rest');

function n4d_get_jobs_rest($args = []){
	$id   = (isset($args['id'])) ? $args['id'] : false;
	$lang = (isset($args['lang'])) ? $args['lang'] : false;
	$html = "";

	$jobs = get_posts(array(
		"post_type"        => "job",
		"posts_per_page"   => -1,
		"fields"           => "ids",
		"suppress_filters" => false
	));

	$html .= "<option value=\"\" disabled>Select Position</option>";
	$en_id = false;
	$th_id = false;

	foreach($jobs as $job){
		$job_id = apply_filters( 'wpml_object_id', $job, 'job', FALSE, $lang );


		$en_id = apply_filters( 'wpml_object_id', $job, 'job', FALSE, "en" );
		$th_id = apply_filters( 'wpml_object_id', $job, 'job', FALSE, "th" );


		$name  = get_the_title($job_id);
		$selected = ($id == $job_id) ? " selected=\"selected\"" : "";
		$email    = get_post_meta($job_id, "_email", true);
		$html .= "<option value=\"{$name}\" data-email=\"{$email}\" data-en=\"{$en_id}\" data-th=\"{$th_id}\"{$selected}>{$name}</option>";

		if ($id == $job_id){
			$selected_en_id = apply_filters( 'wpml_object_id', $job, 'job', FALSE, "en" );
			$selected_th_id = apply_filters( 'wpml_object_id', $job, 'job', FALSE, "th" );
		}
	}


	$returner = array(
		"html"   => $html,
		"id"     => $id,
		"lang"   => $lang,
		"en_id"  => $selected_en_id,
		"th_id"  => $selected_th_id
	);
	return rest_ensure_response($returner);
}
function user_permission_callback($request){
	return true;
}
function user_private_permission_callback($request){
	return ( is_user_logged_in() ) ? true : false;
}
function user_public_permission_callback($request){
	return true;
}

function n4d_rest() {
	register_rest_route( 'n4d/v1', '/jobs/(?P<id>[a-zA-Z0-9-_]+)/(?P<lang>[a-zA-Z0-9-_]+)', array(
		'methods'             => 'GET',
		'callback'            => 'n4d_get_jobs_rest',
		'permission_callback' => 'user_permission_callback',
		'args'                => array(
			'id' => array(
				'validate_callback' => function($param, $request, $key) {
					return true;
				}
			),
		),
	));

}