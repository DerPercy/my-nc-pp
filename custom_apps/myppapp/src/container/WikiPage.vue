<template>
	<div>{{wikiPageUrl}}</div>
</template>

<script>

export default {
	components: { },
	props: {
		wikiPageUrl: {
			type: String,
			default: '',
		},
	},
	computed: {
		wikiPageData() {
			return null
		},
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
