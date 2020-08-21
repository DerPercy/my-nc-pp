<template>
	<div>
		<ul>
			<li v-for="(task, index) in tasks" :key="index">
				Name: {{ task.name }}
				File: {{ task.file }}
			</li>
		</ul>
	</div>
</template>

<script>

export default {
	components: {
	},
	computed: {
		tasks() {
			console.log(this.$store.state.task)
			return this.$store.state.task.tasks
		},
	},
}

</script>
