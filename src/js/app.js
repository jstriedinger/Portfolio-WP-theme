import { gsap } from 'gsap';

const tl = gsap.timeline();

//set toop-section defaults.
gsap.set( '.anim-bottom-top .column > *', { autoAlpha: 0 } )
gsap.set( '#projects-grid > *', { autoAlpha: 0 } )

// Front-page hero intro: start hidden so nothing flashes once the loading overlay fades.
const heroReducedMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches
const heroTextTargets = '.home .intro .title, .home .intro .subtitle, .home .intro .buttons > *'
if ( ! heroReducedMotion ) {
	gsap.set( [ heroTextTargets, '.home .intro-video', '.home .navbar .navbar-item' ], { autoAlpha: 0 } )
}


const getProjects = async (cat, projectsNode) => {
	const fetchData = new FormData()
	fetchData.append( 'action', 'get_projects' )
	fetchData.append( 'nonce', jrsp_ajax.nonce )
	fetchData.append( 'category', cat )
	projectsNode.classList.add("is-loading")
	let response = await fetch( jrsp_ajax.ajaxurl, {
		method: 'POST',
		credentials: 'same-origin',
		body: fetchData,
	} )
		.then( ( response ) => response.json() )

	//lets map the course preview data
	if ( response.success ) {
		projectsNode.innerHTML = ''
		//const buyNowBtn = coursesData.cta_text
		const projectsData = response.data;
		//Lets get course preview data ready when click
		[ ...projectsData ].forEach( ( projectData ) => {

			let projectHtml = `<a href="${projectData.permalink}" title="${projectData.name}" class="has-card ${projectData.grid_dir}">
													<article class="card project" style="background-image: url(${projectData.image})" >
															<div class="card-content">
																	<h3 class="is-size-5 ${projectData.grid_dir == 'horizontal' ? 'is-size-3-widescreen' : ''}">${projectData.name}</h3>
																	<p class="link is-size-7 has-tiny-margin-top is-hidden-desktop">Read more</p>
															</div>
													</article>
													</a>`;
			projectsNode.innerHTML += projectHtml
		} )
		projectsNode.classList.remove("is-loading")
		tl.add(gsap.fromTo("#projects-grid article", {autoAlpha: 0, y: 120}, {autoAlpha: 1, y: 0, duration: 1,stagger: 0.15}));
	}
}

// Video hover functionality
const initVideoHover = () => {
	const hoverVideoItems = document.querySelectorAll('.showcase-row.has-video, .showcase-card.has-video');
	const autoVideoItems = document.querySelectorAll('.showcase-row.only-video, .showcase-card.only-video');
	
	// Handle hover videos
	hoverVideoItems.forEach(item => {
		const video = item.querySelector('.project-video');
		if (!video) return;
		
		let isLoaded = false;
		
		const loadVideo = () => {
			if (!isLoaded) {
				video.load();
				isLoaded = true;
			}
		};
		
		const playVideo = () => {
			loadVideo();
			video.play().catch(e => {
				console.log('Video autoplay prevented:', e);
			});
		};
		
		const pauseVideo = () => {
			video.pause();
			video.currentTime = 0;
		};
		
		item.addEventListener('mouseenter', () => {
			playVideo();
		});
		
		item.addEventListener('mouseleave', () => {
			pauseVideo();
		});
		
		// Preload on scroll into view
		const observer = new IntersectionObserver((entries) => {
			entries.forEach(entry => {
				if (entry.isIntersecting) {
					loadVideo();
					observer.unobserve(entry.target);
				}
			});
		}, { threshold: 0.1 });
		
		observer.observe(item);
	});
	
	// Handle auto-playing videos
	autoVideoItems.forEach(item => {
		const video = item.querySelector('.project-video');
		if (!video) return;
		
		let isLoaded = false;
		
		const loadAndPlayVideo = () => {
			if (!isLoaded) {
				video.load();
				isLoaded = true;
			}
			video.play().catch(e => {
				console.log('Video autoplay prevented:', e);
			});
		};
		
		// Auto-play when scrolled into view
		const observer = new IntersectionObserver((entries) => {
			entries.forEach(entry => {
				if (entry.isIntersecting) {
					loadAndPlayVideo();
					observer.unobserve(entry.target);
				}
			});
		}, { threshold: 0.1 });
		
		observer.observe(item);
	});
};

