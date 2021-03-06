// import axios from '@nextcloud/axios'
// import axios from './axios'
import ax from './axios'
const axios = ax.getAxios()

const modOrgMode = {
	namespaced: true,
	state: {
		settingsFilePath: localStorage.orgmode_filePath || '/myppapp/Timetracking.org',
		settingsTodoFlags: localStorage.orgmode_todoflags || 'TODO,DONE,WAIT,INTEST,CANCELED',
		// tasks: [],
		fileData: {},
	},
	mutations: {
		SET_FILE_DETAILS: (state, details) => {
			state.fileData = details
			state.fileHash = details.hash
		},
		SET_PATH: (state, newValue) => {
			state.settingsFilePath = newValue
			localStorage.orgmode_filePath = newValue
		},
		SET_TODOFLAGS: (state, newValue) => {
			state.settingsTodoFlags = newValue
			localStorage.orgmode_todoflags = newValue
		},
	},
	actions: {
		setPath: ({ commit, state }, newValue) => {
			commit('SET_PATH', newValue)
			return state.settingsFilePath
		},
		setTodoFlags: ({ commit, state }, newValue) => {
			commit('SET_TODOFLAGS', newValue)
			return state.settingsTodoFlags
		},
		// >> Settings
		async getSettings({ commit, state }) {
			return new Promise(function(resolve, reject) {
				resolve(require('./settings.json'))
			})
		},
		async getSettingsTodoFlags({ commit, state, dispatch }) {
			return new Promise(function(resolve, reject) {
				dispatch('getSettings').then((settings) => {
					resolve(settings.todo.flags)
				})
			})
		},

		async getTodoFlagIDs({ commit, state, dispatch }) {
			return new Promise(function(resolve, reject) {
				dispatch('getSettings').then((settings) => {
					const flags = []
					for (const prop in settings.todo.flags) {
						flags.push(prop)
					}
					resolve(flags)
				})
			})
		},
		// << Settings
		async getFileData({ commit, state }) {
			return new Promise(function(resolve, reject) {
				if (state.fileHash) {
					// resolve(state.fileData)
					axios
						.get('./om/hash', {
							params: {
								file: state.settingsFilePath,
							},
						})
						.then(response => {
							if (response.data.hash !== state.fileHash) {
								// alert('File on disk changed. Please reload')
								// resolve(state.fileData)
								axios
									.get('./om/details', {
										params: {
											file: state.settingsFilePath,
											todoflags: state.settingsTodoFlags,
										},
									})
									.then(response => {
										commit('SET_FILE_DETAILS', response.data)
										resolve(response.data)
									})

							} else {
								resolve(state.fileData)
							}
							// commit('SET_FILE_DETAILS', response.data)
							// resolve(response.data)
						})
					return
				}
				axios
					.get('./om/details', {
						params: {
							file: state.settingsFilePath,
							todoflags: state.settingsTodoFlags,
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
			})
		},
		async getProjectTree({ commit, dispatch }) {
			return new Promise(function(resolve, reject) {
				dispatch('getFileData').then((fileData) => {
					resolve(fileData.ptree)
				})
			})
		},
		async getLogbook({ commit, dispatch }) {
			return new Promise(function(resolve, reject) {
				dispatch('getFileData').then((fileData) => {
					resolve(fileData.logbook)
				})
			})
		},
		// other actions
	},
}

export default modOrgMode
