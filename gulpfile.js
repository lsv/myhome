// Copy fonts vendor/twbs/bootstrap/fonts > web/assets/fonts
// app/Resources/less/app.less > web/assets/app.css

var paths = {
    less: {
        src: 'app/Resources/less/*.less',
        dest: 'web/assets'
    },
    fonts: {
        src: 'vendor/twbs/bootstrap/fonts/*.{ttf,woff,eot,svg,woff2}',
        dest: 'web/assets/fonts'
    },
    js: {
        src: 'app/Resources/js/*.js',
        dest: 'web/assets'
    }
};

var gulp = require('gulp'),
    less = require('gulp-less-sourcemap'),
    flatten = require('gulp-flatten')
;

gulp.task('less', function() {
    gulp.src(paths.less.src)
        .pipe(less({
            compress:true,
            sourceMap: {
                sourceMapRootpath: ''
            }
        }))
        .on('error', function (e) { console.log('less', e) })
        .pipe(gulp.dest(paths.less.dest))
    ;
});

gulp.task('js', function() {
    gulp.src(paths.js.src)
        .pipe(flatten())
        .pipe(gulp.dest(paths.js.dest))
    ;
});

gulp.task('fonts', function() {
    gulp.src(paths.fonts.src)
        .pipe(flatten())
        .pipe(gulp.dest(paths.fonts.dest))
    ;
});

gulp.task('dev', ['default'], function() {
    gulp.watch(paths.less.src, ['less']);
    gulp.watch(paths.js.src, ['js']);
});

gulp.task('default', ['less', 'fonts', 'js']);
