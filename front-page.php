<?php
/**
 * The main template file
 */
$context = Timber::get_context();
$context['post'] = new Timber\Post();
$templates = array( 'front-page.twig' );

$args = array (
    'post_type'  => 'project',
    'posts_per_page' => '-1'
);
$context['projects'] = Timber::get_posts( $args );

Timber::render( $templates, $context );