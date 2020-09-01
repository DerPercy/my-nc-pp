<template>
	<div>
		<div>{{wikiPageUrl}}</div>
		<MarkdownPage
			v-if="wikipageData"
			:value="wikipageData.content"
			:file-path="wikipageData.folder"
			@navigate="navToWiki"
			@change="changeContent" />
		<button v-if="wikiPageEditedContent" @click="saveWikiPage">
			Speichern
		</button>
	</div>
</template>

<script>

import MarkdownPage from '../components/MarkdownPage.vue'
import axios from '@nextcloud/axios'

export default {
	components: {
		MarkdownPage,
	},
	props: {
		wikiPageUrl: {
			type: String,
			default: '',
		},
	},
	data() {
		return {
			wikipageData: null,
			wikiPageEditedContent: null,
		}
	},
	computed: {
	},
	methods: {
		navToWiki(sourceFile, href) {
			console.log('Nav')
			this.wikiPageEditedContent = null
			this.fetchWikiPageData(this.wikipageData.url, href)
		},
		changeContent(newContent) {
			console.log(newContent)
			this.wikipageData.content = newContent
			this.wikiPageEditedContent = newContent
		},
		saveWikiPage() {
			console.log(this.wikipageData.content)
			axios
				.post('./wiki/page', {
					wikipageUrl: this.wikipageData.url,
					wikipageContent: this.wikiPageEditedContent,
					wikipageHash: this.wikipageData.hash,
				})
				.then(response => {
					this.$store.dispatch('handleHTTPResponse', response)
					this.wikipageData = response.data.data
				})
				.catch(response => {
					this.$store.dispatch('handleHTTPResponseError', response)
				})
			this.wikiPageEditedContent = null
		},
		fetchWikiPageData(url, link = '') {
			axios
				.get('./wiki/page', {
					params: {
						wikipageUrl: url,
						wikipageLink: link,
					},
				})
				.then(response => {
					this.wikipageData = response.data
					// console.log(response)
				})
		},
	},
	mounted() {
		this.fetchWikiPageData(this.$props.wikiPageUrl)
	},
}
</script>
