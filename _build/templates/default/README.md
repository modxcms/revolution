Contribution Guides
--------------------------------------

The visual design of the MODX Manager UI is developed using Sass and Grunt to allow front end developers to quickly create custom themes and contribute to the core. In the spirit of open source software development, MODX always encourages community code contribution. To help you get started and before you jump into writing code, be sure to read these important contribution guidelines thoroughly.

What you need
--------------------------------------

In order to build MODX front end assets, you need to have Ruby, Node.js/npm latest and git 1.7 or later.
(Earlier versions might work OK, but are not tested.)

For Windows you have to download and install [git](http://git-scm.com/downloads) and [Node.js](http://nodejs.org/download/).

Mac OS users should install [Homebrew](http://mxcl.github.com/homebrew/). Once Homebrew is installed, run `brew install git` to install git,
and `brew install node` to install Node.js.

Linux/BSD users should use their appropriate package managers to install git and Node.js, or build from source
if you swing that way. Easy-peasy.

Installing Ruby and Ruby Gems
----------------------------
You'll need ruby and sass gems installed. If you're on OS X or Linux you probably already have Ruby installed; test with ruby -v in your terminal. When you've confirmed you have Ruby installed, run gem install sass to install Sass.

```bash
gem install sass
```

Make sure you have `sass` installed by testing:

```bash
sass --version
```
_Note: Depending on your ruby setup you may need to install gems using `sudo gem install sass`._

Installing Grunt & Grunt Packages
----------------------------

First, clone a copy of this git repo by running:

```bash
git clone -b develop git://github.com/modxcms/revolution.git
```

Install the [grunt-cli](http://gruntjs.com/getting-started#installing-the-cli) and [bower](http://bower.io/) packages if you haven't before. These should be done as global installs:

```bash
npm install -g grunt-cli bower
```

Make sure you have `grunt` and `bower` installed by testing:

```bash
grunt --version
bower --version
```

Enter the default template directory and install the Node and Bower dependencies, this time *without* specifying a global(-g) install:

```bash
cd revolution/_build/templates/default && npm install
```
_Note: `npm install` updates dependencies and should be run whenever you pull from git._

Optionally enable Growl notifications install [terminal-notifier](https://github.com/alextucker/grunt-growl#getting-started) with RubyGems:
```bash
gem install terminal-notifier
```
_Note: Depending on your ruby setup you may need to install gems using `sudo gem install terminal-notifier`._

Grunt Commands
----------------------------

__Build__<br>
Fetch dependencies (such as bourbon), move items into place and compile by running:

```bash
grunt build
```

__Watch__<br>
Compile the Sass and watch files for changes type the following:

```bash
grunt
```
_Note: grunt is now watching files for changes. When Sass files are changed CSS will automatically be generated.<br>Install the LiveReload [browser extension](http://feedback.livereload.com/knowledgebase/articles/86242-how-do-i-install-and-use-the-browser-extensions-) to inject CSS changes without a page refresh._

__Expand__<br>
Compile Sass using expanded output style for development by running:

```bash
grunt expand
```
_Note: do not check in uncompressed CSS._
