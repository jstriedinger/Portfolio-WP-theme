<?php
$project    = $args['project'];
$meta       = get_fields( $project->ID );
$permalink  = get_permalink( $project->ID );
$thumbnail  = get_the_post_thumbnail_url( $project->ID, 'large' );
$desc       = $meta['desc'];
$position   = $meta['role'];
$tag_string = '';
$tags       = get_the_tags( $project->ID );
foreach ( $tags as $key => $tag ) {
	if ( $key !== array_key_last( $tags ) ) {
		$tag_string .= $tag->slug . ',';
	} else {
		$tag_string .= $tag->slug;
	}
}

?>
<article class="card project" data-categories="<?php echo esc_attr( $tag_string ); ?>">
	<a class="card-header" href="<?php echo esc_url( $permalink ); ?>">
			<?php echo get_the_post_thumbnail( $project->ID ); ?>
		<div class="header-content has-text-centered is-size-6">
			<p class="title is-size-4 is-size-3-widescreen is-gold"><?php echo esc_html( $position ); ?></p>
			<p class="has-text-white"><?php echo esc_html( $desc ); ?></p>
		</div>
	</a>
	<div class="card-content">
		<h3 class="is-size-5 has-text-weight-bold has-text-centered"><?php echo esc_html( $project->post_title ); ?></h3>
	</div>

</article>
