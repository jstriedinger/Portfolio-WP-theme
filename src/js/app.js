import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger'

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
