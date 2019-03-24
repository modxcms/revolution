var coreJSFiles = [
  '<%= dirs.manager %>assets/modext/core/modx.localization.js',
  '<%= dirs.manager %>assets/modext/util/utilities.js',
  '<%= dirs.manager %>assets/modext/util/datetime.js',
  '<%= dirs.manager %>assets/modext/util/uploaddialog.js',
  '<%= dirs.manager %>assets/modext/util/fileupload.js',
  '<%= dirs.manager %>assets/modext/util/superboxselect.js',
  '<%= dirs.manager %>assets/modext/core/modx.component.js',
  '<%= dirs.manager %>assets/modext/core/modx.view.js',
  '<%= dirs.manager %>assets/modext/widgets/core/modx.button.js',
  '<%= dirs.manager %>assets/modext/widgets/core/modx.searchbar.js',
  '<%= dirs.manager %>assets/modext/widgets/core/modx.panel.js',
  '<%= dirs.manager %>assets/modext/widgets/core/modx.tabs.js',
  '<%= dirs.manager %>assets/modext/widgets/core/modx.window.js',
  '<%= dirs.manager %>assets/modext/widgets/core/modx.combo.js',
  '<%= dirs.manager %>assets/modext/widgets/core/modx.grid.js',
  '<%= dirs.manager %>assets/modext/widgets/core/modx.console.js',
  '<%= dirs.manager %>assets/modext/widgets/core/modx.portal.js',
  '<%= dirs.manager %>assets/modext/widgets/windows.js',
  '<%= dirs.manager %>assets/fileapi/FileAPI.js',
  '<%= dirs.manager %>assets/modext/util/multiuploaddialog.js',
  '<%= dirs.manager %>assets/modext/widgets/core/tree/modx.tree.js',
  '<%= dirs.manager %>assets/modext/widgets/core/tree/modx.tree.treeloader.js',
  '<%= dirs.manager %>assets/modext/widgets/modx.treedrop.js',
  '<%= dirs.manager %>assets/modext/widgets/core/modx.tree.asynctreenode.js',
  '<%= dirs.manager %>assets/modext/widgets/resource/modx.tree.resource.js',
  '<%= dirs.manager %>assets/modext/widgets/element/modx.tree.element.js',
  '<%= dirs.manager %>assets/modext/widgets/system/modx.tree.directory.js',
  '<%= dirs.manager %>assets/modext/widgets/system/modx.panel.filetree.js',
  '<%= dirs.manager %>assets/modext/widgets/media/modx.browser.js',
  '<%= dirs.manager %>assets/modext/core/modx.layout.js'
];
var sass = require('node-sass');

