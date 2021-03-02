import Vue from 'vue'
import Vuex from 'vuex'
// import axios from '@nextcloud/axios'
// import axios from './axios'

import modOM from './OrgModeStore'

import ax from './axios'
const axios = ax.getAxios()

Vue.use(Vuex)

// Task module
const modTask = {
	namespaced: true,
	state: {
		tasks: [],
	},
	mutations: {
		addTaskFile: (state, taskData) => {
			const path = taskData.path
			const newTasks = []
			for (let i = 0; i < state.tasks.length; i++) {
				if (state.tasks[i].path !== path) {
					newTasks.push(state.tasks[i])
				}
			}
			for (let i = 0; i < taskData.tasks.length; i++) {
				newTasks.push(taskData.tasks[i])
			}
			state.tasks = newTasks
		},
	},
	actions: {
		loadData({ state, commit, dispatch, rootState }) {
			const fIterator = (project, customer) => {
				const path = project.path
				axios
					.get('./tasks', {
						params: {
							folderPath: path,
							file: '/myppapp/Timetracking.org',
						},
					})
					.then(response => {
						commit('addTaskFile', response.data)
						// console.log('Tasks', response.data)
					})
				// console.log('Iterate', customer, project)
			}
			dispatch('project/projectIterator', fIterator, { root: true })
		},
	},
	getters: { },
}

// Project module
const modProject = {
	namespaced: true,
	state: {
		customerOverview: null,
	},
	mutations: {
		updateProjects: (state, projectData) => { state.customerOverview = projectData },
	},
	actions: {
		async getCustomerOverview({ state, commit, dispatch, rootState }) {
			return new Promise((resolve, reject) => {
				if (state.customerOverview) { // Customers already fetched
					resolve(state.customerOverview)
				} else {
					const fResolve = () => {
						resolve(state.customerOverview)
					}
					dispatch('loadData', { resolve: fResolve })
				}
			})
		},
		loadData({ state, commit, rootState }, { resolve, reject }) {
			axios.get('./customers').then((response) => {
				commit('updateProjects', response.data)
				if (resolve) {
					resolve()
				}
			})
		},
		projectIterator({ state, commit, dispatch, rootState }, cbOnProject) {
			dispatch('getCustomerOverview').then((custOverview) => {
				for (let i = 0; i < custOverview.length; i++) {
					const customer = custOverview[i]
					for (let j = 0; j < customer.projects.length; j++) {
						const project = customer.projects[j]
						if (cbOnProject) {
							cbOnProject(project, customer)
						}
					}
				}
			})
		},
	},
	getters: {

	},
}

const store = new Vuex.Store({
	modules: {
		project: modProject,
		task: modTask,
		om: modOM,
	},
	state: {
		asideType: '', // Currently open aside ('tr')
		asideTRData: {},
		timetracking: null,
		contentType: 'orgmode',
		contentProjectPath: 'dummy',
		contentProjectData: null,
	},
	mutations: {
		closeSideBar: state => { state.asideType = '' },
		openTRSideBar: (state, timerecorddata) => { state.asideTRData = timerecorddata; state.asideType = 'tr' },
		openTaskContent: (state) => { state.contentType = 'tasks' },
		openOrgMode: (state) => { state.contentType = 'orgmode' },
		openProjectDashboard: (state, data) => {
			state.contentProjectPath = data.path
			state.contentProjectData = data
			state.contentType = 'project'
		},
		openCustomerDashboard: (state, data) => {
			state.contentCustomerData = data
			state.contentType = 'customer'
		},
		projectChangeWikiPage: (state, wikiData) => {

			const data = Object.assign({}, state.contentProjectData)
			data.wiki = wikiData
			state.contentProjectData = data
		},
	},
	actions: {
		// Handle a HTTP-Response and display messages etc
		handleHTTPResponse(context, response) {
		// console.log('HTTP-Response', response.data)
		},
		handleHTTPResponseError(context, response) {
		// console.log('HTTP-Response-Error', response.data)
		},
		navtoOrgMode(context) {
			context.commit('openOrgMode')
		},
		navtoTasks(context) {
			context.commit('openTaskContent')
		},
		navtoProject(context, path) {
			axios
				.get('./project', {
					params: {
						projectpath: path,
					},
				})
				.then(response => {
					context.commit('openProjectDashboard', response.data)
				})
		},
		navtoCustomer(context, customer) {
			context.commit('openCustomerDashboard', customer)
		},
		projectDashboardNavWiki(context, navdata) {
			axios
				.get('./wikipage', {
					params: {
						wikipagePath: navdata.sourceFile,
						wikipageLink: navdata.href,
					},
				})
				.then(response => {
					context.commit('projectChangeWikiPage', response.data)
				})
		},
	},
})

export default store
