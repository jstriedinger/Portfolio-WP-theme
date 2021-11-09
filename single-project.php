<?php
/**
 * viewing a single project
 */
$context = Timber::get_context();
$context['post'] = new Timber\Post();
$templates = array( 'project.twig' );

$args = array (
    'post_type'  => 'project',
    'post__not_in' => array( $context['post']->id ),
    'posts_per_page' => '-1'
);
$context['projects'] = Timber::get_posts( $args );

Timber::render( $templates, $context );