module.exports = function (grunt) {
  // Project configuration.
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    dirs: {
      /* just defining some properties */
      lib: 'node_modules/',
      scss: 'sass/',
      css: '../../../manager/templates/default/css/',
      template: '../../../manager/templates/default/',
      manager: '../../../manager/',
      setup: '../../../setup/assets/css/',
      root: '../../../'
    },
    copy: { /* move files */
      bourbon: {
        files: [{
          src: '**/*',
          cwd: '<%= dirs.lib %>bourbon/core',
          dest: '<%= dirs.scss %>/bourbon',
          expand: true,
          nonull: true
        }]
      },
      neat: {
        files: [{
          src: '**/*',
          cwd: '<%= dirs.lib %>bourbon-neat/core',
          dest: '<%= dirs.scss %>/neat',
          expand: true,
          nonull: true
        }]
      },
      fontawesome: {
        files: [{
          src: '**/*',
          cwd: '<%= dirs.lib %>@fortawesome/fontawesome-free/scss/',
          dest: '<%= dirs.scss %>/font-awesome/',
          expand: true,
          flatten: true,
          nonull: true
        }, {
          src: '**/*',
          cwd: '<%= dirs.lib %>@fortawesome/fontawesome-free/webfonts/',
          dest: '<%= dirs.template %>/fonts/',
          expand: true,
          flatten: true,
          nonull: true
        }]
      }
    },
    cssmin: {
      compress: {
        options: {
          report: 'min',
          sourceMap: true,
          keepSpecialComments: 1,
          banner: '/*!' +
            '\n* ' +
            '\n* Copyright (C) <%= grunt.template.today("yyyy") %> MODX LLC' +
            '\n* ' +
            '\n* This file is part of <%= pkg.title %> and was compiled using Grunt.' +
            '\n* ' +
            '\n* <%= pkg.title %> is free software: you can redistribute it and/or modify it under the terms of the' +
            '\n* GNU General Public License as published by the Free Software Foundation, either version 2 of the' +
            '\n* License, or (at your option) any later version.' +
            '\n* ' +
            '\n* <%= pkg.title %> is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;' +
            '\n* without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.' +
            '\n* ' +
            '\n* See the GNU General Public License for more details. You should have received a copy of the GNU' +
            '\n* General Public License along with <%= pkg.title %>. If not, see <http://www.gnu.org/licenses/>.' +
            '\n* ' +
            '\n*/'
        },
        files: {
          '<%= dirs.css %>index-min.css': '<%= dirs.css %>index.css',
          '<%= dirs.css %>login-min.css': '<%= dirs.css %>login.css',
          '<%= dirs.setup %>installer-min.css': '<%= dirs.setup %>installer.css'
        }
      }
    },
    sass: {
      dist: {
        options: {
          style: 'compressed',
          compass: false,
          sourcemap: false,
          implementation: sass,
          update: true
        },
        files: {
          '<%= dirs.css %>index.css': 'sass/index.scss',
          '<%= dirs.css %>login.css': 'sass/login.scss',
          '<%= dirs.setup %>installer.css': 'sass/installer.scss'
        }
      },
      dev: {
        options: {
          style: 'expanded',
          compass: false,
          sourcemap: false,
          implementation: sass,
          trace: true
        },
        files: {
          '<%= dirs.css %>index.css': 'sass/index.scss',
          '<%= dirs.css %>login.css': 'sass/login.scss',
          '<%= dirs.setup %>installer.css': 'sass/installer.scss'
        }
      }
    },
    autoprefixer: { /* this expands the css so it needs to get compressed with cssmin afterwards */
      options: {
        browsers: ['last 2 versions', 'ie 8', 'ie 9']
      },

      // just prefix the specified file
      index: {
        options: {},
        src: '<%= dirs.css %>index.css',
        dest: '<%= dirs.css %>index.css'
      },
      login: {
        options: {},
        src: '<%= dirs.css %>login.css',
        dest: '<%= dirs.css %>login.css'
      },
      setup: {
        options: {},
        src: '<%= dirs.setup %>installer.css',
        dest: '<%= dirs.setup %>installer.css'
      }
    },
    csslint: {
      strict: {
        options: {
          import: 2
        },
        src: ['<%= dirs.css %>*.css']
      }
    },
    watch: { /* trigger tasks on save */
      scss: {
        files: ['<%= dirs.scss %>/**/*'],
        tasks: ['sass:dev', 'notify:sass']
      },
      css: {
        options: {
          livereload: true
        },
        files: ['<%= dirs.css %>*.css'],
        tasks: []
      },
      js: {
        files: coreJSFiles,
        tasks: ['compress']
      }
    },
    imageoptim: {
      png: {
        options: {
          jpegMini: false,
          imageAlpha: true,
          quitAfter: true
        },
        src: [
          '<%= dirs.root %>setup/assets/**/*.png',
          '<%= dirs.root %>_build/docs/**/*.png',
          '<%= dirs.root %>manager/assets/ext3/**/*.png',
          '<%= dirs.root %>manager/templates/default/**/*.png'
        ]
      },
      jpg: {
        options: {
          jpegMini: false,
          imageAlpha: false,
          quitAfter: true
        },
        src: [
          '<%= dirs.root %>setup/assets/**/*.jpg',
          '<%= dirs.root %>_build/docs/**/*.jpg',
          '<%= dirs.root %>manager/assets/ext3/**/*.jpg',
          '<%= dirs.root %>manager/templates/default/**/*.jpg'
        ]
      },
      gif: {
        options: {
          jpegMini: false,
          imageAlpha: false,
          quitAfter: true
        },
        src: [
          '<%= dirs.root %>setup/assets/**/*.gif',
          '<%= dirs.root %>_build/docs/**/*.gif',
          '<%= dirs.root %>manager/assets/ext3/**/*.gif',
          '<%= dirs.root %>manager/templates/default/**/*.gif'
        ]
      }
    },
    uglify: {
      jsgrps: {
        options: {
          report: 'min',
          mangle: false,
          sourceMap: true
        },
        files: {
          '<%= dirs.manager %>assets/modext/modx.jsgrps-min.js': coreJSFiles
        }
      }
    },
    notify: {
      sass: {
        options: {
          message: "Sass files created.",
          title: "grunt"
        }
      },
      js: {
        options: {
          message: "Core JS concatenated and minified.",
          title: "grunt"
        }
      },
      map: {
        options: {
          message: "Sass files created with source maps.",
          title: "grunt"
        }
      },
      build: {
        options: {
          title: "grunt",
          message: "Build complete."
        }
      },
      prefixes: {
        options: {
          title: "grunt",
          message: "CSS prefixes added."
        }
      },
      watch: {
        options: {
          title: "grunt",
          message: "Watching. Grunt has its eye on you."
        }
      },
      expand: {
        options: {
          title: "grunt",
          message: "CSS Expanded. Don't check it in."
        }
      },
      uglify: {
        options: {
          title: "grunt",
          message: "JavaScript uglified."
        }
      }
    }
  });

  grunt.loadNpmTasks('grunt-autoprefixer');
  grunt.loadNpmTasks('grunt-contrib-copy');
  grunt.loadNpmTasks('grunt-contrib-csslint');
  grunt.loadNpmTasks('grunt-contrib-cssmin');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-notify');
  grunt.loadNpmTasks('grunt-sass');
  grunt.loadNpmTasks('grunt-imageoptim');

  // Tasks
  grunt.registerTask('default', ['notify:watch', 'watch']);
  grunt.registerTask('build', ['copy', 'sass:dev', 'autoprefixer', 'notify:prefixes', 'notify:sass', 'cssmin:compress', 'uglify:jsgrps', 'notify:uglify']);
  grunt.registerTask('compress', ['uglify:jsgrps', 'notify:uglify']);
};
