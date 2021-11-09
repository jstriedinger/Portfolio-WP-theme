<?php
/*
Template Name: About Me
*/
$context = Timber::get_context();
$context['post'] = new Timber\Post();
$templates = array( 'about.twig' );

$args = array (
    'post_type'  => 'project',
    'posts_per_page' => '-1'
);
$context['projects'] = Timber::get_posts( $args );
Timber::render( $templates, $context );