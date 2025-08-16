<?php
add_action('save_post', 'post_settings_save_postdata', 10, 3);
add_action('admin_menu', 'post_settings_add_custom_box');


function post_settings_add_custom_box() {
	if ( function_exists( 'add_meta_box' )) {
		add_meta_box( 'post_settings_sectionid', __( 'Settings', 'bkkiff' ), 'post_settings_inner_custom_box', array('page'), 'side', 'low' );
	};
}
function post_settings_inner_custom_box($post) {
	wp_nonce_field( 'n4d_page_meta_box', 'n4d_page_meta_box_nonce' );

	$bg_color     = get_post_meta($post->ID, "_bg_color", true);

	$checked = ($bg_color) ? " checked=\"checked\"" : "";
	echo "<label>Banner Background Color<br /><input type=\"text\" name=\"bg_color\" value=\"{$bg_color}\" style=\"width:100%;\"{$checked} /></label><br />";

//NOTES
/*

	$split    = get_post_meta($post->ID, "_title_split", true);
	$overlap  = get_post_meta($post->ID, "_overlap_hide", true);
	$nomargin = get_post_meta($post->ID, "_margin_none", true);
	$bgFixed  = get_post_meta($post->ID, "_bg_fixed", true);


	$checked = ($split) ? " checked=\"checked\"" : "";
	echo "<label><input type=\"checkbox\" name=\"title_split\" value=\"1\"{$checked} /> Split First Word of Title</label><br />";

	$checked = ($overlap) ? " checked=\"checked\"" : "";
	echo "<label><input type=\"checkbox\" name=\"overlap_hide\" value=\"1\"{$checked} /> Disable Marquee Overlap</label><br />";

	$checked = ($nomargin) ? " checked=\"checked\"" : "";
	echo "<label><input type=\"checkbox\" name=\"margin_none\" value=\"1\"{$checked} /> Disable Marquee Margin</label><br />";

	$checked = ($bgFixed) ? " checked=\"checked\"" : "";
	echo "<label><input type=\"checkbox\" name=\"bg_fixed\" value=\"1\"{$checked} /> Background Fixed</label>";
*/
};
function post_settings_save_postdata( $post_id, $post, $update ) {
	$slugs = array('page');
	if ( !in_array($post->post_type, $slugs) ) return;
	if ( ! isset( $_POST['n4d_page_meta_box_nonce'] ) ) return;
	if ( ! wp_verify_nonce( $_POST['n4d_page_meta_box_nonce'], 'n4d_page_meta_box' ) ) return;
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

	// Check the user's permissions.
	if ( ! current_user_can( 'edit_post', $post_id ) ) return;

	$vars = array("bg_color", "title_split", "overlap_hide", "margin_none", "bg_fixed");

	foreach($vars as $item){
		if ( isset( $_POST[$item] ) ){
			$my_data =  sanitize_text_field( $_POST[$item] );
			update_post_meta( $post_id, "_{$item}", $my_data );
		} else {
			delete_post_meta( $post_id, "_{$item}" );
		}
	}
}