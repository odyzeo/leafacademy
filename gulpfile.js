var concat = require('gulp-concat');
var copy = require('gulp-copy');
var dateFormat = require('dateformat');
var del = require('del');
var gulp = require('gulp');
var minify_css = require('gulp-minify-css');
var pump = require('pump');
var rename = require('gulp-rename');
var sass = require('gulp-sass');
var stripCssComments = require('gulp-strip-css-comments');
var uglify = require('gulp-uglify');
var zip = require('gulp-zip');

var jsSources = [
	'js/src/*.js',
	'!js/src/*.min.js'
];

var sassSources = [
	'css/src/*.scss'
];

gulp.task('copy-css-deps', function(cb) {

	pump([
		gulp.src([
			'node_modules/purecss/build/grids-responsive.css'
		]),
		rename({
			'extname': '.scss'
		}),
		gulp.dest('css/build')
	], cb);

});

gulp.task('process-css', ['copy-css-deps'], function(cb) {

	pump([
		gulp.src(sassSources),
		sass({
			errLogToConsole: true
		}),
		concat('app.css'),
		stripCssComments(),
		gulp.dest('css'),
		minify_css(),
		rename({
			'suffix': '.min'
		}),
		gulp.dest('css')
	], cb);

});

gulp.task('process-js', [], function(cb) {

	pump([
		gulp.src(jsSources),
		uglify(),
		rename({
			'suffix': '.min'
		}),
		gulp.dest('js')
	], cb);

});

gulp.task('watch', ['process-css', 'process-js'], function(cb) {

	gulp.watch([
		'css/src/**/*.scss'
	], ['process-css']);
	gulp.watch(jsSources, ['process-js']);

});

gulp.task('build', ['process-css', 'process-js'], function(cb) {

	pump([
		gulp.src([
			'**/*',
			'!**/js/src',
			'!**/js/src/**',
			'!**/css/src',
			'!**/css/src/**',
			'!**/node_modules/**',
			'!**/node_modules',
			'!**/bower_components/**',
			'!**/bower_components',
			'!**/components/**',
			'!**/components',
			'!**/scss/**',
			'!**/scss',
			'!**/packaged/**',
			'!**/packaged',
			'!**/bower.json',
			'!**/gulpfile.js',
			'!**/package.json',
			'!**/composer.json',
			'!**/composer.lock',
			'!**/codesniffer.ruleset.xml',
			'!**/dist/**',
			'!**/dist'
		]),
		gulp.dest('dist')
	], cb);

});

gulp.task('package', ['build'], function(cb) {

	var fs = require('fs');
	var time = dateFormat(new Date(), "yyyy-mm-dd_HH-MM");
	var pkg = JSON.parse(fs.readFileSync('./package.json'));
	var filename = pkg.name + '-' + pkg.version + '-' + time + '.zip';

	pump([
		gulp.src([
			'./dist/**/*'
		]),
		zip(filename),
		gulp.dest('packaged')
	], cb);

});

gulp.task('clean', function() {

	return del([
		'dist',
		'packaged',
		'vendor'
	]);

});

gulp.task('default', ['build']);