'use-strict';

// node
const path = require('path');

// utils
const argv = require('yargs');
const segregate = require('gulp-watch');
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
const color = require('postcss-color-function');

// js processors
const webpack = require('webpack-stream');

const env = {
  dev: argv.dev || true,
  prod: argv.production || false,
  staging: argv.staging || false,
  distPath: './distribution/dpi-spine',
  devPath: 'C:/Users/DPI/Desktop/dev/wordpress/ctr-dawsonville/wp-content/themes',
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
  color(),
  flexbug()
];

gulp.task('styles', () => gulp.src('./source/styles/*.css')
    .pipe(sourcemaps.init())
    .pipe(postcss(plugins))
    .pipe(nano({
      discardComments: false,
      zindex: false,
      reduceIdents: false
    }))
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest(path.resolve(env.distPath)))
);

/**
* Javascript
*/
gulp.task('js', () => gulp.src('./source/scripts/js/main.js')
    .pipe(webpack(require('./webpack.bundle.js')))
    .pipe(gulp.dest(path.resolve(env.distPath + '/scripts/js')))
);

gulp.task('single-js', () => gulp.src('./source/scripts/js/single/**/*.js')
    .pipe(named())
    .pipe(webpack(require('./webpack.single.js')))
    .pipe(gulp.dest(path.resolve(env.distPath + '/scripts/js')))
);

/**
* Assets
*/
gulp.task('assets', () => {
  const icons = gulp.src('./source/assets/icons/*.+(jpg|jpeg|gif|png|svg)')
                    .pipe(imagemin())
                    .pipe(gulp.dest(path.resolve(env.distPath + '/assets/icons')));

  const images = gulp.src('./source/assets/images/*.+(jpg|jpeg|gif|png|svg)')
                      .pipe(imagemin())
                      .pipe(gulp.dest(path.resolve(env.distPath + '/assets/images')));

  return merge(icons, images);
});

/**
* Migrate static files
*/
gulp.task('migrate', () => gulp.src('./source/**/*+(screenshot.png|.json|.lock|.txt|.php|.es5.js)', {base: './source'})
    .pipe(gulp.dest(path.resolve(env.distPath)))
);

/**
* Browsersync init
*/
gulp.task('bsync', () => bsync.init({proxy: env.devUrl}));

/**
* Local deployment
*/
const distSrc = './distribution/dpi-spine/**/*';
const distBase = {base: './distribution/dpi-spine'};

gulp.task('localDeploy', () => gulp.src(distSrc, distBase)
    .pipe(segregate(distSrc, distBase))
    .pipe(gulp.dest(path.resolve(env.devPath + '/dpi-spine')))
    .pipe(bsync.stream())
);

/**
* Watch tasks
*/
gulp.task('watch', function() {
  // styles
  gulp.watch('./source/styles/**/*.css', ['styles']);

  // js scripts
  gulp.watch('./source/scripts/js/**/*.js', ['js', 'single-js']);

  //php
  gulp.watch('./source/**/*.php', ['migrate']);

  // images
  gulp.watch('./source/assets/images/*', ['assets']);

  // icons
  gulp.watch('./source/assets/icons/*', ['assets']);

  // dev dist
  if (env.dev) {
    gulp.watch('./distribution/dpi-spine/**/*', ['localDeploy']);
  }
});

/**
* Gulp commands
*/
gulp.task('build', ['styles', 'js', 'single-js', 'assets', 'migrate']);

gulp.task('build-watch', ['build', 'watch']);

gulp.task('dev', ['build', 'bsync', 'localDeploy', 'watch']);

gulp.task('default', ['dev']);