const initReelModal = () => {
	const reelBox = document.querySelector('.reel-box');
	if (!reelBox) return;

	const modal  = document.getElementById('reel-modal');
	const iframe = document.getElementById('reel-iframe');

	reelBox.addEventListener('click', () => {
		iframe.src = reelBox.dataset.embed + '?autoplay=1';
		modal.classList.add('is-active');
	});

	const closeModal = () => {
		modal.classList.remove('is-active');
		iframe.src = '';
	};

	modal.querySelector('.modal-background').addEventListener('click', closeModal);
	modal.querySelector('.modal-close').addEventListener('click', closeModal);
};

const initReelVideo = () => {
	const video = document.querySelector('.reel-video');
	if (!video) return;

	video.play().catch(() => {});

	document.addEventListener('visibilitychange', () => {
		if (document.hidden) {
			video.pause();
		} else {
			video.play().catch(() => {});
		}
	});

	let retryTimer;
	const retryPlay = () => {
		clearTimeout(retryTimer);
		retryTimer = setTimeout(() => {
			video.load();
			video.play().catch(() => {});
		}, 2000);
	};

	video.addEventListener('error', retryPlay);
	video.addEventListener('stalled', retryPlay);
};

// About page "Get in touch": the address is never in the HTML. PHP ships it reversed + base64 in
// data-email; it's only decoded here, on click, so scrapers that don't run JS and click never
// see it. "Email me" opens the visitor's mail app, the copy button covers people without one.
const initEmailReveal = () => {
	const decode = ( el ) => atob( el.dataset.email ).split( '' ).reverse().join( '' );

	document.querySelectorAll( '.js-email-open' ).forEach( ( btn ) => {
		btn.addEventListener( 'click', () => { window.location.href = 'mailto:' + decode( btn ); } );
	} );

	document.querySelectorAll( '.js-email-copy' ).forEach( ( btn ) => {
		const icon = btn.querySelector( 'i' );
		btn.addEventListener( 'click', async () => {
			try {
				await navigator.clipboard.writeText( decode( btn ) );
			} catch ( e ) {
				return;
			}
			icon.className = 'fa-solid fa-check';
			setTimeout( () => { icon.className = 'fa-regular fa-copy'; }, 2000 );
		} );
	} );
};

// Single project Engineering / Design tabs (single-project.php). Each tab's panel is the Group
// block with that HTML anchor; the others get Bulma's is-hidden. The URL hash picks the tab, so
// /project/x/#engineering deep-links straight to it. Without JS every section just shows.
const initProjectTabs = () => {
	const tabs = Array.from(document.querySelectorAll('.project-tabs li[data-tab]'));
	if (!tabs.length) return;

	const show = (id) => {
		tabs.forEach(tab => {
			const active = tab.dataset.tab === id;
			tab.classList.toggle('is-active', active);
			const panel = document.getElementById(tab.dataset.tab);
			if (panel) panel.classList.toggle('is-hidden', !active);
		});
	};

	const fromHash = () => {
		const hash = window.location.hash.slice(1);
		return tabs.some(tab => tab.dataset.tab === hash) ? hash : tabs[0].dataset.tab;
	};

	tabs.forEach(tab => {
		tab.querySelector('a').addEventListener('click', (e) => {
			e.preventDefault();
			show(tab.dataset.tab);
			history.replaceState(null, '', '#' + tab.dataset.tab);
			window.dispatchEvent(new Event('scroll')); // sidebar + scroll-spy re-measure
		});
	});

	window.addEventListener('hashchange', () => show(fromHash()));
	show(fromHash());
};

