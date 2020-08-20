<template>
	<div>
		<FullCalendar :options="calendarOptions" />
	</div>
</template>

<script>

import FullCalendar from '@fullcalendar/vue'
import timeGridPlugin from '@fullcalendar/timegrid'
import interactionPlugin from '@fullcalendar/interaction'

import axios from '@nextcloud/axios'

export default {
	components: {
		FullCalendar, // make the <FullCalendar> tag available
	},
	data() {
		return {
			calendarOptions: {
				plugins: [timeGridPlugin, interactionPlugin],
				initialView: 'timeGridWeek',
				selectable: true,
				timeZone: 'America/New_York',
				select: this.handleDateSelect,
				events: this.fetchEvents,
			},
		}
	},
	computed: {
		events() {
			return this.$store.getters.timetracking
		},
	},
	methods: {
		fetchEvents(info, success, failure) {
			axios
				.get('./timetracking')
				.then(response => {
					const events = []
					for (let i = 0; i < response.data.length; i++) {
						const serverEvent = response.data[i]
						events.push({
							title: serverEvent.customer + ': ' + serverEvent.project + ':' + serverEvent.activity,
							start: new Date(serverEvent.start * 1000),
							end: new Date(serverEvent.end * 1000),
							id: serverEvent.start,
						})
					}
					success(events)
				// resolve(response.data)
				})
		},
		handleDateSelect(selectInfo) {
			const title = prompt('Please enter a new title for your event')
			const calendarApi = selectInfo.view.calendar
			calendarApi.unselect() // clear date selection
			this.$store.commit('openTRSideBar', { start: selectInfo.start, end: selectInfo.end })
			if (title) {
				calendarApi.addEvent({
					id: '1',
					title,
					start: selectInfo.startStr,
					end: selectInfo.endStr,
					allDay: selectInfo.allDay,
				})
			}
		},
	},
}
</script>
