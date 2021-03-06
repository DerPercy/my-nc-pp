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
	it("get the correct todoflags", async () => {
		let todoFlags = await store.dispatch("om/getTodoFlagIDs");
		expect(todoFlags.length).toBe(5);
		expect(todoFlags[0]).toBe("TODO");

	})
	it("works", async () => {
		let tasks = await store.dispatch("om/getTasks");
		let projTree = await store.dispatch("om/getProjectTree");
		let todoFlags = await store.dispatch("om/getSettingsTodoFlags");

		expect(tasks.length).toBe(4);

		const wrapper = await mount(TaskList, {
			propsData: {
				tasks,
				projTree,
				todoFlagSettings: todoFlags,
			}
		});
		let pTaskList = wrapper.findAll('li.task')
		expect(pTaskList.length).toBe(4)

		// Comment from TodoChangelog
		let badges = wrapper.findAll('li.task .badge')
		expect(badges.at(0).attributes('title')).toBe('')
		expect(badges.at(1).attributes('title')).toBe('Comment for intest')

		// Correct done-flags
		pTaskList = wrapper.findAll('li.task-done')
		expect(pTaskList.length).toBe(2)



		// Next: Filter by customer/project
		const custList = wrapper.findAll('select.projectselector-custselect option')
		await custList.at(1).setSelected();

		pTaskList = wrapper.findAll('li.task')
		expect(pTaskList.length).toBe(3) // filter by customer

		const projList = wrapper.findAll('select.projectselector-projselect option')
		await projList.at(1).setSelected();

		pTaskList = wrapper.findAll('li.task')
		expect(pTaskList.length).toBe(2) // filter by project

  });
});
