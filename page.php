<?php
/**
 * The main template file
 */

get_header();
?>
<section class="section colored-black  mb-5" >
	<div class="container mb-0">
		<div class="columns is-centered has-text-centered is-variable is-8">
			<div class="column is-two-thirds is-paddingless">
				<h1 class="title is-size-3 has-text-weight-bold ">~ José Rafael Striedinger ~</h1>
				<div class="is-flex is-align-items-center is-justify-content-center has-text-weight-light" style="gap:2rem;">
					<a href="<?php echo esc_url( home_url() . '#projects' ); ?>" class="is-gold">Projects</a>
					<a href="<?php echo esc_url( home_url() . '/about' ); ?>" class="is-gold">About me</a>
				</div>
			</div>
		</div>
	</div>
</section>
<section class="section anim-bottom-top">
	<div class="container is-max-widescreen">
		<div class="columns is-multiline">
			<?php
			while ( have_posts() ) :
				the_post();
				?>
			<div class="column is-full mb-4">
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
