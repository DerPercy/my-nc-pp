import Vue from 'vue'
import Vuex from 'vuex'
import axios from '@nextcloud/axios'

Vue.use(Vuex)

// Task module
const modTask = {
	namespaced: true,
	state: () => { return {} },
	mutations: { },
	actions: {
		loadData({ state, commit, dispatch, rootState }) {
			const fIterator = (project, customer) => {
				const path = project.path
				axios
					.get('./tasks', {
						params: {
							folderPath: path,
						},
					})
					.then(response => {
						console.log('Tasks', response.data)
					})
				console.log('Iterate', customer, project)
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
	},
	state: {
		asideType: '', // Currently open aside ('tr')
		asideTRData: {},
		timetracking: null,
		contentType: 'timetracking',
		contentProjectPath: 'dummy',
		contentProjectData: null,
	},
	mutations: {
		closeSideBar: state => { state.asideType = '' },
		openTRSideBar: (state, timerecorddata) => { state.asideTRData = timerecorddata; state.asideType = 'tr' },
		openTRContent: (state) => { state.contentType = 'timetracking' },
		openProjectDashboard: (state, data) => {
			state.contentProjectPath = data.path
			state.contentProjectData = data
			state.contentType = 'project'
		},
		projectChangeWikiPage: (state, wikiData) => {

			const data = Object.assign({}, state.contentProjectData)
			data.wiki = wikiData
			state.contentProjectData = data
		},
	},
	actions: {
		navtoTimetracking(context) {
			context.commit('openTRContent')
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
