const gulp = require('gulp');
const sass = require('gulp-sass')(require('sass'));
const autoprefixer = require('gulp-autoprefixer');
const cleanCSS = require('gulp-clean-css');
const rename = require('gulp-rename');
const webpackStream = require('webpack-stream');
const webpack = require('webpack');
const uglify = require('gulp-uglify');

// Task to compile SCSS to CSS
function compileSCSS() {
  return gulp.src('./assets/css/*.scss')
    .pipe(sass().on('error', sass.logError))
    .pipe(autoprefixer({
      overrideBrowserslist: ['last 2 versions'],
      cascade: false
    }))
    .pipe(cleanCSS())
    .pipe(rename({ suffix: '.min' }))
    .pipe(gulp.dest('./assets/css'));
}

// Task to bundle and minify admin JavaScript using Webpack
function bundleAdminJS() {
  return gulp.src('./assets/js/custom-tab-plugin-admin.js')
    .pipe(webpackStream({
      output: {
        filename: 'custom-tab-plugin-admin.bundle.js',
      },
      module: {
        rules: [
          {
            test: /\.(js)$/,
            exclude: /(node_modules)/,
            loader: 'babel-loader',
            options: {
              presets: ['@babel/preset-env']
            }
          }
        ]
      },
      externals: {
        jquery: 'jQuery'
      },
      mode: 'production', // Set mode to 'production' for minification
      devtool: false // Disable source maps for production build
    }, webpack))
    .pipe(gulp.dest('./assets/js')) // Output bundled JS
    .pipe(uglify()) // Minify JS
    .pipe(rename({ suffix: '.min' }))
    .pipe(gulp.dest('./assets/js')); // Output minified JS
}

// Task to bundle and minify front-end JavaScript using Webpack
function bundleFrontJS() {
  return gulp.src('./assets/js/front.js')
    .pipe(webpackStream({
      output: {
        filename: 'front.bundle.js',
      },
      module: {
        rules: [
          {
            test: /\.(js)$/,
            exclude: /(node_modules)/,
            loader: 'babel-loader',
            options: {
              presets: ['@babel/preset-env']
            }
          }
        ]
      },
      externals: {
        jquery: 'jQuery'
      },
      mode: 'production', // Set mode to 'production' for minification
      devtool: false // Disable source maps for production build
    }, webpack))
    .pipe(gulp.dest('./assets/js')) // Output bundled JS
    .pipe(uglify()) // Minify JS
    .pipe(rename({ suffix: '.min' }))
    .pipe(gulp.dest('./assets/js')); // Output minified JS
}

// Watch task to automatically run SCSS and JS tasks on file changes
function watchFiles() {
  gulp.watch('./assets/css/*.scss', compileSCSS);
  gulp.watch('./assets/js/custom-tab-plugin-admin.js', bundleAdminJS);
  gulp.watch('./assets/js/front.js', bundleFrontJS);
}

// Default task: build SCSS and JS
gulp.task('default', gulp.parallel(compileSCSS, bundleAdminJS, bundleFrontJS));

// Watch task: watch SCSS and JS files for changes
gulp.task('watch', gulp.series('default', watchFiles));

// Export tasks if needed
exports.compileSCSS = compileSCSS;
exports.bundleAdminJS = bundleAdminJS;
exports.bundleFrontJS = bundleFrontJS;
exports.watch = gulp.series('default', watchFiles);
