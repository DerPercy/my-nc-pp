<template>
	<div>
		<select v-model="custSelected">
			<option disabled value="">
				Customer
			</option>
			<option v-for="(customer, index) in customerList" :key="index" :value="index">
				{{ customer.value }}
			</option>
		</select>
		<span v-if="custSelected">
			<select>
				<option disabled value="">
					Project
				</option>
				<option v-for="(project, index) in projectList" :key="index" :value="index">
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
			if (!this.custSelected) {
				return []
			}
			return this.projTree.children[this.custSelected].children
		},

	},
	watch: {
		custSelected(value) {
			// console.log(value)
		},
	},
}
</script>
