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
				<h1 class="title is-size-0 canela-font gradient-text has-text-weight-bold">
					<?php the_title(); ?>
				</h1>
			</div>
			<div class="column is-two-thirds-desktop">
					<?php echo $bio; ?>
				<div class="level is-justify-content-center mt-5">
						<div class="level-item is-flex-grow-0">
							<a href="<?php echo esc_url( home_url() . '/about-me' ); ?>" class="is-size-6">More about me</a>
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
			<div class="column is-full content">
				<?php the_content(); ?>
			</div>
		</div>
	</div>
</section>
<?php
get_footer(); ?>
							