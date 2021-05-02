<template>
	<div>
		<label>{{propertyKey}}</label>
		<input v-if="showInputField" v-model="inputValue" @change="changeValue" />
		<input
			v-if="showDatePicker"
			v-model="inputValue"
			type="datetime"
			@input="changeValue"
			:auto="dtSettingsAuto"
			:minute-step="dtSettingsMinuteStep"
			format="yyyy-MM-dd HH:mm:ss" />
		<DatetimePicker
			v-if="showDatePicker"
			v-model="inputValueDatePicker"
			:minute-step="dtSettingsMinuteStep"
			:show-second="dtSettingsShowSecond"
			type="datetime"
			format="YYYY-MM-DDTHH:mm:ssZ" />
	</div>
</template>

<script>

// Datetime
// import { Datetime } from 'vue-datetime'
// import 'vue-datetime/dist/vue-datetime.css'

import DatetimePicker from '@nextcloud/vue/dist/Components/DatetimePicker'

// import DatetimePicker from 'vue2-datepicker'
// import 'vue2-datepicker/index.css'

export default {
	components: {
		DatetimePicker,
	},

	props: {
		propertyDefinition: {
			// type: Object,
			required: true,
		},
		propertyKey: {
			type: String,
			required: true,
		},
		entity: {
			type: Object,
			required: false,
		},
	},
	data() {
		return {
			inputValueData: null,
			dtSettingsAuto: true,
			dtSettingsMinuteStep: 15,
			dtSettingsShowSecond: false,
		}
	},
	mounted() {
		// console.log('EFI mounted', this.$props.Entity)
	},
	computed: {
		inputValueDatePicker: {
			get() {
				if (this.inputValueData) {
					return this.inputValueData
				}
				if (this.$props.entity) { // Edit mode
					return this.$props.entity.data[this.$props.propertyKey]
				}
				return null
			},
			set(value) {
				console.log('InputValueDatePicker.set', value)
				this.inputValueData = value
				this.$emit('change', { name: this.$props.propertyKey, value: value.toISOString() })
			}
		},
		inputValue: {
			get() {
				if (this.$props.entity) { // Edit mode
					return this.$props.entity.data[this.$props.propertyKey]
				}
				return this.inputValueData
			},
			set(value) {
				console.log('InputValue.set', value)
				this.inputValueData = value

			}
		},
		showInputField() {
			if (this.showDatePicker) {
				return false
			}
			return true
		},
		showDatePicker() {
			if (this.$props.propertyDefinition.type === 'DATETIME') {
				return true
			}
			return false
		}
	},
	updated() {
		console.log('EFI updated', this.$props.entity)
	},
	methods: {
		changeValue() {
			// console.log('change', this.inputValue)
			this.$emit('change', { name: this.$props.propertyKey, value: this.inputValueData })
		},
	},
}
</script>
