var gulp = require('gulp')
  , zlib = require('zlib')
  , crypto = require('crypto')
  , fs = require('fs')
  , path = require('path')
  , util = require('util')
  , xml = require('js2xmlparser')
  , _ = require('lodash')

gulp.task('build', function () {

  var ignores = _(['.git', '.gitignore', 'gulpfile.js', 'package.json', 'node_modules', 'readme.md'])
    .inject(function (hash, value) {
      hash[value] = true
      return hash
    }, {})


  var prepare = function(name, dir) { 
    var base = _(fs.readdirSync(dir))
      .filter(function(file) { return !ignores[file] })
      .map(function(file) { return path.join(dir, file) })
      .value()

    var tree = { '@': { name: name } }
    var cur = tree
    var stack = []

    while (base.length > 0) {
      var node = base.shift()
      var name = path.basename(node)
      if (fs.statSync(node).isDirectory()) {
        stack.push(base)

        var child = { p: cur, '@': { name: name } }
        if (!cur['dir']) { cur['dir'] = [] }
        cur['dir'].push(child)
        cur = child

        base = _(fs.readdirSync(node))
          .map(function(file) { return path.join(node, file); })
          .value()
      }
      else {
        var md5 = crypto.createHash('md5')
        var content = fs.readFileSync(node, { encoding: 'utf8' })
        md5.update(content)

        if (!cur['file']) { cur['file'] = [] }
        cur['file'].push({ '@': { name: name, hash: md5.digest('hex') } })
      }

      while (base.length == 0 && cur.p) {
        var p = cur.p
        delete cur.p
        cur = p
        base = stack.pop()
      }
    }

    return tree
  }

  var community = prepare('magecommunity', path.join(__dirname, 'app', 'code', 'community'))
  var design = prepare('magedesign', path.join(__dirname, 'app', 'design'))
  var etc = prepare('mageetc', path.join(__dirname, 'app', 'etc'))
  
  console.log (xml('content', { target: [ community, design, etc ] }))

})

gulp.task('default', [ 'build' ])
