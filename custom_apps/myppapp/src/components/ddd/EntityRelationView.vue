<template>
	<div v-if="isMultiValue">
		Multivalue not yet supported
	</div>
	<div v-else>
		{{uiValue}}
	</div>
</template>

<script>

export default {
	props: ['entity', 'relation', 'relationKey'],
	computed: {
		uiValue() {
			return 'Hello Relation'
		},
		isMultiValue() {
			return this.$props.relation.multiValue
		}
	},
	mounted() {
		console.log('Entity', this.$props.entity)
		console.log('Relation', this.$props.relation)
		console.log('Relationkey', this.$props.relationKey)
	},
	created() {
		// console.log('Created Entity', this.$props.entity)
		// console.log('Created Property', this.$props.property)
		// console.log('Created Propertykey', this.$props.propertyKey)
	},
}
</script>
