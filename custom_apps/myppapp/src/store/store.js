import Vue from 'vue'
import Vuex from 'vuex'
// import axios from '@nextcloud/axios'
// import axios from './axios'

import modOM from './OrgModeStore'

Vue.use(Vuex)

const store = new Vuex.Store({
	modules: {
		om: modOM,
	},
	state: {
		asideType: '', // Currently open aside ('tr')
		contentType: 'orgmode',
	},
	mutations: {
		closeSideBar: state => { state.asideType = '' },
		openTRSideBar: (state, timerecorddata) => { state.asideTRData = timerecorddata; state.asideType = 'tr' },
		openOrgMode: (state) => { state.contentType = 'orgmode' },
		setContentType: (state, ct) => { state.contentType = ct },
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
		setContentType(context, ct) {
			context.commit('setContentType', ct)
		},
	},
})

export default store
