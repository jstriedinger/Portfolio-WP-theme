import gsap from 'gsap';

$(function()
{
    //create a timeline instance
    var tl = gsap.timeline();

    //the following two lines do the SAME thing:

    tl.add(gsap.fromTo(".container > .columns > .column:first-child > *", {autoAlpha: 0, y: 120}, {autoAlpha: 1, y: 0, duration: 1,stagger: 0.15}));
    tl.add(gsap.fromTo(".projects-grid article", {autoAlpha: 0, y: 120}, {autoAlpha: 1, y: 0, duration: 1,stagger: 0.15}));

		//manage project categories
		const projects = document.querySelectorAll(".card.project")
		const catSelect = document.getElementById('project-categories')
		if ( catSelect !== null ) {
			catSelect.addEventListener( 'change', ( event ) => {
				const val = event.target.value;
				[ ...projects ].forEach( ( project ) => {
					if (! project.dataset.categories.includes(val)) {
						project.classList.add("is-hidden")
					} else {
						project.classList.remove("is-hidden")
					}
				} )
			} )

		}
});
