// See: https://dev.to/karam/easily-test-your-vuex-store-using-vue-test-utils-3172

import 'regenerator-runtime/runtime'
import Vuex from "vuex";
import { mount, createLocalVue } from "@vue/test-utils";

import TaskList from './../../src/components/TaskList.vue'


jest.mock('./../../src/store/axios.js', () => (
  {
    getAxios: jest.fn(() => {
			const axios = require('axios');
			return axios
		})
  }
))

import store from './../../src/store/store.js'

jest.mock("axios", () => ({
  get: jest.fn((path,options) => {
		// console.log(path,options);
		switch(path){
			case "./om/details":
				return Promise.resolve({ data: require('./dataResponse.json') })
			case "./om/hash":
				return Promise.resolve({ data: { hash: "a" } })
			default:
				console.error("Path "+path+" not mocked");
		}
	})
}));


beforeEach(() => {
  createLocalVue().use(Vuex);
});

describe("TaskList", () => {
	it("works", async () => {
		let tasks = await store.dispatch("om/getTasks");
		let projTree = await store.dispatch("om/getProjectTree");

		expect(tasks.length).toBe(1);

		const wrapper = mount(TaskList, {
			propsData: {
				tasks,
				projTree
			}
		});
		const pTaskList = wrapper.findAll('li.task')
		expect(pTaskList.length).toBe(1)

		// Next: Filter by customer/project

  });
});
