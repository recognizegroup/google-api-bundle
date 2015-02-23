module.exports = function(grunt) {

    grunt.initConfig({
        jasmine: {
            unit: {
                src: ['*.js', '!*Test.js', '!Gruntfile.js'],

                options: {
                    specs: 'Tests/Resources/*Test.js',
                    helpers: 'Tests/Resources/*Helper.js'
                }
            }
        }

    });

    grunt.loadNpmTasks('grunt-contrib-jasmine');
    grunt.registerTask('default', ['jasmine']);
};