<nav id="menu-bar" class="navbar navbar-expand-lg fixed-top">
	<div class="nav-wrap">
		<button class="navbar-toggler hamburger hamburger--spin" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-collapse" aria-controls="navbar-collapse" aria-expanded="false" aria-label="Toggle navigation">
			<span class="hamburger-box">
				<span class="hamburger-inner"></span>
			</span>
		</button>

		<a class="navbar-brand" href="<?php echo home_url(); ?>">
			<img src="<?php echo get_template_directory_uri(); ?>/assets/img/logo.svg?c=1" alt="BKKIFF logo" width="80" height="80" />
		</a>

		<div class="collapse navbar-collapse" id="navbar-collapse">
		<?php
			wp_nav_menu(array(
				'theme_location'  => 'main',
				'menu'            => 'main',
				'menu_class'      => 'navbar-nav',
				'container'       => '',
				'container_class' => 'nav',
				'fallback_cb'     => 'wp_page_menu',
				'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
				'walker'          => new n4d_submenu_walker()
			));
		?>
		</div>
		<div class="controls">
		<?php
			wp_nav_menu(array(
				'theme_location'  => 'language',
				'menu'            => 'language',
				'menu_class'      => 'nav',
				'container'       => '',
				'container_class' => '',
				'fallback_cb'     => 'wp_page_menu',
				'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
				'walker'          => new n4d_submenu_walker()
			));

			echo get_search_form();
		?>
		</div>
	</div>
</nav>