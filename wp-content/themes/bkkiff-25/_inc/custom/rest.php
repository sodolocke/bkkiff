<?php
add_action( 'rest_api_init', 'n4d_rest');

function n4d_get_gallery_rest($args = []){
	$id   = (isset($args['id'])) ? $args['id'] : false;
	$lang = (isset($args['lang'])) ? $args['lang'] : false;
	$html = "";

	$gallery = get_post_meta($id, "_gallery_ids", true);

	$html    .= "<div class=\"galleries\">";
		$ids         = implode(",", $gallery);
		$html       .= apply_filters("the_content", "[n4d_carousel ids=\"{$ids}\" indicators=\"1\" indicatorsThumbnails=\"1\" ratio4x3=\"1\" cover=\"0\" modal=\"0\"]");
	$html    .= "</div>";

	$returner = array(
		"html"   => $html,
		"id"     => $id,
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
	register_rest_route( 'n4d/v1', '/gallery/(?P<id>[a-zA-Z0-9-_]+)', array(
		'methods'             => 'GET',
		'callback'            => 'n4d_get_gallery_rest',
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




function n4d_get_films($reset = false){
	$now          = strtotime("now");
	$expire       = get_option("n4d_films_expire");
	$remote_url   = "https://bo.eventival.com/bkkiff/2025/api/films";


	if ($reset == true){
		$response   = wp_remote_post( $remote_url, array(
			'method'      => 'GET',
			'timeout'     => 45,
			'headers'     => array(
				"x-api-key" => '7HmS5tyc7HDF4uLHYGi3vctggRTv7s',
				'Cache-Control' => 'no-cache',
				'Connection'    => 'keep-alive'
			),
//			'body'        => wp_json_encode(array()),
		));

		if ($response && !is_wp_error($response)){
			$body    = (isset($response['body'])) ? json_decode( $response['body'], true ) : false;

			update_option("n4d_films", $body);
			update_option("n4d_films_expire", strtotime('now'));
		}


	} else {
		return false;
	}
/**/
}