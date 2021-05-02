<template>
	<div>
		<span>Hello Form</span>
		<span>{{entityDefinition.key}}</span>
		<div v-for="(prop, key) in entityProperties" :key="key">
			<EntityFormInput
				:property-definition="prop"
				:property-key="key"
				:entity="editEntity"
				@change="onChangeInput" />
		</div>
		<div v-for="(relation, key) in entityRelations" :key="key">
			<EntityRelationInput
				:relation="relation"
				:relation-key="key"
				@change="onChangeRelation" />
		</div>
		<button @click="onFormSubmit">{{submitLabel}}</button>
	</div>
</template>

<script>
import EntityFormInput from './EntityFormInput'
import EntityRelationInput from './EntityRelationInput'

export default {
	components: {
		EntityFormInput,
		EntityRelationInput,
	},

	props: {
		entityDefinition: {
			type: Object,
			required: true,
		},
		entity: {
			type: Object,
			required: false,
		},
	},

	data() {
		return {
			formData: {},
			relationData: {},
		}
	},

	mounted() {
		console.log('EF mounted', this.entity)
	},
	computed: {
		submitLabel() {
			let actType = ' anlegen'
			if (this.$props.entity) {
				actType = ' bearbeiten'
			}
			return this.$props.entityDefinition.name + actType
		},
		editEntity() {
			return this.$props.entity
		},
		entityProperties() {
			return this.$props.entityDefinition.properties
		},
		entityRelations() {
			return this.$props.entityDefinition.relations
		}
	},
	methods: {
		onChangeInput(data) {
			this.formData[data.name] = data.value
			console.log('EntityForm:formData', this.formData)
		},
		onChangeRelation(data) {
			console.log('EntityForm:onChangeRelation', data)
			this.relationData[data.name] = data.value.id
			console.log('EntityForm:relationData', this.relationData)
		},
		onFormSubmit() {
			console.log('submit')
			if (this.$props.entity) {
				// Edit mode
				this.$store.dispatch('ddd/updateEntity', { entityid: this.$props.entity.id, data: this.formData, relations: this.relationData })
			} else {
				this.$store.dispatch('ddd/createEntity', { entity: this.$props.entityDefinition.key, data: this.formData, relations: this.relationData })
			}
		},
	},
}
</script>
