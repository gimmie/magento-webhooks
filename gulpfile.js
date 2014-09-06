var gulp = require('gulp')
  , zlib = require('zlib')
  , crypto = require('crypto')
  , fs = require('fs')
  , path = require('path')
  , moment = require('moment')
  , util = require('util')
  , xml = require('xmlbuilder')
  , _ = require('lodash')

var multiline = function (fn) {
  return fn.toString().replace(/(^function\s*\(\)\s+{\s+\/\*)|(\s*\*\/\;*\s*})/g, '')
}

gulp.task('build', function () {

  var buildTree = function (dir, tree) {
    var base = fs.readdirSync(dir)

    base.forEach(function (file) {
      var full = path.join(dir, file)
      if (!tree['#list']) { tree['#list'] = [] }

      if (fs.statSync(full).isDirectory()) {
        var child = { 'dir': { '@name': file } }
        tree['#list'].push(child)
        buildTree(full, child['dir'])
      }
      else {
        var md5 = crypto.createHash('md5')
        var content = fs.readFileSync(full, { encoding: 'utf8' })
        md5.update(content)
      
        var child = { 'file': { '@name': file, '@hash': md5.digest('hex') } }
        tree['#list'].push(child)
      }
    })

    return tree
  }

  var meta = {
    'name': 'webhooks',
    'version': '1.0.0',
    'stability': 'stable',
    'license': 'MITL',
    'channel': 'community',
    'extends': {},
    'summary': 'Summary',
    'description': {
      '#cdata': multiline(function() {
/*
Multiline content here
 */
      })
    },
    'notes': {
      '#cdata': multiline(function() {
/*
Other multiline here
 */
      })
    },
    'authors': [
      { 'author': {
          'name': 'Maythee Anegboonlap',
          'user': 'maythee',
          'email': 'maythee@gimmie.io'
        } 
      }
    ],
    'date': moment().format('YYYY-MM-DD'),
    'time': moment().format('hh:mm:ss'),
    'contents': [
      { target: buildTree(path.join(__dirname, 'app', 'code', 'community'), { '@name': 'magecommunity' }) },
      { target: buildTree(path.join(__dirname, 'app', 'design'), { '@name': 'magedesign' }) },
      { target: buildTree(path.join(__dirname, 'app', 'etc'), { '@name': 'mageetc' }) }
    ],
    'compatible': {},
    'dependencies': {
      'required': {
        'php': { 'min': '5.3.0', 'max': '6.0.0' }
      }
    }
  }
  fs.writeFileSync('package.xml', xml.create('package').ele(meta).end({ pretty: true }))

})

gulp.task('default', [ 'build' ])
