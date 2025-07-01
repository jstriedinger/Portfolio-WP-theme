<?php
/**
 * jrsp functions and definitions
 *
 * @package get
 */

define( 'THEME_HOOK_PREFIX', 'jrsp_' );

/** Register all navigation menus */
if ( ! function_exists( 'jrsp_setup' ) ) :

	function jrsp_setup() {
		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );
		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );
		
		// Register navigation menus
		register_nav_menus( array(
			'primary' => esc_html__( 'Primary Menu', 'jrsp' ),
		) );
		
		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);
		// Set up the WordPress core custom background feature.
		add_theme_support(
			'custom-background',
			apply_filters(
				'get_custom_background_args',
				array(
					'default-color' => 'f5f5f5',
					'default-image' => '',
				)
			)
		);
		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 128,
				'width'       => 128,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);
	}
endif;
add_action( 'after_setup_theme', 'jrsp_setup' );

add_filter( 'show_admin_bar', '__return_false' );

// Include custom nav walker
require_once get_template_directory() . '/inc/class-bulma-nav-walker.php';

/**
 * Enqueue scripts and styles.
 */
function jrsp_scripts() {

	wp_enqueue_style( 'main-css', get_template_directory_uri() . '/assets/main.css' );

	// Deregister the jquery version bundled with WordPress.
	wp_deregister_script( 'jquery' );

	// CDN hosted jQuery placed in the header, as some plugins require that jQuery is loaded in the header.
	wp_enqueue_script( 'jquery', 'https://code.jquery.com/jquery-3.6.0.min.js', array(), '3.2.1', false );
	wp_enqueue_script( 'jrsp-js', get_template_directory_uri() . '/assets/js/main.js' );
	
	// Add inline script for mobile menu toggle
	wp_add_inline_script( 'jrsp-js', '
		document.addEventListener("DOMContentLoaded", function() {
			const burger = document.querySelector(".navbar-burger");
			const nav = document.querySelector("#navbarMain");
			
			if (burger && nav) {
				burger.addEventListener("click", function() {
					burger.classList.toggle("is-active");
					nav.classList.toggle("is-active");
				});
			}
		});
	' );
	
	wp_localize_script(
		'jrsp-js',
		'jrsp_ajax',
		array(
			'ajaxurl' => site_url() . '/wp-admin/admin-ajax.php',
			'nonce'   => wp_create_nonce( 'ajax-jrsp-nonce' ),
		)
	);

}
add_action( 'wp_enqueue_scripts', 'jrsp_scripts' );

function get_projects() {
	if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-jrsp-nonce' ) ) {
		wp_send_json_error( 'Failed nonce check', 403 ); // received by JS as 'string'
	}
	if ( ! isset( $_POST['category'] ) ) {
		wp_send_json_error( 'Category can not be empty', 403 );
	}

	$category = sanitize_text_field( $_POST['category'] );

	$args = array(
		'post_type'      => 'project',
		'posts_per_page' => -1,
	);
	if ( $category !== '0' ) {
		$args['tag'] = $category;
	}

	$loop          = new WP_Query( $args );
	$projects_info = array();
	$success       = false;

	if ( $loop->have_posts() ) {
		$success = true;
		while ( $loop->have_posts() ) {
			$loop->the_post();

			$project_info              = array();
			$project_info['permalink'] = get_permalink();
			$project_info['id']        = get_the_ID();
			$project_info['name']      = get_the_title();
			$project_info['image']     = get_the_post_thumbnail_url( get_the_ID(), 'full' );
			$project_info['grid_dir']  = get_field( 'grid_dir' );
			array_push( $projects_info, $project_info );
		}
	}
	wp_reset_postdata();
	echo wp_json_encode(
		array(
			'success' => $success,
			'data'    => $projects_info,
		)
	);
	wp_die();
}

add_action( 'wp_ajax_nopriv_get_projects', 'get_projects' );
add_action( 'wp_ajax_get_projects', 'get_projects' );

