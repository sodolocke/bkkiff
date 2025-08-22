<?php
//ADD POST TYPE
add_action('init', 'post_type_gallery');
add_action('init', 'taxonomy_galleries');
add_action('admin_menu', 'n4d_archive_pages');
add_action( 'save_post', 'n4d_save_project_meta_box_data' );

function post_type_gallery() {
//REGISTER POST TYPE
	register_post_type('gallery', array(
        'labels'             => array(
			'name' => _x('Gallery', 'post type general name', 'bkkiff'),
			'singular_name' => _x('Gallery', 'post type singular name', 'bkkiff'),
			'add_new' => _x('Add New', 'Gallery', 'bkkiff'),
			'add_new_item' => __('Add New Gallery', 'bkkiff'),
			'edit_item' => __('Edit Gallery', 'bkkiff'),
			'new_item' => __('New Gallery', 'bkkiff'),
			'view_item' => __('View Gallery', 'bkkiff'),
			'search_items' => __('Search Gallery', 'bkkiff'),
			'not_found' =>  __('No Gallery found', 'bkkiff'),
			'not_found_in_trash' => __('No Gallery found in Trash', 'bkkiff'),
			'parent_item_colon' => ''
		),
        'public'             => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'capability_type'    => 'page',
		'hierarchical'       => false,
		'menu_icon'          => 'dashicons-embed-photo',
		'rewrite'            => array("slug" => "gallery"), // Permalinks format
        'supports'           => array('title', 'thumbnail', 'revisions', "editor", "excerpt"),
//		'has_archive'        => "galleries",
		'show_in_rest'       => true,
	));

}

//TAXONMY
function taxonomy_galleries() {
	register_taxonomy ( 'galleries', array('gallery'), array(
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
		'query_var'             => 'galleries',
		'update_count_callback' => '_update_generic_term_count',
		'rewrite'               => array(
			'slug'              => 'galleries'
		)
	));
}
function n4d_archive_pages() {

	$suffix = add_meta_box(
		'n4d_project_gallery_sectionid',
		__( 'Gallery', 'bkkiff' ),
		'n4d_project_gallery_meta_box_callback',
		array('gallery'),
		'side'
	);
}

