## Getting Started
### Formatting HTML
Using Bonsai is easy as long as you feed it correctly formmated markup to consume. By default, each expandable item should have a 'folder' class, contain atleast one anchor tag and optionally have child folders.
Each anchor tag should have the i tags displayed below and contain a title.

```html
    <div class="bonsai">
        <ul class="section">
          <li class="folder">
            <a class="" href=""><i class="icon-caret-right"></i><i class="icon-doctype icon-columns"></i>Templates<i class="icon-chevron-sign-right"></i></a>
            <ul class="section">
              <li class="folder">
                <a class="" href=""><i class="icon-caret-right"></i><i class="icon-folder icon-doctype icon-folder-close"></i>Navigation<i class="icon-chevron-sign-right"></i></a>
                <ul class="section">
                  <li><a href=""><i class="icon-filetype icon-doctype icon-columns"></i>header<i class="icon-chevron-sign-right"></i></a></li>
                  <li><a href=""><i class="icon-filetype icon-doctype icon-columns"></i>footer<i class="icon-chevron-sign-right"></i></a></li>
                  <li><a href=""><i class="icon-filetype icon-doctype icon-columns"></i>navigation<i class="icon-chevron-sign-right"></i></a></li>
                  <li><a href=""><i class="icon-filetype icon-doctype icon-columns"></i>main-nav<i class="icon-chevron-sign-right"></i></a></li>
                  <li><a href=""><i class="icon-filetype icon-doctype icon-columns"></i>footer-open<i class="icon-chevron-sign-right"></i></a></li>
                  <li><a href=""><i class="icon-filetype icon-doctype icon-columns"></i>footer-close<i class="icon-chevron-sign-right"></i></a></li>
                  <li><a href=""><i class="icon-filetype icon-doctype icon-columns"></i>wrapper<i class="icon-chevron-sign-right"></i></a></li>
                  <li><a href=""><i class="icon-filetype icon-doctype icon-columns"></i>body-content<i class="icon-chevron-sign-right"></i></a></li>
                </ul>
              </li>
            </ul>
          </li>
          <li class="folder">
            <a class="" href=""><i class="icon-caret-right"></i><i class="icon-doctype icon-list-alt"></i>Fields<i class="icon-chevron-sign-right"></i></a>
            <ul class="section">
              <li class="folder">
                <a class="" href=""><i class="icon-caret-right"></i><i class="icon-folder icon-doctype icon-folder-close"></i>Product<i class="icon-chevron-sign-right"></i></a>
                <ul class="section">
                  <li><a href=""><i class="icon-filetype icon-doctype icon-list-alt"></i>Price<i class="icon-chevron-sign-right"></i></a></li>
                  <li><a href=""><i class="icon-filetype icon-doctype icon-list-alt"></i>Inventory<i class="icon-chevron-sign-right"></i></a></li>
                </ul>
              </li>
            </ul>
          </li>
          <li class="folder">
            <a class="" href=""><i class="icon-caret-right"></i><i class="icon-doctype icon-code"></i>Snippets<i class="icon-chevron-sign-right"></i></a>
            <ul class="section">
              <li class="folder">
                <a class="" href=""><i class="icon-caret-right"></i><i class="icon-folder icon-doctype icon-folder-close"></i>getResources<i class="icon-chevron-sign-right"></i></a>
                <ul class="section">
                  <li><a href=""><i class="icon-filetype icon-doctype icon-code"></i>getResources<i class="icon-chevron-sign-right"></i></a></li>
                </ul>
              </li>
            </ul>
          </li>
        </ul>
      </div>
```

## Using the Plugin
Call the bonsai jQuery plugin like so on the element(s) of your choice:

```js
$('.bonsai').bonsai();
````

## Contributing to MODX Using Grunt
MODX Revolution 2.3 introduces CSS preprocessing to the Manager CSS. 

_Note: If you haven't already you'll first need to [setup node.js and npm](http://shapeshed.com/setting-up-nodejs-and-npm-on-mac-osx/)._

### Install Grunt
In terminal, cd to _build/templates/default. Then run:

	npm install -g grunt-cli
	
You just [installed grunt](http://gruntjs.com/getting-started#installing-the-cli). Now [install any necessary grunt packages](http://gruntjs.com/getting-started#installing-grunt-and-gruntplugins) by running this command:

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
