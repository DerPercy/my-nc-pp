<template>
	<div v-if="isMultiValue">
		Multivalue not yet supported
	</div>
	<div v-else>
		<EntityPicker
			v-if="entityList"
			:entitylist="entityList"
			:entitytype="relation.destination"
			@change="onEntityPickerChange" />
	</div>
</template>

<script>

import EntityPicker from './EntityPicker'

export default {
	components: {
		EntityPicker,
	},
	props: ['relation', 'relationKey'],
	data() {
		return {
			entityList: null,
		}
	},
	computed: {
		uiValue() {
			return 'Hello Relation Input'
		},
		isMultiValue() {
			return this.$props.relation.multiValue
		}
	},
	mounted() {
		this.$store.dispatch('ddd/getEntitiesOfType', this.$props.relation.destination).then((entityList) => {
			this.entityList = entityList
		})
		// console.log('Entity', this.$props.entity)
		console.log('RelationInput', this.$props.relation)
		// console.log('Relationkey', this.$props.relationKey)
	},
	methods: {
		onEntityPickerChange(data) {
			console.log('onEntityPickerChange', data)
			this.$emit('change', { name: this.$props.relationKey, value: data.entity })
		},
	},
	created() {
		// console.log('Created Entity', this.$props.entity)
		// console.log('Created Property', this.$props.property)
		// console.log('Created Propertykey', this.$props.propertyKey)
	},
}
</script>
