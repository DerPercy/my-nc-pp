<template>
	<AppSidebar
		title="Zeiterfassung"
		@close="closeSideBar">
		<AppSidebarTab id="activity" name="Activity" icon="icon-activity">
			<ProjectPicker />
			<DatetimePicker
				v-model="start"
				type="datetime" />
			<DatetimePicker
				v-model="end"
				type="datetime" />
		</AppSidebarTab>
	</AppSidebar>
</template>

<script>

import AppSidebar from '@nextcloud/vue/dist/Components/AppSidebar'
import AppSidebarTab from '@nextcloud/vue/dist/Components/AppSidebarTab'
import DatetimePicker from '@nextcloud/vue/dist/Components/DatetimePicker'

import ProjectPicker from './ProjectPicker.vue'

export default {
	components: {
		AppSidebar,
		AppSidebarTab,
		ProjectPicker,
		DatetimePicker,
	},
	computed: {
		start: {
			get() {
				return this.$store.state.asideTRData.start
			},
			set(date) {
				const data = Object.assign({}, this.$store.state.asideTRData)
				data.start = date
				this.$store.commit('openTRSideBar', data)
			},
		},
		end: {
			get() {
				return this.$store.state.asideTRData.end
			},
			set(date) {
				const data = Object.assign({}, this.$store.state.asideTRData)
				data.end = date
				this.$store.commit('openTRSideBar', data)
			},
		},
	},
	methods: {
		closeSideBar() {
			this.$store.commit('closeSideBar')
		},
	},
}
</script>
