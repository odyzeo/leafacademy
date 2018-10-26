var concat = require('gulp-concat');
var dateFormat = require('dateformat');
var del = require('del');
var gulp = require('gulp');
var minify_css = require('gulp-minify-css');
var pump = require('pump');
var rename = require('gulp-rename');
var sass = require('gulp-sass');
var stripCssComments = require('gulp-strip-css-comments');
var uglify = require('gulp-uglify');
var urlAdjuster = require('gulp-css-url-adjuster');
var zip = require('gulp-zip');

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

	pump([gulp.src([
		'css/src/vendor/slick/fonts/**/*'
	]),
		gulp.dest('css/dist/slick/fonts')], pump([
		gulp.src([
			'css/src/vendor/slick/ajax-loader.gif'
		]),
		gulp.dest('css/dist/slick')
	], pump([
		gulp.src([
			'css/src/vendor/slick/slick*.css'
		]),
		stripCssComments(),
		minify_css(),
		rename({
			'suffix': '.min'
		}),
		gulp.dest('css/dist/slick')
	], pump([
		gulp.src(sassSources),
		sass({
			errLogToConsole: true
		}),
		concat('app.css'),
		urlAdjuster({
			prepend: '../'
		}),
		stripCssComments(),
		minify_css(),
		rename({
			'suffix': '.min'
		}),
		gulp.dest('css/dist')
	], pump([
		gulp.src([
			'css/src/*.css'
		]),
		urlAdjuster({
			prepend: '../'
		}),
		stripCssComments(),
		minify_css(),
		rename({
			'suffix': '.min'
		}),
		gulp.dest('css/dist')
	], pump([
		gulp.src([
			'blog/css/src/*.css',
			'!blog/css/src/admin*.css'
		]),
		concat('blog.css'),
		urlAdjuster({
			prepend: '../'
		}),
		stripCssComments(),
		minify_css(),
		rename({
			'suffix': '.min'
		}),
		gulp.dest('blog/css/dist')
	], pump([
		gulp.src([
			'blog/css/src/admin*.css'
		]),
		urlAdjuster({
			prepend: '../'
		}),
		stripCssComments(),
		minify_css(),
		rename({
			'suffix': '.min'
		}),
		gulp.dest('blog/css/dist')
	], cb)))))));

});

gulp.task('process-js', [], function(cb) {

	pump([
		gulp.src([
			'js/src/vendor/jquery.matchHeight-min.js',
			'js/src/vendor/imagesloaded.pkgd.min.js',
			'js/src/vendor/masonry.pkgd.min.js',
			'js/src/vendor/slick.min.js'
		]),
		concat('vendors.min.js'),
		gulp.dest('js/dist')
	], pump([
		gulp.src([
			'js/src/vendor/slider.js',
			'js/src/customizer.js',
			'js/src/featured-content-admin.js',
			'js/src/keyboard-image-navigation.js'
		]),
		uglify(),
		rename({
			'suffix': '.min'
		}),
		gulp.dest('js/dist')
	], pump([
		gulp.src([
			'js/src/vendor/html5.min.js'
		]),
		gulp.dest('js/dist')
	], pump([
		gulp.src([
			'js/src/functions.js'
		]),
		concat('app.js'),
		uglify(),
		rename({
			'suffix': '.min'
		}),
		gulp.dest('js/dist')
	], pump([
		gulp.src([
			'blog/js/src/exif.js',
			'blog/js/src/script-functions.js',
			'blog/js/src/script-ready.js',
		]),
		concat('blog.js'),
		uglify(),
		rename({
			'suffix': '.min'
		}),
		gulp.dest('blog/js/dist')
	], pump([
		gulp.src([
			'blog/js/src/admin*.js'
		]),
		uglify(),
		rename({
			'suffix': '.min'
		}),
		gulp.dest('blog/js/dist')
	], cb))))));

});

gulp.task('watch', ['process-css', 'process-js'], function(cb) {

	gulp.watch([
		'css/src/**/*.scss'
	], ['process-css']);

	gulp.watch([
		'js/src/functions.js'
	], ['process-js']);

});

gulp.task('build', ['process-css', 'process-js'], function(cb) {

	pump([
		gulp.src([
			'**/*',
			'!**/js/src',
			'!**/js/src/**',
			'!**/css/src',
			'!**/css/src/**',
			'!**/css/build',
			'!**/css/build/**',
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
			'!**/package-lock.json',
			'!**/composer.json',
			'!**/composer.lock',
			'!**/codesniffer.ruleset.xml',
			'!dist/**',
			'!dist'
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
		'vendor',
		'**/css/build',
		'**/css/dist',
		'**/js/dist',

	]);

});

gulp.task('default', ['build']);