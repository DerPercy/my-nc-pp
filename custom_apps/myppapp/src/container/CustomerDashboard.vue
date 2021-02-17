<template>
	<div v-if="projectData">
		<h2>Hello Customer Dashboard {{ projectData.name }}</h2>
		<WikiPage
			v-if="projectData.readme"
			:wiki-page-url="projectData.readme" />
	</div>
</template>

<script>
import WikiPage from './WikiPage.vue'

export default {
	components: {
		WikiPage,
	},
	props: {
	},
	computed: {
		projectData() {
			return this.$store.state.contentCustomerData
		},
	},
	methods: {
		navToWiki(sourceFile, href) {
			this.$store.dispatch('projectDashboardNavWiki', { sourceFile, href })
		},
	},
}
</script>
