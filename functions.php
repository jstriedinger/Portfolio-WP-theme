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
 * Split the primary menu's top-level items in half so a single WP menu can be
 * rendered on both sides of the centered site name. Pass 'menu_half' =>
 * 'left'|'right' to wp_nav_menu(); both calls share one cached split.
 */
function jrsp_split_primary_menu_items( $sorted_menu_items, $args ) {
	if ( empty( $args->menu_half ) || empty( $args->theme_location ) || 'primary' !== $args->theme_location ) {
		return $sorted_menu_items;
	}

	static $halves = null;

	if ( null === $halves ) {
		$top_level_ids = array();
		foreach ( $sorted_menu_items as $item ) {
			if ( empty( $item->menu_item_parent ) || '0' === (string) $item->menu_item_parent ) {
				$top_level_ids[] = (int) $item->ID;
			}
		}

		$split      = (int) ceil( count( $top_level_ids ) / 2 );
		$left_roots = array_slice( $top_level_ids, 0, $split );
		$halves     = array(
			'left'  => array(),
			'right' => array(),
		);

		foreach ( $sorted_menu_items as $item ) {
			$root_id = ( empty( $item->menu_item_parent ) || '0' === (string) $item->menu_item_parent )
				? (int) $item->ID
				: (int) $item->menu_item_parent;

			$halves[ in_array( $root_id, $left_roots, true ) ? 'left' : 'right' ][] = $item;
		}
	}

	return isset( $halves[ $args->menu_half ] ) ? $halves[ $args->menu_half ] : $sorted_menu_items;
}
add_filter( 'wp_nav_menu_objects', 'jrsp_split_primary_menu_items', 10, 2 );

/**
 * Enqueue scripts and styles.
 */
function jrsp_scripts() {

	wp_enqueue_style( 'main-css', get_template_directory_uri() . '/assets/main.css' );

	// Deregister the jquery version bundled with WordPress.
	wp_deregister_script( 'jquery' );

	wp_enqueue_script( 'jquery', 'https://code.jquery.com/jquery-3.6.0.min.js', array(), '3.2.1', true );
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

/**
 * Legacy AJAX handler for the old JS-driven project grid.
 * The current grid uses the [projects_grid] shortcode (server-rendered).
 * Kept for backwards compatibility — do not remove.
 */
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

	// A row needs artwork, so drop anything without a thumbnail.
	$projects = array_values( array_filter( $projects, function ( $project ) {
		return (bool) get_the_post_thumbnail_url( $project->ID, 'large' );
	} ) );

	if (empty($projects)) {
		return '<div class="projects-showcase"><p>No projects found.</p></div>';
	}

	// Rendering lives in template-parts/projects-showcase.php, which reads $projects.
	ob_start();
	include get_template_directory() . '/template-parts/projects-showcase.php';
	return ob_get_clean();
}
add_shortcode('projects_grid', 'projects_grid_masonry_shortcode');

// Compact Projects Grid Shortcode — vertical cards, several per row.
// Same underlying project data as [projects_grid]; only the rendered
// template differs. Tag-driven: point this at a different tag than
// [projects_grid] so a project only ever shows up in one of the two.
function projects_grid_small_shortcode($atts) {
	$atts = shortcode_atts(array(
		'posts_per_page' => -1,
		'category' => '',
		'tag' => '',
	), $atts, 'projects_grid_small');

	$args = array(
		'post_type' => 'project',
		'posts_per_page' => intval($atts['posts_per_page']),
		'post_status' => 'publish'
	);

	if (!empty($atts['category'])) {
		$args['meta_query'] = array(
			array(
				'key' => 'category',
				'value' => $atts['category'],
				'compare' => 'LIKE'
			)
		);
	}

	if (!empty($atts['tag'])) {
		$args['tag'] = $atts['tag'];
	}

	$projects = get_posts($args);

	// A card needs artwork, so drop anything without a thumbnail.
	$projects = array_values( array_filter( $projects, function ( $project ) {
		return (bool) get_the_post_thumbnail_url( $project->ID, 'large' );
	} ) );

	if (empty($projects)) {
		return '<div class="projects-grid-small"><p>No projects found.</p></div>';
	}

	// Rendering lives in template-parts/projects-grid-small.php, which reads $projects.
	ob_start();
	include get_template_directory() . '/template-parts/projects-grid-small.php';
	return ob_get_clean();
}
add_shortcode('projects_grid_small', 'projects_grid_small_shortcode');

// Special Project Feature Shortcode — its own dedicated card style
// (template-parts/project-special.php), exclusive to a single project: the
// first one carrying the given tag, even if more than one gets tagged by mistake.
function project_special_shortcode($atts) {
	$atts = shortcode_atts(array(
		'tag' => 'special',
	), $atts, 'project_special');

	$projects = get_posts(array(
		'post_type' => 'project',
		'posts_per_page' => 1,
		'post_status' => 'publish',
		'tag' => $atts['tag'],
	));

	$projects = array_values( array_filter( $projects, function ( $project ) {
		return (bool) get_the_post_thumbnail_url( $project->ID, 'large' );
	} ) );

	if (empty($projects)) {
		return '';
	}

	$project = $projects[0];

	// Rendering lives in template-parts/project-special.php, which reads $project.
	ob_start();
	include get_template_directory() . '/template-parts/project-special.php';
	return ob_get_clean();
}
add_shortcode('project_special', 'project_special_shortcode');

