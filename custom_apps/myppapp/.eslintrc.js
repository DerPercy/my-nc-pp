
module.exports = {
	extends: [
		'@nextcloud'
	],
	rules: {
    "no-console": "off",
  },
	overrides: [{
  	files: ["*MarkdownPage.vue"],
     rules: {
        "vue/no-v-html": "off"
     }
 	}]
};
