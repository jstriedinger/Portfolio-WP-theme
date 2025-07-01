<?php
/**
 * Single Blog Post Template
 * 
 * This template is used for individual blog posts
 */

get_header();

if (have_posts()) :
    while (have_posts()) : the_post();
?>

<section class="section">
    <div class="container is-max-desktop">
        <div class="columns is-centered">
            <div class="column is-full">
                <h1 class="title is-size-1 canela-font gradient-text has-text-weight-bold">
                    <?php the_title(); ?>
                </h1>
                <p class="subtitle mt-2 has-text-weight-bold has-text-theme-primary">
                    <?php echo get_the_date('F j, Y'); ?>
                </p>
            </div>
        </div>
    </div>
</section>

<section class="section pt-2">
    <div class="container is-max-desktop">
        <div class="columns">
            <div class="column is-full">
                <div class="content">
                    <?php the_content(); ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
    endwhile;
endif;

get_footer();
?>
