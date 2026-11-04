<?php
/**
 * viewing a single project
 */
get_header();
$meta          = get_fields();
$summary       = $meta['summary'];
$enlaces       = $meta['links'];
$video_trailer = isset( $meta['video_trailer'] ) ? $meta['video_trailer'] : null;
$video_preview = isset( $meta['preview']['preview_video'] ) ? $meta['preview']['preview_video'] : null;
$gif_preview   = isset( $meta['gif_preview'] ) ? $meta['gif_preview'] : null;
$tags          = get_the_tags();
$page_sections = isset( $meta['page_sections'] ) ? $meta['page_sections'] : [];
$accolades     = isset( $meta['preview']['preview_accolades'] ) && is_array( $meta['preview']['preview_accolades'] ) ? $meta['preview']['preview_accolades'] : [];

// Previous / next follow the backend order (Post Types Order plugin stores it as menu_order).
$project_ids  = get_posts( [
	'post_type'      => 'project',
	'post_status'    => 'publish',
	'posts_per_page' => -1,
	'orderby'        => 'menu_order',
	'order'          => 'ASC',
	'fields'         => 'ids',
] );
$position     = array_search( get_the_ID(), $project_ids, true );
$prev_project = ( false !== $position && $position > 0 ) ? $project_ids[ $position - 1 ] : null;
$next_project = ( false !== $position && $position < count( $project_ids ) - 1 ) ? $project_ids[ $position + 1 ] : null;

// Discipline tabs: top-level Group blocks whose HTML anchor is "engineering" or "design".
// Two of them -> Bulma tabs (app.js shows one at a time); one or none -> plain content.
$discipline_labels = [
	'engineering' => 'Engineering',
	'design'      => 'Design',
];
$disciplines = [];
foreach ( parse_blocks( get_post_field( 'post_content' ) ) as $block ) {
	if ( 'core/group' === $block['blockName']
		&& preg_match( '/^\s*<\w+[^>]*\sid="([^"]+)"/', $block['innerHTML'], $anchor )
		&& isset( $discipline_labels[ $anchor[1] ] ) ) {
		$disciplines[ $anchor[1] ] = $discipline_labels[ $anchor[1] ];
	}
}
$has_tabs = count( $disciplines ) > 1;


?>
<section class="section ">
	<div class="container my-4 ">
		<div class="columns is-vcentered is-centered anim-bottom-top is-6">
			<div class="column is-half">
				<a class="is-size-7 is-uppercase has-text-weight-bold has-text-grey-light is-inline-block mb-4" href="<?php echo esc_url( home_url( '/#projects-section' ) ); ?>">
						<span class="icon is-small"><i class="fa-solid fa-arrow-left" aria-hidden="true"></i></span>
						<span>All work</span>
					</a>
					<?php if ( ! empty( $accolades ) ) : ?>
					<div class="showcase-accolades is-flex is-align-items-center is-flex-wrap-wrap is-gap-2 mb-4">
						<?php foreach ( $accolades as $accolade ) : ?>
						<img src="<?php echo esc_url( $accolade['url'] ); ?>" alt="<?php echo esc_attr( $accolade['alt'] ?: $accolade['title'] ); ?>" width="<?php echo (int) $accolade['width']; ?>" height="<?php echo (int) $accolade['height']; ?>" data-no-lazy="1">
						<?php endforeach; ?>
					</div>
					<?php endif; ?>
					<h1 class="title mb-0 is-size-2 is-size-1-desktop canela-font">
					<?php the_title(); ?>
				</h1>
				<div class="content mt-4">
					<?php echo $summary; ?>
				</div>
			
				<?php if ( $enlaces && count( $enlaces ) > 0 ) : ?>
					<p class="is-size-7 is-uppercase has-text-weight-bold has-text-grey-light mt-5 mb-2">Available on</p>
					<div class="buttons project-stores">
						<?php foreach ( $enlaces as $enlace ) :
							$store_url = $enlace['url'];
							if ( str_contains( $store_url, 'steam' ) ) {
								$store_name = 'Steam';
								$store_icon = 'fab fa-steam';
							} elseif ( str_contains( $store_url, 'epicgames' ) ) {
								$store_name = 'EGS';
								$store_icon = null;
							} elseif ( str_contains( $store_url, 'itch' ) ) {
								$store_name = 'itch.io';
								$store_icon = 'fab fa-itch-io';
							} else {
								$store_name = wp_parse_url( $store_url, PHP_URL_HOST );
								$store_icon = 'fa-solid fa-arrow-up-right-from-square';
							}
						?>
						<a href="<?php echo esc_url( $store_url ); ?>" class="button is-rounded">
							<?php if ( $store_icon ) : ?>
							<span class="icon"><i class="<?php echo esc_attr( $store_icon ); ?>" aria-hidden="true"></i></span>
							<?php endif; ?>
							<span><?php echo esc_html( $store_name ); ?></span>
						</a>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
			<div class="column project-feature-thing">
				<?php if ( ! is_null( $video_trailer ) && ! empty( $video_trailer ) ) : ?>
					<div class="embed-container is-aspect-ratio-16by9">
						<?php echo $video_trailer; ?>
					</div>
				<?php elseif ( ! is_null( $video_preview ) && ! empty( $video_preview ) ) : ?>
					<video autoplay="" loop="" muted="" src="<?php echo esc_url( $video_preview ); ?>" poster="<?php echo esc_url( get_the_post_thumbnail_url( get_the_ID(), 'full' ) ); ?>" loading="lazy" oncontextmenu="return false;"></video>
				<?php elseif ( ! is_null( $gif_preview ) && ! empty( $gif_preview ) ) : ?>
					<img src="<?php echo esc_url( $gif_preview ); ?>" alt="Jose Striedinger portfolio <?php echo the_title_attribute(); ?>" loading="lazy">
					<?php
					else :
						the_post_thumbnail();
				endif;
					?>
			</div>
		</div>
		
	</div>
