## Contributing to MODX Using Grunt
MODX Revolution 2.3 introduces CSS preprocessing to the process of contributing to the Manager CSS. 

### Install Grunt
In terminal, cd to _build/templates/default. Then run:

	npm install -g grunt-cli
	
Cool, you just installed grunt. Now install any necessary grunt packages by running this command:

	npm install
	
### Using the different Grunt Tasks
To compile the Sass and then watch for changes run:

	grunt
	
To compile the Sass run:

	grunt build
	
To prepare the project for distribution and ensure minification is used run:

	grunt prod

