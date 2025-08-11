<?php
add_filter('nav_menu_css_class', 'n4d_menu_item_class', 10, 4);
//add_filter('walker_nav_menu_start_el', 'my_walker_nav_menu_start_el', 10, 4);

function n4d_menu_item_class ( $classes, $item, $args, $depth ){
	$classes[] = 'nav-item';
	return $classes;
}
function my_walker_nav_menu_start_el($item_output, $item, $depth, $args) {

	if (isset($args->theme_location) && $args->theme_location == "main" && $depth == 0){
		$name = get_post_field('post_name', $item->object_id);

		$item_output = preg_replace('/<a /', '<a class="nav-link" ', $item_output, 1);

	} else {
		$item_output = preg_replace('/<a /', '<a class="nav-link" ', $item_output, 1);
	}
	return $item_output;
}

class n4d_submenu_walker extends Walker_Nav_Menu {
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$dropup = "";
		if (strpos($args->menu_class, 'dropup') !== false) {
			$dropup = " dropup";
		}
		if (0 == $depth) {
			$output .= '<ul class="dropdown-menu'.$dropup.'">';
		} else {
			if (strpos($args->menu_class, 'dropup') !== false) {
				$dropup .= " show";
			}
			$output .= '<ul class="dropdown-menu'.$dropup.'">';
		}


	}

	function end_lvl( &$output, $depth = 0, $args = array() ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$output .= "</ul>";
	}

	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = ( $depth ) ? str_repeat( $t, $depth ) : '';

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

		$has_spy = false;

		foreach($classes as $class){
			if (str_contains($class, "spy-")){
				$has_spy = str_replace("spy-", "", $class);
			}
		}

		/**
		 * Filters the arguments for a single nav menu item.
		 *
		 * @since 4.4.0
		 *
		 * @param stdClass $args  An object of wp_nav_menu() arguments.
		 * @param WP_Post  $item  Menu item data object.
		 * @param int      $depth Depth of menu item. Used for padding.
		 */
		$args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

		/**
		 * Filters the CSS class(es) applied to a menu item's list item element.
		 *
		 * @since 3.0.0
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param array    $classes The CSS classes that are applied to the menu item's `<li>` element.
		 * @param WP_Post  $item    The current menu item.
		 * @param stdClass $args    An object of wp_nav_menu() arguments.
		 * @param int      $depth   Depth of menu item. Used for padding.
		 */

		$has_children = ( in_array("menu-item-has-children", $classes) ) ? true : false;


		if ( $has_children ) {
			array_push($classes, "has_dropdown dropdown-center");
		}


		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );

		$li_classes = $class_names;
		$dropup = false;
		if (strpos($args->menu_class, 'dropup') !== false) {
			$li_classes .= " dropup";
			$dropup = true;
		}


		$class_names = $class_names ? ' class="' . esc_attr( $li_classes ) . '"' : '';

		/**
		 * Filters the ID applied to a menu item's list item element.
		 *
		 * @since 3.0.1
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param string   $menu_id The ID that is applied to the menu item's `<li>` element.
		 * @param WP_Post  $item    The current menu item.
		 * @param stdClass $args    An object of wp_nav_menu() arguments.
		 * @param int      $depth   Depth of menu item. Used for padding.
		 */
		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args, $depth );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		$output .= $indent . '<li' . $id . $class_names .'>';

		$atts = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target )     ? $item->target     : '';
		$atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
		$atts['href']   = ! empty( $item->url )        ? $item->url        : '';
		$atts['class']  = 'nav-link';




		if ($depth != 0){
//			$atts['class'] = 'dropdown-link';
		} else if ($depth == 0){
//			$atts['class'] = $li_classes;
		}

		if ($has_spy && is_front_page()){
			$atts['href'] = "#{$has_spy}";
			$atts['class'] .= " spy";
		} else {

		}


		/**
		 * Filters the HTML attributes applied to a menu item's anchor element.
		 *
		 * @since 3.6.0
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param array $atts {
		 *     The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
		 *
		 *     @type string $title  Title attribute.
		 *     @type string $target Target attribute.
		 *     @type string $rel    The rel attribute.
		 *     @type string $href   The href attribute.
		 * }
		 * @param WP_Post  $item  The current menu item.
		 * @param stdClass $args  An object of wp_nav_menu() arguments.
		 * @param int      $depth Depth of menu item. Used for padding.
		 */
		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}


		/** This filter is documented in wp-includes/post-template.php */
		$title = apply_filters( 'the_title', $item->title, $item->ID );

		/**
		 * Filters a menu item's title.
		 *
		 * @since 4.4.0
		 *
		 * @param string   $title The menu item's title.
		 * @param WP_Post  $item  The current menu item.
		 * @param stdClass $args  An object of wp_nav_menu() arguments.
		 * @param int      $depth Depth of menu item. Used for padding.
		 */
		$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );


		$item_output = $args->before;
		$item_output .= '<a'. $attributes .">";
		$item_output .= $args->link_before . $title . $args->link_after;
		$item_output .= '</a>';
		if ($has_children){
			$item_output .= "<a class=\"dropdown-toggle\" data-bs-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\"></a>";

		/*
					$atts['class']  = 'nav-link dropdown-toggle';
					$atts['data-bs-toggle'] = "dropdown";
		//			$atts['href'] = '#';
		//			unset($atts['href']);
					$atts['role'] = 'button';
					$atts['aria-haspopup'] = 'true';
					$atts['aria-expanded'] = 'false';
		*/
		}
		$item_output .= $args->after;

		/**
		 * Filters a menu item's starting output.
		 *
		 * The menu item's starting output only includes `$args->before`, the opening `<a>`,
		 * the menu item's title, the closing `</a>`, and `$args->after`. Currently, there is
		 * no filter for modifying the opening and closing `<li>` for a menu item.
		 *
		 * @since 3.0.0
		 *
		 * @param string   $item_output The menu item's starting HTML output.
		 * @param WP_Post  $item        Menu item data object.
		 * @param int      $depth       Depth of menu item. Used for padding.
		 * @param stdClass $args        An object of wp_nav_menu() arguments.
		 */
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
	function end_el( &$output, $item, $depth = 0, $args = array() ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;

		if ($depth == 0) {
/*
			if (in_array("menu-item-object-project", $classes)) {
				$output .= do_shortcode("[n4d_nav_projects]");
			}
*/
		}
		$output .= "</li>{$n}";
	}

}