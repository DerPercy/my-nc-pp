/** Determining axios via function
	* reason: possible to replace @nextcloud/axios with default axios during testrun
	* @nextcloud/axios could not be instanced during testrun
*/

function getAxios() {
	const axios = require('@nextcloud/axios')
	return axios.default
}

const exportFunctions = {
	getAxios,
}

export default exportFunctions
