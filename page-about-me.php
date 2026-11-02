<?php
/**
 * About me (WordPress picks this up for the page with the slug "about-me").
 *
 * The page's content (title included) on the left, its featured image on the right, then a
 * short "get in touch" title with the email and social buttons. Contact title and email are
 * ACF fields on that page (acf-json/group_6a0b1c2d3e4f5.json).
 * The email address is never printed: it ships reversed + base64 in data-email and app.js
 * (initEmailReveal) only decodes it on click, so scrapers that don't click never see it.
 */

get_header();

$contact_title = get_field( 'about_contact_title' );
$email         = get_field( 'contact_email' );
$email_data    = $email ? base64_encode( strrev( $email ) ) : '';
?>

<section class="section anim-bottom-top" id="about-hero">
	<div class="container is-max-widescreen">
		<div class="columns is-variable is-8">
			<div class="column is-7">
				<div class="content is-size-5"><?php the_content(); ?></div>
			</div>
			<?php if ( has_post_thumbnail() ) : ?>
				<div class="column is-4 is-offset-1">
					<figure class="image is-aspect-ratio-3by4"><?php the_post_thumbnail( 'large' ); ?></figure>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>

<section class="section pt-0 mb-6" id="contact">
	<div class="container is-max-widescreen">
		<div class="columns">
			<div class="column is-7">
				<?php if ( $contact_title ) : ?>
					<h2 class="title is-4 canela-font"><?php echo esc_html( $contact_title ); ?></h2>
				<?php endif; ?>
				<div class="buttons">
					<?php if ( $email_data ) : ?>
						<button type="button" class="button is-link is-rounded js-email-open" data-email="<?php echo esc_attr( $email_data ); ?>">
							<span class="icon"><i class="fa-solid fa-envelope" aria-hidden="true"></i></span>
							<span>Email me</span>
						</button>
						<button type="button" class="button is-rounded js-email-copy" data-email="<?php echo esc_attr( $email_data ); ?>" aria-label="Copy email address" title="Copy email address">
							<span class="icon"><i class="fa-regular fa-copy" aria-hidden="true"></i></span>
						</button>
					<?php endif; ?>
					<a class="button is-rounded" href="https://github.com/jstriedinger/CodeSamples" aria-label="GitHub">
						<span class="icon"><i class="fab fa-github" aria-hidden="true"></i></span>
					</a>
					<a class="button is-rounded" href="https://www.linkedin.com/in/jstriedinger/" aria-label="LinkedIn">
						<span class="icon"><i class="fab fa-linkedin-in" aria-hidden="true"></i></span>
					</a>
					<a class="button is-rounded" href="https://jstriedinger.itch.io/" aria-label="itch.io">
						<span class="icon"><i class="fa-brands fa-itch-io" aria-hidden="true"></i></span>
					</a>
				</div>
			</div>
		</div>
	</div>
</section>

<?php
get_footer();
