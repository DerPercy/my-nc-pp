
module.exports = {
	extends: [
		'@nextcloud'
	],
	rules: {
    "no-console": "warn",
  },
	overrides: [{
  	files: ["*MarkdownPage.vue"],
     rules: {
        "vue/no-v-html": "off"
     }
 	}]
};
