<?php
/**
 * viewing a single project
 */
get_header();
$meta         = get_fields();
$desc         = $meta['desc'];
$enlaces      = $meta['links'];
$project_role = $meta['role'];

?>
<section class="section is-medium colored-black with-image anim-bottom-top" style="background-image: url(<?php echo esc_url( get_the_post_thumbnail_url( get_the_ID(), 'full' ) ); ?>)" >
	<div class="container mt-4 mb-4">
		<div class="columns is-centered has-text-centered">
			<div class="column is-full is-two-thirds-widescreen">
				<a href="<?php echo esc_url( home_url() ); ?>" class="link arrow left">Go back</a>
				<h1 class="title is-size-2 is-size-1-widescreen mt-6">
					<?php the_title(); ?>
				</h1>
				<h2 class="subtitle is-size-4 has-text-weight-light is-talic"><span class="has-text-weight-bold">Role:</span>  <?php echo esc_html( $project_role ); ?></h2>
				<p class="subtitle is-size-5 mb-6">	<?php echo esc_html( $desc ); ?></p>
				<div class="buttons is-centered">

					<?php	foreach ( $enlaces as $enlace ) { ?>
							<a href="<?php echo esc_url( $enlace['url'] ); ?>" class="button is-outlined is-gold is-medium">
							<?php if ( $enlace['icon'] != null ) : ?>
								<span><?php echo esc_html( $enlace['txt'] ); ?> </span>
								<span class="icon"><?php echo $enlace['icon']; ?></span>
							<?php else : ?>
								<?php echo $enlace['txt']; ?>
							<?php endif; ?>
							</a>
					<?php	} ?>
				</div>

			</div>
		</div>
	</div>
</section>
<section class="section">
	<div class="container is-max-desktop">
		<div class="columns">
			<div class="column is-full">
				<div class="content">
					<?php the_content(); ?>
				</div>
			</div>
		</div>
	</div>
</section>
<?php
get_footer();
