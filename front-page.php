<?php
/**
 * The main template file
 */

get_header();
$meta             = get_fields();
$args             = array(
	'post_type'      => 'project',
	'posts_per_page' => -1,
	'post_status' => 'publish'
);
$projects         = get_posts( $args );
$bio              = $meta['bio'];
$featured_img_url = get_the_post_thumbnail_url( get_the_ID(), 'full' );
$video			  		= $meta['reel_url'];
$preview_video	  = $meta['reel_preview'];
$cv	  = $meta['cv'];
?>

<section class="section  anim-bottom-top" id="top-section" style="--hero-bg-image: url('<?php echo esc_url( $featured_img_url ); ?>');">
	
	<?php $video_info = $video ? jrsp_parse_video_url( $video ) : null; ?>
	<div class="container is-fullhd">
		<div class="columns is-variable is-multiline is-vcentered <?= $video_info ? '' : 'is-centered' ?>">
			<div class="column is-three-fifths-desktop <?= $video_info ? '' : 'has-text-centered' ?>">
				<h2 class="title is-size-3 mb-0">Hello!👋 I'm</h2>
				<h1 class="title is-size-0 gradient-text canela-font">Jose Striedinger</h1>
				<h2 class="subtitle is-size-2 canela-font">Game engineer and designer in L.A</h2>
				<br>
				<?php echo $bio; ?>
				<?php if ( $cv ): ?>
				<div class="mt-4">
					<a href="<?= esc_url( $cv ) ?>" class="button" target="_blank" rel="noopener">
						<span>Check my CV</span>
						<span class="icon">
							<i class="fa-solid fa-arrow-up-right-from-square"></i>
						</span>
					</a>
					<?php endif; ?>
				
			</div>
			<?php if ( $video_info ): ?>
			<div class="column">
				<div class="reel-box" data-embed="<?= esc_url( $video_info['embed_url'] ) ?>">
					<?php if ( $preview_video ): ?>
					<video class="reel-video" muted loop playsinline preload="auto">
						<source src="<?= esc_url( $preview_video ) ?>" type="video/mp4">
					</video>
					<?php else: ?>
					<div class="reel-thumbnail" style="background-image: url('<?= esc_url( $video_info['thumbnail'] ) ?>')"></div>
					<?php endif; ?>
					<div class="reel-overlay">
						<i class="fas fa-play"></i>
						<span>Watch my reel</span>
					</div>
				</div>
			</div>
			<?php endif; ?>
		</div>
	</div>
</section>

<section class="section" id="projects-section">
	<div class="container is-fluid">
		<div class="columns ">
			<div class="column is-full content">
				<?php the_content(); ?>
			</div>
		</div>
	</div>
</section>
<?php if ( $video_info ): ?>
<div class="modal" id="reel-modal">
	<div class="modal-background"></div>
	<div class="modal-content">
		<div class="embed-container">
			<iframe id="reel-iframe" src="" frameborder="0" allowfullscreen allow="autoplay"></iframe>
		</div>
	</div>
	<button class="modal-close is-large" aria-label="close"></button>
</div>
<?php endif; ?>
<?php get_footer(); ?>
							