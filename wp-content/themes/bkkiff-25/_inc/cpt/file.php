<?php
//ADD POST TYPE
add_action('init', 'post_type_file');
add_action( 'save_post', 'n4d_save_file_meta_box_data' );
add_action('init', 'taxonomy_files');

function post_type_file() {
//REGISTER POST TYPE
	register_post_type('file', array(
        'labels'             => array(
			'name' => _x('Files', 'post type general name', 'bkkiff'),
			'singular_name' => _x('File', 'post type singular name', 'bkkiff'),
			'add_new' => _x('Add New', 'File', 'bkkiff'),
			'add_new_item' => __('Add New File', 'bkkiff'),
			'edit_item' => __('Edit File', 'bkkiff'),
			'new_item' => __('New File', 'bkkiff'),
			'view_item' => __('View File', 'bkkiff'),
			'search_items' => __('Search File', 'bkkiff'),
			'not_found' =>  __('No File found', 'bkkiff'),
			'not_found_in_trash' => __('No File found in Trash', 'bkkiff'),
			'parent_item_colon' => ''
		),
        'public'             => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'capability_type'    => 'page',
		'hierarchical'       => false,
		'menu_icon'          => 'dashicons-media-default',
		'rewrite'            => array("slug" => "file"), // Permalinks format
        'supports'           => array('title', 'thumbnail', 'revisions', "editor", "excerpt"),
		'has_archive'        => "files",
		'show_in_rest'       => true,
	));

}
function n4d_add_file_meta_box() {

	add_meta_box(
		'n4d_file_sectionid',
		__( 'Settings', 'bkkiff' ),
		'n4d_file_meta_box_callback',
		'file',
		'side'
	);
}
function n4d_file_meta_box_callback($post){
	// Add an nonce field so we can check for it later.
	wp_nonce_field( 'n4d_file_meta_box', 'n4d_file_meta_box_nonce' );

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
function n4d_save_file_meta_box_data( $post_id ) {
	$allowed = array("file", "post");
	if (!in_array(get_post_type($post_id), $allowed)) return;
	if ( ! isset( $_POST['n4d_file_meta_box_nonce'] ) ) return;
	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $_POST['n4d_file_meta_box_nonce'], 'n4d_file_meta_box' ) ) return;
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
function taxonomy_files() {
	register_taxonomy ( 'files', array('file','attachment'), array(
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
		'query_var'             => 'files',
		'update_count_callback' => '_update_generic_term_count',
		'rewrite'               => array(
			'slug'              => 'files'
		)
	));
}