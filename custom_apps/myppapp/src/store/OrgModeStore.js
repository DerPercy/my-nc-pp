import axios from '@nextcloud/axios'

const modOrgMode = {
	namespaced: true,
	state: {
		settingsFilePath: localStorage.orgmode_filePath || '/myppapp/Timetracking.org',
		// tasks: [],
		fileData: {},
	},
	mutations: {
		SET_FILE_DETAILS: (state, details) => {
			state.fileData = details
		},
		SET_PATH: (state, newValue) => {
			state.settingsFilePath = newValue
			localStorage.orgmode_filePath = newValue
		},
		/* SET_TASKS: (state, tasks) => {
			state.tasks = tasks
		}, */
	},
	actions: {
		setPath: ({ commit, state }, newValue) => {
			commit('SET_PATH', newValue)
			return state.settingsFilePath
		},

		async getFileData({ commit, state }) {
			return new Promise(function(resolve, reject) {
				axios
					.get('./om/details', {
						params: {
							file: state.settingsFilePath,
						},
					})
					.then(response => {
						commit('SET_FILE_DETAILS', response.data)
						resolve(response.data)
					})
			})
		},
		async getTasks({ commit, dispatch }) {
			return new Promise(function(resolve, reject) {
				dispatch('getFileData').then((fileData) => {
					const tasks = []
					for (let i = 0; i < fileData.nodes.length; i++) {
						if (fileData.nodes[i].isTodo === true) {
							tasks.push(fileData.nodes[i])
						}
					}
					// commit('SET_TASKS', tasks)
					resolve(tasks)
				})
				/* axios
					.get('./om/tasks', {
						params: {
							file: state.settingsFilePath,
						},
					})
					.then(response => {
						commit('SET_TASKS', response.data.tasks)
						resolve(response.data.tasks)
					}) */
			})
		},
		// other actions
	},
}

export default modOrgMode
