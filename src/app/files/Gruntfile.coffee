module.exports = (grunt) ->
  grunt.file.expand('node_modules/grunt-*/tasks').forEach grunt.loadTasks

  options = grunt.file.readYAML('compile.yml')

  grunt.initConfig
    cfg: options

    bower:
      dev:
        options:
          expand: true
          packageSpecific: "<%= cfg.bower %>"
        dest: '<%= cfg.paths.assets.plugins %>'

    exec:
      bower:
        cmd: "<%= cfg.php %>vendor/bin/compile bower"
      template:
        cmd: "<%= cfg.php %>vendor/bin/compile template <%= cfg.folder %> <%= cfg.filepath %>"
      templatechars:
        cmd: "<%= cfg.php %>vendor/bin/compile chars <%= cfg.folder %> <%= cfg.filepath %>"
      updateversion:
        cmd: "<%= cfg.php %>vendor/bin/compile updateversion"

    uglify:
      plugins:
        files: [
          expand: true,
          cwd: '<%= cfg.paths.assets.plugins %>',
          src: ['**/*/*.js', '!minify/**'],
          dest: '<%= cfg.paths.assets.plugins %>/minify'
        ]
      app:
        files:
          '<%= cfg.compile.assets.js %>/main.js': '<%= cfg.paths.temp %>/js/main1.js'

    cssmin:
      target:
        files:
          '<%= cfg.compile.assets.css %>/main.css': '<%= cfg.paths.temp %>/css/app.css'

    compass:
      dist:
        options:
          imagesDir: "<%= cfg.compile.assets.imgsdir %>"
          sassDir: "<%= cfg.compile.files.sass %>"
          cssDir: "<%= cfg.paths.temp %>/css"

    haml:
      dist:
        expand: true
        cwd: "<%= cfg.compile.files.haml %>"
        src: ['**/*.haml']
        dest: '<%= cfg.compile.dest %>'
        ext: '.php'
      one:
        expand: true
        cwd: "<%= cfg.compile.files.haml %>"
        src: ['<%= cfg.file %>.haml']
        dest: '<%= cfg.compile.dest %>'
        ext: '.php'

    coffee:
      compile:
        options:
          bare: true
        expand: true
        cwd: "<%= cfg.compile.files.coffee %>"
        src: "**/**/*.coffee"
        dest: "<%= cfg.paths.temp %>/js"
        ext: '.js'

    concat:
      js:
        src: '<%= cfg.paths.temp %>/js/**/*.js'
        dest: '<%= cfg.paths.temp %>/js/main1.js'

    watch:
      sass:
        files: ['!<%= cfg.paths.temp %>/**', '*/**/*.sass']
        tasks: ['buildsass:<%= cfg.folder %>']
        options:
          spawn: false
          interrupt: false
      cofffee:
        files: ['!<%= cfg.paths.temp %>/**/*.coffee', '!Gruntfile.coffee', '*/**/*.coffee']
        tasks: ['buildcoffee:<%= cfg.folder %>']
        options:
          spawn: false
          interrupt: false
      haml:
        files: ['!<%= cfg.paths.temp %>/**/*.coffee', '*/**/*.haml']
        tasks: ['buildhaml:<%= cfg.folder %>:<%= cfg.file %>']
        options:
          spawn: false
          interrupt: false

    htmlmin:
      dist:
        options:
          removeComments: true
          collapseWhitespace: true
          caseSensitive: true
        expand: true
        cwd: "<%= cfg.compile.dest %>"
        src: ['**/*.php']
        dest: "<%= cfg.compile.dest %>"
      one:
        options:
          removeComments: true
          collapseWhitespace: true
          caseSensitive: true
        expand: true
        cwd: "<%= cfg.compile.dest %>"
        src: ['<%= cfg.file %>.php']
        dest: "<%= cfg.compile.dest %>"

    copy:
      folders:
        expand: true
        cwd: "<%= cfg.compile.files.root %>"
        src: ["**", "!coffee/**", "!views/**", "!sass/**"]
        dest: "<%= cfg.compile.assets.root %>"

    clean:
      temp: ["<%= cfg.paths.temp %>"]
      plugins: ["<%= cfg.paths.assets.plugins %>"]
      cache: ["<%= cfg.paths.cache %>/*"]
      public: ["<%= cfg.compile.dest %>"]

  grunt.registerTask 'installplugins', 'Instalamos los plugins', =>
    @checkPlatform()
    grunt.task.run ['clean:plugins', 'bower', 'exec:bower', 'uglify:plugins']
    return

  grunt.registerTask 'buildsass', 'Generamos SASS', (folder = false) =>
    @checkPlatform()
    @iniCompile folder
    grunt.task.run ['compass', 'cssmin', 'clean:temp', 'clean:cache', 'exec:updateversion']
    return

  grunt.registerTask 'buildcoffee', 'Generamos Coffee', (folder = false) =>
    @checkPlatform()
    @iniCompile folder
    grunt.task.run ['coffee', 'concat:js', 'uglify:app', 'clean:temp', 'clean:cache', 'exec:updateversion']
    return

  grunt.registerTask 'buildhaml', 'Generamos HAML', (folder = false, file = false) =>
    @checkPlatform()
    @iniCompile folder
    options.folder = options.compile.name
    if file
      options.file = file
      grunt.task.run ['haml:one', 'exec:template', 'htmlmin:one', 'exec:templatechars']
    else
      grunt.task.run ['clean:public', 'haml:dist', 'exec:template', 'htmlmin:dist', 'exec:templatechars']
    return

  grunt.registerTask 'buildassets', 'copiamos todo lo no autogenerado', (folder = false) =>
    @iniCompile folder
    grunt.task.run ['copy']
    return

  grunt.registerTask 'build', 'Generamos All', (folder = false) =>
    options.folder = if folder then ":#{folder}" else ""
    grunt.task.run ["buildsass#{options.folder}", "buildcoffee#{options.folder}", "buildhaml#{options.folder}",
      "buildassets#{options.folder}"]
    return

  grunt.event.on 'watch', (action, filepath, target) =>
    console.log "#{target}: #{action} => #{filepath}"

    options.file = false

    if process.platform == "win32"
      parts = filepath.split("\\")
    else
      parts = filepath.split("/")

    file = parts.pop()
    file = file.split(".")
    file.pop()
    namefile = file.join(".")

    target = 'coffee' if target == 'cofffee'

    file_path = []

    for element, index of options.compiler_paths
      parts_copy = parts.slice()
      target_path = index.files[target]
      parts_ = target_path.split("/")
      file_path = []
      while parts_copy.length > parts_.length
        file_path.push parts_copy.pop()
      if @compare(parts_copy, parts_)
        options.compile = index

    file_path.reverse()
    join = file_path.join('/')
    options.file = "#{join}/#{namefile}"
    options.filepath = filepath
    options.folder = options.compile.name
    return

  @iniCompile = (folder) =>
    if folder
      for element, index of options.compiler_paths
        if index.name == folder
          options.compile = index
    if !options.compile
      options.compile = options.compiler_paths.compile1

  @compare = (arr1, arr2) =>
    for i in [0..arr1.length]
      if arr1[i] != arr2[i]
        return false
    return true

  @checkPlatform = =>
    if process.platform == "win32"
      options.php = ""
    else
      options.php = "php "
    return