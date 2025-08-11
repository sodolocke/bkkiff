<?php
add_action( 'admin_menu', 'register_my_custom_menu_page' );

function register_my_custom_menu_page(){
    add_menu_page( 'Company Info', 'Company Info', 'edit_pages', 'company-info', 'my_custom_menu_page', 'dashicons-building', 3 );
}
function my_custom_menu_page(){

	if ($_POST){
		$error = false;
		if ( ! isset( $_POST['my_custom_menu_page_nonce'] ) ) {
			$error = true;
		}
		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $_POST['my_custom_menu_page_nonce'], 'my_custom_menu_page' ) ) {
			$error = true;
		}

		if ( ! isset( $_POST['company_telephone'] ) ) {
			$error = true;
		}

		if ( isset( $_POST['company_name'] ) ){
			// Sanitize user input.
			$my_data = sanitize_text_field( $_POST['company_name'] );
			update_option('company_name', $my_data);
		}

		if ( isset( $_POST['company_telephone'] ) ){
			// Sanitize user input.
			$my_data = $_POST['company_telephone'];
			update_option('company_telephone', $my_data);
		}

		if ( isset( $_POST['company_customer'] ) ){
			// Sanitize user input.
			$my_data = $_POST['company_customer'];
			update_option('company_customer', $my_data);
		}

		if ( isset( $_POST['company_fax'] ) ){
			// Sanitize user input.
			$my_data = $_POST['company_fax'];
			update_option('company_fax', $my_data);
		}

		if ( isset( $_POST['company_email'] ) ){
			// Sanitize user input.
			$my_data = sanitize_text_field( $_POST['company_email'] );
			update_option('company_email', $my_data);
		}

		if ( isset( $_POST['company_gmap'] ) ){
			// Sanitize user input.
			$my_data = $_POST['company_gmap'];
			update_option('company_gmap', $my_data);
		}

		if ( isset( $_POST['company_address'] ) ){
			// Sanitize user input.
			$my_data = esc_textarea($_POST['company_address']);
			update_option('company_address', $my_data);
		}

		if ( isset( $_POST['company_name_th'] ) ){
			// Sanitize user input.
			$my_data = sanitize_text_field( $_POST['company_name_th'] );
			update_option('company_name_th', $my_data);
		}

		if ( isset( $_POST['company_address_th'] ) ){
			// Sanitize user input.
			$my_data = esc_textarea($_POST['company_address_th']);
			update_option('company_address_th', $my_data);
		}


		if ( isset( $_POST['company_telephone_hr'] ) ){
			// Sanitize user input.
			$my_data = $_POST['company_telephone_hr'];
			update_option('company_telephone_hr', $my_data);
		}

		if ( isset( $_POST['company_email_hr'] ) ){
			// Sanitize user input.
			$my_data = sanitize_text_field( $_POST['company_email_hr'] );
			update_option('company_email_hr', $my_data);
		}

		if ( isset( $_POST['company_note_hr'] ) ){
			// Sanitize user input.
			$my_data = sanitize_text_field( $_POST['company_note_hr'] );
			update_option('company_note_hr', $my_data);
		}

		if ( isset( $_POST['company_email_com'] ) ){
			// Sanitize user input.
			$my_data = sanitize_text_field( $_POST['company_email_com'] );
			update_option('company_email_com', $my_data);
		}


		if ( isset( $_POST['company_telephone_ir'] ) ){
			// Sanitize user input.
			$my_data = $_POST['company_telephone_ir'];
			update_option('company_telephone_ir', $my_data);
		}

		if ( isset( $_POST['company_email_ir'] ) ){
			// Sanitize user input.
			$my_data = sanitize_text_field( $_POST['company_email_ir'] );
			update_option('company_email_ir', $my_data);
		}



	}


    echo "<div class=\"wrap\">";
    echo "<h2>Company Information</h2>";
    echo "<div class=\"metabox-holder columns-1\">";
    echo "<div id=\"company_information\" class=\"postbox\">";


	echo "<h3 class=\"hndle\"><span>Contact Information</span></h3>";
	echo "<div class=\"inside\">";

	echo "<form method=\"post\">";
    wp_nonce_field( 'my_custom_menu_page', 'my_custom_menu_page_nonce' );

	$name = get_option('company_name');
	echo "<p><h4 class=\"input_label\"><label for=\"company_name\">Company Name:</label></h4>";
	echo '<input type="text" id="company_name" name="company_name" class="form-input-tip" style="width:100%;" autocomplete="off" value="'.$name.'"></p>';

	$name_th = get_option('company_name_th');
	echo "<p><h4 class=\"input_label\"><label for=\"company_name_th\">Company Name (TH):</label></h4>";
	echo '<input type="text" id="company_name_th" name="company_name_th" class="form-input-tip" style="width:100%;" autocomplete="off" value="'.$name_th.'"></p>';

	$address = get_option('company_address');
	echo "<p><h4 class=\"input_label\"><label for=\"company_address\">Address:</label></h4>";
	echo '<textarea id="company_address" name="company_address" class="form-input-tip" style="width:100%;height:150px;" autocomplete="off">'.$address.'</textarea></p>';

	$address_th = get_option('company_address_th');
	echo "<p><h4 class=\"input_label\"><label for=\"company_address_th\">Address:</label></h4>";
	echo '<textarea id="company_address_th" name="company_address_th" class="form-input-tip" style="width:100%;height:150px;" autocomplete="off">'.$address_th.'</textarea></p>';

	$telephone = get_option('company_telephone');
	echo "<p><h4 class=\"input_label\"><label for=\"company_telephone\">Telephone:</label></h4>";
	echo '<textarea id="company_telephone" name="company_telephone" class="form-input-tip" style="width:100%;">'.$telephone.'</textarea></p>';

	$fax = get_option('company_fax');
	echo "<p><h4 class=\"input_label\"><label for=\"company_fax\">Fax:</label></h4>";
	echo '<textarea id="company_fax" name="company_fax" class="form-input-tip" style="width:100%;">'.$fax.'</textarea></p>';

	$email = get_option('company_email');
	echo "<p><h4 class=\"input_label\"><label for=\"company_email\">E-mail:</label></h4>";
	echo '<input type="text" id="company_email" name="company_email" class="form-input-tip" style="width:100%;" autocomplete="off" value="'.$email.'"></p>';

	$gmap = get_option('company_gmap');
	echo "<p><h4 class=\"input_label\"><label for=\"company_gmap\">Google Map URL:</label></h4>";
	echo '<input type="text" id="company_gmap" name="company_gmap" class="form-input-tip" style="width:100%;" autocomplete="off" value="'.$gmap.'">';

	echo '</p>';

	$telephone_hr = get_option('company_telephone_hr');
	echo "<p><h4 class=\"input_label\"><label for=\"company_telephone_hr\">HR Telephone:</label></h4>";
	echo '<textarea id="company_telephone_hr" name="company_telephone_hr" class="form-input-tip" style="width:100%;">'.$telephone_hr.'</textarea></p>';

	$email_hr = get_option('company_email_hr');
	echo "<p><h4 class=\"input_label\"><label for=\"company_email_hr\">HR E-mail:</label></h4>";
	echo '<input type="text" id="company_email_hr" name="company_email_hr" class="form-input-tip" style="width:100%;" autocomplete="off" value="'.$email_hr.'"></p>';
	$note_hr = get_option('company_note_hr');
	echo "<p><h4 class=\"input_label\"><label for=\"company_email_hr\">HR Note:</label></h4>";
	echo '<textarea id="company_note_hr" name="company_note_hr" class="form-input-tip" style="width:100%;">'.$note_hr.'</textarea></p>';

	$email_com = get_option('company_email_com');
	echo "<p><h4 class=\"input_label\"><label for=\"company_email_com\">Communications E-mail:</label></h4>";
	echo '<input type="text" id="company_email_com" name="company_email_com" class="form-input-tip" style="width:100%;" autocomplete="off" value="'.$email_com.'"></p>';

	$telephone_ir = get_option('company_telephone_ir');
	echo "<p><h4 class=\"input_label\"><label for=\"company_telephone_ir\">IR Telephone:</label></h4>";
	echo '<textarea id="company_telephone_ir" name="company_telephone_ir" class="form-input-tip" style="width:100%;">'.$telephone_ir.'</textarea></p>';

	$email_ir = get_option('company_email_ir');
	echo "<p><h4 class=\"input_label\"><label for=\"company_email_ir\">IR E-mail:</label></h4>";
	echo '<input type="text" id="company_email_ir" name="company_email_ir" class="form-input-tip" style="width:100%;" autocomplete="off" value="'.$email_ir.'"></p>';





	echo '<input name="save" type="submit" class="button button-primary button-large" id="publish" accesskey="p" value="Update">';

	echo "</form>";

	echo "</div>";
	echo "</div>";


    echo "</div>";
    echo "</div>";
}