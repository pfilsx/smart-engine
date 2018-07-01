let gulp = require('gulp');
let less = require('gulp-less');

gulp.task('less', function () {
    return gulp.src('smart/less/*.less')
        .pipe(less())
        .pipe(gulp.dest('smart/css'));
});

gulp.task('watch', function(){
    gulp.watch('smart/less/*.less', ['less']);
});