const initSidebarSticky = () => {
	const sidebar = document.getElementById('project-sidebar');
	if (!sidebar) return;

	const section   = document.getElementById('project-content-section');
	const container = section && section.querySelector('.container');
	if (!section || !container) return;

	const OFFSET = 32; // px from viewport top when sticking

	const update = () => {
		const sectionRect   = section.getBoundingClientRect();
		const containerRect = container.getBoundingClientRect();

		// Position left: right edge of sidebar aligns with left edge of container
		sidebar.style.left = (containerRect.left - sectionRect.left - sidebar.offsetWidth - 24) + 'px';

		// Sticky top: offset relative to section (absolute positioning context)
		const scrolledPast = -(sectionRect.top - OFFSET); // how far past OFFSET we've scrolled
		const maxTop       = section.offsetHeight - sidebar.offsetHeight; // can't go past section bottom

		sidebar.style.top = Math.min(Math.max(scrolledPast, 0), maxTop) + 'px';
	};

	window.addEventListener('scroll', update, { passive: true });
	window.addEventListener('resize', update);
	update();
};

const initSectionScrollSpy = () => {
	const sidebar = document.getElementById('project-sidebar');
	if (!sidebar) return;

	const links = Array.from(sidebar.querySelectorAll('.panel-block'));
	if (!links.length) return;

	const sections = links
		.map(l => document.getElementById(l.getAttribute('href').slice(1)))
		.filter(Boolean);
	if (!sections.length) return;

	const getParentLink = (link) => {
		if (!link.classList.contains('is-child')) return null;
		let prev = link.previousElementSibling;
		while (prev) {
			if (prev.classList.contains('panel-block') && !prev.classList.contains('is-child')) return prev;
			prev = prev.previousElementSibling;
		}
		return null;
	};

	const setActive = (id) => {
		links.forEach(l => l.classList.remove('is-active', 'has-active-child'));
		const active = links.find(l => l.getAttribute('href') === '#' + id);
		if (!active) return;
		active.classList.add('is-active');
		const parent = getParentLink(active);
		if (parent) parent.classList.add('has-active-child');
	};

	const onScroll = () => {
		const threshold = window.innerHeight * 0.25;
		let current = sections[0].id;
		for (const section of sections) {
			if (section.getBoundingClientRect().top <= threshold) {
				current = section.id;
			}
		}
		setActive(current);
	};

	links.forEach(link => {
		link.addEventListener('click', (e) => {
			e.preventDefault();
			const target = document.getElementById(link.getAttribute('href').slice(1));
			if (target) target.scrollIntoView({ behavior: 'smooth' });
		});
	});

	window.addEventListener('scroll', onScroll, { passive: true });
	onScroll();
};

// Loading screen management
const initLoadingScreen = () => {
	const loadingOverlay = document.getElementById('loading-overlay');
	if (!loadingOverlay) return;

	let resourcesLoaded = false;
	let timeoutReached = false;
	let animationsStarted = false;

	const hideLoadingScreen = () => {
		if ((resourcesLoaded || timeoutReached) && !animationsStarted) {
			animationsStarted = true;
			
			// Use GSAP to fade out loading screen and fade in body, then start main animations
			const loadingTimeline = gsap.timeline();
			
			loadingTimeline
				.to(loadingOverlay, { 
					opacity: 0, 
					duration: 0.5, 
					ease: "power2.out" 
				})
				.to(document.body, { 
					opacity: 1, 
					duration: 0.5, 
					ease: "power2.out" 
				}, "-=0.3") // Start body fade slightly before overlay finishes
				.set(loadingOverlay, { display: 'none' }) // Remove from DOM
				.call(() => {
					document.body.classList.add('loaded');
					startMainAnimations(); // Start main animations when fadeout completes
				});
		}
	};

	// Check if critical resources are loaded
	const checkResourcesLoaded = () => {
		// Only wait for eagerly-loaded images — lazy images aren't fetched on load
		const images = document.querySelectorAll('img:not([loading="lazy"])');
		let imageLoadCount = 0;
		const totalImages = images.length;

		if (totalImages === 0) {
			resourcesLoaded = true;
			hideLoadingScreen();
			return;
		}

		const onImageLoad = () => {
			imageLoadCount++;
			if (imageLoadCount >= totalImages) {
				resourcesLoaded = true;
				hideLoadingScreen();
			}
		};

		images.forEach(img => {
			if (img.complete && img.naturalHeight !== 0) {
				onImageLoad();
			} else {
				img.addEventListener('load', onImageLoad);
				img.addEventListener('error', onImageLoad);
			}
		});
	};

	// Safety timeout - force show after 5 seconds
	setTimeout(() => {
		timeoutReached = true;
		hideLoadingScreen();
	}, 5000);

	// Start checking resources
	checkResourcesLoaded();
};

