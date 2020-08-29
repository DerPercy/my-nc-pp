<template>
	<div class="myppapp-markdownpage">
		<div>Hello Markdown</div>
		<p @click="onClick" v-html="htmlCode" />
		<textarea v-model="content" style="width:100%" rows="10" />
	</div>
</template>

<script>

import marked from 'marked'

export default {
	components: {
	},
	props: {
		value: {
			type: String,
			default: '',
		},
		filePath: {
			type: String,
			default: '',
		},
	},
	data() {
		return {
			stateValue: this.value
		}
	},
	beforeUpdate() {
		console.log('Update')
	},
	computed: {
		content: {
			get() {
				return this.value
			},
			set(newValue) {
				// this.value = newValue
				this.$emit('change', newValue)
			},
		},
		htmlCode() {
			const renderer = {
				link(href, title, text) {
					if (href.match(/^(https?:)?\/\//)) {
						return '<a href="' + href + '"target="_blank">' + text + '<sup>☁</sup></a>'
					}
					if (href.match(/^(evernote?:)?\/\//)) {
						return '<a href="' + href + '"target="_blank">' + text + '<sup>(EN)</sup></a>'
					}
					// Internal link
					return '<a href="#" data-type="internal-link" data-href="' + href + '">' + text + '</a>'
				},
			}
			marked.use({ renderer })
			return marked(this.content)
		},
	},
	methods: {
		onClick(event) {
			if (event.target.dataset && event.target.dataset.type === 'internal-link') {
				// console.log(event.target.dataset.href)
				this.$emit('navigate', this.$props.filePath, event.target.dataset.href)
			}
		},
	},
}

</script>

<style>
.myppapp-markdownpage a {
	color: var(--color-primary);
}
</style>
