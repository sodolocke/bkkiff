<?php
//REWRITE
add_filter('rewrite_rules_array','wp_insertMyRewriteRules');
add_filter('query_vars','wp_insertMyRewriteQueryVars');
add_filter('wp_loaded','flushRules');

// Remember to flush_rules() when adding rules
function flushRules(){
	global $wp_rewrite;
	   $wp_rewrite->flush_rules();
}

// Adding a new rule
function wp_insertMyRewriteRules($rules){
	$newrules = array();
	$newrules['projects/([^/]+)/timeline/([^/]+)/?$'] = 'index.php?projects=$matches[1]&timeline=$matches[2]';

	return $newrules + $rules;
}

function wp_insertMyRewriteQueryVars($vars){
	array_push($vars, 'timeline');
	return $vars;
}
//Stop wordpress from redirecting
remove_filter('template_redirect', 'redirect_canonical');
?>