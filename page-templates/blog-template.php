<?php
/*
Template Name: Blog Template
*/

// Copy all your existing page-blog.php content here
get_header();
$blog_desc = get_field( 'blog_desc' );
?>

<section class="section">
	<div class="container is-max-widescreen">
		<div class="columns is-centered is-8 mb-4">
			<div class="column is-two-thirds">
				<h1 class="title is-size-0 canela-font gradient-text has-text-weight-bold">
				<?php the_title(); ?>
				</h1>
				<p class="subtitle"><?php echo esc_html($blog_desc); ?></p>
			</div>
			<div class="column">
			<?php if (has_post_thumbnail()) : ?>
					<?php the_post_thumbnail('full'); ?>
			<?php endif; ?>
			</div>
		</div>
	</div>
</section>

<section class="section">
	<div class="container is-max-widescreen">
		<div class="columns">
			<div class="column">
				<?php the_content(); ?>
			</div>
		</div>
	</div>
</section>

<?php
get_footer();
?>
