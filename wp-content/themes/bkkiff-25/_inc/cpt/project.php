<?php
//ADD POST TYPE
add_action('init', 'post_type_project');
//add_action( 'add_meta_boxes', 'n4d_add_project_meta_box' );
add_action( 'save_post', 'n4d_save_project_meta_box_data' );
add_action('init', 'taxonomy_projects');

function post_type_project() {
//REGISTER POST TYPE
	register_post_type('project', array(
        'labels'             => array(
			'name' => _x('Projects', 'post type general name', 'bkkiff'),
			'singular_name' => _x('Project', 'post type singular name', 'bkkiff'),
			'add_new' => _x('Add New', 'Project', 'bkkiff'),
			'add_new_item' => __('Add New Project', 'bkkiff'),
			'edit_item' => __('Edit Project', 'bkkiff'),
			'new_item' => __('New Project', 'bkkiff'),
			'view_item' => __('View Project', 'bkkiff'),
			'search_items' => __('Search Project', 'bkkiff'),
			'not_found' =>  __('No Project found', 'bkkiff'),
			'not_found_in_trash' => __('No Project found in Trash', 'bkkiff'),
			'parent_item_colon' => ''
		),
        'public'             => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'capability_type'    => 'page',
		'hierarchical'       => false,
		'menu_icon'          => 'dashicons-embed-photo',
		'rewrite'            => array("slug" => "project"), // Permalinks format
        'supports'           => array('title', 'thumbnail', 'revisions', "editor", "excerpt"),
		'has_archive'        => "projects",
		'show_in_rest'       => true,
	));

}
function n4d_add_project_meta_box() {

	add_meta_box(
		'n4d_project_sectionid',
		__( 'Settings', 'bkkiff' ),
		'n4d_project_meta_box_callback',
		'project',
		'side'
	);
}
function n4d_project_meta_box_callback($post){
	// Add an nonce field so we can check for it later.
	wp_nonce_field( 'n4d_project_meta_box', 'n4d_project_meta_box_nonce' );

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
function n4d_save_project_meta_box_data( $post_id ) {
	$allowed = array("project", "post");
	if (!in_array(get_post_type($post_id), $allowed)) return;
	if ( ! isset( $_POST['n4d_project_meta_box_nonce'] ) ) return;
	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $_POST['n4d_project_meta_box_nonce'], 'n4d_project_meta_box' ) ) return;
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
function taxonomy_projects() {
	register_taxonomy ( 'projects', array('project'), array(
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
		'query_var'             => 'projects',
		'update_count_callback' => '_update_generic_term_count',
		'rewrite'               => array(
			'slug'              => 'projects'
		)
	));
	register_taxonomy ( 'timeline', array('project'), array(
		'hierarchical'          => true,
		'show_ui'               => true,
		'labels'                => array(
			'name'              => _x( 'Timeline', 'taxonomy general name', 'bkkiff'),
			'singular_name'     => _x( 'Timeline', 'taxonomy singular name', 'bkkiff' ),
			'search_items'      =>  __( 'Search Timeline', 'bkkiff' ),
			'all_items'         => __( 'All Timeline', 'bkkiff' ),
			'parent_item'       => __( 'Parent Timeline', 'bkkiff' ),
			'parent_item_colon' => __( 'Parent Timeline:', 'bkkiff' ),
			'edit_item'         => __( 'Edit Timeline', 'bkkiff' ),
			'update_item'       => __( 'Update Timeline', 'bkkiff' ),
			'add_new_item'      => __( 'Add Timeline', 'bkkiff' ),
			'new_item_name'     => __( 'New Timeline', 'bkkiff' ),
		),
		'public'                => true,
		'show_in_rest'          => true,
		'capability_type'       => 'post',
		'query_var'             => 'timeline',
		'update_count_callback' => '_update_generic_term_count',
		'rewrite'               => array(
			'slug'              => 'timeline'
		)
	));
	register_taxonomy ( 'location', array('project'), array(
		'hierarchical'          => true,
		'show_ui'               => true,
		'labels'                => array(
			'name'              => _x( 'Locations', 'taxonomy general name', 'bkkiff'),
			'singular_name'     => _x( 'Location', 'taxonomy singular name', 'bkkiff' ),
			'search_items'      =>  __( 'Search Locations', 'bkkiff' ),
			'all_items'         => __( 'All Locations', 'bkkiff' ),
			'parent_item'       => __( 'Parent Location', 'bkkiff' ),
			'parent_item_colon' => __( 'Parent Location:', 'bkkiff' ),
			'edit_item'         => __( 'Edit Location', 'bkkiff' ),
			'update_item'       => __( 'Update Location', 'bkkiff' ),
			'add_new_item'      => __( 'Add Location', 'bkkiff' ),
			'new_item_name'     => __( 'New Location', 'bkkiff' ),
		),
		'public'                => true,
		'show_in_rest'          => true,
		'capability_type'       => 'post',
		'query_var'             => 'location',
		'update_count_callback' => '_update_generic_term_count',
		'rewrite'               => array(
			'slug'              => 'location'
		)
	));
	register_taxonomy ( 'venues', array('project'), array(
		'hierarchical'          => true,
		'show_ui'               => true,
		'labels'                => array(
			'name'              => _x( 'Venues', 'taxonomy general name', 'bkkiff'),
			'singular_name'     => _x( 'Venue', 'taxonomy singular name', 'bkkiff' ),
			'search_items'      =>  __( 'Search Venues', 'bkkiff' ),
			'all_items'         => __( 'All Venues', 'bkkiff' ),
			'parent_item'       => __( 'Parent Venue', 'bkkiff' ),
			'parent_item_colon' => __( 'Parent Venue:', 'bkkiff' ),
			'edit_item'         => __( 'Edit Venue', 'bkkiff' ),
			'update_item'       => __( 'Update Venue', 'bkkiff' ),
			'add_new_item'      => __( 'Add Venue', 'bkkiff' ),
			'new_item_name'     => __( 'New Venue', 'bkkiff' ),
		),
		'public'                => true,
		'show_in_rest'          => true,
		'capability_type'       => 'post',
		'query_var'             => 'venues',
		'update_count_callback' => '_update_generic_term_count',
		'rewrite'               => array(
			'slug'              => 'venues'
		)
	));
}


