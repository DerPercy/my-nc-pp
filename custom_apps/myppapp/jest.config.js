const {defaults} = require('jest-config');

module.exports = {
	moduleNameMapper: {
		'\\.(css|less)$': '<rootDir>/__mocks__/styleMock.js',
	},
	moduleFileExtensions: [ ...defaults.moduleFileExtensions, "vue" ],
	//	"js",
	//	"vue"
	//],
	transformIgnorePatterns: [ ".css" ],
  transform: {
    '^.+\\.js$': 'babel-jest',
    '.*\\.(vue)$': 'vue-jest'
  }
}
