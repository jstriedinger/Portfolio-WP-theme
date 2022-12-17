<?php
$project    = $args['project'];
$permalink  = get_permalink( $project->ID );
$thumbnail  = get_the_post_thumbnail_url( $project->ID, 'large' );
$grid_dir   = get_field( 'grid_dir', $project->ID );
$desc       = get_field( 'desc', $project->ID );
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
			<?php echo get_the_post_thumbnail($project->ID); ?>
		<div class="header-content has-text-centered is-size-6">
			<p><?php echo esc_html( $desc ); ?></p>
		</div>
	</a>
	<div class="card-content">
		<h3 class="is-size-5"><?php echo esc_html( $project->post_title ); ?></h3>
	</div>

</article>
