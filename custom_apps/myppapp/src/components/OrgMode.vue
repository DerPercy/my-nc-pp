<template>
	<div>
		<div>Hello OrgMode</div>
		<span>Pfad: </span><input v-model="path" v-bind:style="{width: '270px'}" />
		<div>
			Ort: <input v-model="ort" />
		</div>
		<div>
			Monat: <input v-model="monat" />
		</div>
		<div>
			Jahr: <input v-model="jahr" />
		</div>
		<div>
			Export: <input v-model="exportpfad" v-bind:style="{width: '270px'}" />
		</div>
		<button @click="createTN">
			TN erzeugen
		</button>
	</div>
</template>

<script>

import axios from '@nextcloud/axios'

export default {
	data() {
		const jahr = new Date().getFullYear()
		const monat = new Date().getMonth() + 1
		return {
			path: '/myppapp/Timetracking.org',
			ort: '',
			exportpfad: '/myppapp/TTexport.csv',
			jahr,
			monat,
		}
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
		}
	},
}
</script>
