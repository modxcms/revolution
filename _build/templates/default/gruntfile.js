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
		lib: './lib',
		scss: './sass/'//,
		//js: 'js/'
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
			files: [
				{expand:true,filter: 'isFile',cwd:'../../../manager/templates/default/css/',src:'raw.css', dest: './sass/'}
			]
		}
	},
	rename: { /* move files */
		bourbon: {
			src: './lib/bourbon/',
			dest: './sass/',
			force:true
		},
		raw: {
			src: './sass/raw.css',
			dest: './sass/_raw.scss'
		}
	},
	asciify:{
		dontEdit: {
			options:{
				font:'larry3d'
			},
			text: 'do not edit'
		}
	},
	  usebanner: {
	    dist: {
	      options: {
	        position: 'top',
	        banner: '/*!\n <%= asciify_dontEdit %> \n see _build/templates/default/README.md\n*/\n'
	      },
	      files: {
	        src: ['../../../manager/templates/default/css/index.css','../../../manager/templates/default/css/login.css' ]
	      }
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
  sass: {                             
    dist: {                           
      options: {                      
        style: 'compressed',
        compass: true
      },
      files: {                        
'../../../manager/templates/default/css/index.css' : 'sass/index.scss',
'../../../manager/templates/default/css/login.css' : 'sass/login.scss'
      }
    },
    dev: {                           
      options: {                     
        style: 'compressed',
        compass:true,
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
			files: './sass/*',
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
	clean: { /* take out the trash */
		prebuild: ['./sass/bourbon'] 
	},
	growl:{
		sass : {
			message : "Sass files created",
			title : "grunt"
		},
		build : {
			title : "grunt",
			message : "Build complete"
		},
		watch : {
			title : "grunt",
			message : "Watching. Grunt has its eye on you."
		}
	}
  });

  grunt.loadNpmTasks( 'grunt-bower-task' );
  //grunt.renameTask( 'bower', 'bowerInstall' );
  //grunt.loadNpmTasks( 'grunt-bower' );
  grunt.loadNpmTasks( 'grunt-rename' );
  grunt.loadNpmTasks('grunt-contrib-sass');
  grunt.loadNpmTasks( 'grunt-contrib-watch' );
  grunt.loadNpmTasks('grunt-contrib-copy');
  //grunt.loadNpmTasks( 'grunt-contrib-concat' );
  //grunt.loadNpmTasks( 'grunt-contrib-uglify' );
  grunt.loadNpmTasks('grunt-contrib-clean');
  grunt.loadNpmTasks('grunt-growl');
  grunt.loadNpmTasks('grunt-asciify');
  grunt.loadNpmTasks('grunt-banner');
  //grunt.loadNpmTasks( 'grunt-notify' );

  // Tasks

  grunt.registerTask('default', ['sass:dev','growl:sass','asciify','usebanner','growl:watch','watch']);
  grunt.registerTask('build', ['clean:prebuild','bower','copy','rename','sass','growl:sass','asciify','usebanner']);
  grunt.registerTask('prod',['sass:dist','growl:sass']);
};
