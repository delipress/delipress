require('dotenv').config()

exports.config =
    paths:
        watched: ["app", "test"]
    files:
        javascripts:
            joinTo:
                'js/backend.js': [
                    'app/javascripts/backend/**/*.js'
                ]
                # 'js/optins.js': [
                #     'node_modules/preact/dist/preact.js'
                #     'node_modules/process/index.js'
                #     'node_modules/process/browser.js'
                #     'node_modules/underscore/underscore.js'
                #     'app/javascripts/optins/**/*.js'
                # ]
                'js/vendor-backend.js': [
                    'node_modules/underscore/underscore.js'
                ]
                'js/react.js': /^app\/javascripts\/react/
                'js/vendor.js': /^(node_modules|bower_components)/
            order:
                after:[
                    'bower_components/flatpickr/dist/l10n/fr.js'
                ]
        stylesheets:
            joinTo:
                'css/delipress.css': [
                    'app/styles/delipress.styl'
                    'app/styles/react-select.css'
                    'app/styles/select2.css'
                ]
                'css/optins.css': 'app/javascripts/optins/**/*.styl'
                'css/backend.css': 'app/styles/backend.styl'
                'css/vendor.css' : /^(node_modules|bower_components)/

    plugins:
        postcss:
            processors: [
                require('autoprefixer')(['last 8 versions'])
            ]
        browserSync:
            port: 7337,
            logLevel: "debug"
            proxy: process.env.PROXY || "localhost:8888"
            ghostMode: false
            open: false
    watcher:
        awaitWriteFinish: true
        usePolling: true
