var gulp = require('gulp');
var elixir = require('laravel-elixir');
var uglify = require('gulp-uglify');
var uglifycss = require('gulp-uglifycss');

elixir.extend('compress', function() {
  gulp.task('compress', function() {
  	//Front End
    gulp.src('public/js/*.js').pipe(uglify()).pipe(gulp.dest('public/min_js'));
    gulp.src('public/js/i18n/*.js').pipe(uglify()).pipe(gulp.dest('public/min_js/i18n'));
    gulp.src('public/css/*.css').pipe(uglifycss()).pipe(gulp.dest('public/min_css'));
    gulp.src('public/css/slider/*.css').pipe(uglifycss()).pipe(gulp.dest('public/min_css/slider'));
    //Back End
    gulp.src('public/admin_assets/dist/js/*.js').pipe(uglify()).pipe(gulp.dest('public/admin_assets/dist/min_js'));
    gulp.src('public/admin_assets/dist/css/*.css').pipe(uglifycss()).pipe(gulp.dest('public/admin_assets/dist/min_css'));
    gulp.src('public/admin_assets/dist/css/skins/*.css').pipe(uglifycss()).pipe(gulp.dest('public/admin_assets/dist/min_css/skins'));
  });
  return this.queueTask('compress');
});

elixir(function(mix) {
    mix.compress();
});

var prettify = require('gulp-jsbeautifier');

gulp.task('prettify_css', function() {
  gulp.src(['public/css/*.css'])
    .pipe(prettify())
    .pipe(gulp.dest('public/css'));
});

gulp.task('prettify_js', function() {
  gulp.src(['public/js/*.js'])
    .pipe(prettify())
    .pipe(gulp.dest('public/js'));
});

/*var phpcs = require('gulp-phpcs');

gulp.task('default', function () {
    return gulp.src(['app/Http/Controllers/HomeController.php'])
        // Validate files using PHP Code Sniffer
        .pipe(phpcs({
            bin: 'vendor/bin/phpcs',
            standard: 'PSR2',
            warningSeverity: 0
        }))
        // Log all problems that was found
        .pipe(phpcs.reporter('log'));
});*/

/*var phpcbf = require('gulp-phpcbf');
var gutil = require('gutil');

gulp.task('phpcbf', function () {
  return gulp.src(['app/Http/Controllers/HomeController.php'])
  .pipe(phpcbf({
    bin: 'vendor/bin/phpcbf',
    standard: 'PSR2',
    warningSeverity: 0
  }))
  .on('error', gutil.log)
  .pipe(gulp.dest('src'));
});*/