/**
 * @source: https://github.com/subodha/magento-2-gulp
 */
let gulp = require('gulp'),
  less = require('gulp-less'),
  sourcemaps = require('gulp-sourcemaps'),
  cssmin = require('gulp-cssmin'),
  livereload = require('gulp-livereload'),
  gulpif = require('gulp-if'),
  colors = require('colors'),
  exec = require('child_process').exec,
  { series } = require('gulp');

/* ==========================================================================
   Global configs of Magento2
   ========================================================================== */
let filesRouter = require('./config.d/assets/tools/files-router');
let themesConfig = require('./config.d/assets/themes');

filesRouter.set('themes','./config.d/assets/themes');


/* ==========================================================================
   Variables
   ========================================================================== */

let devArguments = [];
for (let i=3; i <= process.argv.length - 1; i++) {
  if (!process.argv[i]) break;

  let argument = process.argv[i].toString().replace('--','');
  devArguments.push(argument);
}

let themeName = devArguments[0];
const currentDirName = __dirname.split('/').slice(-1)[0]

if (!themeName) {
  console.log(' ');
  console.log('No theme name provided. Please make sure one is provided. Check package.json'.bgRed);
  console.log(' ');
  process.exit();
}

const mage = (command) => {
  return [
    isDev ? `cd /Volumes/Development/swiftotter/${currentDirName} &&` : ``,
    `php bin/magento ${command}`
  ].join(' ');
};

const vmGulp = (command) => {
  const run = [
    `${isDev ? `cd /Volumes/Development/swiftotter/${currentDirName} &&` : ``}`,
    `NODE_ENV=${ isDev ? 'development' : 'production'}`,
    `./node_modules/.bin/gulp ${command} --${devArguments.join(' --')} ${isDev ? `` : ``}`
  ].join(' ')

  try {
    if (devArguments.indexOf('vvv') !== -1 || !isDev) console.log('Running'.gray, run.white);
    return run;
  } catch (e) {
    console.log('ERROR: ---------------------------------------------');
    console.log('Failed with command:'.white)
    console.log(run.white)
    console.log(' ');
    console.log('Failed to connect to virtual machine'.bgRed);
    console.log('Run the following if you have not already:');
    console.log('$ vagrant ssh-config --host vagrant >> ~/.ssh/config'.cyan);
    console.log(' ');
    console.log('---------------------------------------------------');
    process.exit();
  }
};

let localeList = [];

if (!Array.isArray(themesConfig[themeName].locale)) {
  themesConfig[themeName].locale = [themesConfig[themeName].locale];
  localeList = themesConfig[themeName].locale
} else {
  localeList = themesConfig[themeName].locale
}


let isDev = process.env.NODE_ENV === 'development';

const targetThemeDir = function(locale = localeList[0]) {
  return `./pub/static/${themesConfig[themeName].area}/${themesConfig[themeName].name}/${locale}/`;
}

const lessFiles = function(locale = localeList[0]) {
  let fileList = [];
  let path = undefined;

  if (!themeName) {
    for (let i in themesConfig) {
      path = `./pub/static/` + themesConfig[i].area + '/' + themesConfig[i].name + '/' + locale + '/';

      for (let j in themesConfig[i].files) {
        fileList.push(path + themesConfig[i].files[j] + '.' + themesConfig[i].dsl);
      }
    }
  } else {
    path = './pub/static/' + themesConfig[themeName].area + '/' + themesConfig[themeName].name + '/**/';

    for (let i in themesConfig[themeName].files) {
      fileList.push(path + themesConfig[themeName].files[i] + '.' + themesConfig[themeName].dsl)
    }
  }

  if (devArguments.indexOf('vvv') !== -1) console.log(fileList);

  return fileList
}


/* ==========================================================================
   Styles tasks
   ========================================================================== */

