Contribution Guides
--------------------------------------

In the spirit of open source software development, MODX always encourages community code contribution. To help you get started and before you jump into writing code, be sure to read these important contribution guidelines thoroughly:

What you need
--------------------------------------

In order to build MODX front end assets, you need to have Node.js/npm latest and git 1.7 or later.
(Earlier versions might work OK, but are not tested.)

For Windows you have to download and install [git](http://git-scm.com/downloads) and [Node.js](http://nodejs.org/download/).

Mac OS users should install [Homebrew](http://mxcl.github.com/homebrew/). Once Homebrew is installed, run `brew install git` to install git,
and `brew install node` to install Node.js.

Linux/BSD users should use their appropriate package managers to install git and Node.js, or build from source
if you swing that way. Easy-peasy.

How to build
----------------------------

First, clone a copy of this git repo by running:

```bash
git clone git://github.com/rthrash/revolution.git
```

Install the [grunt-cli](http://gruntjs.com/getting-started#installing-the-cli) and [bower](http://bower.io/) packages if you haven't before. These should be done as global installs:

```bash
npm install -g grunt-cli bower
```

Make sure you have `grunt` and `bower` installed by testing:

```bash
grunt -version
bower -version
```

Enter the default template directory and install the Node and Bower dependencies, this time *without* specifying a global(-g) install:

```bash
cd _built/templates/default && npm install
```

Then, to compile the Sass and watch files for changes type the following:

```bash
grunt
```
_Note: grunt is now watching files for changes. When Sass files are changed CSS will automatically be generated._

Fetch dependencies (such as bourbon), move items into place and compile by running:

```bash
grunt build
```

Compile Sass and minify for production by running:

```bash
grunt prod
```
