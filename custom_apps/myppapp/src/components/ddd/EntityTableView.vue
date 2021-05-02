<template>
	<div>
		<span>Hello Tableview</span>
		<span>{{entityDefinition.key}}</span>
		<span>{{entityListLength}}</span>
		<table>
			<tr>
				<td v-for="(prop, key) in entityDefinition.properties" :key="key">
					{{key}}
				</td>
				<td v-for="(relation, key) in entityDefinition.relations" :key="key">
					{{key}}
				</td>
			</tr>
			<tr v-for="(entity, index) in entityList" :key="index" @click="onClickEntity(entity)">
				<td v-for="(prop, key) in entityDefinition.properties" :key="key">
					<EntityPropertyView
						:entity="entity"
						:property="prop"
						:property-key="key" />
				</td>
				<td v-for="(relation, key) in entityDefinition.relations" :key="key">
					<EntityRelationView
						:entity="entity"
						:relation="relation"
						:relation-key="key" />
				</td>
			</tr>

		</table>
	</div>
</template>

<script>

import EntityPropertyView from './EntityPropertyView'
import EntityRelationView from './EntityRelationView'

export default {
	components: {
		EntityPropertyView,
		EntityRelationView,
	},
	props: {
		entityDefinition: {
			type: Object,
			required: true,
		},
		entityList: {
			required: true,
		},
	},
	computed: {
		entityListLength() {
			console.log('TableData:', this.$props.entityList)
			return this.$props.entityList.length
		},
	},
	methods: {
		onClickEntity(entity) {
			console.log('EntityTableView.onClickEntity', entity)
			this.$emit('clickEntity', { entity })
		}
	}
}
</script>
