<?php
$project    = $args['project'];
$meta       = get_fields( $project->ID );
$permalink  = get_permalink( $project->ID );
$thumbnail  = get_the_post_thumbnail_url( $project->ID, 'large' );
$desc       = isset( $meta['desc'] ) ? $meta['desc'] : null;
$position   = isset( $meta['role'] ) ? $meta['role'] : null;
$tag_string = '';
$tags       = get_the_tags( $project->ID );
$is_wip     = false;
foreach ( $tags as $key => $tag ) {
	if ( $tag->slug == 'wip' ) {
		$is_wip = true;
		break;
	}
}

$card_img   = isset( $meta['card_img'] ) ? $meta['card_img'] : null;
$card_gif   = isset( $meta['card_gif'] ) ? $meta['card_gif'] : null;
$card_video = isset( $meta['card_video'] ) ? $meta['card_video'] : null;

?>
<a href="<?php echo esc_url( $permalink ); ?>" style="border: none !important; display:block !important;">
	<article class="card project" data-categories="<?php echo esc_attr( $tag_string ); ?>">
		
		<div class="card-header <?php echo empty( $card_gif ) ? 'no-gif' : ''; ?>" >
				<?php
					echo get_the_post_thumbnail( $project->ID );
				if ( ! empty( $card_video ) ) {
					?>
					<video autoplay muted loop id="">
						<source src="<? echo esc_url($card_video); ?>" type="video/mp4">
					</video>
					<?php
				}
				/*
				if ( ! empty( $card_gif ) ) {
					?>
						<img class="gif-bg" src="<?php echo esc_url( $card_gif ); ?>" alt="Jose Striedinger portfolio <?php echo esc_attr( $project->post_title ); ?>">

					<?php
				}*/
				?>
				<?php if ( $is_wip ) : ?>
					<div class="tag is-primary is-light">WIP</div>
				<?php endif; ?>
			<div class="header-content has-text-centered is-size-6">
				<p class="title is-size-4 is-size-3-desktop  is-size-2-widescreen is-size-1-fullhd has-text-white"><?php echo esc_html( $project->post_title ); ?></p>
				<p class="subtitle has-text-white is-size-5 is-size-4-fullhd"><?php echo esc_html( $position ); ?></p>
				<p class="is-gold is-size-6 has-text-weight-bold">Click for more →</p>
			</div>
		</div>
	</article>
</a>
