<?php
/**
 * Front page: intro, project bento grid, other skills.
 */

get_header();

// Hero background = the front page's "Portfolio preview" ACF field (reel_preview, an .mp4).
// The featured image doubles as the poster frame while the video loads.
$front_page_id = get_queried_object_id();
$reel_preview  = get_field( 'reel_preview', $front_page_id );
$reel_poster   = get_the_post_thumbnail_url( $front_page_id, 'full' );
$cv            = get_field( 'cv', $front_page_id );
?>

<section class="section intro pt-0 is-relative" id="top-section">
	<?php if ( $reel_preview ) : ?>
		<div class="intro-video is-overlay" aria-hidden="true">
			<video autoplay muted loop playsinline preload="auto"<?php echo $reel_poster ? ' poster="' . esc_url( $reel_poster ) . '"' : ''; ?>>
				<source src="<?php echo esc_url( $reel_preview ); ?>" type="video/mp4">
			</video>
		</div>
	<?php endif; ?>
	<div class="container is-max-widescreen">
		<div class="columns is-vcentered">
			<div class="column is-8">
				<div class="buttons">
					<a class="button is-rounded" href="https://github.com/jstriedinger/CodeSamples" aria-label="GitHub">
						<span class="icon"><i class="fab fa-github" aria-hidden="true"></i></span>
					</a>
					<a class="button is-rounded" href="https://www.linkedin.com/in/jstriedinger/" aria-label="LinkedIn">
						<span class="icon"><i class="fab fa-linkedin-in" aria-hidden="true"></i></span>
					</a>
					<a class="button is-rounded" href="https://jstriedinger.itch.io/" aria-label="itch.io">
						<span class="icon"><i class="fa-brands fa-itch-io" aria-hidden="true"></i></span>
					</a>
				</div>
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
					<?php endif; ?>
				</div>
			</div>
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
