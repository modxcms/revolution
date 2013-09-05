module.exports = function(grunt) {
	// Project configuration.
	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),
		dirs: { /* just defining some properties */
			lib: './lib',
			scss: './sass/',
			css: '../../../manager/templates/default/css/'
		},
		bower: {
			install: {
				options: {
					targetDir: './lib'
				}
			}
		},
		copy: {
			main: {
				files: [{
					expand: true,
					filter: 'isFile',
					cwd: '<%= dirs.css %>',
					src: 'raw.css',
					dest: './sass/'
				}]
			}
		},
		rename: { /* move files */
			bourbon: {
				src: './lib/bourbon/',
				dest: './sass/',
				force: true
			},
			raw: {
				src: './sass/raw.css',
				dest: './sass/_raw.scss'
			}
		},
		asciify: {
			dontEdit: {
				options: {
					font: 'larry3d'
				},
				text: 'do not edit'
			}
		},
		csso: {
			compress: {
				options: {
					report: 'gzip',
					banner: '/*!\n <%= asciify_dontEdit %> \n see _build/templates/default/README.md\n*/\n'
				},
				files: {
					'<%= dirs.css %>index.css': '<%= dirs.css %>index.css',
					'<%= dirs.css %>login.css': '<%= dirs.css %>login.css'
				}
			}
		},
		sass: {
			dist: {
				options: {
					style: 'compressed',
					compass: false
				},
				files: {
					'<%= dirs.css %>index.css': 'sass/index.scss',
					'<%= dirs.css %>login.css': 'sass/login.scss'
				}
			},
			dev: {
				options: {
					style: 'expanded',
					compass: false,
				},
				files: {
					'<%= dirs.css %>index.css': 'sass/index.scss',
					'<%= dirs.css %>login.css': 'sass/login.scss'
				}
			}
		},
		autoprefixer: { /* this expands the css so it needs to get compressed with csso afterwards */
			options: {
				// Task-specific options go here.
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
			}
		},
		watch: { /* trigger tasks on save */
			options: {
				livereload: true
			},
			scss: {
				files: './sass/*',
				tasks: ['sass:dist', 'autoprefixer', 'asciify', 'csso', 'growl:sass']
			}
		},
		clean: { /* take out the trash */
			prebuild: ['./sass/bourbon']
		},
		growl: {
			sass: {
				message: "Sass files created.",
				title: "grunt"
			},
			build: {
				title: "grunt",
				message: "Build complete."
			},
			prefixes: {
				title: "grunt",
				message: "CSS prefixes added."
			},
			watch: {
				title: "grunt",
				message: "Watching. Grunt has its eye on you."
			}
		}
	});

	grunt.loadNpmTasks('grunt-bower-task');
	grunt.loadNpmTasks('grunt-rename');
	grunt.loadNpmTasks('grunt-contrib-sass');
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-contrib-copy');
	grunt.loadNpmTasks('grunt-contrib-clean');
	grunt.loadNpmTasks('grunt-growl');
	grunt.loadNpmTasks('grunt-asciify');
	grunt.loadNpmTasks('grunt-autoprefixer');
	grunt.loadNpmTasks('grunt-csso');


	// Tasks
	grunt.registerTask('default', ['sass:dist', 'autoprefixer', 'growl:prefixes', 'growl:sass', 'asciify', 'csso', 'growl:watch', 'watch']);
	grunt.registerTask('build', ['clean:prebuild', 'bower', 'copy', 'rename', 'sass:dist', 'autoprefixer', 'growl:prefixes', 'growl:sass', 'asciify', 'csso']);
	grunt.registerTask('expand', ['sass:dev', 'autoprefixer', 'growl:prefixes', 'growl:sass']);
};
