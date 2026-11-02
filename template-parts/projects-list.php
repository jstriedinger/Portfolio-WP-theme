<?php
/**
 * Front-page "More projects". Renders $projects (array of WP_Post `project` posts)
 * as small vertical cards side by side: thumbnail, title + engine tag, role · summary.
 *
 * Bulma .columns.is-multiline.is-centered: up to 3 per row on desktop (2 on tablet,
 * 1 on mobile), and a short last row stays centered.
 *
 * Cards keep the shared .showcase-card / .showcase-media / .project-video classes so
 * initVideoHover() in app.js plays the thumbnail's preview video on hover.
 * Used by [projects_grid_small].
 */
?>
<div class="projects-list pt-5 pb-6">
	<div class="columns is-multiline is-centered is-variable is-5">
	<?php foreach ( $projects as $project ) :
		$project_meta  = get_fields( $project->ID );
		$thumbnail     = get_the_post_thumbnail_url( $project->ID, 'large' );
		$preview_video = $project_meta['preview']['preview_video'] ?? null;
		$preview_role  = $project_meta['preview']['preview_role'] ?? null;
		$preview_desc  = $project_meta['preview']['preview_desc'] ?? null;
		$without_link  = $project_meta['preview']['without_link'] ?? false;
		$external_link = ! empty( $project_meta['preview']['external_link'] )
						 ? $project_meta['preview']['external_link']
						 : get_permalink( $project->ID );

		$preview_tech = $project_meta['preview']['preview_tech'] ?? [];
		if ( ! is_array( $preview_tech ) ) {
			$preview_tech = ! empty( $preview_tech ) ? [ $preview_tech ] : [];
		}

		$summary = implode( ' · ', array_filter( [ esc_html( $preview_role ), wp_kses_post( $preview_desc ) ] ) );
		$tag     = $without_link ? 'div' : 'a';
	?>
		<div class="column is-6-tablet is-4-desktop">
			<<?= $tag ?> class="showcase-card is-block <?= $preview_video ? 'has-video' : '' ?>"<?= $without_link ? '' : ' href="' . esc_url( $external_link ) . '"' ?>>
				<div class="showcase-media is-aspect-ratio-16by9 mb-4">
					<img src="<?= esc_url( $thumbnail ) ?>" alt="<?= esc_attr( $project->post_title ) ?>" loading="lazy">
					<?php if ( $preview_video ) : ?>
					<video class="project-video" muted loop playsinline preload="none" poster="<?= esc_url( $thumbnail ) ?>">
						<source src="<?= esc_url( $preview_video ) ?>" type="video/mp4">
					</video>
					<?php endif; ?>
				</div>
				<p class="title is-5 canela-font is-flex is-align-items-center is-flex-wrap-wrap mb-2">
					<span><?= esc_html( $project->post_title ) ?></span>
					<?php foreach ( $preview_tech as $tech ) : ?>
						<?php if ( ! empty( $tech['label'] ) ) : ?>
						<span class="tag is-rounded ml-2"><?= esc_html( $tech['label'] ) ?></span>
						<?php endif; ?>
					<?php endforeach; ?>
				</p>
				<?php if ( $summary ) : ?>
				<p class="showcase-desc is-size-7"><?= $summary ?></p>
				<?php endif; ?>
			</<?= $tag ?>>
		</div>
	<?php endforeach; ?>
	</div>
</div>
