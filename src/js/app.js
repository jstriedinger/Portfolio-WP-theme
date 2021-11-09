import gsap from 'gsap';

$(function()
{
    //create a timeline instance
    var tl = gsap.timeline();

    //the following two lines do the SAME thing:

    tl.add(gsap.fromTo(".container > .columns > .column:first-child > *", {autoAlpha: 0, y: 120}, {autoAlpha: 1, y: 0, duration: 1,stagger: 0.15}));
    tl.add(gsap.fromTo(".projects-grid article", {autoAlpha: 0, y: 120}, {autoAlpha: 1, y: 0, duration: 1,stagger: 0.15}));
});
