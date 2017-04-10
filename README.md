AppCompile
================================

[![Packagist](https://img.shields.io/packagist/v/buuum/appcompile.svg)](https://packagist.org/packages/buuum/appcompile)
[![license](https://img.shields.io/github/license/mashape/apistatus.svg?maxAge=2592000)](#license)

## Install

### System Requirements

You need PHP >= 7.0 to use Buuum\appcompile but the latest stable version of PHP is recommended.

### Composer

Buuum\appcompile is available on Packagist and can be installed using Composer:

```
composer require buuum/appcompile
```

### Manually

You may use your own autoloader as long as it follows PSR-0 or PSR-4 standards. Just put src directory contents in your vendor directory.
 

## Initialize Config

```
php vendor/bin/compile init
```

file generated compile.yml

```yaml
paths:
  temp: _tmp
  cache: temp/assets
  assets:
    root: httpdocs/assets

compiler_paths:
  compile1:
    name: default
    dest: app/public2
    files:
      root: app/_gen
      coffee: app/_gen/coffee
      haml: app/_gen/views
      sass: app/_gen/sass
    assets:
      root: httpdocs/assets
      css: httpdocs/assets/css
      js: httpdocs/assets/js
      imgsdir: httpdocs/assets/imgs
  compile2:
    name: Web
    dest: app/Web/public2
    files:
      root: app/_gen
      coffee: app/Web/_gen/coffee
      haml: app/Web/_gen/views
      sass: app/Web/_gen/sass
    assets:
      root: httpdocs/assets/Web
      css: httpdocs/assets/Web/css
      js: httpdocs/assets/Web/js
      imgsdir: httpdocs/assets/Web/imgs

bower:
  masonry:
    files:
      - "dist/masonry.pkgd.min.js"
  chosen:
    files:
      - "chosen-sprite.png"
      - "chosen-sprite@2x.png"
      - "chosen.jquery.js"
      - "chosen.css"
  font-awesome:
    files:
      - "css/font-awesome.css"
      - "fonts/*"
  bootstrap:
    files:
      - "dist/css/bootstrap.min.css"
      - "dist/js/bootstrap.min.js"
      - "dist/fonts/*"
    replaces:
      files:
        dist/css/bootstrap.min.css:
          ../fonts: plugins/bootstrap/dist/fonts
  summernote:
    files:
      - "dist/summernote.js"
      - "dist/summernote.css"
      - "dist/font/*"
  moment:
    files:
      - "min/moment-with-locales.min.js"
  jquery:
    renames:
      dist/jquery.js: dist/jqueryrename.js
```

## initialize npm

```
npm install
```
 
 
## LICENSE

The MIT License (MIT)

Copyright (c) 2017

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.