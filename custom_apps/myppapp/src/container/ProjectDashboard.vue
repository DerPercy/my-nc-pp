<template>
	<div v-if="projectData">
		<h2>Hello Dashboard {{ projectPath }}</h2>
		<MarkdownPage v-if="projectData.wiki" :value="projectData.wiki.content" />
	</div>
</template>

<script>
import MarkdownPage from '../components/MarkdownPage.vue'

export default {
	components: {
		MarkdownPage,
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
}
</script>
