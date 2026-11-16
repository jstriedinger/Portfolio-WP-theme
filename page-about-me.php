<?php
/**
 * About me (WordPress picks this up for the page with the slug "about-me").
 *
 * The page title on top, then the featured image beside the page content (side set by
 * $image_side below), then a short "get in touch" title with the email and social buttons. Contact title and email are
 * ACF fields on that page (acf-json/group_6a0b1c2d3e4f5.json).
 * The email address is never printed: it ships reversed + base64 in data-email and app.js
 * (initEmailReveal) only decodes it on click, so scrapers that don't click never see it.
 */

get_header();

$image_side    = 'left'; // 'left' or 'right': the only line to change to flip the photo
$contact_title = get_field( 'about_contact_title' );
$email         = get_field( 'contact_email' );
$email_data    = $email ? base64_encode( strrev( $email ) ) : '';
?>

<section class="section about-page anim-bottom-top" id="about-hero">
	<div class="container">
		<h1 class="subtitle is-size-4 has-text-white"><?php the_title(); ?></h1>
		<div class="columns is-variable is-8 about-layout<?php echo 'right' === $image_side ? ' is-image-right' : ''; ?>">
			<?php if ( has_post_thumbnail() ) : ?>
				<div class="column is-4">
					<figure class="about-photo image is-aspect-ratio-3by4"><?php the_post_thumbnail( 'large', array( 'data-no-lazy' => '1' ) ); ?></figure>
				</div>
			<?php endif; ?>
			<div class="column is-8">
				<div class="content is-size-5 about-body"><?php the_content(); ?></div>
				<br>
				<?php if ( $contact_title ) : ?>
					<h2 class="title is-4 canela-font"><?php echo esc_html( $contact_title ); ?></h2>
				<?php endif; ?>
				<div class="buttons">
					<?php if ( $email_data ) : ?>
						<button type="button" class="button is-link is-rounded js-email-open" data-email="<?php echo esc_attr( $email_data ); ?>">
							<span class="icon"><i class="fa-solid fa-envelope" aria-hidden="true"></i></span>
							<span>Email me</span>
						</button>
					<?php endif; ?>
					<a class="button is-rounded is-social" href="https://github.com/jstriedinger/CodeSamples" aria-label="GitHub">
						<span class="icon"><i class="fab fa-github" aria-hidden="true"></i></span>
					</a>
					<a class="button is-rounded is-social" href="https://www.linkedin.com/in/jstriedinger/" aria-label="LinkedIn">
						<span class="icon"><i class="fab fa-linkedin-in" aria-hidden="true"></i></span>
					</a>
					<a class="button is-rounded is-social" href="https://jstriedinger.itch.io/" aria-label="itch.io">
						<span class="icon"><i class="fa-brands fa-itch-io" aria-hidden="true"></i></span>
					</a>
				</div>
			</div>
		</div>
	</div>
</section>

<?php
get_footer();
