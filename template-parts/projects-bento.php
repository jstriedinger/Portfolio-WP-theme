<?php
/**
 * Front-page "Selected work". Renders $projects (array of WP_Post `project` posts) as
 * readable rows: media on the left, text on the right (award badges, title, role and
 * engine, one-line description, "View project" button).
 *
 * Each row is a plain row (no box). The row is not a link. Hovering anywhere on it plays the
 * preview video on the media (shared .showcase-row / .showcase-media / .project-video classes,
 * see initVideoHover() in app.js). The media links to the project, unless the project has a
 * trailer (ACF `video_trailer`, a YouTube or Vimeo URL): then the media shows a play button on
 * hover that opens the trailer in the shared modal (template-parts/video-modal.php, initVideoModal()
 * in app.js) and the "View project" button is the way to the page.
 * On mobile the media stacks above the text.
 * Used by [projects_grid].
 */
?>
<div class="projects-rows">
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
		$tech_labels = array_filter( array_column( $preview_tech, 'label' ) );

		// Award / grant badges (ACF gallery), shown above the title.
		$accolades = $project_meta['preview']['preview_accolades'] ?? [];
		if ( ! is_array( $accolades ) ) {
			$accolades = [];
		}

		// Trailer: the oEmbed field's raw URL (third argument false skips the iframe formatting).
		$trailer_url = get_field( 'video_trailer', $project->ID, false );
		$trailer     = $trailer_url ? jrsp_parse_video_url( $trailer_url ) : null;
		$embed_url   = $trailer['embed_url'] ?? '';

		$role_line  = implode( ' · ', array_filter( [ $preview_role, implode( ', ', $tech_labels ) ] ) );
		$media_tag  = ( $without_link || $embed_url ) ? 'div' : 'a';
		$media_attr = ( $without_link || $embed_url ) ? '' : ' href="' . esc_url( $external_link ) . '" aria-label="' . esc_attr( $project->post_title ) . '"';
	?>
	<article class="showcase-row py-5 <?= $preview_video ? 'has-video' : '' ?>">
		<div class="columns is-vcentered is-variable is-8">
			<div class="column is-6-tablet">
				<<?= $media_tag ?> class="showcase-media-link"<?= $media_attr ?>>
					<div class="showcase-media is-aspect-ratio-16by9">
						<img src="<?= esc_url( $thumbnail ) ?>" alt="<?= esc_attr( $project->post_title ) ?>" loading="lazy">
						<?php if ( $preview_video ) : ?>
						<video class="project-video" muted loop playsinline preload="none" poster="<?= esc_url( $thumbnail ) ?>">
							<source src="<?= esc_url( $preview_video ) ?>" type="video/mp4">
						</video>
						<?php endif; ?>
						<?php if ( $embed_url ) : ?>
						<button type="button" class="trailer-play is-overlay is-flex is-align-items-center is-justify-content-center" data-embed="<?= esc_url( $embed_url ) ?>" data-title="<?= esc_attr( $project->post_title ) ?>" data-role="<?= esc_attr( $role_line ) ?>"<?= $without_link ? '' : ' data-url="' . esc_url( $external_link ) . '"' ?> aria-label="Play <?= esc_attr( $project->post_title ) ?> trailer">
							<span class="icon is-large has-text-link"><i class="fa-solid fa-circle-play fa-4x" aria-hidden="true"></i></span>
						</button>
						<?php endif; ?>
					</div>
				</<?= $media_tag ?>>
			</div>

			<div class="column is-6-tablet">
				<?php if ( ! empty( $accolades ) ) : ?>
				<div class="showcase-accolades is-flex is-align-items-center is-flex-wrap-wrap is-gap-2 mb-3">
					<?php foreach ( $accolades as $accolade ) : ?>
					<img src="<?= esc_url( $accolade['url'] ) ?>" alt="<?= esc_attr( $accolade['alt'] ?: $accolade['title'] ) ?>" width="<?= (int) $accolade['width'] ?>" height="<?= (int) $accolade['height'] ?>" data-no-lazy="1">
					<?php endforeach; ?>
				</div>
				<?php endif; ?>
				<h3 class="title is-3 canela-font mb-2"><?= esc_html( $project->post_title ) ?></h3>
				<?php if ( $preview_role || ! empty( $preview_tech ) ) : ?>
				<p class="role-text is-size-6 mb-3 is-flex is-align-items-center is-flex-wrap-wrap is-gap-2">
					<?php if ( $preview_role ) : ?><span><?= esc_html( $preview_role ) ?></span><?php endif; ?>
					<?php foreach ( $preview_tech as $tech ) : ?>
						<?php if ( ! empty( $tech['value'] ) ) : ?>
						<i class="devicon-<?= esc_attr( $tech['value'] ) ?> is-size-5" role="img" title="<?= esc_attr( $tech['label'] ?? '' ) ?>" aria-label="<?= esc_attr( $tech['label'] ?? '' ) ?>"></i>
						<?php endif; ?>
					<?php endforeach; ?>
				</p>
				<?php endif; ?>
				<?php if ( $preview_desc ) : ?>
				<p class="showcase-desc is-size-6 mb-4"><?= nl2br( wp_kses_post( $preview_desc ) ) ?></p>
				<?php endif; ?>
				<?php if ( ! $without_link ) : ?>
				<a class="button is-link is-rounded" href="<?= esc_url( $external_link ) ?>">
					<span>View project</span>
					<span class="icon"><i class="fa-solid fa-arrow-right" aria-hidden="true"></i></span>
				</a>
				<?php endif; ?>
			</div>
		</div>
	</article>
	<?php endforeach; ?>
</div>
