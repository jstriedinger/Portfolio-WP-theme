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

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;700&display=swap">
	<link rel="preconnect" href="https://cdn.jsdelivr.net">
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

<!-- Loading Overlay -->
<div id="loading-overlay" class="loading-overlay">
	<div class="loading-content">
		<div class="css-spinner"></div>
		<p class="loading-text">Loading...</p>
	</div>
</div>

<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'arkdewp' ); ?></a>
	<?php do_action( THEME_HOOK_PREFIX . 'before_header' ); ?>

	<nav class="navbar is-transparent" role="navigation" aria-label="main navigation">
		<div class="container">
			<div class="navbar-brand">
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
						'menu_half'      => 'left',
					) );
					?>
				</div>
				<div class="navbar-end">
					<?php
					wp_nav_menu( array(
						'theme_location' => 'primary',
						'menu_class'     => '',
						'container'      => false,
						'fallback_cb'    => false,
						'items_wrap'     => '%3$s',
						'walker'         => new Bulma_Nav_Walker(),
						'menu_half'      => 'right',
					) );
					?>
				</div>
			</div>
		</div>
	</nav>

	<?php do_action( THEME_HOOK_PREFIX . 'after_header' ); ?>

	<?php do_action( THEME_HOOK_PREFIX . 'before_content' ); ?>
