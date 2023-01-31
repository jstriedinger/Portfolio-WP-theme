<?php
/**
 * viewing a single project
 */
get_header();
$meta         = get_fields();
$desc         = $meta['desc'];
$enlaces      = $meta['links'];
$project_role = $meta['role'];
$year_made    = $meta['year'];


?>
<section class="pt-5 pb-6">
	<div class="container"><div class="columns is-centered">
		<div class="column is-full has-text-centered">
			<p class="title is-size-3 mb-2">Jose Striedinger</p>
			<div class="is-flex is-align-items-center is-justify-content-center" style="gap:2rem;">
				<a href="<?php echo esc_url( home_url() . '#projects' ); ?>">Projects</a>
				<a href="<?php echo esc_url( home_url() . '/about' ); ?>">About me</a>
			</div>
		</div>
	</div></div>
</section>
<div class="container">
	<section class="section colored-black with-image anim-bottom-top" style="background-image: url(<?php echo esc_url( get_the_post_thumbnail_url( get_the_ID(), 'full' ) ); ?>)" >
		<div class="container mt-4 mb-4">
			<div class="columns is-centered has-text-centered">
				<div class="column is-full is-two-thirds-widescreen">
					<?php if ( $year_made ) : ?>
						<div class="tag is-rounded is-gold is-medium">
							<?php echo esc_html( $year_made ); ?>
						</div>
					<?php endif; ?>
					<h1 class="title is-size-2 is-size-1-widescreen mt-2">
						<?php the_title(); ?>
					</h1>
					<h2 class="subtitle is-size-4 has-text-weight-light is-talic"><span class="has-text-weight-bold">Role:</span>  <?php echo esc_html( $project_role ); ?></h2>
					<?php if ( $enlaces && count( $enlaces ) > 0 ) : ?>
						<div class="buttons is-centered pt-4">
		
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
					<?php endif; ?>
	
				</div>
			</div>
		</div>
	</section>

</div>
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
