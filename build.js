({
    appDir: './app',
    dir: './build',
    baseUrl: '.',
    modules: [{
        name: 'main'
    }],
    paths: {
        jquery: 'assets/lib/jquery.min',
        underscore: 'assets/lib/underscore',
        backbone: 'assets/lib/backbone',
        template: "models/template",
        qrcode:'assets/lib/qrcode'
    },
    fileExclusionRegExp: /^(r|build)\.js$/,
    optimizeCss: 'standard',
    removeCombined: true,
    shim: {
        backbone: {
            deps: ["underscore", "jquery"],
            exports: "Backbone"
        },
        underscore: {
            exports: "_"
        },
        qrcode:{
            deps: ["jquery"]
        }
    }
})