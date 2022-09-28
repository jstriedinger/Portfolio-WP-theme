import gsap from 'gsap';
const tl = gsap.timeline();

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
		tl.add(gsap.fromTo(".projects-grid article", {autoAlpha: 0, y: 120}, {autoAlpha: 1, y: 0, duration: 1,stagger: 0.15}));
	}
}
document.addEventListener( 'DOMContentLoaded', () => {
	//create a timeline instance
	
	const projectsNode = document.getElementById("projects-grid")
	tl.add(gsap.fromTo(".container > .columns > .column:first-child > *", {autoAlpha: 0, y: 120}, {autoAlpha: 1, y: 0, duration: 1,stagger: 0.15}));
	tl.add(gsap.fromTo(".projects-grid article", {autoAlpha: 0, y: 120}, {autoAlpha: 1, y: 0, duration: 1,stagger: 0.15}));
	
	//manage project categories
	const projects = document.querySelectorAll(".card.project")
	const catSelect = document.getElementById('project-categories')
	if ( catSelect !== null ) {
		catSelect.addEventListener( 'change', ( event ) => {
			const cat = event.target.value;
			getProjects(cat,projectsNode);
	
			/*[ ...projects ].forEach( ( project ) => {
				if (! project.dataset.categories.includes(val)) {
					project.classList.add("is-hidden")
				} else {
					project.classList.remove("is-hidden")
				}
			} )*/
		} )
	}
} )
