var gulp=require('gulp'),
    concat=require('gulp-concat'),//文件合并
    babel = require('gulp-babel'), //es6 编译
    uglify=require('gulp-uglify'),//js压缩
    minifyCss=require('gulp-minify-css'),//css压缩
    htmlmin = require('gulp-htmlmin'), //压缩html
    rev  = require('gulp-rev-append'); // 给URL自动加上版本号
clean=require('gulp-clean');//清理
//css处理任务
gulp.task('mini-css',function(){
    gulp.src(['./src/pages/**/**/*.css'])
        .pipe(minifyCss())
        .pipe(gulp.dest('./dist/pages'));
});
//js处理任务
gulp.task('mini-js',function(){
    gulp.src(['./src/pages/**/**/*.js'])
        .pipe(babel({
            presets: ['es2015']
        }))
        .pipe(uglify({mangle: true}))
        .pipe(gulp.dest('./dist/pages'));
});
// 拷贝插件
gulp.task('copy-utils',function(){
    gulp.src(['./src/utils/**/*','!./src/utils/less/**/*.*','!./src/utils/less'])
        .pipe(gulp.dest('./dist/utils'));
});
//app.js app.css  app-ui.js 处理
gulp.task('app-base',function(){
    //css
    gulp.src(['./src/app.css'])
        .pipe(minifyCss())
        .pipe(gulp.dest('./dist'));
    //js-app-ui
    gulp.src(['./src/app-ui.js'])
        .pipe(babel({
            presets: ['es2015']
        }))
        .pipe(uglify({mangle: true}))
        .pipe(gulp.dest('./dist'));
    //js-app
    gulp.src(['./src/app.js'])
        .pipe(gulp.dest('./dist'));

});
//路径替换任务
gulp.task('rev',function(){
    var options = {
        removeComments: true,//清除HTML注释
        collapseWhitespace: true,//压缩HTML
        collapseBooleanAttributes: true,//省略布尔属性的值 <input checked="true"/> ==> <input />
        removeEmptyAttributes: true,//删除所有空格作属性值 <input id="" /> ==> <input />
        removeScriptTypeAttributes: true,//删除<script>的type="text/javascript"
        removeStyleLinkTypeAttributes: true,//删除<style>和<link>的type="text/css"
        minifyJS: true,//压缩页面JS
        minifyCSS: true//压缩页面CSS
    };
    gulp.src('./src/pages/**/**/*.html')
        .pipe(htmlmin(options))
        .pipe(rev())
        .pipe(gulp.dest('./dist/pages'));

    gulp.src('./src/index.html')
        .pipe(htmlmin(options))
        .pipe(rev())
        .pipe(gulp.dest('./dist'));
});
//清理文件
gulp.task('clean', function() {
    gulp.src(['./dist'], {read: false})
        .pipe(clean());
});
//图片处理，
gulp.task('images', function() {
    return gulp.src('src/images/**/*')
        .pipe(gulp.dest('./dist/images'));
});
// 多线程-处理-请先执行clean
gulp.task('dist',['clean','mini-css','mini-js','images','copy-utils','app-base','rev']);
