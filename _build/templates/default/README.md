## Contributing to MODX Using Grunt
MODX Revolution 2.3 introduces CSS preprocessing to the Manager CSS. 

### Install Grunt
In terminal, cd to _build/templates/default. Then run:

	npm install -g grunt-cli
	
Cool, you just [installed grunt](http://gruntjs.com/getting-started#installing-the-cli). Now [install any necessary grunt packages](http://gruntjs.com/getting-started#installing-grunt-and-gruntplugins) by running this command:

	npm install
	
### Installing Bourbon
Bourbon is a Sass Mixin Framework you will need to compile the Sass.

cd to the sass directory
	
	cd _build/templates/default/sass
	
Install [bourbon](https://github.com/thoughtbot/bourbon#non-rails-projects)

	gem install bourbon
	bourbon install
	
You now have a sass/bourbon directory that mixins can be imported from using

	@import 'bourbon/bourbon';
	
### Using the different Grunt Tasks
To compile the Sass and then watch for changes run:

	grunt
	
You can now make changes to index.scss and login.scss and see them automatically compile CSS.
	
To just compile the Sass run:

	grunt build
	
To prepare the project for distribution and ensure minification is used run:

	grunt prod
