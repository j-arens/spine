'use-strict';

// node
const path = require('path');

// utils
const argv = require('yargs');
const segregate = require('gulp-watch');
const ftp = require('vinyl-ftp');
const sequence = require('run-sequence');
const bsync = require('browser-sync');
const merge = require('merge-stream');
const named = require('vinyl-named');

// general processors
const gulp = require('gulp');
const sourcemaps = require('gulp-sourcemaps');

// image processors
const imagemin = require('gulp-imagemin');

// css processors
const postcss = require('gulp-postcss');
const next = require('postcss-cssnext');
const cssImport = require('postcss-import');
const cssFor = require('postcss-for');
const nano = require('gulp-cssnano');
const flexbug = require('postcss-flexbugs-fixes');

// js processors
const webpack = require('webpack-stream');

const env = {
  dev: argv.dev || true,
  prod: argv.production || false,
  staging: argv.staging || false,
  distPath: './dpi-spine',
  devPath: 'C:/Users/DPI/Desktop/dev/wordpress/wordpress/wp-content/themes',
  devUrl: 'localhost'
}

/**
* Styles
*/

const browserSupport = [
  "Android 2.3",
  "Android >= 4",
  "Chrome >= 20",
  "Firefox >= 24",
  "Explorer >= 8",
  "iOS >= 6",
  "Opera >= 12",
  "Safari >= 6"
];

const plugins = [
  cssImport(),
  cssFor(),
  next({
    browsers: browserSupport,
    features: {
      rem: false,
      customProperties: {
        strict: false
      }
    }
  }),
  flexbug()
];

gulp.task('styles', () => gulp.src('./src/styles/*.css')
    .pipe(sourcemaps.init())
    .pipe(postcss(plugins))
    .pipe(nano({
      discardComments: false,
      zindex: false,
      reduceIdents: false
    }))
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest(env.distPath))
);

/**
* Javascript
*/
gulp.task('js', () => gulp.src('./src/scripts/js/main.js')
    .pipe(webpack(require('./webpack.config.js')))
    .pipe(gulp.dest(env.distPath + '/scripts/js'))
);

gulp.task('single-js', () => gulp.src('./src/scripts/js/single/**/*.js')
    .pipe(named())
    .pipe(webpack(require('./webpack.single.js')))
    .pipe(gulp.dest(env.distPath + '/scripts/js'))
);

/**
* Assets
*/
gulp.task('assets', () => {
  const icons = gulp.src('./src/assets/icons/*.+(jpg|jpeg|gif|png|svg)')
                    .pipe(imagemin())
                    .pipe(gulp.dest(env.distPath + '/assets/icons'));

  const images = gulp.src('./src/assets/images/*.+(jpg|jpeg|gif|png|svg)')
                      .pipe(imagemin())
                      .pipe(gulp.dest(env.distPath + '/assets/images'));

  return merge(icons, images);
});

/**
* Migrate static files
*/
gulp.task('migrate', () => gulp.src('./src/**/*+(screenshot.png|.php|.es5.js)', {base: './src'})
    .pipe(gulp.dest(env.distPath))
);

/**
* Synchronous actions
*/
gulp.task('sequence', () => sequence(
  'styles',
  'js',
  'single-js',
  'images',
  'icons',
  'migrate',
  'screenshot',
  'ftp'
));

/**
* Browsersync init
*/
gulp.task('bsync', () => bsync.init({proxy: env.devUrl}));

/**
* Local deployment
*/
const distSrc = './dpi-spine/**/*';
const distBase = {base: './dpi-spine'};

gulp.task('localDeploy', () => gulp.src(distSrc, distBase)
    .pipe(segregate(distSrc, distBase))
    .pipe(gulp.dest(env.devPath + '/dpi-spine'))
    .pipe(bsync.stream())
);

/**
* Ftp deployment
*/
gulp.task('ftp', function() {

  const ftpConfig = {
    user: '',
    password: '',
    host: '',
    port: '',
    remoteFolder: './public_html/wp-content/themes',
    glob: ['./dpi-spine/**']
  }

  const connection = ftp.create({
      host: ftpConfig.host,
      port: ftpConfig.port,
      user: ftpConfig.user,
      password: ftpConfig.password,
      parallel: 10,
      log(err) {
        console.log(err);
      },
      debug(debug) {
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
  gulp.watch('./src/styles/**/*.css', ['styles']);

  // js scripts
  gulp.watch('./src/scripts/js/**/*.js', ['js', 'single-js']);

  //php
  gulp.watch('./src/**/*.php', ['migrate']);

  // images
  gulp.watch('./src/assets/images/*', ['assets']);

  // icons
  gulp.watch('./src/assets/icons/*', ['assets']);

  // dev dist
  if (env.dev) {
    gulp.watch('./dpi-spine/**/*', ['localDeploy']);
  }
});

/**
* Gulp commands
*/
gulp.task('build', ['styles', 'js', 'single-js', 'assets', 'migrate']);

gulp.task('build-watch', ['build', 'watch']);

gulp.task('dev', ['build', 'bsync', 'localDeploy', 'watch']);

gulp.task('deploy', ['sequence']);

gulp.task('default', ['dev']);
