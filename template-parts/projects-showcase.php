<?php
$main_projects    = [];
$regular_projects = [];

foreach ( $projects as $project ) {
	$project_meta = get_fields( $project->ID );
	$thumbnail    = get_the_post_thumbnail_url( $project->ID, 'large' );
	if ( ! $thumbnail ) continue;

	$tags     = wp_get_post_tags( $project->ID, array( 'fields' => 'slugs' ) );
	$is_main  = in_array( 'main', $tags, true ) || in_array( 'main-inverse', $tags, true );

	$entry = compact( 'project', 'project_meta', 'thumbnail', 'tags', 'is_main' );

	if ( $is_main ) {
		$main_projects[] = $entry;
	} else {
		$regular_projects[] = $entry;
	}
}

$render_card = function( $entry, $size_class ) {
	$project      = $entry['project'];
	$project_meta = $entry['project_meta'];
	$thumbnail    = $entry['thumbnail'];
	$tags         = $entry['tags'];
	$is_main      = $entry['is_main'];

	$preview_video = $project_meta['preview']['preview_video'] ?? null;
	$preview_role  = $project_meta['preview']['preview_role']  ?? null;
	$external_link = ! empty( $project_meta['preview']['external_link'] )
					 ? $project_meta['preview']['external_link']
					 : get_permalink( $project->ID );
	$without_link  = $project_meta['preview']['without_link'] ?? false;
	$preview_desc  = $project_meta['preview']['preview_desc'] ?? null;
	$is_released   = in_array( 'released', $tags, true );

	$preview_tech = $project_meta['preview']['preview_tech'] ?? [];
	if ( ! is_array( $preview_tech ) ) {
		$preview_tech = ! empty( $preview_tech ) ? [ $preview_tech ] : [];
	}

	$video_class = '';
	if ( $preview_video ) {
		$video_class = $is_main ? 'only-video' : 'has-video';
	}

	$card_class = $is_main ? 'showcase-card--main' : 'showcase-card--regular';
	?>
	<div class="column <?= esc_attr( $size_class ) ?>">
		<div class="showcase-card <?= $card_class ?>">
			<?php if ( ! $without_link ) : ?><a href="<?= esc_url( $external_link ) ?>"><?php endif; ?>
			<div class="showcase-media-box <?= esc_attr( $video_class ) ?>" <?= $preview_video ? 'data-video="' . esc_url( $preview_video ) . '"' : '' ?>>
				<img src="<?= esc_url( $thumbnail ) ?>" alt="<?= esc_attr( $project->post_title ) ?>" loading="lazy">
				<?php if ( $preview_video ) : ?>
				<video class="project-video" muted loop preload="none" poster="<?= esc_url( $thumbnail ) ?>">
					<source src="<?= esc_url( $preview_video ) ?>" type="video/mp4">
				</video>
				<?php endif; ?>
				<?php if ( $is_released ) : ?>
				<span class="tag is-released">Released</span>
				<?php endif; ?>
			</div>
			<?php if ( ! $without_link ) : ?></a><?php endif; ?>
			<div class="showcase-card__info content">
				<div class="is-flex is-flex-wrap-wrap is-align-items-center">
					<h3 class="title <?= $is_main ? 'is-size-2 is-size-1-fullhd' : 'is-4' ?> canela-font mb-1 gradient-text mt-0"><?= esc_html( $project->post_title ) ?></h3>
					<?php if ( ! empty( $preview_tech ) ) : ?>
					<div class="is-flex is-flex-wrap-wrap ml-4" style="gap: 0.25rem;">
						<?php foreach ( $preview_tech as $tech ) : ?>
							<?php if ( ! empty( $tech ) ) : ?>
							<i class="ml-2 is-size-4 devicon-<?= esc_attr( $tech['value'] ) ?>" title="<?= esc_attr( $tech['label'] ) ?>"></i>
							<?php if ( $is_main ) : ?><?= esc_html( $tech['label'] ) ?><?php endif; ?>
							<?php endif; ?>
						<?php endforeach; ?>
					</div>
					<?php endif; ?>
				</div>
				<?php if ( $preview_role ) : ?>
				<p class="role-text <?= ! $is_main ? 'subtitle is-size-6' : '' ?>"><?= esc_html( $preview_role ) ?></p>
				<?php endif; ?>
				<?php if ( $preview_desc ) : ?>
				<div><?= wp_kses_post( $preview_desc ) ?></div>
				<?php endif; ?>
				<?php if ( ! $without_link && $is_main ) : ?>
				<a href="<?= esc_url( $external_link ) ?>" class="button mt-5">Read More</a>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<?php
};
?>

<?php if ( ! empty( $main_projects ) ) : ?>
<div class="projects-showcase columns is-multiline row-gap-4 is-centered">
	<?php foreach ( $main_projects as $entry ) : ?>
		<?php $render_card( $entry, 'is-half-desktop' ); ?>
	<?php endforeach; ?>
</div>
<?php endif; ?>

<?php if ( ! empty( $regular_projects ) ) : ?>
<div class="projects-showcase columns is-multiline row-gap-4 is-centered mt-6">
	<?php foreach ( $regular_projects as $entry ) : ?>
		<?php $render_card( $entry, 'is-full-mobile is-one-third-desktop' ); ?>
	<?php endforeach; ?>
</div>
<?php endif; ?>

<?php wp_reset_postdata(); ?>
