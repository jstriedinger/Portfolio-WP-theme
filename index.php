<?php
/**
 * The main template file — fallback for anything without a more specific template.
 */

get_header();
?>
<section class="section anim-bottom-top">
	<div class="container is-max-widescreen">
		<div class="columns is-multiline">
			<?php if ( have_posts() ) : ?>
				<?php while ( have_posts() ) : the_post(); ?>
				<div class="column is-full">
					<a href="<?php the_permalink(); ?>">
						<h2 class="title is-3 canela-font"><?php the_title(); ?></h2>
					</a>
					<p class="subtitle is-6"><?php echo esc_html( get_the_date( 'F j, Y' ) ); ?></p>
				</div>
				<?php endwhile; ?>
			<?php else : ?>
				<div class="column is-full">
					<p class="subtitle">Nothing here yet.</p>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
<?php
get_footer();
