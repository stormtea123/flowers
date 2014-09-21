require.config({
    baseUrl: "app/",
    paths: {
        jquery: 'assets/lib/jquery.min',
        underscore: 'assets/lib/underscore',
        backbone: 'assets/lib/backbone',
        template: "models/template",
        qrcode:'assets/lib/qrcode'
    },
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
});
var count = "4";
var currentPage = 0;
var categoryNum = 0;
var videoUrl = imgUrl = videoSize = imgSize = "";
//返回
function isShowReturn(){
    if (!/html$|html#$|\/wht\/$/.test(location.href)){
        document.getElementById("return").style.display="block";
    } else {
        document.getElementById("return").style.display="none";
    }
}
//初始化
require(["init"]);
//路由
require(["app"],function(App){
    new App();
})


