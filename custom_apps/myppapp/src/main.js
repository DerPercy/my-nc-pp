import Vue from 'vue'
import Vuex from 'vuex'
import { translate, translatePlural } from '@nextcloud/l10n'

import store from './store/store.js'
import App from './App'

Vue.use(Vuex)

Vue.prototype.t = translate
Vue.prototype.n = translatePlural

export default new Vue({
	el: '#content',
	store,
	render: h => h(App),
})
