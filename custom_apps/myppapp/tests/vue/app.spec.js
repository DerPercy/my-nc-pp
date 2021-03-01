import { mount } from '@vue/test-utils'
import App from './../../src/App.vue'

import pTree from './projectTree.json'

import ProjectSelector from './../../src/components/ProjectSelector.vue'

describe('ProjectSelector', () => {
  // Inspect the raw component options
  it('works', (done) => {
    // expect(typeof App.data).toBe('function')

		const wrapper = mount(ProjectSelector, {
			propsData: {
				projTree: pTree
			}
		})

		const optList = wrapper.findAll('select.projectselector-custselect option')
		expect(optList.length).toBe(9)

		expect(wrapper.find(".projectselector-projselect").exists()).toBe(false)


		optList.at(1).setSelected().then(() => {
			expect(wrapper.find(".projectselector-projselect").exists()).toBe(true)
			const optList = wrapper.findAll('select.projectselector-projselect option')
			expect(optList.length).toBe(7)

			done()
		})
   })
})
