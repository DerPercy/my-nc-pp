import axios from '@nextcloud/axios'

// Application Store
export const store = {
	state: {
		numbers: [1, 2, 3],
		asideType: 'tr',
	},
	addNumber(newNumber) {
		this.state.numbers.push(newNumber)
	},
	// Actions
	getCustomers() {
		return new Promise((resolve, reject) => {
			axios
				.get('./customers')
				.then(response => (
					resolve(response.data)
				))
		})
	},
	closeSideBar() {
		this.state.asideType = null
	},
}
