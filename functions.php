<?php
/**
 * jrsp functions and definitions
 *
 * @package get
 */
require_once(__DIR__ . '/vendor/autoload.php');
$timber = new Timber\Timber();
Timber::$dirname = array('views');

/** Register all navigation menus */
if ( ! function_exists( 'jrsp_setup' ) ) :

    function jrsp_setup()
    {
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
        /*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );
        // Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'get_custom_background_args', array(
			'default-color' => 'f5f5f5',
			'default-image' => '',
		) ) );
        // Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 128,
			'width'       => 128,
			'flex-width'  => true,
			'flex-height' => true,
		) );
    }
endif;
add_action( 'after_setup_theme', 'jrsp_setup' );
	
add_filter( 'show_admin_bar', '__return_false' );


/**
 * Enqueue scripts and styles.
 */
function jrsp_scripts() {

	wp_enqueue_style( 'main-css', get_template_directory_uri() . '/assets/main.css');

	// Deregister the jquery version bundled with WordPress.
	wp_deregister_script( 'jquery' );

	// CDN hosted jQuery placed in the header, as some plugins require that jQuery is loaded in the header.
	wp_enqueue_script( 'jquery', 'https://code.jquery.com/jquery-3.6.0.min.js', array(), '3.2.1', false );
	wp_enqueue_script( 'jrsp-js', get_template_directory_uri() . '/assets/js/main.js' );
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

	$category = sanitize_text_field($_POST['category']);

	$args = array(
		'post_type' => 'project',
		'posts_per_page' => -1
	);
	if($category !== "0") {
		$args['tag'] = $category;
	}

	$loop = new WP_Query( $args );
	$projects_info = array();
	$success = false;

	if ( $loop->have_posts() ) {
		$success = true;
		while ( $loop->have_posts() ) {
			$loop->the_post();
			
			$project_info = array();
			$project_info['permalink'] = get_permalink();
			$project_info['id'] = get_the_ID();
			$project_info['name'] = get_the_title();
			$project_info['image'] = get_the_post_thumbnail_url(get_the_ID(),'full');
			$project_info['grid_dir'] =  get_field('grid_dir');
			array_push($projects_info, $project_info);
		}
	}
	wp_reset_postdata();
	echo wp_json_encode(
		array(
			'success' => $success,
			'data'   => $projects_info
		)
	);
	wp_die();
}

add_action( 'wp_ajax_nopriv_get_projects', 'get_projects' );
add_action( 'wp_ajax_get_projects', 'get_projects' );
