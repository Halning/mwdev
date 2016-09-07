var gulp = require('gulp');
var less = require('gulp-less');
var plumber = require('gulp-plumber'); // какой-то обработчик ошибок
var postcss = require('gulp-postcss'); // несколько действий в трубе
var concat = require('gulp-concat'); // обьединение и записб файлов в один
var uglify = require('gulp-uglify'); // сжатие js
var autoprefixer = require('autoprefixer');
var sourcemaps = require('gulp-sourcemaps');
var csso = require('gulp-csso');
var uncss = require('gulp-uncss'); // для удаления из css дублированых стилей, работает только с Html
var inlineCss = require('gulp-inline-css');
var Promise = require('es6-promise').Promise;
var path = {
    build: {//Тут мы укажем куда складывать готовые после сборки файлы
        js: 'templates/shop/build',
        css: 'templates/shop/build'
    },
    src: {//Пути откуда брать исходники
        js_plugins: [
            'templates/shop/js/bootstrap.min.js',
            'templates/shop/js/jquery.elevatezoom.js',
            'templates/shop/js/jquery.magnific-popup.min.js',
            'templates/shop/js/jquery.pjax.js',
            'templates/shop/js/jquery.mCustomScrollbar.concat.min.js',
            'templates/shop/js/jquery.formstyler.min.js',
            'templates/shop/js/jquery-migrate-1.2.1.min.js',
            'templates/shop/js/jquery-ui-1.11.4.custom/jquery-ui.min.js',
            'templates/shop/js/jquery.rating-2.0.js',
            'templates/shop/slick/slick.min.js',
            'templates/shop/js/jquery.easing.1.3.js',
            'templates/shop/js/jquery.twbsPagination.min.js'

        ],
        js_custom: [
            'templates/shop/js/profile.js',
            'templates/shop/js/fast_order.js',
            'templates/shop/js/script.js',
            'templates/shop/js/basket.js',
            'templates/shop/js/myscript.js'
        ],
        css: [
            'templates/shop/css/bootstrap.min.css',
            'templates/shop/css/fonts.css',
            'templates/shop/css/styles.css',
            'templates/shop/css/jquery.rating.css',
            'templates/shop/css/profile.css',
            'templates/shop/css/jquery.formstyler.css',
            'templates/shop/slick/slick.css',
            'templates/shop/css/stylename.css',
            'templates/shop/css/magnific-popup.css',
            'templates/shop/js/jquery-ui-1.11.4.custom/jquery-ui.min.css',
            'templates/shop/css/jquery.mCustomScrollbar.min.css',
            'templates/shop/css/basket.css',
            'templates/shop/css/katalog-style.css',
            'templates/shop/css/fast_order.css',
            'templates/shop/css/profile_new.css',
            'templates/shop/css/actions-style.css'
        ]
    },
    build_file: {
        js_plugins: 'app.min.plug.js',
        js_custom: 'app.min.custom.js',
        css: 'app.min.css'
    },
    watch: {//Тут мы укажем, за изменением каких файлов мы хотим наблюдать
        js: 'templates/shop/js/*.js',
        css: 'templates/shop/css/*.css'
    }
};

gulp.task('jsp', function () {
    return gulp.src(path.src.js_plugins)
            .pipe(uglify())
            .pipe(concat(path.build_file.js_plugins))
            .pipe(gulp.dest(path.build.js));
});

gulp.task('jsc', function () {
    return gulp.src(path.src.js_custom)
            .pipe(uglify())
            .pipe(concat(path.build_file.js_custom))
            .pipe(gulp.dest(path.build.js));
});

gulp.task('css', function () {
    return gulp.src(path.src.css)
            .pipe(sourcemaps.init())
            .pipe(postcss([autoprefixer()]))
            .pipe(concat(path.build_file.css))
            .pipe(csso())
            .pipe(sourcemaps.write('.'))
            .pipe(gulp.dest(path.build.css));
});

gulp.task('email', function () {
    return gulp.src('templates/shop/mail.html')
            .pipe(plumber())
            .pipe(inlineCss({
                preserveMediaQueries: true
            }))
            .pipe(gulp.dest('dist/'));
});

gulp.task('watcher', function () {
    gulp.watch(path.src.css, ['css']);
    gulp.watch(path.src.js_custom, ['jsc']);
    gulp.watch('templates/shop/mail.html', ['email']);
});

gulp.task('default', ['watcher']);


