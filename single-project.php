<?php
/**
 * viewing a single project
 */
get_header();
$meta          = get_fields();
$desc          = $meta['desc'];
$enlaces       = $meta['links'];
$project_date  = $meta['year'];
$video_trailer = isset( $meta['video_trailer'] ) ? $meta['video_trailer'] : null;
$video_preview = isset( $meta['video_preview'] ) ? $meta['video_preview'] : null;
$gif_preview   = isset( $meta['gif_preview'] ) ? $meta['gif_preview'] : null;
$tags          = get_the_tags();


?>
<section class="section colored-black mb-5" >
	<div class="container mb-0">
		<div class="columns is-centered has-text-centered is-variable is-8">
			<div class="column is-two-thirds is-paddingless">
				<h1 class="title is-size-3 has-text-weight-bold mb-2">José Rafael Striedinger</h1>
				<div class="is-flex is-align-items-center is-justify-content-center has-text-weight-light" style="gap:2rem;">
					<a href="<?php echo esc_url( home_url() . '#projects' ); ?>" class="is-gold">Projects</a>
					<a href="<?php echo esc_url( home_url() . '/about' ); ?>" class="is-gold">About me</a>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="section anim-bottom-top">
	<div class="container  mt-4">
		<div class="columns is-vcentered is-centered">
			<div class="column is-7">
				<div class="level mb-2" >
					<div class="level-left" style="gap: 2rem">
						<h1 class="title mb-0 is-size-2 is-size-1-desktop">
							<?php the_title(); ?>
							
						</h1>
						
					</div>
					
				</div>
				<div class="tags are-large mb-4">
					<?php
					foreach ( $tags as $key => $tag ) {
						?>
						<div class="tag is-rounded is-link is-light ">
							<?php echo $tag->name; ?>
						</div>
						<?php
					}
					?>
					<div class="tag is-rounded is-link is-light ">
						<?php echo esc_html( $project_date ); ?>
					</div>
				</div>
				<div class="content">
					<?php esc_html_e( $desc ); ?>
				</div>
			
				<?php if ( $enlaces && count( $enlaces ) > 0 ) : ?>
					<div class="buttons pt-2">
	
						<?php	foreach ( $enlaces as $enlace ) { ?>
							<a href="<?php echo esc_url( $enlace['url'] ); ?>" class="button is-ghost">
							<?php if ( str_contains( $enlace['url'], 'steam' ) ) : ?>
									<img src="https://www.jstriedinger.com/wp-content/uploads/2024/08/steam-btn.png" alt="steam download game" style="width:120px;">
							<?php else : ?>
									<img src="https://www.jstriedinger.com/wp-content/uploads/2024/07/itchbadge-min.png" alt="itch io play game" style="width:120px;">
							<?php endif; ?>
							</a>
						<?php	} ?>
					</div>
				<?php endif; ?>
			</div>
			<div class="column project-feature-thing">
				<?php if ( ! is_null( $video_trailer ) && ! empty( $video_trailer ) ) : ?>
					<div class="embed-container">
						<?php echo $video_trailer; ?>
					</div>
				<?php elseif ( ! is_null( $video_preview ) && ! empty( $video_preview ) ) : ?>
					<video autoplay="" loop="" muted="" src="<?php echo esc_url( $video_preview ); ?>" poster="<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(),'full'));?>"></video>
				<?php elseif ( ! is_null( $gif_preview ) && ! empty( $gif_preview ) ) : ?>
					<img src="<?php echo esc_url( $gif_preview ); ?>" alt="Jose Striedinger portfolio <?php echo the_title_attribute(); ?>">
				<?php
					else :
						the_post_thumbnail();
				endif;
					?>
			</div>
		</div>
		<hr>
	</div>
	
</section>
<section class="section pt-0">
	<div class="container is-max-widescreen">
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
