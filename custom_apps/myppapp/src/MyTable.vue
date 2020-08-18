<template>
	<div>
		<div>Hello Template: {{ title }}</div>
		<table>
			<tr v-for="row in data" :key="row.id">
				<td v-for="hid in headerids" :key="hid">
					{{ row[hid] }}
				</td>
			</tr>
		</table>
	</div>
</template>

<script>

export default {
	name: 'MyTable',
	components: {
	},
	props: {
		data: {
			type: Array,
			default: () => [],
		},
		headerids: {
			type: Array,
			default: () => ['name', 'id'],
		},
		title: {
			type: String,
			default: () => 'empty',
		},
	},
	data() {
		return {
			loading: false,
			date: Date.now() + 86400000 * 3,
			date2: Date.now() + 86400000 * 3 + Math.floor(Math.random() * 86400000 / 2),
			show: true,
			starred: false,
			value: 'Hello',
		}
	},
	methods: {
		addOption(val) {
			this.options.push(val)
			this.select.push(val)
		},
		previous(data) {
			console.debug(data)
		},
		next(data) {
			console.debug(data)
		},
		close(data) {
			console.debug(data)
		},
		newButtonAction(e) {
			console.debug(e)
		},
		log(e) {
			console.debug(e)
		},
	},
}
</script>
