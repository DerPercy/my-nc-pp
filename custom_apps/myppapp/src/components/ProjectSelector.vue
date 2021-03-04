<template>
	<div>
		<select v-model="custSelected" class="projectselector-custselect">
			<option value="">
				Customer
			</option>
			<option v-for="(customer, index) in customerList" :key="index" :value="customer">
				{{ customer.value }}
			</option>
		</select>
		<span v-if="custSelected">
			<select v-model="projSelected" class="projectselector-projselect">
				<option value="">
					Project
				</option>
				<option v-for="(project, index) in projectList" :key="index" :value="project">
					{{ project.value }}
				</option>
			</select>
		</span>
	</div>
</template>

<script>

export default {
	components: {
	},
	props: {
		projTree: {
			type: Object,
			required: true,
		},
	},
	data() {
		return {
			custSelected: null,
			projSelected: null,
		}
	},
	computed: {
		customerList() {
			if (!this.projTree || !this.projTree.children) {
				return []
			}
			return this.projTree.children
		},
		projectList() {
			if (!this.custSelected && this.custSelected !== 0) {
				return []
			}
			// return this.projTree.children[this.custSelected].children
			return this.custSelected.children
		},

	},
	watch: {
		custSelected(value) {
			// console.log(value)
			// this.$emit('change', {})
			this.$emit('change', { customer: value, project: null })
		},
		projSelected(value) {
			this.$emit('change', { customer: this.custSelected, project: value })
		},
	},
}
</script>
