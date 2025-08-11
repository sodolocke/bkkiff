<?php

//	if (is_home()){
		$splash = get_page_by_path("splash");

		if ($splash && isset($splash->ID) && get_post_status($splash->ID) == "publish"){
			$html = "";
			$html .= "<div class=\"modal fade\" id=\"modal-splash\" tabindex=\"-1\" role=\"dialog\" aria-label=\"Contact Us Popup\" aria-hidden=\"true\">";
			$html .= "<div class=\"modal-dialog modal-dialog-centered modal-xl\" role=\"document\">";
			$html .= "<div class=\"modal-content\">";
			$html .= "<div class=\"modal-header\">";
			$html .= "<button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"modal\" aria-label=\"Close\"><i class=\"fa-solid fa-xmark\"></i></button>";
			$html .= "</div>";
			$html .= "<div class=\"modal-body\">";
			$html .= apply_filters("the_content", $splash->post_content);
			$html .= "</div>";
			$html .= "</div>";
			$html .= "</div>";
			$html .= "</div>";

			$html .= "<a id=\"modal-trigger\" class=\"d-none\" data-bs-toggle=\"modal\" data-bs-target=\"#modal-splash\"></a>";

			echo $html;
		}
//	}
?>