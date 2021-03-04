<template>
	<div>
		<h2>OrgMode Calendar</h2>
		<FullCalendar :options="calendarOptions" />
	</div>
</template>

<script>

import FullCalendar from '@fullcalendar/vue'
import timeGridPlugin from '@fullcalendar/timegrid'

export default {
	components: {
		FullCalendar, // make the <FullCalendar> tag available
	},
	data() {
		return {
			calendarOptions: {
				plugins: [timeGridPlugin],
				initialView: 'timeGridWeek',
				timeZone: 'America/New_York',
				events: this.fetchEvents,
			},
		}
	},
	computed: {
	},
	watch: {
	},
	mounted() {
	},
	methods: {
		fetchEvents(info, success, failure) {
			this.$store.dispatch('om/getLogbook').then((logbook) => {
				const events = []
				for (let i = 0; i < logbook.length; i++) {
					const serverEvent = logbook[i]
					events.push({
						title: serverEvent.title,
						start: new Date(serverEvent.start),
						end: new Date(serverEvent.end),
						allDay: serverEvent.allDay,
						id: serverEvent.start,
					})
				}
				success(events)
			})
		},
	},
}
</script>
