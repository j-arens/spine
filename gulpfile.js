'use-strict';

// node
const path = require('path');

// utils
const argv = require('yargs');
const bsync = require('browser-sync');
const merge = require('merge-stream');
const named = require('vinyl-named');
const newer = require('gulp-newer');

// general processors
const gulp = require('gulp');
const sourcemaps = require('gulp-sourcemaps');

// css processors
const sass = require('gulp-sass');
const autoprefixer = require('gulp-autoprefixer');
const nano = require('gulp-cssnano');

// js processors
const webpack = require('webpack-stream');

const env = {
    dev: argv.dev || true,
    prod: argv.production || false,
    staging: argv.staging || false,
    devPath: `C:/Users/DPI/Desktop/dev-sites/as-dunwoody/wp-content/themes/dpi-spine`,
    devUrl: 'localhost',
    migrateList: ['./**/*', '!./.git', '!node_modules', '!./node_modules/**/*'],
    watchList: ['./**/*', '!.git', '!node_modules', '!./node_modules/**/*', '!./styles/**/*', '!./scripts/js/source/**/*']
}

/**
* Styles
*/
gulp.task('styles', () => gulp.src(['./styles/style.scss', './styles/login.scss'])
    .pipe(sourcemaps.init())
    .pipe(sass().on('error', sass.logError))
    .pipe(autoprefixer())
    .pipe(nano({discardComments: false, zindex: false, reduceIdents: false}))
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest('./'))
);

/**
* Javascript
*/
gulp.task('js', () => gulp.src('./scripts/js/source/main.js')
    .pipe(webpack(require('./webpack.bundle.js')))
    .pipe(gulp.dest(path.resolve('/scripts/js')))
);

gulp.task('single-js', () => gulp.src('./scripts/js/source/single/**/*.js')
    .pipe(named())
    .pipe(webpack(require('./webpack.single.js')))
    .pipe(gulp.dest(path.resolve('./scripts/js')))
);

/**
* Browsersync init
*/
gulp.task('bsync', () => bsync.init({proxy: env.devUrl}));
gulp.task('bsync-reload', () => bsync.reload());

/**
* Local deployment
*/
gulp.task('localDeploy', () => gulp.src(env.migrateList, {base: './'})
    .pipe(newer(path.resolve(env.devPath)))
    .pipe(gulp.dest(path.resolve(env.devPath)))
    .pipe(bsync.stream())
);

/**
* Watch tasks
*/
gulp.task('watch', function() {

    // styles
    gulp.watch('./styles/**/*.scss', ['styles']);

  // js scripts
    gulp.watch('./scripts/js/source/!single/**/*.js', ['js']);

    gulp.watch('./scripts/js/source/single/**/*.js', ['single-js']);

    // dev dist
    if (env.dev) {
        gulp.watch(env.watchList, ['localDeploy']);
    }

});

/**
* Gulp commands
*/
gulp.task('build', ['styles', 'js', 'single-js']);

gulp.task('build-watch', ['build', 'watch']);

gulp.task('dev', ['build', 'bsync', 'localDeploy', 'watch']);

gulp.task('default', ['dev']);
