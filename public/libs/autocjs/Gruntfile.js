module.exports = function ( grunt ) {

    grunt.initConfig( {
        pkg: grunt.file.readJSON( 'package.json' ),
        banner: '/*!\n' +
        '* <%= pkg.name %> v<%= pkg.version %> - <%= pkg.description %>\n' +
        '* Copyright (c) <%= grunt.template.today(\'yyyy\') %> <%= pkg.author %>. All rights reserved.\n' +
        '* Licensed under <%= pkg.license %> License.\n' +
        '*/',
        connect: {
            docs: {
                options: {
                    protocol: 'http',
                    port: 8080,
                    hostname: 'localhost',
                    livereload: true,
                    base: {
                        path: 'docs/',
                        options: {
                            index: 'index.htm'
                        }
                    },
                    open: 'http://localhost:8080/index.htm'
                }
            }
        },
        sass: {
            docs: {
                options: {
                    style: 'expanded'
                },
                files: {
                    'dist/autoc.css': 'src/sass/autoc.scss',
                    'docs/css/layout.css': 'sass/layout.scss'
                }
            }
        },
        csslint: {
            docs: {
                options: {
                    'bulletproof-font-face': false,
                    'order-alphabetical': false,
                    'box-model': false,
                    'vendor-prefix': false,
                    'known-properties': false
                },
                src: [
                    'dist/autoc.css',
                    'docs/css/layout.css'
                ]
            }
        },
        cssmin: {
            dist: {
                files: {
                    'dist/autoc.min.css': [
                        'dist/autoc.css'
                    ]
                }
            },
            docs: {
                files: {
                    'docs/css/layout.min.css': [
                        'node_modules/normalize.css/normalize.css',
                        'docs/css/layout.css',
                        'dist/autoc.css'
                    ]
                }
            }
        },
        jshint: {
            src: {
                options: {
                    jshintrc: '.jshintrc'
                },
                src: [
                    'src/**/*.js'
                ],
                filter: 'isFile'
            }
        },
        uglify: {
            options: {
                banner: '<%= banner %>'
            },
            docs: {
                files: {
                    'docs/js/autoc.min.js': [
                        'src/autoc.js'
                    ]
                }
            },
            dist: {
                files: {
                    'dist/autoc.min.js': [
                        'src/autoc.js'
                    ]
                }
            }
        },
        copy: {
            docs: {
                files: [
                    {
                        'docs/js/jquery.js': 'node_modules/jquery/dist/jquery.js'
                    }
                ]
            },
            dist: {
                files: [
                    {
                        'dist/autoc.js': 'src/autoc.js'
                    }
                ]
            }
        },
        pug: {
            docs: {
                options: {
                    pretty: true,
                    data: {
                        debug: true
                    }
                },
                files: {
                    // create api home page
                    'docs/index.htm': 'pug/api/index.pug'
                }
            }
        },
        watch: {
            css: {
                files: [
                    'sass/**/**.scss'
                ],
                tasks: [
                    'sass',
                    'csslint',
                    'cssmin'
                ]
            },
            js: {
                files: [
                    'src/**/*.js'
                ],
                tasks: [
                    'jshint:src',
                    'uglify',
                    'copy:docs'
                ]
            },
            pug: {
                files: [
                    'pug/**/**.pug'
                ],
                tasks: [
                    'pug:docs'
                ]
            },
            docs: {
                files: [
                    'docs/**/**.html',
                    'docs/**/**.js',
                    'docs/**/**.css'
                ],
                options: {
                    livereload: true
                }
            }
        }
    } );

    grunt.loadNpmTasks( 'grunt-contrib-connect' );
    grunt.loadNpmTasks( 'grunt-contrib-copy' );
    grunt.loadNpmTasks( 'grunt-contrib-sass' );
    grunt.loadNpmTasks( 'grunt-contrib-csslint' );
    grunt.loadNpmTasks( 'grunt-contrib-cssmin' );
    grunt.loadNpmTasks( 'grunt-contrib-jshint' );
    grunt.loadNpmTasks( 'grunt-contrib-uglify' );
    grunt.loadNpmTasks( 'grunt-contrib-pug' );
    grunt.loadNpmTasks( 'grunt-contrib-watch' );

    grunt.registerTask( 'html', [ 'pug' ] );

    grunt.registerTask( 'http', [
        'connect:docs',
        'watch'
    ] );

    grunt.registerTask( 'hint', [ 'jshint:src' ] );

    grunt.registerTask( 'scripts', [
        'jshint',
        'uglify',
        'copy'
    ] );

    grunt.registerTask( 'styles', [
        'sass',
        'csslint',
        'cssmin'
    ] );

    grunt.registerTask( 'default', [
        'connect:docs',
        'sass',
        'csslint',
        'cssmin',
        'jshint:src',
        'uglify',
        'copy',
        'pug',
        'watch'
    ] );
};