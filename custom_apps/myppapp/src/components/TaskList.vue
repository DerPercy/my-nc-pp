<template>
	<div class="myppapp-tasklist">
		<h2>Meine Aufgaben {{ taskList.length }}</h2>
		<ul>
			<li v-for="(task, index) in taskList" :key="index" :class="task.classNames">
				<div>{{ task.name }}</div>
				<div class="task-content-line-two">
					{{ task.customer }}: {{ task.project }}
				</div>
			</li>
		</ul>
	</div>
</template>

<script>

export default {
	components: {
	},
	props: {
		tasks: {
			type: Array,
			required: true,
		},
	},
	computed: {
		taskList() {
			// console.log(this.$store.state.task)
			const taskList = []
			for (let i = 0; i < this.tasks.length; i++) {
				const task = this.tasks[i]
				task.classNames = 'task'
				if (task.done === true) {
					task.classNames += ' task-done'
				}
				if (task.prio) {
					task.classNames += ' task-prio-' + task.prio
				}
				taskList.push(task)
			}

			const sorter = function(a, b) {
				// Done
				if (a.done !== b.done) {
					return a.done ? 1 : -1
				}
				// Prio
				if (a.prio !== b.prio) {
					if (!a.prio) {
						return 1
					}
					if (!a.prio) {
						return -1
					}
					if (a.prio > b.prio) {
						return 1
					} else {
						return -1
					}
				}

				return 0
			}
			taskList.sort(sorter)
			return taskList
		},
		// other computed
	},
}

</script>

<style scoped>
.task-done {
	opacity: 0.5;
}

.task {
	border-top: 1px solid lightgray;
	padding: 10px;
}

.task-prio-A {
	border-left: 3px solid red;
}

.task-prio-B {
	border-left: 3px solid rgba(255, 127, 0, 1);
}

.task-prio-C {
	border-left: 3px solid rgba(255, 255, 0, 1);
}

.task-content-line-two {
	color: grey;
	font-size: 90%;
	font-style: italic;
}
</style>
