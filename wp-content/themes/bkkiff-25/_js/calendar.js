/*global wp_ajax_object, Snap, Calendar */
import { Alert, Button, Carousel, Collapse, Dropdown, Modal, Offcanvas, Popover, ScrollSpy, Tab, Toast, Tooltip } from 'bootstrap'; //quiet
import Calendar from '@mxsoft/tui-calendar'; //quiet

const bootstrap = {
	Alert,
	Button,
	Carousel,
	Collapse,
	Dropdown,
	Modal,
	Offcanvas,
	Popover,
	ScrollSpy,
	Tab,
	Toast,
	Tooltip
};

class n4dCalendar {
	constructor() {

	}
	init(){
		const calendars = document.querySelectorAll('.calendar')
		if (calendars){
			calendars.forEach(item => {
				const calendar = new Calendar(item, {
					defaultView: 'month',
					isReadOnly: true,
					month: {
						dayNames: ['S', 'M', 'T', 'W', 'T', 'F', 'S'],
					},
					theme: {
						month: {
							dayExceptThisMonth: { color: '#FFF' },
							weekend: { color: 'red' },
							holidayExceptThisMonth: { color: '#FFF' }
						},
						common: {
							holiday: {
								color: 'black',
							},
						}
					}
				});
				const cal_date = item.dataset.year+'-'+item.dataset.month+'-01'
				calendar.setDate(cal_date);

				const days = calendarApp.getDays( cal_date )

				if (days){
					const excludes_el = document.querySelector("#billing-excludes")
					const excludes    = (excludes_el) ? excludes_el.value : []

console.log( excludes )

					days.forEach( (d, index) => {
						let year  = d.getFullYear()
						let month = d.getMonth()+1
						let day   = d.getDate().toString()

						if (month.toString().length < 2) month = '0' + month;
						if (day.length < 2) day = '0' + day;

						let date = `${year}-${month}-${day}`

						if ( !excludes.includes(date) ){

							calendar.createEvents([{
								id: index,
								calendarId: 'cal1',
								title: 'Billing',
								start: `${date}T10:00:00`,
								end: `${date}T11:00:00`,
								location: 'Meeting Room A',
								attendees: ['A', 'B', 'C'],
								category: 'allday',
								state: 'Busy',
								isReadOnly: true,
								color: '#fff',
								backgroundColor: '#ccc',
								customStyle: {
									fontStyle: 'italic',
									fontSize: '15px',
								},
								}, // EventObject
							]);
						}
					} )
				}
			})
			setTimeout(() => {
				const events =  document.querySelectorAll( '.toastui-calendar-grid-cell-more-events' )
				events.forEach( event => {
					event.parentElement.classList.add("active")
				} )
			}, 100)

			const billingSelectMonth = document.querySelectorAll('.billing-select-month')
			billingSelectMonth.forEach( select => {
				select.addEventListener("change", e => {
					const carousel = new bootstrap.Carousel(e.target.dataset.target)
					carousel.to(e.target.value - 1)

				})
			} )
			const carouselCalendars = document.querySelector("#carouselCalendars")
			if (carouselCalendars){
				carouselCalendars.addEventListener('slid.bs.carousel', event => {
					const trigger = document.querySelector('.billing-select-month[data-target="#carouselCalendars"]')
					if (trigger){
						if (trigger.value !== event.to) {
							trigger.value = (event.to+1 > 6) ? 6 : event.to+1
						}
					}
				})
			}


		}
	}
	getDays(date) {
		var d = new Date(date)
		var month = d.getMonth(),
			days = []

		d.setDate(1);

		// Get the first Monday in the month
		while (d.getDay() !== 1) {
			d.setDate(d.getDate() + 1);
		}
		// Get all the other Mondays in the month
		while (d.getMonth() === month) {
			days.push(new Date(d.getTime()));
			d.setDate(d.getDate() + 7);
		}

		d = new Date(date)

		d.setDate(1);

		while (d.getDay() !== 2) {
			d.setDate(d.getDate() + 1);
		}

		while (d.getMonth() === month) {
			days.push(new Date(d.getTime()));
			d.setDate(d.getDate() + 7);
		}

		return days;
	}
}
//Create Site APP
let calendarApp = new n4dCalendar();


document.addEventListener('readystatechange', function(evt) {
	switch (evt.target.readyState) {
		case "complete":
			calendarApp.init();
		break;
	}
}, false);