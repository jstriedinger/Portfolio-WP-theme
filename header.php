<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package ARKDE
 */

?>
<!doctype html>
<html <?php language_attributes(); ?> data-theme="light" >
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<!-- Favicon -->
	<link rel="icon" type="image/png" sizes="32x32" href="<?php echo get_template_directory_uri(); ?>/assets/images/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="<?php echo get_template_directory_uri(); ?>/assets/images/favicon-16x16.png">
	<link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_template_directory_uri(); ?>/assets/images/apple-touch-icon.png">
	<link rel="manifest" href="<?php echo get_template_directory_uri(); ?>/assets/images/site.webmanifest">

	<?php wp_head(); ?>
	
	<link rel="stylesheet" type='text/css' href="https://cdn.jsdelivr.net/gh/devicons/devicon@latest/devicon.min.css" />
          
	<?php if(!current_user_can('administrator')): ?>
		<!-- Hotjar Tracking Code for https://www.jstriedinger.com -->
		<script>
				(function(h,o,t,j,a,r){
						h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
						h._hjSettings={hjid:3689675,hjsv=6};
						a=o.getElementsByTagName('head')[0];
						r=o.createElement('script');r.async=1;
						r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
						a.appendChild(r);
				})(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
		</script>
	<?php endif; ?>
</head>

<body <?php body_class(); ?> >
<?php wp_body_open(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'arkdewp' ); ?></a>
	<?php do_action( THEME_HOOK_PREFIX . 'before_header' ); ?>

	<nav class="navbar is-transparent" role="navigation" aria-label="main navigation">
		<div class="container">
			<div class="navbar-brand">
				<a class="navbar-item logo-container" href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<div class="circular-logo">
						<span class="title has-text-weight-bold gradient-text animated center-text">J</span>
						<div id="circular-text" class="circular-text">• Game Engineer • Designer • Educator</div>
					</div>
				</a>
				<a class="navbar-burger" role="button" aria-label="menu" aria-expanded="false" data-target="navbarMain">
					<span aria-hidden="true"></span>
					<span aria-hidden="true"></span>
					<span aria-hidden="true"></span>
					<span aria-hidden="true"></span>
				</a>
			</div>

			<div id="navbarMain" class="navbar-menu">
				<div class="navbar-start">
					<?php
					wp_nav_menu( array(
						'theme_location' => 'primary',
						'menu_class'     => '',
						'container'      => false,
						'fallback_cb'    => false,
						'items_wrap'     => '%3$s',
						'walker'         => new Bulma_Nav_Walker(),
					) );
					?>
				</div>
				<div class="navbar-end">
					<div class="navbar-item">
						<a href="https://github.com/jstriedinger" class="social" >
							<i class="icon">
								<span class="fab fa-github"></span>
							</i>
						</a>
						<a href="https://www.linkedin.com/in/jstriedinger/" class="social" style="margin-left: 0.5rem;">
							<i class="icon">
								<span class="fab fa-linkedin-in"></span>
							</i>
						</a>
						<a href="https://jstriedinger.itch.io/" class="social" style="margin-left: 0.5rem;">
							<i class="icon">
								<span class="fa-brands fa-itch-io"></span>
							</i>
						</a>
					</div>
				</div>
			</div>
		</div>
	</nav>

	<?php do_action( THEME_HOOK_PREFIX . 'after_header' ); ?>

	<?php do_action( THEME_HOOK_PREFIX . 'before_content' ); ?>
