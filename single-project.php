<?php
/**
 * viewing a single project
 */
get_header();
$meta          = get_fields();
$contributions = $meta['contributions'];
$summary       = $meta['summary'];
$enlaces       = $meta['links'];
$video_trailer = isset( $meta['video_trailer'] ) ? $meta['video_trailer'] : null;
$video_preview = isset( $meta['preview']['preview_video'] ) ? $meta['preview']['preview_video'] : null;
$gif_preview   = isset( $meta['gif_preview'] ) ? $meta['gif_preview'] : null;
$tags          = get_the_tags();


?>
<section class="section ">
	<div class="container my-4 ">
		<div class="columns is-vcentered is-centered anim-bottom-top is-6">
			<div class="column is-half">
				<h1 class="title mb-0 is-size-2 is-size-1-desktop gradient-text has-text-weight-bold">
					<?php the_title(); ?>
				</h1>
				<div class="content has-text-justified mt-4">
					<?php echo $summary; ?>
				</div>
			
				<?php if ( $enlaces && count( $enlaces ) > 0 ) : ?>
					<p>Get it on:</p>
					<div class="buttons pt-2">
						<?php	foreach ( $enlaces as $enlace ) { ?>
							<a href="<?php echo esc_url( $enlace['url'] ); ?>" class="button is-ghost">
							<?php if ( str_contains( $enlace['url'], 'steam' ) ) : ?>
									<img src="https://jstriedinger.com/wp-content/uploads/2025/07/steam_btn.png" alt="steam download game" style="width:130px;" loading="lazy">
							<?php elseif ( str_contains( $enlace['url'], 'epicgames' ) ) : ?>
									<img src="https://jstriedinger.com/wp-content/uploads/2025/07/egs_btn.png" alt="epic games download game" style="width:130px;" loading="lazy">
							<?php else : ?>
									<img src="https://www.jstriedinger.com/wp-content/uploads/2024/07/itchbadge-min.png" alt="itch io play game" style="width:130px;" loading="lazy">
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
					<video autoplay="" loop="" muted="" src="<?php echo esc_url( $video_preview ); ?>" poster="<?php echo esc_url( get_the_post_thumbnail_url( get_the_ID(), 'full' ) ); ?>" loading="lazy" oncontextmenu="return false;"></video>
				<?php elseif ( ! is_null( $gif_preview ) && ! empty( $gif_preview ) ) : ?>
					<img src="<?php echo esc_url( $gif_preview ); ?>" alt="Jose Striedinger portfolio <?php echo the_title_attribute(); ?>" loading="lazy">
					<?php
					else :
						the_post_thumbnail();
				endif;
					?>
			</div>
		</div>
		
	</div>
</section>
<section class="section pt-2">
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
