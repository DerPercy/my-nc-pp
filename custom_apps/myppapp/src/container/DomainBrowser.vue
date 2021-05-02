<template>
	<div>
		<span>Hello Browser</span>
		<EntityTypePicker v-if="dataLoaded"
			@change="onSelectEntityDefinition"
			:entity-definitions="entityDefinitions" />
		<div  v-if="activeEntityDefinition">
			<EntityTableView
				:entity-definition="selectedEntityDefinition"
				:entity-list="entityList"
				@clickEntity="onClickEntity" />
			<div>
				<h3>New Entity</h3>
				<EntityForm :entity-definition="selectedEntityDefinition" />
			</div>
			<div v-if="editEntity">
				<h3>Edit Entity {{editEntity.name}}</h3>
				<EntityForm :entity-definition="selectedEntityDefinition" :entity="editEntity" />
			</div>
		</div>
	</div>
</template>

<script>

import EntityForm from '../components/ddd/EntityForm'
import EntityTypePicker from '../components/ddd/EntityTypePicker'
import EntityTableView from '../components/ddd/EntityTableView'

export default {
	components: {
		EntityForm,
		EntityTypePicker,
		EntityTableView,
	},
	data() {
		return {
			entityDefinitions: null,
			selectedEntityDefinition: null,
			entityList: null,
			editEntity: null,
		}
	},
	computed: {
		dataLoaded() {
			if (this.entityDefinitions) {
				return true
			}
			return false
		},
		activeEntityDefinition() {
			if (this.selectedEntityDefinition && this.entityList) {
				return true
			}
			return false
		},
		/* tasks() {
			return this.$store.dispatch('om/getTasks')
		}, */
	},
	watch: {
	},
	mounted() {
		this.$store.dispatch('ddd/getEntityDefinitions').then((edList) => {
			this.entityDefinitions = edList
			 // console.log('Tasks', edList)
		})
	},
	methods: {
		onClickEntity(data) {
			console.log('DomainBrowser.onClickEntity', data)
			this.editEntity = data.entity
		},
		onSelectEntityDefinition(data) {
			// console.log(data.entityDefinition)
			this.selectedEntityDefinition = data.entityDefinition
			this.editEntity = null
			this.$store.dispatch('ddd/getEntitiesOfType', data.entityDefinition.key).then((entityList) => {
				this.entityList = entityList
				console.log('entitylist', entityList)
			})
		},
	},
}
</script>
