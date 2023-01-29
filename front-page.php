<?php
/**
 * The main template file
 */

get_header();
$meta        = get_fields();
$args        = array(
	'post_type'      => 'project',
	'posts_per_page' => -1,
);
$projects    = get_posts( $args );
$bio         = $meta['bio'];
$last_update = $meta['last_update'];
$month       = date( 'F', strtotime( $last_update ) );
$year        = date( 'Y', strtotime( $last_update ) );
?>

<section class="section colored-black pb-0 anim-bottom-top" id="top-section">
	<div class="container mb-0">
		<div class="columns is-vcentered">
			<div class="column is-half content">
				<h1 class="title is-size-2 has-text-weight-light ">
					<span>¡Hola! I'm</span><br><span class="has-text-weight-bold is-size-0"><?php the_title(); ?></span>
				</h1>
				<div class="title is-size-4 has-text-weight-light mt-2 has-text-justified">
					<?php the_content(); ?>
				</div>
				<div class="level mt-5 pt-5 mb-5">
					<div class="level-left">
						<a href="<?php echo esc_url( $meta['cv'] ); ?>" class="button is-medium is-gold is-outlined">
							<?php esc_html_e( 'Download CV', 'jrsp' ); ?>
						</a>
					</div>
					<div class="level-right">
						<p >
							<span class="is-size-6 is-hidden-touch"><?php esc_html_e( 'Connect with me: ', 'jrsp' ); ?></span>
							<a href="https://github.com/jstriedinger" class="social">
								<i class="icon is-medium">
									<span class="fab fa-github fa-lg"></span>
								</i>
							</a>
							<a href="https://www.linkedin.com/in/jstriedinger/" class="social">
								<i class="icon is-medium">
									<span class="fab fa-linkedin-in fa-lg"></span>
								</i>
							</a>
						</p>
					</div>
				</div>
			</div>
			<div class="column is-half pb-0 is-flex">
				<?php the_post_thumbnail(); ?>
			</div>
		</div>
	</div>
</section>
<section class="section" id="projects">
	<div class="container mt-5 mb-5">
		<div class="columns is-multiline is-vcentered">
			<div class="column is-narrow">
				<h2 class="title is-size-2"><?php esc_html_e( 'Checkout my work', 'jrsp' ); ?></h2>
				<h3 class="subtitle is-size-5">Last update: <?php echo esc_html( $month ); ?>, <?php echo esc_html( $year ); ?></h3>
			</div>
			<div class="column has-text-right is-flex is-align-items-center is-justify-content-flex-end" style="gap:15px">
				<span>Categories: </span>
				<div class="select is-medium">
					<select id="project-categories">
						<option value="0" selected>View all</option>
						<?php foreach ( get_tags() as $tag ) { ?>
							<option value="<?php echo $tag->slug; ?>"><?php echo $tag->name; ?></option>
				<?php	} ?>
					</select>
				</div>
			</div>
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

