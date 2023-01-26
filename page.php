<?php
/**
 * The main template file
 */

get_header();
?>
<section class="pt-5 pb-5">
	<div class="container"><div class="columns is-centered">
		<div class="column is-full has-text-centered">
			<p class="title is-size-1 mb-2">Jose Striedinger</p>
			<div class="is-flex is-align-items-center is-justify-content-center" style="gap:2rem;">
				<a href="<?php echo esc_url( home_url() . '#projects' ); ?>">Projects</a>
				<a href="<?php echo esc_url( home_url() . '/about' ); ?>">About me</a>
			</div>
		</div>
	</div></div>
</section>
<section class="section">
	<div class="container is-max-widescreen">
		<div class="columns is-multiline">
			<?php	while ( have_posts() ) : the_post();?>
			<div class="column is-full mb-4">
				<h1 class="title is-size-1"><?php echo esc_html(the_title()); ?></h1>
			</div>
			<div class="column is-full has-text-justified">
				<?php the_content(); ?>
			</div>
<?php 
			endwhile;
			?>
		</div>
	</div>
</section>
<?php
get_footer();