// Launches LESS compilation *from* host to VM where it will work correctly
gulp.task('buildStyles', function buildStyles(cb) {
  console.log('====================================');
  console.log('Running Less compilation for ' + lessFiles().length + ' files:');

  for (let i in lessFiles()) {
    console.log('|', lessFiles()[i].split('/').slice(-2).join('/').yellow);
  }

  console.log('|------>>', (targetThemeDir() + 'css').cyan);

  return compileLess(function (err, stdout, stderr) {
    if (err && err.message) console.log('Error when compiling LESS'.bgRed)

    if (err.message) {
      console.log('ERROR: ---------------------------------------------');
      console.log(' ');
      console.log('Failed with error:'.white)
      console.log(err.message);
      console.log(' ');
      console.log('---------------------------------------------------');
    }

    cb(err);
  })
})

function compileLess(cb) {
  const mode = isDev ? 'development' : 'production';
  console.log(`Compiling LESS in ${mode.cyan} mode into`, targetThemeDir() + 'css/')

  return gulp.src(lessFiles(), {allowEmpty: true, sourcemaps: isDev })
    .pipe(less({allowEmpty: true}).on('error', function (err) {
      console.log(err.gray);
      cb(err)
    }))
    .pipe(gulpif(!isDev, cssmin()))
    .pipe(rename(function(path) {
      if (!isDev) {
        path.extname = '.min' + path.extname;
      }
      return path;
    }).on('error', function (err) {
      console.log('Rename broke'.bgRed, err.gray);
    }))
    .pipe(gulp.dest(file => {
      const dest = file.base.replace('var/view_preprocessed/', '');

      console.log('Writing'.white, `${file.path.replace('var/view_preprocessed/', '')}`.cyan);
      return dest;
    }, {sourcemaps: '.'}))
    .pipe(livereload())
}

// Primary Less task
gulp.task('_less', compileLess);

let changedFile = null;
// gulp.task('afterStyleCompile', function afterStyleCompile() {
//   console.log('Reloading:', changedFile || '[none]');
//
//   return gulp.src('./pub/static/frontend/SwiftOtter/**/css/*.css', {
//     allowEmpty: true,
//     resolveSymlinks: false,
//     sourcemaps: true
//   })
//     .pipe(livereload())
// })



/* ==========================================================================
   Javascript Task
   ========================================================================== */
const uglifyJs = require('gulp-uglify');
const rename = require('gulp-rename');
const babel = require('gulp-babel');
let changedJs = null;

gulp.task('js', function(cb) {
  exec(vmGulp('_js'), function (err, stdout, stderr) {
    if ((devArguments.indexOf('vvv') !== -1 || stdout.indexOf('Error:') !== -1) && stdout.trim()) {
      if (stdout.indexOf('Error:') !== -1) console.log('Error when compiling JS'.bgRed)
      console.log(stdout);
    } else {
      console.log(stdout.substring(0, 100) + '...');
    }

    if (stderr) {
      console.log('ERROR: ---------------------------------------------');
      console.log(' ');
      console.log('Failed with error:'.white)
      console.log(stderr.red);
      console.log(' ');
      console.log('---------------------------------------------------');
    }
    cb(err);
  })
});

gulp.task('_js', function compileJavascript(cb) {
  console.log('====================================');
  const mode = isDev ? 'development' : 'production';
  console.log(`Running Javascript compilation in ${mode.cyan} mode`);

  localeList.forEach(locale => {
    if (devArguments.indexOf('vvv') !== -1) console.log('Locale:', locale);

    try {
      return gulp.src([
        'app/code/*/*/view/frontend/web/**/*.js',
        'app/design/*/*/*/web/**/*.js'
      ], {allowEmpty: true, sourcemaps: isDev})
        .pipe(babel({
          presets: ['@babel/preset-env', {sourceType: "script"}],
          // plugins: ["@babel/plugin-transform-strict-mode", {
          //   "strict": false
          // }]
        }).on('error', function (err) {
          console.log('Babel error thrown'.bgRed, `${err}`.gray);
        }))
        .pipe(gulpif(!isDev, uglifyJs()))
        .pipe(rename(function(path) {
          let parts = path.dirname
            .replace('app/code/', '')
            .replace('view/frontend/web', '')
            .split('//');

          parts[0] = parts[0].replace('/', '_');

          path.dirname = `${parts[0]}/${parts[1]}`;

          if (!isDev) {
            path.extname = '.min' + path.extname;
          }
          if (devArguments.indexOf('vvv') !== -1) console.log('Outputting', path.dirname, `(${locale.gray})`);

          return path;
        }).on('error', function (err) {
          console.log('Rename broke'.bgRed, err.gray);
        }))
        .pipe(gulp.dest( targetThemeDir(locale), {sourcemaps: '.'} ))
    } catch (e) {
      console.log(`Errored on ${locale} locale:`.bgRed)
      console.log(`${e}`.red);
    }
  })

  cb()
});

