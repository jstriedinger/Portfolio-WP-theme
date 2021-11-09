<?php
/**
 * The main template file
 */
$context = Timber::get_context();
$context['post'] = new Timber\Post();
$templates = array( 'blog-post.twig' );

Timber::render( $templates, $context );