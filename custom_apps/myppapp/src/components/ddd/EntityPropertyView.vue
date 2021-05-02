<template>
	<div>{{uiValue}}</div>
</template>

<script>

export default {
	props: ['entity', 'property', 'propertyKey'],
	computed: {
		uiValue() {
			return this.$props.entity.data[this.$props.propertyKey]
		}
	},
	mounted() {
		console.log('Entity', this.$props.entity)
		console.log('Property', this.$props.property)
		console.log('Propertykey', this.$props.propertyKey)
	},
	created() {
		console.log('Created Entity', this.$props.entity)
		console.log('Created Property', this.$props.property)
		console.log('Created Propertykey', this.$props.propertyKey)
	},
}
</script>
