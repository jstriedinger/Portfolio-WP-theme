<?php
/**
 * Blog Archive Page Template
 * 
 * This template is used when someone visits /blog
 * Create a page called "Blog" in WordPress admin for this to work
 */

get_header();
?>

<section class="section">
	<div class="container is-max-widescreen">
		<div class="columns is-centered has-text-centered">
			<div class="column is-full">
				<h1 class="title is-size-0 canela-font gradient-text has-text-weight-bold">
					Jose's yap corner
				</h1>
				<p class="subtitle">Or blog. Whatever you wanna call it. Here I talk about game, coding, design and whatnot</p>
			</div>
		</div>
	</div>
</section>

<section class="section">
	<div class="container">
		<?php
		// Query for all blog posts
		$blog_posts = new WP_Query(array(
			'post_type' => 'post',
			'posts_per_page' => -1, // Get all posts
			'post_status' => 'publish',
			'orderby' => 'date',
			'order' => 'DESC'
		));

		if ($blog_posts->have_posts()) :
		?>
		<div class="masonry-grid blog-grid">
			<?php while ($blog_posts->have_posts()) : $blog_posts->the_post(); ?>
				<div class="masonry-item">
					<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
						<?php if (has_post_thumbnail()) : ?>
							<img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'large'); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy">
						<?php else : ?>
							<!-- Placeholder if no featured image -->
							<div style="width: 100%; height: 200px; background: #f0f0f0; display: flex; align-items: center; justify-content: center; color: #999;">
								<span>No Image</span>
							</div>
						<?php endif; ?>
						
						<div class="blog-overlay">
							<div class="blog-content content">
								<h3 class="title mb-2 is-size-5">
									<?php the_title(); ?>
								</h3>
								<p class="excerpt subtitle is-size-6">
									<?php 
									$excerpt = get_the_excerpt();
									if (empty($excerpt)) {
										$excerpt = wp_trim_words(get_the_content(), 20, '...');
									} else {
										$excerpt = wp_trim_words($excerpt, 20, '...');
									}
									echo esc_html($excerpt);
									?>
								</p>
								<div class="is-flex is-align-items-center is-justify-content-space-between">
									<p class="mb-0">
										<span class="datetime is-size-7"><?php echo get_the_date('F j, Y'); ?></span>
										
									</p>
									<button class="button is-rounded">Read more</button>

								</div>
							</div>
						</div>
					</a>
				</div>
			<?php endwhile; ?>
		</div>
		<?php 
		wp_reset_postdata();
		else : 
		?>
		<div class="has-text-centered">
			<p>No blog posts found.</p>
		</div>
		<?php endif; ?>
	</div>
</section>

<?php get_footer(); ?>
