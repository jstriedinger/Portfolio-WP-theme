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

	wp_enqueue_style( 'main-css', get_template_directory_uri() . '/dist/main.css');

	// Deregister the jquery version bundled with WordPress.
	wp_deregister_script( 'jquery' );

	// CDN hosted jQuery placed in the header, as some plugins require that jQuery is loaded in the header.
	wp_enqueue_script( 'jquery', 'https://code.jquery.com/jquery-3.6.0.min.js', array(), '3.2.1', false );
	wp_enqueue_script( 'app', get_template_directory_uri() . '/dist/js/main.js' );

}
add_action( 'wp_enqueue_scripts', 'jrsp_scripts' );
