<template>
	<div>
		<span>Hello EntityTypePicker for type {{entitytype}}</span>
		<select v-model="entitySelected" class="entity-selector">
			<option>
			</option>
			<option
				v-for="(entity, index) in entitylist"
				:key="index"
				:value="entity">
				{{ entity.name }}
			</option>
		</select>
	</div>
</template>

<script>

export default {
	props: ['entitytype', 'entitylist'],
	data() {
		return {
			entitySelected: null,
		}
	},
	computed: {
	},
	mounted() {
	},
	watch: {
		entitySelected(value) {
			this.$emit('change', { entity: value })
		},
	},
}
</script>
