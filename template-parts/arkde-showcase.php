<?php
/**
 * Template part for the Arkde showcase section on the front page.
 * Data sourced from ACF fields on the front page post.
 */
$arkde          = get_field( 'arkde_section' );
$arkde_title    = $arkde['arkde_title'] ?? '';
$arkde_body     = $arkde['arkde_body'] ?? '';
$arkde_cta      = $arkde['arkde_cta_link'] ?? null;
$arkde_bg_image = $arkde['arkde_bg_image'] ?? '';
?>
<div class="content">
	<h2 class="title is-size-1 has-text-centered">Early years <strong>developing the game industry</strong>	<br>in Latin America</h2>

</div>
<section class="arkde-showcase section">
	<div class="arkde-showcase__wave arkde-showcase__wave--top">
		<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 80" preserveAspectRatio="none">
			<path d="M0,0 L1440,0 L1440,50 C1080,80 360,0 0,30 Z"/>
		</svg>
	</div>

	<?php if ( $arkde_bg_image ) : ?>
	<img class="arkde-showcase__bg-image" src="<?php echo esc_url( $arkde_bg_image ); ?>" alt="" aria-hidden="true">
	<?php endif; ?>

	<div class="container is-fluid my-6 py-3">
		<div class="columns is-vcentered">
			<div class="column is-half"></div>
			<div class="column is-half arkde-showcase__col-text content has-text-justified">
				<?php if ( $arkde_title ) : ?>
				<img class="arkde-showcase__title-img" src="<?php echo esc_url( $arkde_title ); ?>" alt="">
				<?php endif; ?>
				<?php if ( $arkde_body ) : ?>
				<?php echo wp_kses_post( $arkde_body ); ?>
				<?php endif; ?>
				<?php if ( $arkde_cta && $arkde_cta['url'] ) : ?>
				<div class="has-text-centered mt-4">
					<a href="<?php echo esc_url( $arkde_cta['url'] ); ?>"
					   class="button is-arkde"
					   <?php echo $arkde_cta['target'] ? 'target="' . esc_attr( $arkde_cta['target'] ) . '" rel="noopener"' : ''; ?>>
						<?php echo esc_html( $arkde_cta['title'] ); ?>
					</a>
				</div>
				<?php endif; ?>
			</div>
		</div>
	</div>

	<div class="arkde-showcase__wave arkde-showcase__wave--bottom">
		<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 80" preserveAspectRatio="none">
			<path d="M0,50 C360,80 1080,0 1440,30 L1440,80 L0,80 Z"/>
		</svg>
	</div>
</section>
