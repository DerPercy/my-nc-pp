<template>
	<div>
		<div>Hello OrgMode</div>
		<span>Pfad: </span><input v-model="path" :style="{width: '270px'}">
		<div>
			Ort: <input v-model="ort">
		</div>
		<div>
			Monat: <input v-model="monat">
		</div>
		<div>
			Jahr: <input v-model="jahr">
		</div>
		<div>
			Export: <input v-model="exportpfad" :style="{width: '270px'}">
		</div>
		<button @click="createTN">
			TN erzeugen
		</button>
		<div>
			Todoflags: <input v-model="todoflags" :style="{width: '270px'}">
		</div>
		<TaskList v-if="tasksLoaded" :tasks="tasks" :proj-tree="projectTree" />
	</div>
</template>

<script>

import axios from '@nextcloud/axios'
import TaskList from '../components/TaskList'

export default {
	components: {
		TaskList,
	},
	data() {
		const jahr = new Date().getFullYear()
		const monat = new Date().getMonth() + 1
		return {
			path: this.$store.state.om.settingsFilePath,
			ort: '',
			exportpfad: '/myppapp/TTexport.csv',
			todoflags: 'TODO,DONE',
			jahr,
			monat,
			tasks: null,
			projectTree: null,
		}
	},
	computed: {
		tasksLoaded() {
			if (this.tasks && this.projectTree) {
				return true
			}
			return false
		},
		/* tasks() {
			return this.$store.dispatch('om/getTasks')
		}, */
	},
	watch: {
		path(value) {
			this.$store.dispatch('om/setPath', value)
		},
		todoflags(value) {
			localStorage.orgmode_todoflags = value
		},
	},
	mounted() {
		if (localStorage.orgmode_todoflags) {
			this.todoflags = localStorage.orgmode_todoflags
		}
		this.$store.dispatch('om/getTasks').then((tasks) => {
			this.tasks = tasks
			// console.log('Tasks', tasks)
		})
		this.$store.dispatch('om/getProjectTree').then((ptree) => {
			this.projectTree = ptree
			// console.log('PTree', ptree)
		})
	},
	methods: {
		createTN() {
			axios
				.post('./omts', {
					file: this.path,
					ort: this.ort,
					export: this.exportpfad,
					jahr: this.jahr,
					monat: this.monat,
				})
				.then(response => {
				})
			// alert(this.path)
		},
	},
}
</script>