// Blog Posts Shortcode
function blog_posts_shortcode($atts) {
    // Set default attributes
    $atts = shortcode_atts(array(
        'posts_per_page' => -1,
        'orderby' => 'date',
        'order' => 'DESC',
        'category' => '',
        'class' => 'masonry-grid blog-grid'
    ), $atts);

    // Query for blog posts
    $blog_posts = new WP_Query(array(
        'post_type' => 'post',
        'posts_per_page' => intval($atts['posts_per_page']),
        'post_status' => 'publish',
        'orderby' => $atts['orderby'],
        'order' => $atts['order'],
        'category_name' => $atts['category']
    ));

    $output = '';

    if ($blog_posts->have_posts()) {
        $output .= '<div class="' . esc_attr($atts['class']) . '">';
        
        while ($blog_posts->have_posts()) {
            $blog_posts->the_post();
            
            $output .= '<div class="masonry-item">';
            $output .= '<a href="' . get_the_permalink() . '" title="' . get_the_title() . '">';
            
            // Featured image
            if (has_post_thumbnail()) {
                $output .= '<img src="' . get_the_post_thumbnail_url(get_the_ID(), 'large') . '" alt="' . get_the_title() . '" loading="lazy">';
            } else {
                $output .= '<div style="width: 100%; height: 200px; background: #f0f0f0; display: flex; align-items: center; justify-content: center; color: #999;">';
                $output .= '<span>No Image</span>';
                $output .= '</div>';
            }
            
            $output .= '<div class="blog-overlay">';
            $output .= '<div class="blog-content content">';
            $output .= '<h3 class="title mb-2 is-size-5">' . get_the_title() . '</h3>';
            
            // Excerpt
            $excerpt = get_the_excerpt();
            if (empty($excerpt)) {
                $excerpt = wp_trim_words(get_the_content(), 20, '...');
            } else {
                $excerpt = wp_trim_words($excerpt, 20, '...');
            }
            $output .= '<p class="excerpt subtitle is-size-6">' . esc_html($excerpt) . '</p>';
            
            $output .= '<div class="is-flex is-align-items-center is-justify-content-space-between">';
            $output .= '<p class="mb-0">';
            $output .= '<span class="datetime is-size-7">' . get_the_date('F j, Y') . '</span>';
            $output .= '</p>';
            $output .= '<button class="button is-rounded">Read more</button>';
            $output .= '</div>';
            $output .= '</div>';
            $output .= '</div>';
            $output .= '</a>';
            $output .= '</div>';
        }
        
        $output .= '</div>';
        wp_reset_postdata();
    } else {
        $output .= '<div class="has-text-centered">';
        $output .= '<p>No blog posts found.</p>';
        $output .= '</div>';
    }

    return $output;
}
add_shortcode('blog_posts', 'blog_posts_shortcode');

function jrsp_parse_video_url( $url ) {
    $id       = '';
    $platform = '';

    if ( preg_match( '/youtube\.com\/watch\?v=([^&]+)/', $url, $m ) ||
         preg_match( '/youtu\.be\/([^?]+)/', $url, $m ) ||
         preg_match( '/youtube\.com\/embed\/([^?]+)/', $url, $m ) ) {
        $platform = 'youtube';
        $id       = $m[1];
    } elseif ( preg_match( '/(?:vimeo\.com\/video\/|player\.vimeo\.com\/video\/|vimeo\.com\/)(\d+)/', $url, $m ) ) {
        $platform = 'vimeo';
        $id       = $m[1];
    }

    if ( ! $id ) {
        return null;
    }

    if ( $platform === 'youtube' ) {
        return array(
            'platform'  => 'youtube',
            'id'        => $id,
            'thumbnail' => "https://img.youtube.com/vi/{$id}/maxresdefault.jpg",
            'embed_url' => "https://www.youtube.com/embed/{$id}",
        );
    }

    $cache_key = 'vimeo_thumb_' . $id;
    $thumbnail = get_transient( $cache_key );

    if ( ! $thumbnail ) {
        $response = wp_remote_get( "https://vimeo.com/api/v2/video/{$id}.json" );
        if ( ! is_wp_error( $response ) ) {
            $data      = json_decode( wp_remote_retrieve_body( $response ), true );
            $thumbnail = $data[0]['thumbnail_large'] ?? '';
            if ( $thumbnail ) {
                set_transient( $cache_key, $thumbnail, WEEK_IN_SECONDS );
            }
        }
    }

    return array(
        'platform'  => 'vimeo',
        'id'        => $id,
        'thumbnail' => $thumbnail ?: '',
        'embed_url' => "https://player.vimeo.com/video/{$id}",
    );
}

// Projects Showcase Shortcode
function projects_showcase_shortcode( $atts ) {
	$atts = shortcode_atts( array(
		'posts_per_page' => -1,
	), $atts, 'projects_showcase' );

	$projects = get_posts( array(
		'post_type'      => 'project',
		'posts_per_page' => intval( $atts['posts_per_page'] ),
		'post_status'    => 'publish',
		'tag'            => 'featured',
	) );

	if ( empty( $projects ) ) {
		return '<div class="projects-showcase"><p>No projects found.</p></div>';
	}

	ob_start();
	include get_template_directory() . '/template-parts/projects-showcase.php';
	return ob_get_clean();
}
add_shortcode( 'projects_showcase', 'projects_showcase_shortcode' );

function arkde_showcase_shortcode() {
	ob_start();
	get_template_part( 'template-parts/arkde-showcase' );
	return ob_get_clean();
}
add_shortcode( 'arkde_showcase', 'arkde_showcase_shortcode' );


