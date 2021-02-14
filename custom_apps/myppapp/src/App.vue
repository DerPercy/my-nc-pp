<template>
	<Content :class="{'icon-loading': loading}" app-name="myppapp" id="content">
		<AppNavigation>
			<template id="app-myppapp-navigation" #list>
				<AppNavigationItem
					key="timeTracking"
					title="Zeiterfassung"
					:allow-collapse="true"
					icon="icon-user"
					@click="showTimetracking()">
					<AppNavigationItem
						key="timeTrackingExport"
						title="TN erzeugen"
						icon="icon-external"
						@click="buildTN()" />

				</AppNavigationItem>
				<AppNavigationItem
					key="toOrgMode"
					title="Org Mode"
					icon="icon-external"
					@click="showOrgMode()" />
				<AppNavigationItem
					key="tasks"
					title="Aufgaben"
					icon="icon-checkmark"
					@click="showTasks()" />
				<AppNavigationItem v-for="(customer, index) in customerList"
					:key="index"
					:title="customer.name"
					:allow-collapse="true"
					icon="icon-folder"
					@click="showCustomer(customer)">
					<AppNavigationCounter slot="counter" :highlighted="false">
						{{daysSinceLastChange(customer)}}
					</AppNavigationCounter>
					<AppNavigationItem v-for="(project, pIndex) in customer.projects"
						:key="pIndex"
						:title="project.name"
						@click="showProject(project)" />
				</AppNavigationItem>
			</template>
		</AppNavigation>
		<AppContent>
			<div id="app-content-wrapper">
				<TaskOverview v-if="contentType == 'tasks'" />
				<ProjectDashboard v-if="contentType == 'project'" :project-path="projectPath" />
				<CustomerDashboard v-if="contentType == 'customer'" />
				<TimeRecording v-if="contentType == 'timetracking'" />
				<OrgMode v-if="contentType == 'orgmode'" />
			</div>
		</AppContent>
		<AsideTimeRecording v-show="asideType == 'tr'" />
		<AppSidebar v-show="show"
			title="eberhard-grossgasteiger-VDw-nsi5TpE-unsplash.jpg"
			subtitle="4,3 MB, last edited 41 days ago"
			:starred.sync="starred"
			@close="show=false">
			<template #primary-actions>
				<button class="primary">
					Button 1
				</button>
				<input id="link-checkbox"
					name="link-checkbox"
					class="checkbox link-checkbox"
					type="checkbox">
				<label for="link-checkbox" class="link-checkbox-label">Do something</label>
			</template>
			<template #secondary-actions>
				<ActionButton icon="icon-edit" @click="alert('Edit')">
					Edit
				</ActionButton>
				<ActionButton icon="icon-delete" @click="alert('Delete')">
					Delete
				</ActionButton>
				<ActionLink icon="icon-external" title="Link" href="https://nextcloud.com" />
			</template>
			<AppSidebarTab id="vueexample" name="Vueexample" icon="icon-vueexample">
				this is the vueexample tab
			</AppSidebarTab>
			<AppSidebarTab id="activity" name="Activity" icon="icon-activity">
				this is the activity tab
			</AppSidebarTab>
			<AppSidebarTab id="comments" name="Comments" icon="icon-comment">
				this is the comments tab
			</AppSidebarTab>
			<AppSidebarTab id="sharing" name="Sharing" icon="icon-shared">
				this is the sharing tab
			</AppSidebarTab>
			<AppSidebarTab id="versions" name="Versions" icon="icon-history">
				this is the versions tab
			</AppSidebarTab>
		</AppSidebar>
	</Content>
</template>

<script>
import Content from '@nextcloud/vue/dist/Components/Content'
import AppContent from '@nextcloud/vue/dist/Components/AppContent'
import AppNavigation from '@nextcloud/vue/dist/Components/AppNavigation'
import AppNavigationCounter from '@nextcloud/vue/dist/Components/AppNavigationCounter'
import AppNavigationItem from '@nextcloud/vue/dist/Components/AppNavigationItem'
import AppSidebar from '@nextcloud/vue/dist/Components/AppSidebar'
import AppSidebarTab from '@nextcloud/vue/dist/Components/AppSidebarTab'
import ActionButton from '@nextcloud/vue/dist/Components/ActionButton'
import ActionLink from '@nextcloud/vue/dist/Components/ActionLink'
import TimeRecording from './components/TimeRecording.vue'
import OrgMode from './components/OrgMode.vue'
import AsideTimeRecording from './components/AsideTimeRecording.vue'

import ProjectDashboard from './container/ProjectDashboard.vue'
import CustomerDashboard from './container/CustomerDashboard.vue'
import TaskOverview from './container/TaskOverview.vue'

import { store } from './store.js'
import Moment from 'moment'

export default {
	name: 'App',
	components: {
		Content,
		AppContent,
		AppNavigation,
		AppNavigationCounter,
		AppNavigationItem,
		AppSidebar,
		AppSidebarTab,
		ActionButton,
		ActionLink,
		TimeRecording,
		AsideTimeRecording,
		ProjectDashboard,
		CustomerDashboard,
		TaskOverview,
		OrgMode,
	},
	data() {
		return {
			loading: false,
			date: Date.now() + 86400000 * 3,
			date2: Date.now() + 86400000 * 3 + Math.floor(Math.random() * 86400000 / 2),
			show: false, // Shoe demo sidebar
			starred: false,
			customerListData: null,
			customerHeader: ['id', 'name', 'id'],
		}
	},
	computed: {
		asideType() {
			return this.$store.state.asideType
		},
		projectPath() {
			return this.$store.state.contentProjectPath
		},
		contentType() {
			return this.$store.state.contentType
		},
		customerList() {
			if (this.customerListData) {
				const cld = this.customerListData
				return cld.sort((a, b) => { return b.mtime - a.mtime })
			}
			return null
		},

	},
	mounted() {
		store.getCustomers().then(response => (this.customerListData = response))
		this.$store.dispatch('task/loadData')
	},
	methods: {
		daysSinceLastChange(customer) {
			const x = new Moment()
			const y = new Moment(customer.mtime)
			const duration = Moment.duration(x.diff(y)).asDays()
			return parseInt(duration)
		},
		showProject(project) {
			this.$store.dispatch('navtoProject', project.path)
		},
		showCustomer(customer) {
			this.$store.dispatch('navtoCustomer', customer)
		},
		showOrgMode() {
			this.$store.dispatch('navtoOrgMode')
		},
		showTimetracking() {
			this.$store.dispatch('navtoTimetracking')
		},
		buildTN() {
			this.$store.dispatch('buildTN')
		},
		showTasks() {
			this.$store.dispatch('navtoTasks')
		},
		addOption(val) {
			this.options.push(val)
			this.select.push(val)
		},
		previous(data) {
			console.debug(data)
		},
		next(data) {
			console.debug(data)
		},
		close(data) {
			console.debug(data)
		},
		newButtonAction(e) {
			console.debug(e)
		},
		log(e) {
			console.debug(e)
		},
	},
}
</script>
<style>

main.app-content {
	padding: 14px;
}
</style>