function n4d_archive_pages() {
	$page = add_submenu_page(
		'edit.php?post_type=project',
		__('Import', 'bkkiff'),
		__('Import', 'bkkiff'),
		'edit_pages',
		'import-tools',
		'n4d_archive_import_options'
	);
}
add_action('admin_menu', 'n4d_archive_pages');


function n4d_archive_import_options() {
	if (!current_user_can('edit_posts')) wp_die( __('You do not have sufficient permissions to access this page.','bkkiff') );

	$importing = false;

	if (isset($_POST['archive_import']) && $_POST['archive_import'] == '1'){
		$args = array();

		if (isset($_POST['reset']) && $_POST['reset'] == 1 ){
			$args['reset'] = 1;
		}

		$importing = true;
	}

	$html  = "";
	$html .= '<div id="brand-order" class="wrap">';
	$html .= "<h2>Import</h2><br />";

	$html .= "<h3>Project Importer</h3>";
	$html .= "<form method=\"post\" action=\"\" id=\"n4d_product_import\" enctype=\"multipart/form-data\"> ";
	$html .= '<input type="hidden" name="_wpnonce" id="_wpnonce" value="'.wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
	$html .= '<input type="hidden" name="product_import" id="product_import" value="1" />';


	$html .= '<div class="progress n4d-progress">';
	$html .= '<div id="progress-app" class="progress-bar" role="progressbar" aria-label="Import Applicants" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>';
	$html .= '</div>';
	$html .= '<div id="status-app" class="status-ajax">&nbsp;</div>';

	$html .= '<input type="button" name="submit_ajax" class="button button-primary submit-ajax" value="Import">';
	$html .= '</form>';
	$html .= "<br /><hr />";

	$html .= '</div>';

	echo $html;

}