<?php
/**
 * Shared trailer modal (Bulma .modal). Printed once per page that lists projects with a trailer.
 * initVideoModal() in app.js fills the title, role line, project link and iframe from the clicked
 * .trailer-play button's data attributes, and clears the iframe on close so the video stops.
 */
?>
<div class="modal" id="video-modal" role="dialog" aria-modal="true" aria-labelledby="video-modal-title">
	<div class="modal-background"></div>
	<div class="modal-content">
		<div class="is-flex is-align-items-flex-end is-justify-content-space-between is-flex-wrap-wrap is-gap-3 mb-4">
			<div>
				<h3 class="title is-3 canela-font mb-1" id="video-modal-title"></h3>
				<p class="role-text is-size-6" id="video-modal-role"></p>
			</div>
			<a class="button is-link is-rounded" id="video-modal-link" href="#">
				<span>View project</span>
				<span class="icon"><i class="fa-solid fa-arrow-right" aria-hidden="true"></i></span>
			</a>
		</div>
		<div class="video-frame is-relative is-aspect-ratio-16by9 is-clipped">
			<iframe id="video-modal-frame" class="is-overlay" title="Trailer" allow="autoplay; encrypted-media; fullscreen" allowfullscreen></iframe>
		</div>
	</div>
	<button type="button" class="modal-close is-large" aria-label="Close"></button>
</div>
