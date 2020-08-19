<template>
	<div>
		<div>Hello Markdown</div>
		<p @click="onClick" v-html="htmlCode" />
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
	computed: {
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
			return marked(this.value)
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
/* if (this.props.href.match(/^(file?:)?\/\//)) {
		 return (
				 <a href={this.props.href} target="_blank">
						 {this.props.children} <sup>(Filesystem)</sup>
				 </a>
		 );
 }
 if (this.props.href.match(/^(evernote?:)?\/\//)) {
		 return (
				 <a href={this.props.href} target="_blank">
						 {this.props.children} <sup>(EN)</sup>
				 </a>
		 );
 }
 return <Link to={this.props.href}>{this.props.children}</Link>; */
</script>
