<?php
/**
 * Front page: intro, project bento grid, other skills.
 */

get_header();

// Hero background is either the "Portfolio preview" video (reel_preview, an .mp4) or, when the
// "Use image background" switch (hero_use_image) is on, a photo on the right (hero_image).
// The featured image doubles as the video's poster frame while it loads.
$front_page_id = get_queried_object_id();
$use_image     = (bool) get_field( 'hero_use_image', $front_page_id );
$hero_image    = $use_image ? (int) get_field( 'hero_image', $front_page_id ) : 0;
$reel_preview  = $use_image ? '' : get_field( 'reel_preview', $front_page_id );
$reel_poster   = get_the_post_thumbnail_url( $front_page_id, 'full' );
$cv            = get_field( 'cv', $front_page_id );
// About me page, found by slug (the one page-about-me.php applies to) so the URL is never hardcoded.
$about_page    = get_page_by_path( 'about-me' );
$about_url     = $about_page ? get_permalink( $about_page ) : home_url( '/about-me/' );
?>

<section class="section intro pt-0 is-relative" id="top-section">
	<?php if ( $reel_preview ) : ?>
		<?php // No src here: app.js (initHeroReel) sets it from data-src on tablet/desktop only, so phones never download the reel. ?>
		<div class="intro-video is-overlay is-hidden-mobile" aria-hidden="true">
			<video muted loop playsinline preload="none" data-src="<?php echo esc_url( $reel_preview ); ?>"<?php echo $reel_poster ? ' poster="' . esc_url( $reel_poster ) . '"' : ''; ?>></video>
		</div>
	<?php endif; ?>
	<div class="container is-max-widescreen">
		<div class="columns is-vcentered">
			<div class="column is-8">
				
				<h1 class="title is-size-0 canela-font"><span class="has-text-weight-light">Jose</span> Striedinger</h1>
				<p class="subtitle is-size-3 is-size-4-mobile">
					Gameplay engineer and designer.
				</p>

				<div class="buttons">
					<?php if ( $cv ) : ?>
					<a class="button is-link is-rounded" href="<?php echo esc_url( $cv ); ?>">
						<span>Download CV</span>
						<span class="icon"><i class="fa-solid fa-arrow-down" aria-hidden="true"></i></span>
					</a>
					<a class="button is-link is-outlined is-rounded" href="<?php echo esc_url( $about_url ); ?>">
						<span>More about me</span>
					</a>
					<?php endif; ?>
				</div>
			</div>
			<?php if ( $hero_image ) : ?>
				<div class="column is-4">
					<?php // data-no-lazy: LiteSpeed's lazy-load placeholder would collapse this layout-critical image. ?>
					<?php echo wp_get_attachment_image( $hero_image, 'large', false, array( 'class' => 'intro-image', 'data-no-lazy' => '1', 'fetchpriority' => 'high' ) ); ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>

<section class="section" id="projects-section">
	<div class="container">
		<h2 class="title is-2 canela-font section-title">Selected work</h2>
		<?php echo do_shortcode( '[projects_grid tag="featured"]' ); ?>
		<?php echo do_shortcode( '[projects_grid_small tag="other"]' ); ?>

	</div>
</section>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<section class="section" id="page-content-section">
	<div class="container is-fullhd content">
		<?php the_content(); ?>
	</div>
</section>
<?php endwhile; endif; ?>

<?php get_footer(); ?>
