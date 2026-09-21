<?php
/**
 * Renders $projects (array of WP_Post `project` posts) as a grid of vertical
 * cards — media on top, title/role/summary below, several per row.
 *
 * Same visual language as template-parts/projects-showcase.php (shared CSS
 * classes: .showcase-media, .showcase-title, .showcase-tech, .role-text,
 * .showcase-desc) — only the orientation differs. Used by [projects_grid_small].
 */
?>
<div class="projects-grid-small columns is-multiline">
	<?php foreach ( $projects as $project ) :
		$project_meta = get_fields( $project->ID );
		$thumbnail    = get_the_post_thumbnail_url( $project->ID, 'large' );

		if ( ! $thumbnail ) {
			continue;
		}

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

		// Video class lives on the whole card, not just the media box — hovering
		// anywhere in the card (including the text) plays it.
		$video_class = '';
		if ( $preview_video ) {
			$video_class = $preview_only_video ? 'only-video' : 'has-video';
		}
	?>
	<div class="column is-one-third-tablet">
		<div class="showcase-card <?= esc_attr( $video_class ) ?>" <?= $preview_video ? 'data-video="' . esc_url( $preview_video ) . '"' : '' ?>>
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
			<div class="showcase-card-content content">
				<h3 class="title is-4 canela-font showcase-title mb-1 is-flex">
					<?php if ( ! $without_link ) : ?>
					<a href="<?= esc_url( $external_link ) ?>"><?= esc_html( $project->post_title ) ?></a>
					<?php else : ?>
					<?= esc_html( $project->post_title ) ?>
					<?php endif; ?>
					<?php if ( ! empty( $preview_tech ) ) : ?>
					<span class="showcase-tech">
						<?php foreach ( $preview_tech as $tech ) : ?>
							<?php if ( ! empty( $tech['value'] ) ) : ?>
							<i class="devicon-<?= esc_attr( $tech['value'] ) ?>" title="<?= esc_attr( $tech['label'] ) ?>"></i>
							<?php endif; ?>
						<?php endforeach; ?>
					</span>
					<?php endif; ?>
				</h3>
				<?php if ( $preview_role ) : ?>
				<p class="role-text subtitle is-6"><?= esc_html( $preview_role ) ?></p>
				<?php endif; ?>
				<?php if ( $preview_desc ) : ?>
				<p class="showcase-desc"><?= nl2br( esc_html( $preview_desc ) ) ?></p>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<?php endforeach; ?>
</div>
