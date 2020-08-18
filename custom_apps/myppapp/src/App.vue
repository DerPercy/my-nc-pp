<template>
	<Content :class="{'icon-loading': loading}" app-name="vueexample">
		<AppNavigation>
			<template id="app-vueexample-navigation" #list>
				<AppNavigationItem
					key="timeTracking"
					title="Zeiterfassung"
					icon="icon-user"
					@click="showTimetracking()" />
				<AppNavigationItem v-for="(customer, index) in customerList"
					:key="index"
					:title="customer.name"
					:allow-collapse="true"
					icon="icon-folder">
					<AppNavigationItem v-for="(project, pIndex) in customer.projects"
						:key="pIndex"
						:title="project.name"
						@click="showProject(project)" />
				</AppNavigationItem>
			</template>
		</AppNavigation>
		<AppContent>
			<ProjectDashboard v-show="contentType == 'project'" :project-path="projectPath" />
			<TimeRecording v-show="contentType == 'timetracking'" />
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
import AppNavigationItem from '@nextcloud/vue/dist/Components/AppNavigationItem'
import AppSidebar from '@nextcloud/vue/dist/Components/AppSidebar'
import AppSidebarTab from '@nextcloud/vue/dist/Components/AppSidebarTab'
import ActionButton from '@nextcloud/vue/dist/Components/ActionButton'
import ActionLink from '@nextcloud/vue/dist/Components/ActionLink'
import TimeRecording from './components/TimeRecording.vue'
import AsideTimeRecording from './components/AsideTimeRecording.vue'

import ProjectDashboard from './container/ProjectDashboard.vue'

import { store } from './store.js'

export default {
	name: 'App',
	components: {
		Content,
		AppContent,
		AppNavigation,
		AppNavigationItem,
		AppSidebar,
		AppSidebarTab,
		ActionButton,
		ActionLink,
		TimeRecording,
		AsideTimeRecording,
		ProjectDashboard,
	},
	data() {
		return {
			loading: false,
			date: Date.now() + 86400000 * 3,
			date2: Date.now() + 86400000 * 3 + Math.floor(Math.random() * 86400000 / 2),
			show: false, // Shoe demo sidebar
			starred: false,
			customerList: null,
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
	},
	mounted() {
		store.getCustomers().then(response => (this.customerList = response))
	},
	methods: {
		showProject(project) {
			console.log(project.path)
			this.$store.dispatch('navtoProject', project.path)
		},
		showTimetracking() {
			this.$store.dispatch('navtoTimetracking')
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
