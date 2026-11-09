<?php
/**
 * [blog_posts] output. Reads $blog_posts (WP_Query of `post`) and $atts (shortcode attributes,
 * `class` is the grid wrapper class) put in scope by blog_posts_shortcode().
 */

if ( ! $blog_posts->have_posts() ) : ?>
<div class="has-text-centered">
	<p>No blog posts found.</p>
</div>
<?php return; endif; ?>

<div class="<?= esc_attr( $atts['class'] ) ?>">
	<?php while ( $blog_posts->have_posts() ) : $blog_posts->the_post();
		$excerpt = get_the_excerpt();
		$excerpt = wp_trim_words( empty( $excerpt ) ? get_the_content() : $excerpt, 20, '...' );
	?>
	<div class="masonry-item">
		<a href="<?= esc_url( get_the_permalink() ) ?>" title="<?= esc_attr( get_the_title() ) ?>">
			<?php if ( has_post_thumbnail() ) : ?>
			<img src="<?= esc_url( get_the_post_thumbnail_url( get_the_ID(), 'large' ) ) ?>" alt="<?= esc_attr( get_the_title() ) ?>" loading="lazy">
			<?php else : ?>
			<div class="is-flex is-align-items-center is-justify-content-center has-background-light has-text-grey" style="width: 100%; height: 200px;">
				<span>No Image</span>
			</div>
			<?php endif; ?>

			<div class="blog-overlay">
				<div class="blog-content content">
					<h3 class="title mb-2 is-size-5"><?= esc_html( get_the_title() ) ?></h3>
					<p class="excerpt subtitle is-size-6"><?= esc_html( $excerpt ) ?></p>
					<div class="is-flex is-align-items-center is-justify-content-space-between">
						<p class="mb-0">
							<span class="datetime is-size-7"><?= esc_html( get_the_date( 'F j, Y' ) ) ?></span>
						</p>
						<button class="button is-rounded">Read more</button>
					</div>
				</div>
			</div>
		</a>
	</div>
	<?php endwhile; wp_reset_postdata(); ?>
</div>
