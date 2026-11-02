<?php
/**
 * Front-page "Selected work" bento. Renders $projects (array of WP_Post `project`
 * posts) as full-bleed cards in a Bulma 12-column fixed grid.
 *
 * Rows alternate wide/narrow: 7+5, then 5+7, and so on. A lone card on the last
 * row spans the full width. On mobile every card is full width.
 *
 * Cards keep the shared .showcase-card / .showcase-media / .project-video classes so
 * initVideoHover() in app.js plays the preview video while the card is hovered.
 * Used by [projects_grid].
 */

$project_count = count( $projects );
?>
<div class="fixed-grid has-12-cols projects-bento">
	<div class="grid">
	<?php foreach ( $projects as $index => $project ) :
		$project_meta  = get_fields( $project->ID );
		$thumbnail     = get_the_post_thumbnail_url( $project->ID, 'full' );
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

		// Award / grant badges (ACF gallery), shown above the title.
		$accolades = $project_meta['preview']['preview_accolades'] ?? [];
		if ( ! is_array( $accolades ) ) {
			$accolades = [];
		}

		// Even rows: wide card first. Odd rows: wide card second.
		$is_row_start = 0 === $index % 2;
		$is_even_row  = 0 === intdiv( $index, 2 ) % 2;
		if ( $is_row_start && $index === $project_count - 1 ) {
			$span = 12;
		} else {
			$span = ( $is_even_row === $is_row_start ) ? 7 : 5;
		}

		$tag = $without_link ? 'div' : 'a';
	?>
		<div class="cell is-col-span-12-mobile is-col-span-<?= (int) $span ?>-tablet">
			<<?= $tag ?> class="showcase-card is-block is-relative is-clipped <?= $preview_video ? 'has-video' : '' ?>"<?= $without_link ? '' : ' href="' . esc_url( $external_link ) . '"' ?>>
				<div class="showcase-media is-overlay">
					<img src="<?= esc_url( $thumbnail ) ?>" alt="<?= esc_attr( $project->post_title ) ?>" loading="lazy">
					<?php if ( $preview_video ) : ?>
					<video class="project-video" muted loop playsinline preload="none" poster="<?= esc_url( $thumbnail ) ?>">
						<source src="<?= esc_url( $preview_video ) ?>" type="video/mp4">
					</video>
					<?php endif; ?>
				</div>

				<div class="is-overlay is-flex is-flex-direction-column is-justify-content-flex-end p-5 p-6-tablet">
					<?php if ( ! empty( $accolades ) ) : ?>
					<div class="showcase-accolades is-flex is-align-items-center is-flex-wrap-wrap is-gap-2 mb-4">
						<?php foreach ( $accolades as $accolade ) : ?>
						<img src="<?= esc_url( $accolade['url'] ) ?>" alt="<?= esc_attr( $accolade['alt'] ?: $accolade['title'] ) ?>" loading="lazy">
						<?php endforeach; ?>
					</div>
					<?php endif; ?>
					<h3 class="title is-3 canela-font is-flex is-align-items-center mb-2">
						<span><?= esc_html( $project->post_title ) ?></span>
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
					<p class="role-text is-size-7-mobile is-size-6 mb-3"><?= esc_html( $preview_role ) ?></p>
					<?php endif; ?>
					<?php if ( $preview_desc ) : ?>
					<p class="showcase-desc is-size-6"><?= nl2br( wp_kses_post( $preview_desc ) ) ?></p>
					<?php endif; ?>
				</div>
			</<?= $tag ?>>
		</div>
	<?php endforeach; ?>
	</div>
</div>
