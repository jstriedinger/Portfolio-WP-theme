import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger'
// import Isotope from 'isotope-layout';

gsap.registerPlugin( ScrollTrigger )
const tl = gsap.timeline();

//set toop-section defaults.
gsap.set( '.anim-bottom-top .column > *', { autoAlpha: 0 } )
gsap.set( '#projects-grid > *', { autoAlpha: 0 } )


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



document.addEventListener( 'DOMContentLoaded', () => {

	tl.add(gsap.fromTo( '.anim-bottom-top .column > *:not(img)', { autoAlpha: 0, y: 50 }, { autoAlpha: 1, y: 0, duration: 1, stagger: 0.2 } ))
	tl.add(gsap.fromTo( '.anim-bottom-top .column > img', { autoAlpha: 0 }, { autoAlpha: 1, duration: 1 } ))
	tl.add(gsap.fromTo( '.anim-bottom-whole', { autoAlpha: 0, y:50 }, { autoAlpha: 1, y:0, duration: 1 } ))
	
} )

// 12-Column masonry fallback with responsive design
const initMasonry = () => {
	const container = document.querySelector('.projects-grid-masonry');
	if (!container) return;
	
	// Check if browser supports CSS Grid masonry
	const supportsMasonry = CSS.supports('grid-template-rows', 'masonry');
	
	if (!supportsMasonry) {
		container.classList.add('js-masonry');
		const items = container.querySelectorAll('.project-item');
		const gap = 16; // 1rem = 16px
		
		const layout = () => {
			const containerWidth = container.offsetWidth;
			let totalColumns = 1; // Mobile first
			
			// Determine column count based on screen size
			if (containerWidth >= 1216) { // Widescreen
				totalColumns = 12;
			} else if (containerWidth >= 769) { // Tablet AND Desktop
				totalColumns = 2;
			} else { // Mobile
				totalColumns = 1;
			}
			
			const columnWidth = (containerWidth - (gap * (totalColumns - 1))) / totalColumns;
			const columnHeights = new Array(totalColumns).fill(0);
			
			// First pass: handle the first two items - force them to be exactly 50% width ONLY on widescreen
			let maxHeightOfFirstTwo = 0;
			
			// Only apply special first-two logic on widescreen where we have 12 columns
			if (totalColumns === 12) {
				items.forEach((item, index) => {
					if (index < 2) {
						const img = item.querySelector('img');
						if (!img) return;
						
						// Both first items get exactly 6 columns (50% each)
						const columns = 6;
						const itemWidth = (columnWidth * columns) + (gap * (columns - 1));
						item.style.width = itemWidth + 'px';
						item.style.height = 'auto';
						
						// Better height calculation that works when tab is not focused
						let calculatedHeight = 0;
						
						if (img.naturalWidth && img.naturalHeight) {
							// Use natural dimensions if available
							const aspectRatio = img.naturalHeight / img.naturalWidth;
							calculatedHeight = itemWidth * aspectRatio;
						} else if (img.width && img.height) {
							// Fallback to current dimensions
							const aspectRatio = img.height / img.width;
							calculatedHeight = itemWidth * aspectRatio;
						} else {
							// Final fallback: force image to load and calculate
							const tempImg = new Image();
							tempImg.onload = () => {
								const aspectRatio = tempImg.height / tempImg.width;
								const newHeight = itemWidth * aspectRatio;
								if (newHeight > maxHeightOfFirstTwo) {
									maxHeightOfFirstTwo = newHeight;
									// Re-run layout after image loads
									requestAnimationFrame(() => layout());
								}
							};
							tempImg.src = img.src;
							// Use a reasonable default for now
							calculatedHeight = itemWidth * 0.6; // Assume 16:10 aspect ratio
						}
						
						if (calculatedHeight > maxHeightOfFirstTwo) {
							maxHeightOfFirstTwo = calculatedHeight;
						}
					}
				});
				
				// Ensure we have a minimum height if calculations failed
				if (maxHeightOfFirstTwo === 0) {
					maxHeightOfFirstTwo = columnWidth * 0.6; // Default aspect ratio
				}
			}
			
			// Second pass: position all items
			items.forEach((item, index) => {
				const img = item.querySelector('img');
				if (!img) return;
				
				let columns = 1; // Default: all items are 1 column
				
				// ONLY on widescreen, use the grid-span data
				if (totalColumns === 12) {
					if (index < 2) {
						// Both first items get exactly 6 columns (50% each)
						columns = 6;
					} else {
						columns = parseInt(item.getAttribute('data-columns')) || 1;
					}
				}
				// On all other screen sizes, columns stays 1
				
				const itemWidth = (columnWidth * columns) + (gap * (columns - 1));
				
				item.classList.add('js-positioned');
				item.style.width = itemWidth + 'px';
				
				// Special handling for first two items on widescreen only
				if (totalColumns === 12 && index < 2 && maxHeightOfFirstTwo > 0) {
					item.classList.add('large-item');
					item.style.height = maxHeightOfFirstTwo + 'px';
					
					if (index === 0) {
						item.style.left = '0px';
						item.style.top = '0px';
						// First item spans columns 0-5
						for (let i = 0; i < 6; i++) {
							columnHeights[i] = maxHeightOfFirstTwo + gap;
						}
					} else {
						// Second item starts at column 6
						item.style.left = (columnWidth * 6 + gap * 6) + 'px';
						item.style.top = '0px';
						// Second item spans columns 6-11
						for (let i = 6; i < 12; i++) {
							columnHeights[i] = maxHeightOfFirstTwo + gap;
						}
					}
				} else {
					// Regular items - use auto height
					item.style.height = 'auto';
					item.classList.remove('large-item');
					
					// Find the best position for this item
					let bestColumn = 0;
					let minHeight = Infinity;
					
					for (let col = 0; col <= totalColumns - columns; col++) {
						let maxHeightInSpan = 0;
						for (let spanCol = col; spanCol < col + columns; spanCol++) {
							maxHeightInSpan = Math.max(maxHeightInSpan, columnHeights[spanCol]);
						}
						
						if (maxHeightInSpan < minHeight) {
							minHeight = maxHeightInSpan;
							bestColumn = col;
						}
					}
					
					const x = bestColumn * (columnWidth + gap);
					const y = minHeight;
					
					item.style.left = x + 'px';
					item.style.top = y + 'px';
					
					const itemHeight = item.offsetHeight;
					for (let spanCol = bestColumn; spanCol < bestColumn + columns; spanCol++) {
						columnHeights[spanCol] = y + itemHeight + gap;
					}
				}
			});
			
			const maxHeight = Math.max(...columnHeights) - gap;
			container.style.height = maxHeight + 'px';
		};

		// Better image loading detection with visibility API support
		const images = container.querySelectorAll('img');
		let loadedCount = 0;
		const totalImages = images.length;
		let layoutExecuted = false;
		
		const executeLayout = () => {
			if (!layoutExecuted) {
				layoutExecuted = true;
				// Use multiple requestAnimationFrame to ensure DOM is ready
				requestAnimationFrame(() => {
					requestAnimationFrame(() => {
						layout();
					});
				});
			}
		};
		
		const onImageLoad = () => {
			loadedCount++;
			if (loadedCount === totalImages) {
				executeLayout();
			}
		};
		
		// Handle page visibility changes
		const handleVisibilityChange = () => {
			if (!document.hidden) {
				// Page became visible, re-layout after a short delay
				setTimeout(() => {
					layoutExecuted = false;
					executeLayout();
				}, 100);
			}
		};
		
		document.addEventListener('visibilitychange', handleVisibilityChange);
		
		if (totalImages === 0) {
			executeLayout();
		} else {
			images.forEach(img => {
				if (img.complete && img.naturalHeight !== 0) {
					onImageLoad();
				} else {
					img.addEventListener('load', onImageLoad);
					img.addEventListener('error', onImageLoad);
				}
			});
		}
		
		// Multiple fallback attempts with longer delays for unfocused tabs
		setTimeout(() => {
			executeLayout();
		}, 1000);
		
		setTimeout(() => {
			layoutExecuted = false;
			executeLayout();
		}, 2000);
		
		// Additional fallback for when tab becomes focused
		window.addEventListener('focus', () => {
			setTimeout(() => {
				layoutExecuted = false;
				executeLayout();
			}, 100);
		});
		
		// Layout on resize
		window.addEventListener('resize', () => {
			clearTimeout(window.resizeTimeout);
			window.resizeTimeout = setTimeout(() => {
				layoutExecuted = false;
				layout();
			}, 250);
		});
	} else {
		// For browsers with native CSS Grid masonry, CSS handles responsiveness
	}
};

// Video hover functionality
const initVideoHover = () => {
	const hoverVideoItems = document.querySelectorAll('.project-item.has-video');
	const autoVideoItems = document.querySelectorAll('.project-item.only-video');
	
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

document.addEventListener('DOMContentLoaded', () => {
	tl.add(gsap.fromTo( '.anim-bottom-top .column > *:not(img)', { autoAlpha: 0, y: 50 }, { autoAlpha: 1, y: 0, duration: 1, stagger: 0.2 } ))
	tl.add(gsap.fromTo( '.anim-bottom-top .column > img', { autoAlpha: 0 }, { autoAlpha: 1, duration: 1 } ))
	tl.add(gsap.fromTo( '.anim-bottom-whole', { autoAlpha: 0, y:50 }, { autoAlpha: 1, y:0, duration: 1 } ))
	
	// Initialize masonry immediately
	initMasonry();
	
	// Initialize video hover
	initVideoHover();
	
	// Also try again after a delay as additional fallback
	setTimeout(initMasonry, 1000);
});