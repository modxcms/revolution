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
        '<%= dirs.manager %>assets/modext/widgets/resource/modx.window.resource.js',
        '<%= dirs.manager %>assets/modext/widgets/element/modx.tree.element.js',
        '<%= dirs.manager %>assets/modext/widgets/system/modx.tree.directory.js',
        '<%= dirs.manager %>assets/modext/widgets/system/modx.panel.filetree.js',
        '<%= dirs.manager %>assets/modext/widgets/media/modx.browser.js',
        '<%= dirs.manager %>assets/modext/core/modx.layout.js'
    ],
    sassCompileFileMap = {
        '<%= dirs.css %>index.css': 'sass/index.scss',
        '<%= dirs.css %>login.css': 'sass/login.scss',
        '<%= dirs.setup %>installer.css': 'sass/installer.scss'
    },
    bannerText = '/*' +
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
    '\n*/' +
    '\n',
    cssFileMain = '<%= dirs.css %>index.css',
    cssFileSetup = '<%= dirs.setup %>installer.css',
    cssFileLogin = '<%= dirs.css %>login.css',
    cssMinFileMain = '<%= dirs.css %>index-min.css',
    cssMinFileSetup = '<%= dirs.setup %>installer-min.css',
    cssMinFileLogin = '<%= dirs.css %>login-min.css'
    ;

module.exports = function(grunt) {
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
        copy: {
            /* move files */
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
        concat: {
            options: {
                stripBanners: true,
                banner: bannerText
            },
            mainCss: {
                src: [cssFileMain],
                dest: cssFileMain
            },
            setupCss: {
                src: [cssFileSetup],
                dest: cssFileSetup
            },
            loginCss: {
                src: [cssFileLogin],
                dest: cssFileLogin
            },
            mainCssDist: {
                src: [cssMinFileMain],
                dest: cssMinFileMain
            },
            setupCssDist: {
                src: [cssMinFileSetup],
                dest: cssMinFileSetup
            },
            loginCssDist: {
                src: [cssMinFileLogin],
                dest: cssMinFileLogin
            }
        },
        'dart-sass': {
            dist: {
                options: {
                    indentWidth: 4,
                    sourceMap: false,
                    update: true
                },
                files: sassCompileFileMap
            }
        },
        postcss: {
            options: {
                map: {
                    inline: false
                },
                processors: [
                    require('autoprefixer')(),
                    require('cssnano')({
                        preset: 'default'
                    })
                ]
            },
            index: {
                src: cssFileMain,
                dest: cssMinFileMain
            },
            login: {
                src: cssFileLogin,
                dest: cssMinFileLogin
            },
            setup: {
                src: cssFileSetup,
                dest: cssMinFileSetup
            }
        },
        watch: {
            /* trigger tasks on save */
            scss: {
                files: ['<%= dirs.scss %>/**/*'],
                tasks: ['dart-sass:dist', 'notify:sass']
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
        terser: {
            jsgrps: {
                options: {
                    ecma: 2015,
                    mangle: false,
                    sourceMap: true,
                    format: {
                        preamble: bannerText
                    }
                },
                files: {
                    '<%= dirs.manager %>assets/modext/modx.jsgrps-min.js': coreJSFiles
                }
            }
        },
        notify: {
            sass: {
                options: {
                    message: "Sass files compiled.",
                    title: "grunt"
                }
            },
            js: {
                options: {
                    message: "Core JS concatenated and minified.",
                    title: "grunt"
                }
            },
            build: {
                options: {
                    title: "grunt",
                    message: "Build complete."
                }
            },
            postcss: {
                options: {
                    title: "grunt",
                    message: "PostCSS ran autoprefixer and minified files."
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
            terser: {
                options: {
                    title: "grunt",
                    message: "JavaScript groups compiled."
                }
            },
            concat: {
                options: {
                    title: "grunt",
                    message: "Added banner to compiled files."
                }
            }
        }
    });

    grunt.loadNpmTasks('@lodder/grunt-postcss');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-dart-sass');
    grunt.loadNpmTasks('grunt-notify');
    grunt.loadNpmTasks('grunt-terser');

    // Tasks
    grunt.registerTask('default', ['notify:watch', 'watch']);
    grunt.registerTask('build', ['copy', 'dart-sass:dist', 'notify:sass', 'postcss', 'notify:postcss', 'concat', 'notify:concat', 'terser:jsgrps', 'notify:terser']);
    grunt.registerTask('compress', ['terser:jsgrps', 'notify:terser']);
    grunt.registerTask('style', ['copy', 'dart-sass:dist', 'notify:sass', 'postcss', 'notify:postcss', 'concat', 'notify:concat']);
};
