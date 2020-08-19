import Vue from 'vue'
import Vuex from 'vuex'
import { translate, translatePlural } from '@nextcloud/l10n'
import axios from '@nextcloud/axios'

import App from './App'

Vue.use(Vuex)

const store = new Vuex.Store({
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
		openTRSideBar: (state, timerecorddata) => { console.log(timerecorddata); state.asideTRData = timerecorddata; state.asideType = 'tr' },
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
			console.log(state.contentProjectData)
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
					console.log(response.data)
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
					console.log(response.data)
					context.commit('projectChangeWikiPage', response.data)
				})
		},
	},
})

Vue.prototype.t = translate
Vue.prototype.n = translatePlural

export default new Vue({
	el: '#content',
	store,
	render: h => h(App),
})
