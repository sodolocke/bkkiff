</div>
<?php if ( !is_front_page() ) : ?>
	<footer id="main-footer" class="footer mt-auto">
		<div class="row">
			<div class="col-12 col-lg-3 order-lg-1">
				<a href="<?php echo home_url(); ?>">
					<img src="<?php echo get_template_directory_uri(); ?>/assets/img/logo-w.svg?c=1" alt="BKKIFF logo" height="120" class="footer-brand" />
				</a>
				<?php
				wp_nav_menu(array(
					'theme_location'  => 'social',
					'menu'            => 'social',
					'menu_class'      => 'social-nav nav',
					'link_before'     => '<span>',
					'link_after'     => '</span>',
					'container'       => '',

					'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
					'walker'          => new n4d_submenu_walker()
				));
				?>
				<a href="mailto:info@bkkiff.com" class="mb-5">info@bkkiff.com</a>

			</div>
			<div class="col-12 col-lg-3">
				<?php
				wp_nav_menu(array(
					'theme_location'  => 'footer-1',
					'menu'            => 'footer-1',
					'menu_class'      => 'footer-nav nav flex-column',
					'container'       => '',

					'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
				));
				?>
			</div>
			<div class="col-12 col-lg-3">
				<?php
				wp_nav_menu(array(
					'theme_location'  => 'footer-2',
					'menu'            => 'footer-2',
					'menu_class'      => 'footer-nav nav flex-column',
					'container'       => '',

					'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
				));
				?>
			</div>
			<div class="col-12 col-lg-3">
				<?php
				wp_nav_menu(array(
					'theme_location'  => 'footer-3',
					'menu'            => 'footer-3',
					'menu_class'      => 'footer-nav nav flex-column',
					'container'       => '',

					'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
				));
				?>
			</div>
		</div>
		<div class="row">
			<div class="col-12 col-lg-9">
				&copy;Copyright Bangkok International Film Festival
			</div>
			<div class="col-12 col-lg-3">
				<a href="<?php echo home_url(); ?>">Privacy Policy</a>
			</div>
		</div>
	</footer>
<?php endif; ?>
<?php
//	get_template_part("template-parts/modal/splash");
	get_template_part("template-parts/modal/popup");
	wp_footer();

?>
</body>
</html>