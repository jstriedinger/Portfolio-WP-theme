<?php
/**
 * Renders $project (a single WP_Post `project` post) as one row — same
 * markup/classes as template-parts/projects-showcase.php's row, just for a
 * single project instead of a foreach over $projects. Kept as its own file
 * (used exclusively by [project_special]) so it can diverge later without
 * touching the shared showcase template.
 */
$project_meta = get_fields( $project->ID );
$thumbnail    = get_the_post_thumbnail_url( $project->ID, 'large' );

$preview_video      = $project_meta['preview']['preview_video'] ?? null;
$preview_role       = $project_meta['preview']['preview_role'] ?? null;
$preview_desc       = $project_meta['preview']['preview_desc'] ?? null;
$preview_only_video = $project_meta['preview']['video_only'] ?? false;
$external_link      = ! empty( $project_meta['preview']['external_link'] )
					   ? $project_meta['preview']['external_link']
					   : get_permalink( $project->ID );
$without_link       = $project_meta['preview']['without_link'] ?? false;

$preview_tech = $project_meta['preview']['preview_tech'] ?? [];
if ( ! is_array( $preview_tech ) ) {
	$preview_tech = ! empty( $preview_tech ) ? [ $preview_tech ] : [];
}

// Video class lives on the whole row, not just the media box — hovering
// anywhere in the row (including the text side) plays it.
$video_class = '';
if ( $preview_video ) {
	$video_class = $preview_only_video ? 'only-video' : 'has-video';
}
?>
<div class="projects-showcase py-6">
	<div class="showcase-row project-special-row columns is-variable <?= esc_attr( $video_class ) ?>" <?= $preview_video ? 'data-video="' . esc_url( $preview_video ) . '"' : '' ?>>
		<div class="column is-three-fifths">
			<?php if ( ! $without_link ) : ?><a href="<?= esc_url( $external_link ) ?>" class="showcase-media-link"><?php endif; ?>
			<div class="showcase-media is-aspect-ratio-16by9">
				<img src="<?= esc_url( $thumbnail ) ?>" alt="<?= esc_attr( $project->post_title ) ?>" loading="lazy">
				<?php if ( $preview_video ) : ?>
				<video class="project-video" muted loop preload="none" poster="<?= esc_url( $thumbnail ) ?>">
					<source src="<?= esc_url( $preview_video ) ?>" type="video/mp4">
				</video>
				<?php endif; ?>
			</div>
			<?php if ( ! $without_link ) : ?></a><?php endif; ?>
		</div>
		<div class="column showcase-content ">
			<div class="content py-5 is-flex is-justify-content-center">
				<h3 class="title is-3 is-size-2-fullhd canela-font showcase-title is-flex is-align-items-center mb-4">
					<?php if ( ! $without_link ) : ?>
					<a href="<?= esc_url( $external_link ) ?>"><?= esc_html( $project->post_title ) ?></a>
					<?php else : ?>
					<?= esc_html( $project->post_title ) ?>
					<?php endif; ?>
				</h3>
				<?php if ( $preview_role ) : ?>
					<p class="role-text subtitle is-4"><?= esc_html( $preview_role ) ?></p>
					<?php endif; ?>
					<?php if ( $preview_desc ) : ?>
						<p class="showcase-desc"><?= nl2br( esc_html( $preview_desc ) ) ?></p>
					<?php endif; ?>
					<img src="<?= esc_url( get_template_directory_uri() . '/assets/images/megagrants_black.png' ) ?>" alt="Epic MegaGrants recipient" class="project-special-badge" loading="lazy">
			</div>
		</div>
	</div>
</div>
