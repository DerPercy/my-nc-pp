<template>
	<div class="myppapp-markdownpage">
		<div>Hello Markdown</div>
		<p @click="onClick" v-html="htmlCode" />
		<textarea v-model="content" style="width:100%" rows="10" />
	</div>
</template>

<script>

import marked from 'marked'
import mermaid from 'mermaid'

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
			stateValue: this.value,
		}
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
				code(src, tokens) {
					if (tokens !== 'diagramm') {
						return '<pre><code class="' + tokens + ' language-' + tokens + '">' + src + '</code></pre>'
					}
					try {
						mermaid.parse(src)
					} catch (e) {
						// console.log(e.str)
						return '<span>Error at parsing mermaid graph (see console)</span>'
					}
					// const className = props.language && `language-${props.language}`;
					return '<span class="mermaid">' + src + '</span>'
				},
			}
			marked.use({ renderer })
			return marked(this.content)
		},
	},
	beforeUpdate() {
		// console.log('Update')
	},
	mounted() {
		// console.log('mounted')
		mermaid.contentLoaded()
	},
	updated() {
		// console.log('updated')
		mermaid.contentLoaded()
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
