'use-strict';

// general processors
const gulp = require('gulp');
const sourcemaps = require('gulp-sourcemaps');
const vinyl = require('vinyl-ftp');
const concat = require('gulp-concat');

// image processors
const imagemin = require('gulp-imagemin');

// css processors
const postcss = require('gulp-postcss');
const autoprefixer = require('gulp-autoprefixer');
const lost = require('lost');
const smartImport = require('postcss-smart-import');
const cssnext = require('postcss-cssnext');
const nano = require('gulp-cssnano');

// js processors
const webpack = require('webpack-stream');

/**
* Styles
*/
const plugins = [
  smartImport({
    from: './src/styles/main.css'
  }),
  lost(),
  cssnext(),
];

gulp.task('styles', () => gulp.src('./src/styles/**/*.css')
    .pipe(sourcemaps.init())
    .pipe(postcss(plugins))
    .pipe(concat('style.css'))
    .pipe(autoprefixer({browsers: ['last 2 versions']}))
    .pipe(nano({discardComments: false}))
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest('./spine'))
);

/**
* Javascript
*/
gulp.task('js', () => gulp.src('./src/scripts/js/main.js')
    .pipe(webpack(require('./webpack.config.js')))
    .pipe(gulp.dest('./spine/scripts/js'))
)

/**
* Images
*/
gulp.task('images', () => gulp.src('./src/assets/images/*')
    .pipe(imagemin())
    .pipe(gulp.dest('./spine/assets/images'))
);

/**
* Icons
*/
gulp.task('icons', () => gulp.src('./src/assets/icons/*')
    .pipe(imagemin())
    .pipe(gulp.dest('./spine/assets/icons'))
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
    .pipe(gulp.dest('./spine'))
);

/**
* Ftp deployment
*/
const ftpConfig = {
  user: '',
  password: '',
  host: '',
  remoteFolder: '/public_html/wp-content/themes',
  glob: ['./spine/*']
}

function connection() {
  return vinyl.create({
    host: ftpConfig.host,
    port: ftpConfig.port,
    user: ftpConfig.user,
    password: ftpConfig.password,
    parallel: 5
  });
}

gulp.task('deploy', () => gulp.src(ftpConfig.glob, {base: '.', buffer: false})
    .pipe(connection.newer(ftpConfig.remoteFolder))
    .pipe(connection.dest(ftpConfig.remoteFolder))
);

/**
* Watch tasks
*/
gulp.task('watch', function() {
  // styles
  gulp.watch('./src/styles/**/*.css', ['styles']);
  // js
  gulp.watch('./src/scripts/js/**/*.js', ['js']);
  // images
  gulp.watch('./src/assets/images/*', ['images']);
  // icons
  gulp.watch('./src/assets/icons/*', ['icons'])
  // migrate
  gulp.watch('./src/**/*.php', ['migrate'])
});

gulp.task('deploy-watch', function() {
  // styles
  gulp.watch('./src/styles/**/*.css', ['styles', 'deploy']);
  // js
  gulp.watch('./src/scripts/js/**/*.js', ['js', 'deploy']);
  // images
  gulp.watch('./src/assets/images/*', ['images', 'deploy']);
  // icons
  gulp.watch('./src/assets/icons/*', ['icons', 'deploy'])
  // migrate
  gulp.watch('./src/**/*.php', ['migrate', 'deploy'])
});

/**
* Gulp commands
*/
gulp.task('build', ['styles', 'js', 'images', 'icons', 'migrate']);

gulp.task('build-watch', ['styles', 'js', 'images', 'icons', 'migrate', 'watch']);

gulp.task('deploy', ['styles', 'js', 'images', 'icons', 'migrate', 'deploy']);

gulp.task('deploy-watch', ['styles', 'js', 'images', 'icons', 'migrate', 'deploy', 'deploy-watch']);

gulp.task('default', ['deploy-watch']);
