module.exports = function(grunt) {

	var deployDir = '/Users/Don/Sites/happycollision/wp-content/themes/tomossowski';
	
	grunt.initConfig({
		paths: {
			app: 'app',
			dist: 'tomossowski'
		}
	  compass: {
	  	options: {
	  		sassDir: '<%= paths.app %>/scss',
	  		cssDir: '<%= paths.dist %>',
	  		generatedImagesDir: 'images/generated',
	  		imagesDir: 'images',
	  		javascriptsDir: 'scripts',
	  		fontsDir: 'styles/fonts',
	  		importPath: '<%= paths.app %>/bower_components',
	  		httpImagesPath: '/images',
	  		httpGeneratedImagesPath: '/images/generated',
	  		httpFontsPath: '/styles/fonts',
	  		relativeAssets: false,
	  		assetCacheBuster: false,
	  		debugInfo: true
	  	},
	  	dist: {
	  		options: {
		  		specify: ['<%= paths.app %>/scss/**/*.scss','!<%= paths.app %>/scss/testing.sass'],
		  		debugInfo: false
	  		},
	  	},
	  	dev: {}
	  },

	  watch: {
	  	compass: {
				// scss/{,*/}*.{scss,sass} : one level down
				// scss/**/*.{scss,sass} : all levels
				files: ['<%= paths.app %>/scss/**/*.{scss,sass}'], 
				tasks: ['compass:dev', 'concat:testCss', 'copy:deployCss']
			},
			uglify: {
				files: ['<%= paths.app %>/lib/*.js'], 
				tasks: ['uglify:dev', 'concat:testJs', 'copy:deployJs']
			},
			php: {
				files: ['<%= paths.app %>/*.php', '<%= paths.app %>/extensions/*.php'],
				tasks: ['copy:php', 'copy:deployPhp']
			}
		},
		uglify: {
			dev: {
				options: {
					compress: false, //doesn't seem to work. Ugh.
					mangle: false,
					beautify: true
				},
				files: {}
			},
			dist: {
				options: {
					compress: {
						drop_console: true
					}
				},
				files: uglifyFiles
			}
		},
		concat:{
			testCss:{
				files:{
					'.tmp/style.css' : ['<%= paths.dist %>/style.css','<%= paths.dist %>/testing.css'],
					'<%= paths.dist %>/style.css' : ['.tmp/style.css']
				}
			},
			testJs:{
				files: {
					'.tmp/js/plugins.js' : ['js/plugins.js','<%= paths.app %>/js/tests.js'],
					'<%= paths.dist %>/js/plugins.js' : ['.tmp/js/plugins.js']
				}
			}
		},
		copy: {
			php: {
				files: [
					{expand: true, flatten: true, src: ['<%= paths.app %>/*.php'], dest: '<%= paths.dist %>/'},
					{expand: true, flatten: true, src: ['<%= paths.app %>/extensions/*.php'], dest: '<%= paths.dist %>/extensions/'}					
				]
			},
			remaining: {
				files: [{
					expand: true,
					cwd: '<%= paths.app %>/',
					src: ['*.ico','images/*'],
					dest: '<%= paths.dist %>/'					
				}]
			},
			deployAll:{
				files: [
					{expand: true, cwd: '<%= paths.dist %>/', src: ['**'], dest: deployDir }
				]
			},
			deployCss:{
				files: [
					{expand: true, cwd: '<%= paths.dist %>/', src: ['*.css','**/*.css'], dest: deployDir }
				]
			},
			deployJs:{
				files: [
					{expand: true, cwd: '<%= paths.dist %>/', src: ['*.js','**/*.js'], dest: deployDir }
				]
			},
			deployPhp:{
				files: [
					{expand: true, cwd: '<%= paths.dist %>/', src: ['*.php','**/*.php'], dest: deployDir }
				]
			}
		},
		clean: ['<%= paths.dist %>', '.tmp']
	});

	grunt.loadNpmTasks('grunt-contrib-compass');
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-contrib-concat');
	grunt.loadNpmTasks('grunt-contrib-copy');
	grunt.loadNpmTasks('grunt-contrib-clean');

	grunt.registerTask('default', ['build']);
	grunt.registerTask('dev', ['build', 'copy:deployAll', 'watch']);
	//grunt.registerTask('build', ['compass:dist', 'uglify:dist']);
	grunt.registerTask('build', [
		'clean',
		'compass:dev', 
		'uglify:dev', 
		'concat', 
		'copy:php', 
		'copy:remaining'
	]);
	
	grunt.registerTask('build:dist', [
		'clean',
		'compass:dist', 
		'uglify:dev', 
		'concat', 
		'copy:php', 
		'copy:remaining'
	]);


}