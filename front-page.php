<?php
/**
 * The main template file
 */

get_header();
$meta             = get_fields();
$args             = array(
	'post_type'      => 'project',
	'posts_per_page' => -1,
	'post_status' => 'publish'
);
$projects         = get_posts( $args );
$bio              = $meta['bio'];
$last_update      = $meta['last_update'];
$month            = date( 'F', strtotime( $last_update ) );
$year             = date( 'Y', strtotime( $last_update ) );
$featured_img_url = get_the_post_thumbnail_url( get_the_ID(), 'full' );
?>

<section class="section  anim-bottom-top" id="top-section" style="--hero-bg-image: url('<?php echo esc_url( $featured_img_url ); ?>');">
	
	<div class="container is-max-widescreen">
		<div class="columns is-centered has-text-centered is-variable is-multiline">
			<div class="column is-full">
				<h1 class="title is-size-0 canela-font gradient-text">
					<?php the_title(); ?>
				</h1>
			</div>
			<div class="column is-two-thirds-desktop">
					<?php echo $bio; ?>
				<div class="level is-justify-content-center mt-3">
						<div class="level-item is-flex-grow-0">
							<a href="<?php echo esc_url( home_url() . '/about-me' ); ?>" class="is-gold is-size-6">More about me</a>
						</div>
						<div class="level-item is-flex-grow-0">
							<a href="https://github.com/jstriedinger" class="social">
								<i class="icon is-medium">
									<span class="fab fa-github "></span>
								</i>
							</a>
							<a href="https://www.linkedin.com/in/jstriedinger/" class="social">
								<i class="icon is-medium">
									<span class="fab fa-linkedin-in "></span>
								</i>
							</a>
							<a href="https://jstriedinger.itch.io/" class="social">
								<i class="icon">
									<span class="fa-brands fa-itch-io"></span>
								</i>
							</a>

						</div>

				</div>
			</div>
		</div>
	</div>
</section>
<section class="section" id="projects-section">
	<div class="container is-fluid">
		<div class="columns ">
			<div class="column is-full">
				<h2 class="title has-text-centered mb-6">My Projects</h2>
				<div class="projects-grid-masonry">
					<?php
					foreach ($projects as $project) :
						$project_meta = get_fields($project->ID);
						$preview_video = isset($project_meta['preview']['preview_video']) ? $project_meta['preview']['preview_video'] : null;
						$preview_title = isset($project_meta['preview']['preview_title']) ? $project_meta['preview']['preview_title'] : $project->post_title;
						$preview_desc = isset($project_meta['preview']['preview_desc']) ? $project_meta['preview']['preview_desc'] : null;
						$preview_role = isset($project_meta['preview']['preview_role']) ? $project_meta['preview']['preview_role'] : null;
						$preview_only_video = isset($project_meta['preview']['video_only']) ? $project_meta['preview']['video_only'] : false;
						$grid_width = isset($project_meta['preview']['grid_width']) ? $project_meta['preview']['grid_width'] : 1;
						$thumbnail = get_the_post_thumbnail_url($project->ID, 'large');

						if (!$thumbnail) {
							continue; // Skip if no thumbnail is available
						}
						
						// Determine classes based on video settings
						$video_classes = '';
						if ($preview_video) {
							if ($preview_only_video) {
								$video_classes = 'only-video';
							} else {
								$video_classes = 'has-video';
							}
						}
						
						// Add grid width class
						$grid_class = 'grid-span-' . $grid_width;
					?>
					<div class="project-item <?php echo esc_attr($video_classes); ?> <?php echo esc_attr($grid_class); ?>" data-columns="<?php echo esc_attr($grid_width); ?>" <?php if ($preview_video) echo 'data-video="' . esc_url($preview_video) . '"'; ?>>
						<img src="<?php echo esc_url($thumbnail); ?>" alt="<?php echo esc_attr($project->post_title); ?>" loading="lazy">
						<?php if ($preview_video) : ?>
							<video class="project-video" muted loop preload="none">
								<source src="<?php echo esc_url($preview_video); ?>" type="video/mp4">
							</video>
						<?php endif; ?>
						<div class="project-overlay">
							<div class="overlay-content">
								<h3 class="title canela-font mb-0"><?php echo esc_html($project->post_title); ?></h3>
								<?php if ($preview_role) : ?>
										<span class="role-text"><?php echo esc_html($preview_role); ?></span>
								<?php endif; ?>
								
							</div>
						</div>
					</div>
					<?php 
					endforeach;
					wp_reset_postdata();
					?>
				</div>
			</div>
		</div>
	</div>
</section>
<section class="section pt-2" id="primary">
	<div class="container mt-5 mb-5 is-fluid">
		<div class="columns">
			<div class="column is-full">
				<div class="content projects">
					<?php the_content(); ?>
				</div>
			</div>
		</div>
	</div>
</section>
		</div>
	</div>
</section>
<?php
get_footer();

