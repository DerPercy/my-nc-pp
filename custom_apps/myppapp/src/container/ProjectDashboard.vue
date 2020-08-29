<template>
	<div v-if="projectData">
		<h2>Hello Dashboard {{ projectPath }}</h2>
		<WikiPage
			v-if="projectData.wiki"
			:wiki-page-url="projectData.wiki.path" />
	</div>
</template>

<script>
import WikiPage from './WikiPage.vue'

export default {
	components: {
		WikiPage,
	},
	props: {
		projectPath: {
			type: String,
			default: '',
		},
	},
	computed: {
		projectData() {
			return this.$store.state.contentProjectData
		},
	},
	methods: {
		navToWiki(sourceFile, href) {
			this.$store.dispatch('projectDashboardNavWiki', { sourceFile, href })
		},
	},
}
</script>
