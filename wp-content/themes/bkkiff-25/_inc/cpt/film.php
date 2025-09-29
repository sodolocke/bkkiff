<?php
//ADD POST TYPE
add_action('init', 'post_type_film');
add_action( 'save_post', 'n4d_save_film_meta_box_data' );
add_action('init', 'taxonomy_films');

function post_type_film() {
//REGISTER POST TYPE
	register_post_type('film', array(
        'labels'             => array(
			'name' => _x('Films', 'post type general name', 'bkkiff'),
			'singular_name' => _x('Film', 'post type singular name', 'bkkiff'),
			'add_new' => _x('Add New', 'Film', 'bkkiff'),
			'add_new_item' => __('Add New Film', 'bkkiff'),
			'edit_item' => __('Edit Film', 'bkkiff'),
			'new_item' => __('New Film', 'bkkiff'),
			'view_item' => __('View Film', 'bkkiff'),
			'search_items' => __('Search Film', 'bkkiff'),
			'not_found' =>  __('No Film found', 'bkkiff'),
			'not_found_in_trash' => __('No Film found in Trash', 'bkkiff'),
			'parent_item_colon' => ''
		),
        'public'             => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'capability_type'    => 'page',
		'hierarchical'       => false,
		'menu_icon'          => "data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCA2NDAgNjQwIj48cGF0aCBkPSJNOTYgMTYwQzk2IDEyNC43IDEyNC43IDk2IDE2MCA5Nkw0ODAgOTZDNTE1LjMgOTYgNTQ0IDEyNC43IDU0NCAxNjBMNTQ0IDQ4MEM1NDQgNTE1LjMgNTE1LjMgNTQ0IDQ4MCA1NDRMMTYwIDU0NEMxMjQuNyA1NDQgOTYgNTE1LjMgOTYgNDgwTDk2IDE2MHpNMTQ0IDQzMkwxNDQgNDY0QzE0NCA0NzIuOCAxNTEuMiA0ODAgMTYwIDQ4MEwxOTIgNDgwQzIwMC44IDQ4MCAyMDggNDcyLjggMjA4IDQ2NEwyMDggNDMyQzIwOCA0MjMuMiAyMDAuOCA0MTYgMTkyIDQxNkwxNjAgNDE2QzE1MS4yIDQxNiAxNDQgNDIzLjIgMTQ0IDQzMnpNNDQ4IDQxNkM0MzkuMiA0MTYgNDMyIDQyMy4yIDQzMiA0MzJMNDMyIDQ2NEM0MzIgNDcyLjggNDM5LjIgNDgwIDQ0OCA0ODBMNDgwIDQ4MEM0ODguOCA0ODAgNDk2IDQ3Mi44IDQ5NiA0NjRMNDk2IDQzMkM0OTYgNDIzLjIgNDg4LjggNDE2IDQ4MCA0MTZMNDQ4IDQxNnpNMTQ0IDMwNEwxNDQgMzM2QzE0NCAzNDQuOCAxNTEuMiAzNTIgMTYwIDM1MkwxOTIgMzUyQzIwMC44IDM1MiAyMDggMzQ0LjggMjA4IDMzNkwyMDggMzA0QzIwOCAyOTUuMiAyMDAuOCAyODggMTkyIDI4OEwxNjAgMjg4QzE1MS4yIDI4OCAxNDQgMjk1LjIgMTQ0IDMwNHpNNDQ4IDI4OEM0MzkuMiAyODggNDMyIDI5NS4yIDQzMiAzMDRMNDMyIDMzNkM0MzIgMzQ0LjggNDM5LjIgMzUyIDQ0OCAzNTJMNDgwIDM1MkM0ODguOCAzNTIgNDk2IDM0NC44IDQ5NiAzMzZMNDk2IDMwNEM0OTYgMjk1LjIgNDg4LjggMjg4IDQ4MCAyODhMNDQ4IDI4OHpNMTQ0IDE3NkwxNDQgMjA4QzE0NCAyMTYuOCAxNTEuMiAyMjQgMTYwIDIyNEwxOTIgMjI0QzIwMC44IDIyNCAyMDggMjE2LjggMjA4IDIwOEwyMDggMTc2QzIwOCAxNjcuMiAyMDAuOCAxNjAgMTkyIDE2MEwxNjAgMTYwQzE1MS4yIDE2MCAxNDQgMTY3LjIgMTQ0IDE3NnpNNDQ4IDE2MEM0MzkuMiAxNjAgNDMyIDE2Ny4yIDQzMiAxNzZMNDMyIDIwOEM0MzIgMjE2LjggNDM5LjIgMjI0IDQ0OCAyMjRMNDgwIDIyNEM0ODguOCAyMjQgNDk2IDIxNi44IDQ5NiAyMDhMNDk2IDE3NkM0OTYgMTY3LjIgNDg4LjggMTYwIDQ4MCAxNjBMNDQ4IDE2MHoiIGZpbGw9IiNhN2FhYWQiLz48L3N2Zz4=",
		'rewrite'            => array("slug" => "film"), // Permalinks format
        'supports'           => array('title', 'thumbnail', 'revisions', "editor", "excerpt"),
		'has_archive'        => "films",
		'show_in_rest'       => true,
	));

}
function n4d_add_film_meta_box() {

	add_meta_box(
		'n4d_film_sectionid',
		__( 'Settings', 'bkkiff' ),
		'n4d_film_meta_box_callback',
		'film',
		'side'
	);
}
function n4d_film_meta_box_callback($post){
	// Add an nonce field so we can check for it later.
	wp_nonce_field( 'n4d_film_meta_box', 'n4d_film_meta_box_nonce' );

	$fields = array(
		"location"      => "Location",
		"owner"         => "Owner",
		"capacity"      => "Capacity",
		"period"        => "Period",
		"certification" => "Certification"
	);


	$html  = "";
	foreach($fields as $key => $name){
		$value = get_post_meta($post->ID, "_{$key}", true);
		$html .= "<label class=\"components-checkbox-control__label\" for=\"{$key}\">{$name}:</label>";
		$html .= "<input name=\"{$key}\" type=\"text\" value=\"{$value}\" style=\"width:100%;\"><br />";
	}

	echo $html;
}
function n4d_save_film_meta_box_data( $post_id ) {
	$allowed = array("film", "post");
	if (!in_array(get_post_type($post_id), $allowed)) return;
	if ( ! isset( $_POST['n4d_film_meta_box_nonce'] ) ) return;
	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $_POST['n4d_film_meta_box_nonce'], 'n4d_film_meta_box' ) ) return;
	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

	// Check the user's permissions.
	if ( isset( $_POST['post_type'] ) && 'post' == $_POST['post_type'] ) {
		if ( ! current_user_can( 'edit_page', $post_id ) ) return;
	} else {
		if ( ! current_user_can( 'edit_post', $post_id ) ) return;
	}

	$vars = array("gallery","location", "owner", "capacity", "period", "certification");
	foreach($vars as $item){
		if ( isset( $_POST[$item] ) ){
			$my_data =  sanitize_text_field( $_POST[$item] );
			update_post_meta( $post_id, "_{$item}", $my_data );
		} else {
			delete_post_meta( $post_id, "_{$item}", true );
		}
	}
}

//TAXONMY
function taxonomy_films() {
	register_taxonomy ( 'films', array('film','attachment'), array(
		'hierarchical'          => true,
        'show_ui'               => true,
		'labels'                => array(
		    'name'              => _x( 'Types', 'taxonomy general name', 'bkkiff'),
		    'singular_name'     => _x( 'Type', 'taxonomy singular name', 'bkkiff' ),
		    'search_items'      =>  __( 'Search Types', 'bkkiff' ),
		    'all_items'         => __( 'All Types', 'bkkiff' ),
		    'parent_item'       => __( 'Parent Type', 'bkkiff' ),
		    'parent_item_colon' => __( 'Parent Type:', 'bkkiff' ),
		    'edit_item'         => __( 'Edit Type', 'bkkiff' ),
		    'update_item'       => __( 'Update Type', 'bkkiff' ),
		    'add_new_item'      => __( 'Add Type', 'bkkiff' ),
		    'new_item_name'     => __( 'New Type', 'bkkiff' ),
		),
	    'public'                => true,
        'show_in_rest'          => true,
		'capability_type'       => 'post',
		'query_var'             => 'films',
		'update_count_callback' => '_update_generic_term_count',
		'rewrite'               => array(
			'slug'              => 'films'
		)
	));
}