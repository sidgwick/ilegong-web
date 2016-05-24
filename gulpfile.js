var gulp = require('gulp');
var uglify = require('gulp-uglify');
var concat = require('gulp-concat');
var cleancss = require('gulp-cleancss');
var sass = require('gulp-sass');
var _ = require('underscore');
var del = require('del');
var vinylPaths = require('vinyl-paths');

var all_js = [
  {
    name: 'weshare.min.js',
    sources: [
      'webroot/src/scripts/module-filters.js',
      'webroot/src/scripts/module-directives.js',
      'webroot/src/scripts/module-services.js',
      'webroot/src/scripts/app.js',
    ],
    dist: 'webroot/static/weshares/js/'
  }, {
    name: 'index.min.js',
    sources: [
      'webroot/src/scripts/subscription-controller.js',
      'webroot/src/scripts/index-products-controller.js',
    ],
    dist: 'webroot/static/weshares/js/'
  }, {
    name: 'weshare-view.min.js',
    sources: [
      'webroot/src/scripts/consignee-view-controller.js',
      'webroot/src/scripts/consignee-edit-controller.js',
      'webroot/src/scripts/weshare-view-controller.js',
      'webroot/src/scripts/pool-product-factory.js',
      'webroot/src/scripts/share-product-view-controller.js',
    ],
    dist: 'webroot/static/weshares/js/'
  }, {
    name: 'weshare-edit.min.js',
    sources: [
      'webroot/src/scripts/weshare-edit-controller.js',
    ],
    dist: 'webroot/static/weshares/js/'
  }, {
    name: 'share-opt-index.min.js',
    sources: [
      'webroot/src/scripts/share-opt-index-controller.js',
      'webroot/src/scripts/subscription-controller.js',
    ],
    dist: 'webroot/static/weshares/js/'
  }, {
    name: 'avatar.min.js',
    sources: [
      'webroot/static/user/layer.m.js',
      'webroot/static/user/jquery.crop.js',
    ],
    dist: 'webroot/static/user/'
  }, {
    name: 'share-order-list.min.js',
    sources: [
      'webroot/static/weshares/js/share-order-list.js',
    ],
    dist: 'webroot/static/weshares/js/'
  }, {
    name: 'faq.min.js',
    sources: [
      'webroot/static/share_faq/custom/word-limit.js',
      'webroot/static/share_faq/custom/faq.js'
    ],
    dist: 'webroot/static/share_faq/custom/'
  }, {
    name: 'user-info.min.js',
    sources: [
      'webroot/src/scripts/user-list-controller.js'
    ],
    dist: 'webroot/static/weshares/js/'
  }, {
    name: 'get-user-info.min.js',
    sources: [
      'webroot/src/scripts/get-user-info-controller.js'
    ],
    dist: 'webroot/static/weshares/js/'
  }, {
    name: 'tutorial.min.js',
    sources: [
      'webroot/src/scripts/tutorial-binding-card-controller.js',
      'webroot/src/scripts/tutorial-binding-mobile-controller.js',
    ],
    dist: 'webroot/static/weshares/js/'
  }];

var all_css = [
  {
    name: 'site-common.min.css',
    sources: [
      'webroot/src/scss/site-common.scss',
    ],
    dist: 'webroot/static/weshares/css/'
  }, {
    name: 'weshare-view.min.css',
    sources: [
      'webroot/static/weshares/css/main.css',
      'webroot/static/weshares/css/share-balance-view.css',
      'webroot/static/weshares/css/share.css',
    ],
    dist: 'webroot/static/weshares/css/'
  }, {
    name: 'weshare-edit.min.css',
    sources: [
      'webroot/src/scss/weshare-edit.scss',
    ],
    dist: 'webroot/static/weshares/css/'
  }, {
    name: 'index.min.css',
    sources: [
      'webroot/src/scss/index.scss'
    ],
    dist: 'webroot/static/weshares/css/'
  }, {
    name: 'share-opt-index.min.css',
    sources: [
      'webroot/src/scss/share-opt-index.scss',
    ],
    dist: 'webroot/static/weshares/css/'
  }, {
    name: 'opt.min.css',
    sources: [
      'webroot/static/opt/css/postinfo.css',
      'webroot/static/opt/css/opt.css',
    ],
    dist: 'webroot/static/opt/css/'
  }, {
    name: 'product_pool.min.css',
    sources: [
      'webroot/static/product_pool/css/product_pool.css',
    ],
    dist: 'webroot/static/product_pool/css/'
  }, {
    name: 'share-info.min.css',
    sources: [
      'webroot/static/weshares/css/share-info.css',
    ],
    dist: 'webroot/static/weshares/css/'
  }, {
    name: 'user-info.min.css',
    sources: [
      'webroot/src/scss/user-info.css',
    ],
    dist: 'webroot/static/weshares/css/'
  }, {
    name: 'get-user-info.min.css',
    sources: [
      'webroot/src/scss/get-user-info.scss',
    ],
    dist: 'webroot/static/weshares/css/'
  }, {
    name: 'tutorial.min.css',
    sources: [
      'webroot/src/scss/tutorial.scss',
    ],
    dist: 'webroot/static/weshares/css/'
  }
];

var js_tasks = [];
var css_tasks = [];

for (var i = 0; i < all_js.length; i++) {
  var name = all_js[i].name;
  var sources = all_js[i].sources;
  var dist = all_js[i].dist;
  var task_name = function (name, sources, dist) {
    gulp.task(name, function () {
      gulp.src(sources)
        .pipe(concat(name))
        .pipe(uglify({mangle: false}))
        .pipe(gulp.dest(dist));
    });
    return name;
  }(name, sources, dist);
  console.log('add task ' + task_name);
  js_tasks.push(task_name);
}

for (var i = 0; i < all_css.length; i++) {
  var name = all_css[i].name;
  var sources = all_css[i].sources;
  var dist = all_css[i].dist;
  var task_name = function (name, sources, dist) {
    gulp.task(name, function () {
      gulp.src(sources)
        .pipe(sass())
        .pipe(concat(name))
        .pipe(cleancss({keepBreaks: false}))
        .pipe(gulp.dest(dist));
    });
    return name;
  }(name, sources, dist);
  css_tasks.push(task_name);
}

var tasks = _.union(_.map(all_css, function (file) {
  return file.name;
}), _.map(all_js, function (file) {
  return file.name;
}));
gulp.task('default', tasks, function () {
  console.log("Default donef.");
});

gulp.task('dev', tasks, function () {
  for (var i = 0; i < all_js.length; i++) {
    gulp.watch(all_js[i].sources, [all_js[i].name]);
  }

  for (var i = 0; i < all_css.length; i++) {
    gulp.watch(all_css[i].sources, [all_css[i].name]);
  }
});

//clean 任务单独执行，一般用不到
gulp.task('clean', function () {
  gulp.src(_.map(all_css, function (file) {
    return file.dist + file.name;
  })).pipe(vinylPaths(del));
  gulp.src(_.map(all_js, function (file) {
    return file.dist + file.name;
  })).pipe(vinylPaths(del));
});