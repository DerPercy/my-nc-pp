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
	data() {
		return {
			relationEntity: null,
		}
	},
	methods: {
		loadEntity() {
			console.log('LoadEntity')
			if (this.$props.entity.relations[this.$props.relationKey] && this.$props.entity.relations[this.$props.relationKey].length > 0) {

				this.$store.dispatch('ddd/getEntityByID', this.$props.entity.relations[this.$props.relationKey][0]).then((entity) => {
					this.relationEntity = entity
					// console.log('Tasks', edList)
				})
			}
		}
	},
	computed: {
		uiValue() {
			if (this.relationEntity) {
				return this.relationEntity.name
			}
			this.loadEntity()
			// console.log(this)
			return ''
		},
		isMultiValue() {
			return this.$props.relation.multiValue
		}
	},
	mounted() {
		console.log('ERV.Entity', this.$props.entity)
		console.log('ERV.Relation', this.$props.relation)
		console.log('ERV.Relationkey', this.$props.relationKey)
	},
	updated() {
		console.log('ERV.updated')
		// console.log('Created Entity', this.$props.entity)
		// console.log('Created Property', this.$props.property)
		// console.log('Created Propertykey', this.$props.propertyKey)
	},
}
</script>
