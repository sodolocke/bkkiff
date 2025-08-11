<?php
add_action('restrict_manage_posts','restrict_project_by_projects');


function restrict_project_by_projects() {
	global $typenow;
	global $wp_query;
	if ($typenow == 'project') {
		$taxonomy = 'projects';
		$products_taxonomy = get_taxonomy($taxonomy);


		$selected = (isset($wp_query->query[$taxonomy])) ? $wp_query->query[$taxonomy] : "";


		wp_dropdown_categories(array(
			'show_option_all' =>  __("Show All {$products_taxonomy->label}"),
			'taxonomy'        =>  $taxonomy,
			'name'            =>  $taxonomy,
			'orderby'         =>  'name',
			'selected'        =>  $selected,
			'hierarchical'    =>  true,
			'depth'           =>  3,
			'show_count'      =>  true, // Show # listings in parens
			'hide_empty'      =>  true, // Don't show productses w/o listings
		));
	}
}


add_filter('parse_query','convert_project_to_projects_query');


function convert_project_to_projects_query($query) {
	global $pagenow;
	$qv = &$query->query_vars;
	$q = $query->query;
	$taxonomy = 'projects';


	if ($pagenow == 'edit.php' &&
			isset($q[$taxonomy]) && is_numeric($q[$taxonomy]) &&
			isset($qv[$taxonomy]) && $qv[$taxonomy] != 0) {
		$term = get_term_by('id', $q[$taxonomy], $taxonomy);
		$qv[$taxonomy] = $term->slug;
	}
}