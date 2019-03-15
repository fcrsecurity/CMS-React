"use strict";

let gulp = require('gulp'),
    debug = require('gulp-debug'),
    sass = require('gulp-sass'),
    concat = require('gulp-concat'),
    autoprefixer = require('gulp-autoprefixer'),
    plumber = require('gulp-plumber'),
    notify = require('gulp-notify'),
    minifyCSS = require('gulp-minify-css'),
    minify = require('gulp-minify'),
    gutil = require('gulp-util'),
    babel = require('gulp-babel'),
    exec = require('child_process').exec,
    rename = require('gulp-rename');

const config = {
    production: !!gutil.env.production,
    srcCSS : '**/*.scss',
    srcJS : '**/src/**/*.js',
    bundles :
        'src/CraftKeen/'
	};

gulp.task('sass', () => {
    gulp.src(`${config.bundles}${config.srcCSS}`)
        .pipe(plumber({
                errorHandler: notify.onError("Error: \n <%= error.message %>")
            }
        ))
        .pipe(sass())
        .pipe(autoprefixer({
            browsers: [
                "last 10 versions",
                "Android 2.3",
                "Android >= 4",
                "Chrome >= 20",
                "safari < 6",
                "firefox < 49",
                "opera < 12.1",
                "explorer > 11",
                "iOS >= 6"
            ]
        }))
        .pipe(config.production ? minifyCSS() : gutil.noop())
        .pipe(rename(function (file) {
            let path = file.dirname;
            let destCSS = path.substring(0, path.lastIndexOf('scss')) + '../dist/css';
            file.dirname = destCSS;
        }))
        .pipe(gulp.dest(config.bundles))
        // .pipe(debug({title: "output"}))
    ;
// }
});

gulp.task('scripts', () => {
        gulp.src(`${config.bundles}${config.srcJS}`)
            .pipe(plumber({
                errorHandler: notify.onError("Error: \n <%= error.message %>")
            }))
            .pipe(minify({				
                ext:{
                    src:'.js',
                    min:'.js'
                }
            }) )
            .pipe(rename(function (file) {
                let path = file.dirname;
                let destJS = path.substring(0, path.lastIndexOf('js')) + '../dist/js';
                file.dirname = destJS;
            }))
            .pipe(gulp.dest(config.bundles));
});
// Watch, apply only on Dev Environment
gulp.task('watch', () => {
    gulp.watch(`${config.bundles}${config.srcCSS}`, ['sass', 'scripts']);
});

// PLEASE ADD --production for deployment.
gulp.task('default', config.production ? ['sass','scripts'] : ['sass', 'scripts', 'watch']);