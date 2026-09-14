<?php
/**
 * Front page: intro, project bento grid, other skills.
 */

get_header();
?>

<section class="section intro" id="top-section">
	<div class="container is-fullhd">
		<div class="columns is-vcentered">
			<div class="column is-three-fifths">
				<h1 class="title is-size-0 canela-font gradient-text">Jose Striedinger</h1>
				<p class="subtitle is-size-4">
					Game engineer and technical designer, with a focus on exploring curiosity. 
					Previously an award-winning educator and entrepreneur.
				</p>
			</div>

			<div class="column">
				<div class="buttons is-justify-content-end">
					<a class="button is-rounded is-outlined is-link" href="https://github.com/jstriedinger/CodeSamples" aria-label="GitHub">
						<span class="icon"><i class="fab fa-github" aria-hidden="true"></i></span>
					</a>
					<a class="button is-rounded is-outlined is-link" href="https://www.linkedin.com/in/jstriedinger/" aria-label="LinkedIn">
						<span class="icon"><i class="fab fa-linkedin-in" aria-hidden="true"></i></span>
					</a>
					<a class="button is-rounded is-outlined is-link" href="https://jstriedinger.itch.io/" aria-label="itch.io">
						<span class="icon"><i class="fa-brands fa-itch-io" aria-hidden="true"></i></span>
					</a>
				</div>
			</div>
		</div>
	</div>
</section>

<?php $special = do_shortcode( '[project_special tag="special"]' ); ?>
<section class="section" id="projects-section">
	<div class="container is-fullhd">
		<h2 class="title is-2 canela-font section-title">Selected work</h2>
		<?php echo do_shortcode( '[projects_grid tag="featured"]' ); ?>
		<?php echo do_shortcode( '[projects_grid_small tag="other"]' ); ?>
		<?php if ( ! empty( $special ) ) :
			echo $special; 
		endif; ?>

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
