<template>
	<div>
		<span>Hello EntityTypePicker</span>
		<select v-model="edSelected" class="entity-selector">
			<option>
			</option>
			<option
				v-for="(entity, key) in entityDefinitions"
				:key="key"
				:value="entity">
				{{ entity.name }}
			</option>
		</select>
	</div>
</template>

<script>

export default {
	props: {
		entityDefinitions: {
			type: Object,
			required: true,
		},
	},
	data() {
		return {
			edSelected: null,
		}
	},
	computed: {
	},
	mounted() {
	},
	watch: {
		edSelected(value) {
			this.$emit('change', { entityDefinition: value })
		},
	},
}
</script>
