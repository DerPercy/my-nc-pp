<template>
	<div>
		<div>Hello Markdown</div>
		<p v-html="htmlCode" />
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
	},
	computed: {
		htmlCode() {
			console.log(this.value)
			return marked(this.value)
		},
	},
}
</script>
