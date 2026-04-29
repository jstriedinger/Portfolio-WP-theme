<div class="projects-showcase columns is-multiline row-gap-4">
	<?php foreach ( $projects as $project ) :
		$project_meta  = get_fields( $project->ID );
		$preview_video = $project_meta['preview']['preview_video'] ?? null;
		$preview_role  = $project_meta['preview']['preview_role']  ?? null;
		$external_link = ! empty( $project_meta['preview']['external_link'] )
						 ? $project_meta['preview']['external_link']
						 : get_permalink( $project->ID );
		$without_link  = $project_meta['preview']['without_link'] ?? false;
		$preview_desc       = $project_meta['preview']['preview_desc'] ?? null;
		$thumbnail     = get_the_post_thumbnail_url( $project->ID, 'large' );

		if ( ! $thumbnail ) continue;

		$tags            = wp_get_post_tags( $project->ID, array( 'fields' => 'slugs' ) );
		$is_main         = in_array( 'main', $tags, true );
		$is_main_inverse = in_array( 'main-inverse', $tags, true );

		$video_class = '';
		if ( $preview_video ) {
			$video_class = ( $is_main || $is_main_inverse ) ? 'only-video' : 'has-video';
		}
		
		$preview_tech = isset($project_meta['preview']['preview_tech']) ? $project_meta['preview']['preview_tech'] : array();
		if (!is_array($preview_tech)) {
				$preview_tech = !empty($preview_tech) ? array($preview_tech) : array();
			}
	?>
	<?php if ( $is_main || $is_main_inverse ) : ?>
	<div class="column is-full mt-5">
		<div class="showcase-card showcase-card--main <?= $is_main_inverse ? 'showcase-card--main-inverse' : '' ?> columns is-8 is-vcentered is-desktop">
			<div class="column is-half-desktop showcase-card__media">
				<div class="showcase-media-box <?= esc_attr( $video_class ) ?>" <?= $preview_video ? 'data-video="' . esc_url( $preview_video ) . '"' : '' ?>>
					<img src="<?= esc_url( $thumbnail ) ?>" alt="<?= esc_attr( $project->post_title ) ?>" loading="lazy">
					<?php if ( $preview_video ) : ?>
					<video class="project-video" muted loop preload="none" poster="<?= esc_url( $thumbnail ) ?>">
						<source src="<?= esc_url( $preview_video ) ?>" type="video/mp4">
					</video>
					<?php endif; ?>
				</div>
			</div>
			<div class="column is-half-desktop showcase-card__info content">
				<h5 class="subtitle is-size-6 mb-0"> 
								<?php if (!empty($preview_tech)) : ?>
									<?php foreach ($preview_tech as $tech) : ?>
										<?php if (!empty($tech)) : ?>
											<i class=" is-size-4 devicon-<?php echo esc_attr($tech["value"]); ?>"></i>
											<?php echo esc_html($tech["label"]); ?>
										<?php endif; ?>
									<?php endforeach; ?>
								<?php endif; ?></h5>	
				<h3 class="title is-size-2 is-size-1-fullhd canela-font gradient-text mt-0">
						<?php if ( ! $without_link ) : ?><a href="<?= esc_url( $external_link ) ?>"><?php endif; ?>
						<?= esc_html( $project->post_title ) ?>
						

						<?php if ( ! $without_link ) : ?>
						</a><?php endif; ?>
					</h3>
				<?php if ( $preview_role ) : ?>
				<p class="role-text"><?= esc_html( $preview_role ) ?></p>
				<?php endif; ?>
				<?php if ( $preview_desc ) : ?>
				<div><?= wp_kses_post( $preview_desc ) ?></div>
				<?php endif; ?>
				<?php if ( ! $without_link ) : ?><a href="<?= esc_url( $external_link ) ?>" class="button mt-5"><?php endif; ?>
					Read more
					<?php if ( ! $without_link ) : ?></a><?php endif; ?>
			</div>
		</div>
	</div>
	<?php elseif ( ! $is_main && ! $is_main_inverse ) : ?>
	<div class="column is-full-mobile is-half-desktop is-one-third-widescreen">
		<div class="showcase-card showcase-card--regular">
			<?php if ( ! $without_link ) : ?><a href="<?= esc_url( $external_link ) ?>"><?php endif; ?>
			<div class="showcase-media-box <?= esc_attr( $video_class ) ?>" <?= $preview_video ? 'data-video="' . esc_url( $preview_video ) . '"' : '' ?>>
				<img src="<?= esc_url( $thumbnail ) ?>" alt="<?= esc_attr( $project->post_title ) ?>" loading="lazy">
				<?php if ( $preview_video ) : ?>
				<video class="project-video" muted loop preload="none" poster="<?= esc_url( $thumbnail ) ?>">
					<source src="<?= esc_url( $preview_video ) ?>" type="video/mp4">
				</video>
				<?php endif; ?>
			</div>
			<div class="showcase-card__info content">
				<h3 class="title is-4 canela-font mb-1 gradient-text"><?= esc_html( $project->post_title ) ?></h3>
				<?php if ( $preview_role ) : ?>
				<p class="subtitle role-text is-size-6"><?= esc_html( $preview_role ) ?></p>
				<?php endif; ?>
				
			</div>
			<?php if ( ! $without_link ) : ?></a><?php endif; ?>
				<?php if ( $preview_desc ) : ?>
				<p><?= wp_kses_post( $preview_desc ) ?></p>
				<?php endif; ?>
		</div>
	</div>
	<?php endif; ?>
	<?php endforeach; wp_reset_postdata(); ?>
</div>
