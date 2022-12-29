import ax from './axios'
const axios = ax.getAxios()

const modDomain = {
	namespaced: true,
	actions: {
		async getEntityDefinitions({ commit, state }) {
			return new Promise(function(resolve, reject) {
				axios
					.get('./ddd/entitydefinitions', {
						params: {},
					})
					.then(response => {
						resolve(response.data)
					})
			})
		},

		// Update an entity
		async updateEntity({ commit, state }, data) {
			console.log('DomainStore.updateEntity', data)
			return new Promise(function(resolve, reject) {
				axios
					.post('./ddd/updateentity', {
						entityid: data.entityid,
						entitydata: data.data,
						relations: data.relations,
					})
					.then(response => {
						resolve(response.data)
					})
			})
		},
		// Create an entity
		async createEntity({ commit, state }, data) {
			console.log('DomainStore.createEntity', data)
			return new Promise(function(resolve, reject) {
				axios
					.post('./ddd/createentity', {
						entityname: data.entity,
						entitydata: data.data,
						relations: data.relations,
					})
					.then(response => {
						resolve(response.data)
					})
			})
		},

		// Get all entities of type
		async getEntitiesOfType({ commit, state }, type) {
			return new Promise(function(resolve, reject) {
				axios
					.get('./ddd/entities/' + type, {
					})
					.then(response => {
						resolve(response.data)
					})
			})
		},

		// Get entityByID
		async getEntityByID({ commit, state }, entityID) {
			return new Promise(function(resolve, reject) {
				axios
					.get('./ddd/entity/' + entityID, {
					})
					.then(response => {
						resolve(response.data)
					})
			})
		},

	},
}

export default modDomain
