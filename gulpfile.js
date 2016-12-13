'use-strict';

// general processors
const gulp = require('gulp');
const sourcemaps = require('gulp-sourcemaps');
const ftp = require('vinyl-ftp');
const concat = require('gulp-concat');
const sequence = require('run-sequence');

// image processors
const imagemin = require('gulp-imagemin');

// css processors
const postcss = require('gulp-postcss');
const autoprefixer = require('gulp-autoprefixer');
const lost = require('lost');
// const smartImport = require('postcss-smart-import');
// const cssnext = require('postcss-cssnext');
const sass = require('gulp-sass');
const nano = require('gulp-cssnano');

// js processors
const webpack = require('webpack-stream');

/**
* Styles
*/
// const plugins = [
//   cssnext(),
//   lost(),
//   smartImport({
//     from: './src/styles/main.css'
//   })
// ];

const plugins = [
  lost()
];

gulp.task('styles', () => gulp.src('./src/styles/**/*.scss')
    .pipe(sourcemaps.init())
    .pipe(sass())
    .pipe(postcss(plugins))
    // .pipe(concat('style.css'))
    .pipe(autoprefixer({browsers: ['last 2 versions']}))
    .pipe(nano({discardComments: false}))
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest('./dpi-spine'))
);

/**
* Javascript
*/
gulp.task('js', () => gulp.src('./src/scripts/js/main.js')
    .pipe(webpack(require('./webpack.config.js')))
    .pipe(gulp.dest('./dpi-spine/scripts/js'))
)

/**
* Images
*/
gulp.task('images', () => gulp.src('./src/assets/images/*')
    .pipe(imagemin())
    .pipe(gulp.dest('./dpi-spine/assets/images'))
);

/**
* Icons
*/
gulp.task('icons', () => gulp.src('./src/assets/icons/*')
    .pipe(imagemin())
    .pipe(gulp.dest('./dpi-spine/assets/icons'))
);

/**
* Migrate php
*/
const targets = [
  './src/functions.php',
  './src/index.php',
  './src/scripts/php/**/*',
  './src/templates/**/*',
  './src/lib/**/*'
];

gulp.task('migrate', () => gulp.src(targets, {base: './src'})
    .pipe(gulp.dest('./dpi-spine'))
);

/**
* Theme screenshot
*/
gulp.task('screenshot', () => gulp.src('./src/screenshot.png')
    .pipe(gulp.dest('./dpi-spine'))
);

/**
* Ftp deployment
*/
gulp.task('ftp', function() {

  const ftpConfig = {
    user: 'ccfoundationhz',
    password: '#dpi105074@HO',
    host: 'ftp.ccfoundationhz.diocesanweb.com',
    port: '21',
    remoteFolder: './public_html/wp-content/themes',
    glob: ['./dpi-spine/*']
  }

  const connection = ftp.create({
      host: ftpConfig.host,
      port: ftpConfig.port,
      user: ftpConfig.user,
      password: ftpConfig.password,
      parallel: 10,
      log: function(err) {
        console.log(err);
      },
      debug: function(debug) {
        console.log(debug);
      }
    });

  return gulp.src(ftpConfig.glob, {base: '.', buffer: false})
             .pipe(connection.newer(ftpConfig.remoteFolder))
             .pipe(connection.dest(ftpConfig.remoteFolder))
});

/**
* Watch tasks
*/
gulp.task('watch', function() {
  // styles
  gulp.watch('./src/styles/**/*.scss', ['styles']);
  // js
  gulp.watch('./src/scripts/js/**/*.js', ['js']);
  // images
  gulp.watch('./src/assets/images/*', ['images']);
  // icons
  gulp.watch('./src/assets/icons/*', ['icons'])
  // migrate
  gulp.watch('./src/**/*.php', ['migrate'])
});

gulp.task('deploy-watcher', function() {
  // styles
  gulp.watch('./src/styles/**/*.scss', ['styles', 'ftp']);
  // js
  gulp.watch('./src/scripts/js/**/*.js', ['js', 'ftp']);
  // images
  gulp.watch('./src/assets/images/*', ['images', 'ftp']);
  // icons
  gulp.watch('./src/assets/icons/*', ['icons', 'ftp'])
  // migrate
  gulp.watch('./src/**/*.php', ['migrate', 'ftp'])
});

/**
* Gulp commands
*/
gulp.task('build', ['styles', 'js', 'images', 'icons', 'migrate', 'screenshot']);

gulp.task('build-watch', ['build', 'watch']);

gulp.task('deploy', function() {sequence('build', 'ftp')});

gulp.task('deploy-watch', function() {sequence('build', 'ftp', 'deploy-watcher')});

gulp.task('default', ['deploy-watch']);
