// See: https://dev.to/karam/easily-test-your-vuex-store-using-vue-test-utils-3172

import 'regenerator-runtime/runtime'


import Vuex from "vuex";
import { createLocalVue } from "@vue/test-utils";
//import ax from './../../src/store/axios.js';

//import mockPosts from "./__mocks__/posts.json";
//ax.getAxios = jest.fn().mockReturnValue(null);

jest.mock('./../../src/store/axios.js', () => (
  {
    getAxios: jest.fn(() => {
			const axios = require('axios');
			return axios 
		})
  }
))

//console.log(ax);

import store from './../../src/store/store.js'


//console.log(axios_mock);

//const fs = jest.createMockFromModule('axios');

//console.log(fs);
/*
jest.mock("axios", () => ({
	...axios,
	get: jest.fn(() => Promise.resolve({ data: { } })),
}));*/

jest.mock("axios", () => ({
  get: jest.fn(() => Promise.resolve({ data: { nodes: [] } }))
}));


beforeEach(() => {
  createLocalVue().use(Vuex);
  //const storeConfig = createStoreConfig();
  //store = new Vuex.Store(storeConfig);
});

describe("Post Store Tests", () => {
  it("loads posts and updates them in state", (done) => {
    store.dispatch("om/getTasks").then(() => {
			done()
		});
    //expect(store.getters["posts/getPosts"]).toEqual(mockPosts);
  });
});
