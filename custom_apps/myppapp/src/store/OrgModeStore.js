import axios from '@nextcloud/axios'

const modOrgMode = {
	namespaced: true,
	state: {
		settingsFilePath: localStorage.orgmode_filePath || '/myppapp/Timetracking.org',
		tasks: [],
	},
	mutations: {
		SET_PATH: (state, newValue) => {
			state.settingsFilePath = newValue
			localStorage.orgmode_filePath = newValue
		},
		SET_TASKS: (state, tasks) => {
			state.tasks = tasks
		},
	},
	actions: {
		setPath: ({ commit, state }, newValue) => {
			commit('SET_PATH', newValue)
			return state.settingsFilePath
		},
		async getTasks({ commit, state }) {
			return new Promise(function(resolve, reject) {
				axios
					.get('./om/tasks', {
						params: {
							file: state.settingsFilePath,
						},
					})
					.then(response => {
						commit('SET_TASKS', response.data.tasks)
						resolve(response.data.tasks)
					})
			})
		},
		// other actions
	},
}

export default modOrgMode
