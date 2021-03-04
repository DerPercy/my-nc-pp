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
			const pOptList = wrapper.findAll('select.projectselector-projselect option')
			expect(pOptList.length).toBe(7)

			pOptList.at(2).setSelected().then(() => {
				expect(wrapper.emitted().change).toBeTruthy();
				expect(wrapper.emitted().change[0][0].customer.value).toBe("Cust1"); 			// 1st emit: only customer
				expect(wrapper.emitted().change[1][0].project.value).toBe("Project 1-2");	// 2nd emit: customer and project
				done()
			})

		})
   })
})
