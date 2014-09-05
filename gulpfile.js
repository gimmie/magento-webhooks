var gulp = require('gulp')
  , zlib = require('zlib')
  , crypto = require('crypto')
  , fs = require('fs')
  , path = require('path')
  , util = require('util')
  , xml = require('js2xmlparser')
  , _ = require('lodash')

gulp.task('build', function () {

  var block = function (name) { return { '@': { name: name } } }
  var contents = function (dir, tree) {
    var base = fs.readdirSync(dir)

    base.forEach(function (file) {
      var full = path.join(dir, file)
      var child = block(file)

      if (fs.statSync(full).isDirectory()) {
        if (!tree['dir']) { tree['dir'] = [] }

        tree['dir'].push(child)
        contents(full, child)
      }
      else {
        if (!tree['file']) { tree['file'] = [] }

        var md5 = crypto.createHash('md5')
        var content = fs.readFileSync(full, { encoding: 'utf8' })
        md5.update(content)
       
        child['@'].hash = md5.digest('hex')
        tree['file'].push(child)
      }
    })

    return tree
  }

  var community = contents(path.join(__dirname, 'app', 'code', 'community'), block('magecommunity'))
  var design = contents(path.join(__dirname, 'app', 'design'), block('magedesign'))
  var etc = contents(path.join(__dirname, 'app', 'etc'), block('mageetc'))
  console.log (xml('content', { target: [ community, design, etc ] }))

})

gulp.task('default', [ 'build' ])