// Hero intro: reel video fades up behind while the copy sweeps in left to right.
// Kept subtle — short distances, one soft ease, ~1.5s total.
// Hero background reel (front page). It is 4+ MB and a moving backdrop is wasted data and battery
// on a phone, so it only loads from tablet width up (the markup also hides it on mobile with
// Bulma's is-hidden-mobile). Phones get the plain navy hero. Re-checked if the width changes,
// e.g. a phone rotated to landscape.
const initHeroReel = () => {
	const video = document.querySelector( '.home .intro-video video[data-src]' )
	if ( ! video ) return

	const wide = window.matchMedia( '(min-width: 769px)' )
	const load = () => {
		if ( ! wide.matches || video.getAttribute( 'src' ) ) return
		video.src = video.dataset.src
		video.play().catch( () => {} )
	}
	load()
	wide.addEventListener( 'change', load )
}

const initHeroIntro = () => {
	// Reduced motion: no intro, and hold the background reel on its first frame.
	if ( heroReducedMotion ) {
		const reel = document.querySelector( '.home .intro-video video' )
		if ( reel ) reel.pause()
		return
	}
	if ( ! document.querySelector( '.home .intro' ) ) return

	const intro = gsap.timeline( { defaults: { ease: 'power3.out' } } )

	intro
		.fromTo( '.home .navbar .navbar-item', { autoAlpha: 0, y: -8 }, { autoAlpha: 1, y: 0, duration: 0.8, stagger: 0.06 }, 0 )
		.fromTo( '.home .intro-video', { autoAlpha: 0, scale: 1.04 }, { autoAlpha: 1, scale: 1, duration: 2, ease: 'power2.out' }, 0 )
		// Name: wiped in from the left (clip-path) while sliding a touch — padded top/bottom so descenders aren't clipped.
		.fromTo( '.home .intro .title',
			{ autoAlpha: 0, x: -32, clipPath: 'inset(-10% 100% -10% 0)' },
			{ autoAlpha: 1, x: 0, clipPath: 'inset(-10% -2% -10% 0)', duration: 1.3, ease: 'power2.out', clearProps: 'clipPath' }, 0.3 )
		.fromTo( '.home .intro .subtitle', { autoAlpha: 0, x: -24 }, { autoAlpha: 1, x: 0, duration: 1 }, 0.6 )
		.fromTo( '.home .intro .buttons > *', { autoAlpha: 0, x: -16 }, { autoAlpha: 1, x: 0, duration: 0.8, stagger: 0.1 }, 0.8 )
}

// Separate function for main animations
const startMainAnimations = () => {
	// Clear any existing timeline to prevent duplicates
	tl.clear();
		initHeroIntro();
	
	// GSAP animations start immediately when called
	tl.add(gsap.fromTo( '.anim-bottom-top .column > *:not(img)', { autoAlpha: 0, y: 50 }, { autoAlpha: 1, y: 0, duration: 1.5, stagger: 0.2 } ))
	tl.add(gsap.fromTo( '.anim-bottom-top .column > img', { autoAlpha: 0 }, { autoAlpha: 1, duration: 1.5 } ))
	tl.add(gsap.fromTo( '.anim-bottom-whole', { autoAlpha: 0, y:50 }, { autoAlpha: 1, y:0, duration: 1.5 } ))
	
	// Initialize functionality after animations start
	initVideoHover();
	initReelModal();
	initReelVideo();
	initSidebarSticky();
	initSectionScrollSpy();
};

// Updated DOMContentLoaded event listener
document.addEventListener('DOMContentLoaded', () => {
	initProjectTabs(); // before the loading screen lifts, so the hidden tab never flashes
	initEmailReveal();
	initHeroReel(); // starts the reel at page load, not after the loading screen
	// Initialize loading screen first
	initLoadingScreen();
});