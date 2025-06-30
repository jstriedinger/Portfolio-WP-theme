<?php
/**
 * The main template file
 */

get_header();
?>
<section class="section anim-bottom-top">
	<div class="container is-max-widescreen">
		<div class="columns is-multiline">
			<?php
			while ( have_posts() ) :
				the_post();
				?>
			<div class="column is-full mb-2">
				<h1 class="title is-size-1"><?php echo esc_html( the_title() ); ?></h1>
			</div>
			<div class="column is-full content has-text-justified">
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
