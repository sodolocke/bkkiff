/*global wp_ajax_object, masonry, wpcf7_recaptcha */
import { Alert, Button, Carousel, Collapse, Dropdown, Modal } from 'bootstrap'; //quiet
//, Offcanvas, Popover, ScrollSpy, Tab, Toast, Tooltip
import ScrollTrigger from "gsap/ScrollTrigger.js" //quiet
import ScrollToPlugin from "gsap/ScrollToPlugin.js" //quiet
import gsap from 'gsap' //quiet
import {BProgress} from '@bprogress/core' //quiet

const bootstrap = {
	Alert,
	Button,
	Carousel,
	Collapse,
	Dropdown,
	Modal,
};
/*
Offcanvas,
Popover,
ScrollSpy,
Tab,
Toast,
Tooltip
*/

class n4d {
	constructor() {
		this.body       = document.querySelector('body')
		this.wrapper    = document.querySelector('.wrapper')
		this.footer     = document.querySelector('#main-footer')
		this.navigation = document.querySelector('#menu-bar')
		this.navToggle  = document.querySelector('.navbar-toggler')
		this.backToTop  = document.querySelector(".back-to-top")
		this.accordions = document.querySelectorAll(".accordion.horizontal .accordion-button")
		this.scrollTriggers = []

		gsap.registerPlugin(ScrollTrigger, ScrollToPlugin)//Observer, ScrollSmoother

	}
	init(){
//Show page
		if (this.wrapper) this.wrapper.classList.add('show');
//Navigation
		if (this.navigation) this.initNav()

		this.slides()
		this.scrollBox()
		this.popup()

		const searchBox = document.querySelector("#search-box")
		if (searchBox) {
			const searchCollapse = new bootstrap.Collapse('#collapseSearch', {
				toggle: false
			})

			searchBox.addEventListener("mouseenter", e => {
				const search = e.target.querySelector("#s")
				if (searchCollapse){
					searchCollapse.show()
					setTimeout( e => {
						search.focus()
					}, 400)
				}
				console.log('over')
			})
			searchBox.addEventListener("mouseleave", e => {
				const search = e.target.querySelector("#s")

				if (searchCollapse && !search.hasFocus()){
					searchCollapse.hide()
				}
			})
		}

		const cf7Floating = document.querySelectorAll(".wpcf7-form .form-floating")
		if (cf7Floating){
			cf7Floating.forEach( input => {
				const cf7wrap = input.closest(".wpcf7-form-control-wrap")


				const wrapper = document.createElement("div")
				wrapper.classList.add("form-floating")
				wrapper.classList.add("mb-3")
				const label = document.createElement("label")
				label.setAttribute("for", input.id)
				label.innerHTML = input.placeholder

				wrapper.appendChild(input)
				wrapper.appendChild(label)

				cf7wrap.appendChild(wrapper)
			} )
		}

//		var player = videojs('video-player');

//console.log('check', videojs);


	}
	scrollBox(){
		const scrollBoxes = document.querySelectorAll(".scroll-box")
		scrollBoxes.forEach(box => {
			const box_height    = box.offsetHeight
			const scroll_height = box.scrollHeight
			const scroll_scale  = box_height/scroll_height
			const scrub_height  = scroll_scale * box_height
			const is_init       = box.classList.contains("init")

			if (scroll_height > box_height && !is_init){
				const content  = document.createElement('div')
				const scroller = document.createElement('div')
				const scrub    = document.createElement('div')

				content.innerHTML = box.innerHTML
				box.innerHTML     = ""

				scrub.classList.add("scrub")
				scrub.style.height = scrub_height+"px"

				content.classList.add("scroll-content")

				scroller.classList.add("scroller")
				scroller.appendChild(scrub)

				box.appendChild(content)
				box.appendChild(scroller)


				content.addEventListener("scroll", e => {
					const box           = content.parentElement
					const box_height    = box.offsetHeight
					const scroll_height = content.scrollHeight

					const scroll_scale  = box_height/scroll_height
					const scrub_height  = 10//scroll_scale * box_height
					const scrub         = box.querySelector(".scrub")
					scrub.style.top     = (e.target.scrollTop * ( (box_height - (scrub_height/2) )/scroll_height ) )+"px"
				})

				box.classList.add('init')
			}

			const active = box.querySelector(".nav-link.active")
			if (active){
				const scroll_content = box.querySelector(".scroll-content")
				gsap.to(scroll_content, { duration: 0.4, scrollTo: active.offsetTop });
			}


		})

		const triggerOffcanvas = document.querySelectorAll('[href="#offcanvas-timeline"]')
		const myOffcanvas = document.querySelector('#offcanvas-timeline')
		if (myOffcanvas){
			myOffcanvas.addEventListener('show.bs.offcanvas', event => {
				triggerOffcanvas.forEach(trigger => {
					trigger.setAttribute("aria-expanded", true)
				})
			})
			myOffcanvas.addEventListener('hide.bs.offcanvas', event => {
				triggerOffcanvas.forEach(trigger => {
					trigger.setAttribute("aria-expanded", false)
				})
			})
		}
	}
	slides(){
		this.resize()
		const scrollSlide = document.querySelector(".slide-scroll")
		const sections    = (scrollSlide) ? scrollSlide.querySelectorAll('.slide') : []
		const scrolling   = {
			enabled: true,
			started: false,
			events: "scroll,wheel,touchmove,pointermove".split(","),
			prevent: e => e.preventDefault(),
			disable() {
				if (scrolling.enabled) {
					scrolling.enabled = false;
					window.addEventListener("scroll", gsap.ticker.tick, {passive: true});
					scrolling.events.forEach((e, i) => (i ? document : window).addEventListener(e, scrolling.prevent, {passive: false}));
				}
			},
			enable() {
				if (!scrolling.enabled) {
					scrolling.enabled = true;
					window.removeEventListener("scroll", gsap.ticker.tick);
					scrolling.events.forEach((e, i) => (i ? document : window).removeEventListener(e, scrolling.prevent));
				}
			}
		}
		const stage_w     = window.innerWidth
		const tl          = gsap.timeline()

		tl.pause();

		function goToSection(section, anim) {
			const scrolldown = document.querySelector(".scrolldown")
			if (scrolldown){
				if (section.id == "section-4"){
					gsap.to(scrolldown, { opacity: 0, duration: 1 })
				}
				else {
					gsap.to(scrolldown, { opacity: 1, duration: 1 })
				}
			}

			if (scrolling.enabled && scrolling.started) {
				scrolling.disable();
				if (stage_w > 768) {
					gsap.to(window, {
						scrollTo: {y: section, autoKill: false},
						onComplete: () => {
							setTimeout(scrolling.enable, 200);
						},
						duration: 1
					});
				}

				anim && anim.restart();
			}
		}


		sections.forEach((item, index) => {
			this.scrollTriggers[index] = ScrollTrigger.create({
				trigger: item,
				end: "bottom top+=1",
				onEnter: () => goToSection(item),
				onEnterBack: () => goToSection(item),
				markers: false,
			});
		});
		if (stage_w > 768) scrolling.started = true

	}
	popup(){
		const splash = document.querySelector("#modal-splash")
		if (splash){
			setTimeout(function(){
				app.checkCookie();
			}, 200);
		}

		const popup = document.querySelector("#popup-modal")
		popup.addEventListener('show.bs.modal', e => {
			const trigger = e.relatedTarget
			const mode    = trigger.dataset.mode
			const body    = popup.querySelector(".modal-body")
			popup.classList.remove("gallery")

			body.innerHTML = ""


			switch(mode){
				case "gallery":
					popup.classList.add("gallery")
					const gallery = trigger.closest(".gallery-carousel")
					if (gallery){
						const newGallery = document.createElement("div")
						newGallery.id = `modal-${gallery.id}`
						newGallery.classList.add("carousel","slide","gallery-carousel")
						newGallery.innerHTML = gallery.innerHTML
						const btns = newGallery.querySelectorAll("[data-bs-toggle=modal]")
						btns.forEach( btn => {
							btn.removeAttribute("data-bs-toggle")
						} )
						const triggers = newGallery.querySelectorAll(`[data-bs-target="#${gallery.id}"]`)
						triggers.forEach( trigger => {
							trigger.setAttribute("data-bs-target", `#modal-${gallery.id}`)
						} )

						body.appendChild(newGallery)
					}
				break;
			}

console.log('popup', mode)
		})
	}
	initNav(){
		let lastScrollTop = 0;
		let isChanging    = false;
		let currentSP     = window.pageYOffset || document.documentElement.scrollTop;
		let toggler       = document.querySelector(".navbar-toggler.hamburger")

		if (toggler) {
			toggler.addEventListener("click", e => {
console.log('click')
				const expanded = toggler.getAttribute("aria-expanded")
				const nav      = document.querySelector(".navbar")
				console.log( toggler.getAttribute("aria-expanded") )

				if (expanded == "true"){
					nav.classList.add("open")
				}
				else {
					nav.classList.remove("open")
				}


			})
		}

		if (currentSP > 0) app.navigation.classList.add("mini")

		window.addEventListener("scroll", function(){
			let st     = window.pageYOffset || document.documentElement.scrollTop;
			if (st > lastScrollTop && st > 100){
				if (!isChanging){
					app.navigation.classList.add("mini");
					app.navigation.classList.remove("active");

					setTimeout(function(){
						isChanging = false;
					}, 200);
					isChanging = true;
				}
			}
			else {
				if (st < lastScrollTop && st > 100) {
					if (!isChanging){
						app.navigation.classList.add("active");
						setTimeout(function(){
							isChanging = false;
						}, 200);
						isChanging = true;
					}
				}
			}
			if (st == 0) app.navigation.classList.remove("mini");
			lastScrollTop = st <= 0 ? 0 : st; // For Mobile or negative scrolling
		}, false);

		const dropdowns = document.querySelectorAll(".nav-item.has_dropdown")
		dropdowns.forEach(el => {
			const trigger = el.querySelector("a[data-bs-toggle=dropdown]")


			el.addEventListener("mouseenter", () => {
				const trigger = el.querySelector("a[data-bs-toggle=dropdown]")

				const dd = el.querySelector(".dropdown-menu")
				if (dd){
					const dropdown = new bootstrap.Dropdown(trigger)
					dropdown.show()
				}

			})
			el.addEventListener("mouseleave", () => {
				const trigger = el.querySelector("[data-bs-toggle=dropdown]")
				if ( trigger.getAttribute("aria-expanded") == "true" ) trigger.click()
			})
		})



	}
	resize(){
		const sections    = document.querySelectorAll('.slide')
		sections.forEach((el, index) => {
			let stage_h  = window.innerHeight
			const home   = document.querySelector("#marquee-indicators")

			const is_fill = el.classList.contains("fill")
			if (is_fill && el.offsetHeight < stage_h){
				el.classList.remove("my-5")
				el.style.height =  stage_h+"px"
			} else {
//				el.classList.add("my-5")
			}

			if ( home ) {
				home.style.height =  stage_h+"px"
			}

		})
		this.scrollBox()
	}
	getCookie(cname){
		let name = cname + "=";
		let decodedCookie = decodeURIComponent(document.cookie);
		let ca = decodedCookie.split(';');
		for(let i = 0; i <ca.length; i++) {
			let c = ca[i];
			while (c.charAt(0) == ' ') {
				c = c.substring(1);
			}
			if (c.indexOf(name) == 0) {
				return c.substring(name.length, c.length);
			}
		}
		return "";
	}
	setCookie(cname, cvalue, exdays){
		const d = new Date();
		d.setTime(d.getTime() + (exdays*24*60*60*1000));
		let expires = "expires="+ d.toUTCString();
		document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
	}
	checkCookie(){
		var popup = app.getCookie("popup-splash");
		if (popup == "") {
			const trigger = document.querySelector("#modal-trigger")
			if (trigger){
				trigger.click()
				app.setCookie("popup-splash", true, 1);
			}
		}
	}
}
//Create Site APP
let app = new n4d();
//CHECK READY STATE
BProgress.start()

document.onreadystatechange = () => {

	if (document.readyState === 'complete') {
		app.init();
		BProgress.done()

		window.addEventListener("resize", e => {
			app.resize()
		})
	}
};

