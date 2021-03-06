var Encore = require('@symfony/webpack-encore');

Encore
    // directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')
    // only needed for CDN's or sub-directory deploy
    //.setManifestKeyPrefix('build/')

    /*
     * ENTRY CONFIG
     *
     * Add 1 entry for each "page" of your app
     * (including one that's included on every page - e.g. "app")
     *
     * Each entry will result in one JavaScript file (e.g. app.js)
     * and one CSS file (e.g. app.css) if you JavaScript imports CSS.
     */
    .addEntry('js/app', './assets/js/app.js')
    .addEntry('js/trainerCal','./assets/js/Components/Trainer/index.js')
    .addEntry('js/management','./assets/js/Components/Trainer/Management/index.js')
    .addEntry('js/customerWorkouts','./assets/js/Components/Customer/index.js')
    .addEntry('js/trainerWorkoutsCal','./assets/js/Components/Trainer/Workouts/index.js')
    .addEntry('js/trainerInfo','./assets/js/Components/Trainer/TrainerInfo/index.js')
    .addEntry('js/trainerTags','./assets/js/Components/Trainer/TrainerTags/index.js')
    .addStyleEntry('css/app', './assets/css/app.scss')
    //.addEntry('page1', './assets/js/page1.js')
    //.addEntry('page2', './assets/js/page2.js')

    /*
     * FEATURE CONFIG
     *
     * Enable & configure other features below. For a full
     * list of features, see:
     * https://symfony.com/doc/current/frontend.html#adding-more-features
     */
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    // enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())

    // enables Sass/SCSS support
    .enableSassLoader()

    //enable React support
    .enableReactPreset()

    //enable postCSS support
    .enablePostCssLoader(options=>{
        options.config={
            path:'postcss.config.js'
        }
    })

    // uncomment if you use TypeScript
    //.enableTypeScriptLoader()

    // uncomment if you're having problems with a jQuery plugin
    //.autoProvidejQuery()
;

module.exports = Encore.getWebpackConfig();
