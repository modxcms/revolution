module.exports = function(grunt) {
  // Project configuration.
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
	/*notify:{
		watch: {
			options: {
				title: 'Done Grunting!',
				message: 'Sass complete'
			}
		}
	},*/
	dirs: { /* just defining some properties */
		dest: 'lib',
		scss: 'sass/'//,
		//js: 'js/'
	},
	/*bower: { /* package management 
		dev: {
			dest: 'lib/', 
			options: {
				stripJSAffix: false
			}
		}
	},
	bowerInstall: {
		install: {
			options: {
				verbose: true,
				layout: 'byType',
				cleanTargetDir: true,
				'targetDir':'sources/'
			}
		}
	},*/
	/*rename: { /* move files 
		jquery: {
			src: '_build/lib/jquery.js',
			dest: 'assets/js/lib/jquery.js'
		},
		modernizr: {
			src: '_build/lib/modernizr.js',
			dest: 'assets/js/lib/modernizr.dev.js'
		},
		requirejs: {
			src: '_build/lib/requirejs.js',
			dest: 'assets/js/lib/requirejs.js'
		}
	},
	/*concat: { /* concatenate javascript 
		script: {
			files: {
				'assets/js/main.dev.js' : ['assets/js/plugins.js','assets/js/lib/requirejs.js','assets/js/main.js'] 
			}
		}
	},*/
    /*uglify: { /* minify javascript 
      options: {
        banner: ''
      },
      build: {
        //src: 'src/<%= pkg.name %>.js',
        //dest: 'build/<%= pkg.name %>.min.js'
		src: 'assets/js/main.dev.js',
		dest: 'assets/js/main-min.js'
      }
    },*/
	sass: { /* compile Sass */
		options: {
			trace: true,
			compass: true//,
			/*loadPath: [
				'lib/matter'
			]*/
		},
		dist: {
			options: {
				style: 'compressed'
			},
			files: {
				'../../../manager/templates/default/css/index.css' : 'sass/index.scss',
				'../../../manager/templates/default/css/login.css' : 'sass/login.scss'
			}
		},
		dev: {
			options: {
				style: 'expanded'
			},
			files: {
				'../../../manager/templates/default/css/index.css' : 'sass/index.scss',
				'../../../manager/templates/default/css/login.css' : 'sass/login.scss'
			}
		} 
	},
	watch: { /* trigger tasks on save */
		options: {
			livereload: true
		},
		scss: {
			files: 'sass/*',
			tasks: ['sass:dev']
		},
		/*script: {
			files: [ 'assets/js/main.js', 'assets/js/plugins.js' ],
			tasks: [ 'concat:script','uglify' ]
		},*/
		/*uglify: {
			files: 'assets/js/main.js',
			tasks: [ 'uglify' ]
		}*/
	},
	/*clean: {  take out the trash 
		buildLib: {
			dirs:['_build/lib']
		}
	}*/
  });

  grunt.loadNpmTasks( 'grunt-bower-task' );
  //grunt.renameTask( 'bower', 'bowerInstall' );
  //grunt.loadNpmTasks( 'grunt-bower' );
  //grunt.loadNpmTasks( 'grunt-rename' );
  grunt.loadNpmTasks( 'grunt-contrib-sass' );
  grunt.loadNpmTasks( 'grunt-contrib-watch' );
  //grunt.loadNpmTasks( 'grunt-contrib-concat' );
  //grunt.loadNpmTasks( 'grunt-contrib-uglify' );
  //grunt.loadNpmTasks('grunt-cleanx');
  //grunt.loadNpmTasks( 'grunt-notify' );

  // Tasks

  grunt.registerTask('default', ['sass:dev','watch']);
  grunt.registerTask('build', ['sass:dev']);
  grunt.registerTask('prod',['sass:dist']);
};
