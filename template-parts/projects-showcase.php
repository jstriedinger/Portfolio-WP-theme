<?php
/**
 * Renders $projects (array of WP_Post `project` posts) as a stack of
 * full-width rows — media on the left, title/role/summary on the right.
 *
 * Shared by [projects_grid] (front page) and [projects_showcase] (about-me);
 * both shortcodes just query $projects and include this file.
 */
?>
<div class="projects-showcase">
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

		// Video class lives on the whole row, not just the media box — hovering
		// anywhere in the row (including the text side) plays it.
		$video_class = '';
		if ( $preview_video ) {
			$video_class = $preview_only_video ? 'only-video' : 'has-video';
		}
	?>
	<div class="showcase-row columns is-variable is-8 <?= esc_attr( $video_class ) ?>" <?= $preview_video ? 'data-video="' . esc_url( $preview_video ) . '"' : '' ?>>
		<div class="column is-half">
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
		<div class="column showcase-content content ">
			<div class="content py-5">
				<h3 class="title is-3 is-size-2-fullhd canela-font showcase-title is-flex is-align-items-center mb-4">
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
				<p class="role-text subtitle is-4"><?= esc_html( $preview_role ) ?></p>
				<?php endif; ?>
				<?php if ( $preview_desc ) : ?>
				<p class="showcase-desc"><?= nl2br( esc_html( $preview_desc ) ) ?></p>
				<?php endif; ?>
				<?php if ( ! empty( $project_meta['links'] ) ) : ?>
				<div class="buttons showcase-links mt-4">
					<?php foreach ( $project_meta['links'] as $link ) : ?>
						<?php if ( empty( $link['url'] ) ) continue; ?>
						<a href="<?= esc_url( $link['url'] ) ?>" class="button is-ghost">
							<?php if ( str_contains( $link['url'], 'steam' ) ) : ?>
							<img src="https://jstriedinger.com/wp-content/uploads/2025/07/steam_btn.png" alt="Play on Steam" loading="lazy">
							<?php elseif ( str_contains( $link['url'], 'epicgames' ) ) : ?>
							<img src="https://jstriedinger.com/wp-content/uploads/2025/07/egs_btn.png" alt="Play on Epic Games" loading="lazy">
							<?php else : ?>
							<img src="https://www.jstriedinger.com/wp-content/uploads/2024/07/itchbadge-min.png" alt="Play on itch.io" loading="lazy">
							<?php endif; ?>
						</a>
					<?php endforeach; ?>
				</div>
				<?php endif; ?>

			</div>
		</div>
	</div>
	<?php endforeach; ?>
</div>
