<?php
/**
 * The main template file
 */

get_header();
$meta             = get_fields();
$args             = array(
	'post_type'      => 'project',
	'posts_per_page' => -1,
);
$projects         = get_posts( $args );
$bio              = $meta['bio'];
$last_update      = $meta['last_update'];
$month            = date( 'F', strtotime( $last_update ) );
$year             = date( 'Y', strtotime( $last_update ) );
$featured_img_url = get_the_post_thumbnail_url( get_the_ID(), 'full' );
?>

<section class="section colored-black anim-bottom-top" id="top-section">
	<div class="container is-max-widescreen mb-0">
		<div class="columns is-centered is-vcentered is-variable is-8">
			<div class="column is-two-thirds">
				<h1 class="title is-size-1 has-text-weight-normal ">
					<?php the_title(); ?>
				</h1>
				<div class="is-size-6 has-text-justified-center mb-5">
					<?php the_content(); ?>
				</div>
				<div class="level">
					<div class="level-left">
						<div class="level-item">
							<a href="<?php echo esc_url( home_url() . '/about-me' ); ?>" class="is-gold is-size-6">More about me</a>

						</div>
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
			<div class="column is-narrow is-hidden-touch">
				<?php the_post_thumbnail(); ?>
			</div>
		</div>
	</div>
</section>
<section class="section" id="projects">
	<div class="container mt-5 mb-5">
		<div class="columns is-multiline  is-centered">
			<div class="column is-narrow">
				<h2 class="title is-size-2"><?php esc_html_e( 'Featured work', 'jrsp' ); ?></h2>
				<?php /* <div class="select is-medium">
					<select id="project-categories">
						<option value="0" selected>View all</option>
						<?php foreach ( get_tags() as $tag ) { ?>
							<option value="<?php echo $tag->slug; ?>"><?php echo $tag->name; ?></option>
				<?php	} ?>
					</select>
				</div> */ ?>
			</div>
			<?php /*<div class="column has-text-right is-flex is-align-items-center is-justify-content-flex-end" style="gap:15px">
				<span>Categories: </span>
			</div> */ ?>
		</div>
	</div>
	<div class="container is-fluid pr-0 pl-0 pt-5">
		<div class="projects-grid" id="projects-grid">
		<?php
		foreach ( $projects as $project ) {
				get_template_part( 'template-parts/project-card', '', array( 'project' => $project ) );
		}
		?>
		</div>
	</div>
</section>
<?php
get_footer();/*
<section class="section colored-black" id="about">
	<div class="container">
		<div class="columns is-multiline">
			<div class="column is-full">
				<h2 class="title is-size-2"><?php esc_html_e( 'More about me', 'jrsp' ); ?> 👋</h2>
			</div>
			<div class="column is-full content">
				<?php echo $bio; ?>
			</div>
		</div>
	</div>
</section>*/

