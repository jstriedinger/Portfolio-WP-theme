<?php
/**
 * viewing a single project
 */
get_header();
$meta         = get_fields();
$desc         = $meta['desc'];
$enlaces      = $meta['links'];
$project_role = $meta['role'];
$project_date = $meta['year'];
$tags         = get_the_tags();
$with_layer   = isset( $meta['dark_cover_layer'] ) ? $meta['dark_cover_layer'] : true;


?>
<section class="section colored-black anim-bottom-top mb-5" id="top-section">
	<div class="container mb-0">
		<div class="columns is-centered has-text-centered is-variable is-8">
			<div class="column is-two-thirds content">
				<h1 class="title is-size-3 has-text-weight-bold ">José Rafael Striedinger</h1>
				<div class="is-flex is-align-items-center is-justify-content-center has-text-weight-light" style="gap:2rem;">
					<a href="<?php echo esc_url( home_url() . '#projects' ); ?>">Projects</a>
					<a href="<?php echo esc_url( home_url() . '/about' ); ?>">About me</a>
				</div>
			</div>
		</div>
	</div>
</section>
<div class="container">
	<section class="section colored-black with-image anim-bottom-top <?php echo $with_layer ? 'with-layer' : ''; ?>" style="background-image: url(<?php echo esc_url( get_the_post_thumbnail_url( get_the_ID(), 'full' ) ); ?>)" >
		<div class="container mt-4 mb-4">
			<div class="columns is-centered has-text-centered">
				<div class="column is-full is-two-thirds-widescreen">
					<div class="tags is-centered">
					<?php
					foreach ( $tags as $key => $tag ) {
						?>
						<div class="tag is-rounded is-warning is-light has-text-weight-bold">
							<?php echo $tag->name; ?>
						</div>
						<?php
					}
					?>
					</div>
					<h1 class="title is-size-2 is-size-1-widescreen mt-2">
						<?php the_title(); ?>
					</h1>
					<div class="columns has-text-centered is-centered mt-4">
						<div class="column is-5">
							<p class="is-size-3 title mb-0">Date</p>
							<p class="is-size-5"><?php echo esc_html( $project_date ); ?></p>
						</div>
						<div class="column is-5">
							<p class="is-size-3 title  mb-0">Role</p>
								<p class="is-size-5"><?php echo esc_html( $project_role ); ?></p>
						</div>
					</div>
					<?php if ( $enlaces && count( $enlaces ) > 0 ) : ?>
						<div class="buttons is-centered pt-4">
		
							<?php	foreach ( $enlaces as $enlace ) { ?>
									<a href="<?php echo esc_url( $enlace['url'] ); ?>" class="button is-gold is-medium">
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
