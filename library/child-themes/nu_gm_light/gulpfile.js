'use strict';

var input         = './sass/**/*.scss';
var mapSourceRoot = '../sass';
var output        = './css';

var gulp          = require('gulp'),
    sass          = require('gulp-sass'),
    cleanCSS      = require('gulp-clean-css'),
    sourcemaps    = require('gulp-sourcemaps'),
    autoprefixer  = require('gulp-autoprefixer'),
    livereload    = require('gulp-livereload');

var sassOptions  = {
  errLogToConsole: true,
  outputStyle: 'expanded'
};

var autoprefixerOptions = {
  browsers: ['last 2 versions', '> 5%', 'Firefox ESR']
};

gulp.task('sass', function () {
  return gulp.src(input)
    .pipe(sourcemaps.init())
    .pipe(sass(sassOptions).on('error', sass.logError))
    .pipe(autoprefixer(autoprefixerOptions))
    .pipe(cleanCSS())
    .pipe(sourcemaps.write('.', {sourceRoot: mapSourceRoot}))
    .pipe(gulp.dest(output));
});
 
gulp.task('sass:watch', function () {
  livereload.listen();
  gulp.watch(input, ['sass']);
  gulp.watch('**/*.css', function (files) {
    livereload.changed(files)
  });
});

gulp.task('default', ['sass:watch']);