'use strict';
var gulp = require('gulp'),
    config = require('./config.json'),
    bundles = require('./bundles.json'),
    twig = require('gulp-twig'),
    tasks = (function (bundles) {
        console.log(bundles);
        var tasks = [];
        for (var i in bundles) {
            tasks.push(bundles[i].name);
        }
        return tasks;
    })(bundles)
    ;

/**
 * Register bundles.
 * */
bundles.forEach(function (bundle) {
    module.exports = function (gulp, twig, bundle) {
        gulp.task(bundle.name, function() {
            console.log(bundle.name.replace(/(\w)/,function(v){return v.toUpperCase()}) + 'Bundle: task running......');
            return gulp.src(bundle.origin + '/views/**/*.twig')
                .pipe(twig({
                    data: bundle.data,
                    functions: [
                        {
                            "name": "asset",
                            func: function (args) {
                                return "the function";
                            }
                        }
                    ]
                }))
                .pipe(gulp.dest(bundle.dest));
        });
    }(gulp, twig, bundle);
});



/**
 * Task list.
 * */
gulp.task('default', tasks, function () {
    console.log("Tasks: " + tasks.join(','));
    console.log('Register bundles length: ' + bundles.length);
    console.log("config: ");
    console.log(config)
});
