<?php
add_filter("manage_edit-project_columns", "admin_columns_project");
add_filter("manage_edit-member_columns", "admin_columns_member");
add_filter("manage_edit-job_columns", "admin_columns_job");
add_action("manage_posts_custom_column", "n4d_admin_columns");
add_action("manage_pages_custom_column", "n4d_admin_columns");

function admin_columns_project($columns){
	$new_columns = [];
	foreach($columns as $key => $value){
		if ($key == "title") {
			$new_columns["thumbnail"] = "";
		}
		$new_columns[$key] = $value;
		if ($key == "title") {
			$new_columns["projects"] = "Type";
//			$new_columns["rooms"] = "Room";
		}
	}
	return $new_columns;
}

function n4d_admin_columns($column){
	global $post;

	switch ($column){
		case "thumbnail":
			if( has_post_thumbnail($post->ID) ){
				echo get_the_post_thumbnail($post->ID,'thumbnail' );
			}
		break;
		case "projects":
		case "rooms":
			$terms = wp_get_post_terms($post->ID, $column, array("fields" => "names"));
			if ($terms) echo implode(", ", $terms);
		break;
		case "jobs":
			$terms = wp_get_post_terms($post->ID, "jobs", array("fields" => "names"));
			if ($terms) echo implode(", ", $terms);
		break;
		case "positions":
		case "board":
		case "email":
			$value = get_post_meta($post->ID, "_{$column}", true);
			if ($value) echo $value;
		break;
	}
}