// Projects Grid Masonry Shortcode
function projects_grid_masonry_shortcode($atts) {
	// Parse shortcode attributes
	$atts = shortcode_atts(array(
		'posts_per_page' => -1,
		'category' => '',
		'tag' => '',
	), $atts, 'projects_grid');

	// Build query arguments
	$args = array(
		'post_type' => 'project',
		'posts_per_page' => intval($atts['posts_per_page']),
		'post_status' => 'publish'
	);

	// Add category filter if specified
	if (!empty($atts['category'])) {
		$args['meta_query'] = array(
			array(
				'key' => 'category',
				'value' => $atts['category'],
				'compare' => 'LIKE'
			)
		);
	}

	// Add tag filter if specified
	if (!empty($atts['tag'])) {
		$args['tag'] = $atts['tag'];
	}

	$projects = get_posts($args);

	if (empty($projects)) {
		return '<div class="masonry-grid"><p>No projects found.</p></div>';
	}

	// Start output buffering
	ob_start();
	?>
	<div class="masonry-grid projects-grid">
		<?php
		foreach ($projects as $project) :
			$project_meta = get_fields($project->ID);
			$preview_video = isset($project_meta['preview']['preview_video']) ? $project_meta['preview']['preview_video'] : null;
			$preview_tech = isset($project_meta['preview']['preview_tech']) ? $project_meta['preview']['preview_tech'] : array();
			$preview_desc = isset($project_meta['preview']['preview_desc']) ? $project_meta['preview']['preview_desc'] : null;
			$preview_role = isset($project_meta['preview']['preview_role']) ? $project_meta['preview']['preview_role'] : null;
			$preview_only_video = isset($project_meta['preview']['video_only']) ? $project_meta['preview']['video_only'] : false;
			$preview_link = isset($project_meta['preview']['external_link']) && !empty($project_meta['preview']['external_link']) ? $project_meta['preview']['external_link'] : get_permalink($project->ID);
			$without_link = isset($project_meta['preview']['without_link']) ? $project_meta['preview']['without_link'] : false;
			$grid_width = isset($project_meta['preview']['grid_width']) ? $project_meta['preview']['grid_width'] : 1;
			$thumbnail = get_the_post_thumbnail_url($project->ID, 'large');

			if (!$thumbnail) {
				continue; // Skip if no thumbnail is available
			}
			// Ensure preview_tech is always an array
			if (!is_array($preview_tech)) {
				$preview_tech = !empty($preview_tech) ? array($preview_tech) : array();
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
		<div class="masonry-item <?php echo esc_attr($video_classes); ?> <?php echo esc_attr($grid_class); ?>" data-columns="<?php echo esc_attr($grid_width); ?>" <?php if ($preview_video) echo 'data-video="' . esc_url($preview_video) . '"'; ?>>
		<?php if(!$without_link) : ?>
			<a href="<?php echo esc_url($preview_link); ?>" title="<?php echo esc_attr($project->post_title); ?>">
		<?php endif; ?>	
				<img src="<?php echo esc_url($thumbnail); ?>" alt="<?php echo esc_attr($project->post_title); ?>" loading="lazy">
				<?php if ($preview_video) : ?>
					<video class="project-video" muted loop preload="none" poster="<?php echo esc_url($thumbnail); ?>">
						<source src="<?php echo esc_url($preview_video); ?>" type="video/mp4">
					</video>
				<?php endif; ?>
				<div class="project-overlay">
					<div class="overlay-content">
						<h3 class="title canela-font mb-0" style="line-height: 1.1;">
							<?php echo esc_html($project->post_title); ?>
						</h3>
						<?php if ($preview_role) : ?>
						<p>
						<span class="role-text"><?php echo esc_html($preview_role); ?></span>
						<?php if (!empty($preview_tech)) : ?>
								<?php foreach ($preview_tech as $tech) : ?>
									<?php if (!empty($tech)) : ?>
										<i class="devicon-<?php echo esc_attr($tech); ?>"></i>
									<?php endif; ?>
								<?php endforeach; ?>
							<?php endif; ?>
						</p>
						<?php endif; ?>
					</div>
				</div>
			<?php if(!$without_link) : ?>
			</a>
			<?php endif; ?>
		</div>
		<?php 
		endforeach;
		wp_reset_postdata();
		?>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode('projects_grid', 'projects_grid_masonry_shortcode');