</section>
<section class="section pt-2" id="project-content-section">
	<?php if ( ! empty( $page_sections ) ) : ?>
	<nav class="project-sidebar is-hidden-touch" id="project-sidebar">
		<p class="">On this page: </p>
		<?php foreach ( $page_sections as $item ) : ?>
			<a href="#<?php echo esc_attr( $item['section_id'] ); ?>" class="panel-block">
				<?php echo esc_html( $item['label'] ); ?>
			</a>
			<?php if ( ! empty( $item['children'] ) ) : ?>
				<?php foreach ( $item['children'] as $child ) : ?>
					<a href="#<?php echo esc_attr( $child['section_id'] ); ?>" class="panel-block is-child">
						<?php echo esc_html( $child['label'] ); ?>
					</a>
				<?php endforeach; ?>
			<?php endif; ?>
		<?php endforeach; ?>
	</nav>
	<?php endif; ?>
	<div class="container is-max-widescreen">
		<?php if ( $has_tabs ) : ?>
		<p class="is-size-7 is-uppercase has-text-weight-bold has-text-grey-light has-text-centered mb-2">My contributions</p>
		<div class="tabs is-centered is-medium project-tabs mb-6">
			<ul>
				<?php foreach ( $disciplines as $discipline_id => $discipline_label ) : ?>
				<li data-tab="<?php echo esc_attr( $discipline_id ); ?>"><a href="#<?php echo esc_attr( $discipline_id ); ?>"><?php echo esc_html( $discipline_label ); ?></a></li>
				<?php endforeach; ?>
			</ul>
		</div>
		<?php endif; ?>
		<div class="content">
			<?php the_content(); ?>
		</div>
	</div>
</section>
<?php if ( $prev_project || $next_project ) : ?>
<section class="section pt-0">
	<div class="container is-max-widescreen project-nav">
		<div class="columns is-mobile">
			<div class="column">
				<?php if ( $prev_project ) : ?>
				<a href="<?php echo esc_url( get_permalink( $prev_project ) ); ?>" class="is-block" rel="prev">
					<p class="is-size-7 is-uppercase has-text-weight-bold has-text-grey-light mb-2">
						<span class="icon is-small"><i class="fa-solid fa-arrow-left" aria-hidden="true"></i></span>
						<span>Previous</span>
					</p>
					<p class="title is-4 canela-font"><span><?php echo esc_html( get_the_title( $prev_project ) ); ?></span></p>
				</a>
				<?php endif; ?>
			</div>
			<div class="column has-text-right">
				<?php if ( $next_project ) : ?>
				<a href="<?php echo esc_url( get_permalink( $next_project ) ); ?>" class="is-block" rel="next">
					<p class="is-size-7 is-uppercase has-text-weight-bold has-text-grey-light mb-2">
						<span>Next</span>
						<span class="icon is-small"><i class="fa-solid fa-arrow-right" aria-hidden="true"></i></span>
					</p>
					<p class="title is-4 canela-font"><span><?php echo esc_html( get_the_title( $next_project ) ); ?></span></p>
				</a>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
<?php endif; ?>
<?php
get_footer();