function n4d_project_gallery_generator($post, $suffix = "", $source = "gallery_ids", $js = true){
	$icon_close = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path fill="#FFF" d="M310.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L160 210.7 54.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L114.7 256 9.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 301.3 265.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L205.3 256 310.6 150.6z"/></svg>';
	$icon_video = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="#FFF" d="M0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256zM188.3 147.1c-7.6 4.2-12.3 12.3-12.3 20.9V344c0 8.7 4.7 16.7 12.3 20.9s16.8 4.1 24.3-.5l144-88c7.1-4.4 11.5-12.1 11.5-20.5s-4.4-16.1-11.5-20.5l-144-88c-7.4-4.5-16.7-4.7-24.3-.5z"/></svg>';
	$handle = "<a class=\"handle\">".'<svg viewBox="0 0 320 512" xmlns="http://www.w3.org/2000/svg"><path fill="#FFF" d="m137.4 41.4c12.5-12.5 32.8-12.5 45.3 0l128 128c9.2 9.2 11.9 22.9 6.9 34.9s-16.6 19.8-29.6 19.8h-256c-12.9 0-24.6-7.8-29.6-19.8s-2.2-25.7 6.9-34.9l128-128zm0 429.3-128-128c-9.2-9.2-11.9-22.9-6.9-34.9s16.6-19.8 29.6-19.8h255.9c12.9 0 24.6 7.8 29.6 19.8s2.2 25.7-6.9 34.9l-128 128c-12.5 12.5-32.8 12.5-45.3 0z"/></svg>'."</a>";

	$html  = "";
	$images = "";
	$img_id = $post->ID;
	$img_ids = get_post_meta( $post->ID, "_{$source}", true);

	$img_ids = (!$img_ids) ? [] : $img_ids;
	$images = "";
	foreach($img_ids as $img_id){
		if ($img_id){
			$mime = get_post_mime_type($img_id);
			$mime = explode("/", $mime);
			$type = (sizeof($mime) == 2) ? $mime[0] : "image";

			if ($type == "video"){
				$image = "<span class=\"video\">{$icon_video}</span>";
			} else {
				$src = wp_get_attachment_image_src( $img_id, "thumbnail" );
				$image = "";
				if ($src){
					$url = $src[0];
					$image = "<img src=\"$url\" height=\"100\" style=\"margin:0 5px 5px 0;\">";
				}
			}
			$remove  = "<a class=\"gallery-remove\" data-id=\"{$img_id}\" data-parent=\"#n4d_gallery_values{$suffix}\">";
			$remove .= $icon_close;
			$remove .= "</a>";


			$images .= "<li class=\"gallery-item\" data-id=\"{$img_id}\">{$handle}{$image}{$remove}</li>";
		}
	}
	$img_ids = ( is_array($img_ids) ) ? implode(",", $img_ids) : "";


	$html .= "<div id=\"image-preview-{$post->ID}{$suffix}\">";
	$html .= "<ul id=\"n4d-gallery{$suffix}\" class=\"gallery-sort\" data-target=\"#n4d_gallery_values{$suffix}\">{$images}</ul>";
	$html .= "</div>";
	$html .= "<input id=\"upload_image_button_{$post->ID}{$suffix}\" type=\"button\" class=\"button n4d_img_select\" value=\"".__( 'Add to Gallery','sls' )."\" data-atid=\"#n4d_gallery_values{$suffix}\" data-img_id=\"#image-preview-{$post->ID}{$suffix}\" data-default=\"{$img_id}\" style=\"display:block;width:100%;\" />";
	$html .= "<input type=\"hidden\" name=\"{$source}\" id=\"n4d_gallery_values{$suffix}\" value=\"{$img_ids}\">";
	$html .= "<hr style=\"clear:both;\" />";

	$html .= ($js) ? "
	<script type='text/javascript'>
		jQuery( document ).ready( function( $ ) {
			function initGalleryRemove(){
				jQuery('.gallery-remove').click(function(){
					var target = jQuery(this).data('id');
					var parent = jQuery(this).data('parent');
					var current = jQuery(parent).val().split(',');
					var newOrder = current.filter(value => {
						if (Number(value) !== target) return value
					});
					jQuery(parent).val(newOrder.join(','));

					jQuery(this).parent().remove();
				});

				jQuery( '.gallery-sort' ).sortable({
					update:  function (event, ui){
						let galleryOrder = [];
						let parent = '#'+this.id
						let target = jQuery(this).data('target')

						jQuery( parent + ' .gallery-item' ).each(function(i, el){
							let val = el.dataset.id;
							galleryOrder.push(val);
						});
						jQuery(target).val(galleryOrder.join(','));
					}
				});
			}
			initGalleryRemove();

			// Uploading files
			var file_frame;
			var wp_media_post_id = wp.media.model.settings.post.id; // Store the old id
			var set_to_post_id;

			var atid;
			var img_id;

			jQuery('.n4d_img_select').on('click', function( event ){
				event.preventDefault();
				atid           = $(this).data('atid');
				img_id         = $(this).data('img_id');
				set_to_post_id = $(this).data('default');

				// If the media frame already exists, reopen it.
				if ( file_frame ) {
					// Set the post ID to what we want
					file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
					// Open frame
					file_frame.open();
					return;
				} else {
					// Set the wp.media post id so the uploader grabs the ID we want when initialised
					wp.media.model.settings.post.id = set_to_post_id;
				}

				// Create the media frame.
				file_frame = wp.media.frames.file_frame = wp.media({
					title: 'Select a image to upload',
					button: {
						text: 'Use this image',
					},
					library: {
					  uploadedTo: wp_media_post_id
					},
					multiple: true,
					frame: 'select'
				});

				// When an image is selected, run a callback.
				file_frame.on( 'select', function() {
					// We set multiple to false so only get one image from the uploader
					attachment = file_frame.state().get('selection').toJSON();

					var ids = [];

					if ($( atid ).val()) ids = $( atid ).val().split(',');

					for (var i = 0; i < attachment.length; i++){
						ids.push(attachment[i].id);
						var li = document.createElement('li');
						li.classList.add('gallery-item');
						li.dataset.id = attachment[i].id;
						li.innerHTML = '{$handle}<img src=\"'+ attachment[i].url +'\"><a class=\"gallery-remove\" data-id=\"'+attachment[i].id+'\">{$icon_close}</a>';

						jQuery(img_id+' .gallery-sort').append(li);
					}
					initGalleryRemove();

					// Do something with attachment.id and/or attachment.url here
					$( img_id ).attr( 'src', attachment.url ).css( 'width', 'auto' );
					$( atid ).val( ids );

					$( atid ).trigger('change');

					// Restore the main post ID
					wp.media.model.settings.post.id = wp_media_post_id;
				});

					// Finally, open the modal
					file_frame.open();
			});

			// Restore the main ID when the add media button is pressed
			jQuery( 'a.add_media' ).on( 'click', function() {
				wp.media.model.settings.post.id = wp_media_post_id;
			});
		});

	</script>" : "";

	return $html;
}
function n4d_project_gallery_meta_box_callback($post){
	// Add an nonce field so we can check for it later.
	wp_nonce_field( 'n4d_project_meta_box', 'n4d_project_meta_box_nonce' );

	echo n4d_project_gallery_generator($post);
}
function n4d_save_project_meta_box_data( $post_id ) {
	$allowed = array("gallery");
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

	$vars = array("gallery","gallery_ids","photo_2_ids","photo_3_ids","photo_4_ids");
	foreach($vars as $item){
		if ( isset( $_POST[$item] ) ){
			$my_data =  sanitize_text_field( $_POST[$item] );
			update_post_meta( $post_id, "_{$item}", explode(",", $my_data) );
		} else {
			delete_post_meta( $post_id, "_{$item}", true );
		}
	}
}