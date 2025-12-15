/*global wp_ajax_object, masonry, wpcf7_recaptcha */
import { Alert, Button, Carousel, Collapse, Dropdown, Modal } from 'bootstrap'; //quiet
//, Offcanvas, Popover, ScrollSpy, Tab, Toast, Tooltip
//import ScrollTrigger from "gsap/ScrollTrigger.js" //quiet
//import ScrollToPlugin from "gsap/ScrollToPlugin.js" //quiet
import gsap from 'gsap' //quiet
import {BProgress} from '@bprogress/core' //quiet
import Xwiper from 'xwiper' //quiet

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

//		gsap.registerPlugin(ScrollTrigger, ScrollToPlugin)//Observer, ScrollSmoother

	}
	init(){
//Show page
		if (this.wrapper) this.wrapper.classList.add('show');
//Navigation
		if (this.navigation) this.initNav()

		this.popup()
		this.createSlider()


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

		const filters = document.querySelector(".filter-gallery")
		if (filters){
			const selects = filters.querySelectorAll("select")
			selects.forEach(select => {
				select.addEventListener("change", e => {
					let qstr = []

					selects.forEach(form => {
						let tmp = form.value.replace("?", "")
						let values = tmp.split("&")
						values.forEach(value => {
							let data = value.split("=")
							if (data.length == 2 && data[0] !== "post_type"){
								qstr[data[0]] = data[1]
							}
						})
					})

					window.location.href = window.location.pathname+"?"+this.serialize(qstr)
				})
			})
		}

	}
	serialize(obj) {
	  var str = [];
	  for (var p in obj)
		if (obj.hasOwnProperty(p)) {
		  str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
		}
	  return str.join("&");
	}
	popup(){
		const images = []
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
			const id      = trigger.dataset.id
			const body    = popup.querySelector(".modal-body")
			popup.classList.remove("gallery")

			body.innerHTML = ""


			popup.classList.remove("gallery")
			popup.classList.remove("image")

			switch(mode){
				case "gallery":
					popup.classList.add("gallery")
					if (id){
						body.innerHTML = '<div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>'

						fetch('/wp-json/n4d/v1/gallery/'+id, {
							method: 'GET', // *GET, POST, PUT, DELETE, etc.
							mode: 'cors', // no-cors, *cors, same-origin
							cache: 'default', // *default, no-cache, reload, force-cache, only-if-cached
							credentials: 'same-origin', // include, *same-origin, omit
							headers: {
								'Content-Type': 'application/json',
								'X-WP-Nonce': wp_ajax_object.nonce
							}
						})
						.then(response => response.json())
						.then(data => {
							body.innerHTML = data.html
						});
					}
				break;
				case "image":
					popup.classList.add("image")
					body.innerHTML = '<div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>'
					this.createGallery(popup, images, trigger.dataset.index)
				break;
			}

console.log('popup', mode)
		})

		const pop_images = document.querySelectorAll(".popup-gallery .wp-block-image a[target='_blank']")
		if (pop_images){
			pop_images.forEach((link, index) => {

				link.setAttribute("data-bs-toggle", "modal")
				link.setAttribute("data-bs-target", "#popup-modal")
				link.dataset.mode = "image"
				link.dataset.index = index
				link.dataset.src   = link.href

				images.push(link.href)

				link.removeAttribute("href")
			})
		}
//
		const downloads = document.querySelectorAll(".wp-block-image.download a, .wp-block-n4d-card.download a")
		if (downloads){
			downloads.forEach(link => {
				link.setAttribute("download", "")
			})
		}



	}
	createGallery(popup, images, selected){
		const body     = popup.querySelector(".modal-body")

		body.innerHTML = ""

		const carousel = document.createElement("div")
		carousel.id = "popup-gallery"
		carousel.classList.add("carousel")
		carousel.classList.add("slide")
		carousel.classList.add("gallery-carousel")
		carousel.style.height = (window.innerHeight - 30)+"px"

		const inner = document.createElement("div")
		inner.classList.add("carousel-inner")

		images.forEach((url, i) => {
			let item  = document.createElement("div")
			item.classList.add("carousel-item")
			if (i == selected) item.classList.add("active")

			let image = document.createElement("img")
			image.src = url

			let wrap  = document.createElement("div")
			wrap.classList.add("ratio-wrap")

			let figure  = document.createElement("figure")

			let ratio  = document.createElement("div")
			ratio.classList.add("ratio")
			ratio.classList.add("ratio-4x3")

/*
			ratio.append(image)
			figure.append(ratio)
			wrap.append(figure)
			item.append(wrap)
*/
			item.append(image)
			inner.append(item)
		})




		const prev = document.createElement("button")
		prev.classList.add("carousel-control-prev")
		prev.setAttribute("type", "button")
		prev.setAttribute("data-bs-target", "#popup-gallery")
		prev.setAttribute("data-bs-slide", "prev")

		const prev_icon = document.createElement("span")
		prev_icon.classList.add("carousel-control-prev-icon")

		const next = document.createElement("button")
		next.classList.add("carousel-control-next")
		next.setAttribute("type", "button")
		next.setAttribute("data-bs-target", "#popup-gallery")
		next.setAttribute("data-bs-slide", "next")

		const next_icon = document.createElement("span")
		next_icon.classList.add("carousel-control-next-icon")

		prev.append(prev_icon)
		next.append(next_icon)

		carousel.append(inner)
		carousel.append(prev)
		carousel.append(next)

		body.append(carousel)
	}
	createSlider(){
		const sliders = document.querySelectorAll(".n4d-slider")
		if (sliders){
			const xwipers = []

			sliders.forEach( (slider, index) => {
				const prev   = slider.querySelector(".control.prev")
				const next   = slider.querySelector(".control.next")
				const stage  = slider.querySelector(".slides")
				const slides = slider.querySelectorAll(".slide")
				const limit  = (slides && slides.length > 0) ? (slides.length - Math.floor(slider.offsetWidth/slides[0].offsetWidth) ) : 0//slides.length - 1

				if (slider.offsetWidth > stage.offsetWidth){
					slider.classList.add("single")
				}
				else {
					slider.classList.remove("single")
				}

				if (prev){
					prev.addEventListener("click", e => {
						if (slider.dataset.current > 0 ) {
							slider.dataset.current--
						}

						const target = slider.querySelectorAll(`.slide[data-index="${slider.dataset.current}"]`)
						gsap.to(stage, { left: -((target[0]) ? target[0].offsetLeft : 0), duration: 0.4, ease: "power1.out" });
					})
				}
				if (next){
					next.addEventListener("click", e => {
						if (slider.dataset.current < limit ) {
							slider.dataset.current++
						}

						const target = slider.querySelectorAll(`.slide[data-index="${slider.dataset.current}"]`)
						gsap.to(stage, { left: -(target[0].offsetLeft), duration: 0.4, ease: "power1.out" });
					})
				}

				xwipers[index] =  new Xwiper(slider);
				if (xwipers[index]){
					xwipers[index].onSwipeLeft(() => {
						const target = slider.querySelector(".control.next")
						if (target) target.click()
					});
					xwipers[index].onSwipeRight(() => {
						const target = slider.querySelector(".control.prev")
						if (target) target.click()
					});
				}
			} )
		}

	}
	initNav(){
		let lastScrollTop = 0;
		let isChanging    = false;
		let currentSP     = window.pageYOffset || document.documentElement.scrollTop;
		let toggler       = document.querySelector(".navbar-toggler.hamburger")

		if (toggler) {
			toggler.addEventListener("click", e => {
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