gulp.task('afterJs', function afterJs() {
  const [target] = changedJs.split('/').slice(-1)

  console.log('Reloading:', './pub/static/frontend/SwiftOtter/**/js/' + target);

  return gulp.src('./pub/static/frontend/SwiftOtter/**/js/' + target, {
    allowEmpty: true,
    resolveSymlinks: false,
    sourcemaps: true
  })
    .pipe(livereload())
})


/* ==========================================================================
   Watcher Task
   ========================================================================== */
function watchTask() {
  console.log('====================================');

  console.log(' LiveReload:', 'enabled'.magenta);
  livereload.listen();

  console.log('====================================');

  gulp.watch(['app/**/*.less'], series('buildStyles')).on('change', file => changedFile = file)
  // gulp.watch(['app/**/*.css'], series('afterStyleCompile')).on('change', file => changedFile = file)

  // Inexplicably creates infinite loop if 'js' task is run like it should be
  gulp.watch([
    'app/code/*/*/view/frontend/web/**/*.js',
    'app/design/*/*/*/web/**/*.js',
  ], {followSymlinks: false}, series('afterJs')).on('change', file => changedJs = file);
}
gulp.task('watch', watchTask);


gulp.task('exec', function (cb) {
  localeList.forEach(locale => {
    exec(mage('dev:source-theme:deploy --locale="' + locale + '" --area="' + themesConfig[themeName].area + '" --theme="' + themesConfig[themeName].name + '" ' + themesConfig[themeName].files.join(' ')), function (err, stdout, stderr) {
      if (stdout.trim()) console.log(stdout);
      if (stderr.trim()) console.log(stderr);
      cb(err);
    });
  })
});

// Static content deploy task
gulp.task('deploy', function (cb) {
  if (themeName) {
    console.log(`Building ${themesConfig[themeName].name} in:`, localeList.join(' '));
    exec(mage(`setup:static-content:deploy -f --no-less -t ${themesConfig[themeName].name} ${localeList.join(' ')}`), function (err, stdout, stderr) {
      if (stdout.trim()) console.log(stdout);
      if (stderr.trim()) console.log(stderr);
      cb(err);
    });
  }

  else {
    console.log('Please add your defined Theme  ex: --luma'.red);
  }
});

// Cache flush task
gulp.task('cache-flush', function (cb) {
  exec(mage('cache:flush'), function (err, stdout, stderr) {
    if (stdout.trim()) console.log(stdout);
    if (stderr.trim()) console.log(stderr);
    cb(err);
  });
});

// Clean static files cache
gulp.task('clean', function (cb) {
  if (themeName) {
    exec(`rm -rf var/cache var/view_preprocessed/pub/static/${themesConfig[themeName].area}/ pub/static/${themesConfig[themeName].area}/` +
      themesConfig[themeName].name + '/',
      function (err, stdout, stderr) {
        if (stdout.trim()) console.log(stdout);
        if (stderr.trim()) console.log(stderr);
        cb(err);
      });
  } else {
    console.log('Please add your defined Theme  ex: --luma'.red);
  }
});

// Default task. Run compilation for all themes
gulp.task('dev', gulp.series(
  cb => (console.log('Starting pipeline for', themesConfig[themeName].name.inverse), cb()),
  'clean',
  'exec',
  'buildStyles',
  'watch'
));

gulp.task('prod', gulp.series('js', 'buildStyles'));

gulp.task('help', cb => {
  console.log('Use the --vvv argument to show additional logging information.')
  cb();
})
