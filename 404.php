<?php
/**
 * 404 — page not found.
 */

get_header();
?>
<section class="section anim-bottom-top">
	<div class="container is-max-widescreen">
		<div class="columns">
			<div class="column is-8">
				<p class="is-size-7 is-uppercase has-text-weight-bold has-text-grey-light mb-2">404</p>
				<h1 class="title is-size-1 canela-font">Page not found</h1>
				<p class="subtitle">That page doesn't exist, or it moved.</p>
				<div class="buttons">
					<a class="button is-link is-rounded is-medium" href="<?php echo esc_url( home_url( '/' ) ); ?>">
						<span class="icon"><i class="fa-solid fa-arrow-left" aria-hidden="true"></i></span>
						<span>Back home</span>
					</a>
				</div>
			</div>
		</div>
	</div>
</section>
<?php
get_footer();
