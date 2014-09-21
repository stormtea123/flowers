define([
    'jquery',
    'underscore',
    'backbone',
    'template',
    'qrcode'
], function($, _, Backbone, Template, QRCode) {
    var $module = $('#main');
    var AppRouter = Backbone.Router.extend({
        routes: {
            '': 'main',
            'category/:id': 'category',
            'about': 'about',
            'create': 'create',
            'single/:id': 'single',
            'category/:id/:which/:count': 'categoryPage',
            'search/:keyword/:which/:count': 'searchPage',
            'page/:which/:count': 'page',
            'edit/:id': 'edit'
        },
        main: function() {
            categoryNum = 0;
            currentPage = 0;
            isShowReturn();
            $.get('app/views/home.html', function(templateData) {
                $module.html(templateData);
                //初始化页面数据
                $.ajax({
                    type: "POST",
                    url: "http://ppms.paipaioa.com/wht/admin/getAll.php?page=1&count=" + count,
                    dataType: "jsonp"
                }).done(function(defaultData) {
                    new Template.render("goods-list", defaultData["content"], "template-goods");
                    //分页导航
                    var pagesNum = Math.ceil(parseInt(defaultData["count"]) / count);
                    var pageNavgations = [];
                    for (var i = 0; i < pagesNum; i++) {
                        pageNavgations[i] = '<a href="#page/' + (i + 1) + '/' + count + '" class="page_navgation_item">' + (i + 1) + '</a>'
                    }
                    $("#page-navgation").html(pageNavgations.join(""));
                    $("#page-navgation .page_navgation_item").eq(currentPage).addClass("current");
                });
            })
        },
        category: function(id) {
            categoryNum = id;
            currentPage = 0;
            isShowReturn();
            $.get('app/views/home.html', function(templateData) {

                $module.html(templateData);
                //初始化页面数据
                $.ajax({
                    url: "http://ppms.paipaioa.com/wht/admin/getAll.php?category=" + id + "&page=1&count=" + count,
                    dataType: "jsonp"
                }).done(function(initData) {
                    //分页导航
                    var pagesNum = Math.ceil(parseInt(initData["count"]) / count);
                    var pageNavgations = []
                    for (var i = 0; i < pagesNum; i++) {
                        pageNavgations[i] = '<a href="#category/' + id + '/' + (i + 1) + '/' + count + '" class="page_navgation_item">' + (i + 1) + '</a>'
                    }
                    $("#page-navgation").html(pageNavgations.join(""));
                    new Template.render("goods-list", initData["content"], "template-goods");
                    $("#page-navgation .page_navgation_item").eq(0).addClass("current");
                });
            });
        },
        categoryPage: function(id, which, count) {
            isShowReturn();
            $.get('app/views/home.html', function(templateData) {
                $module.html(templateData);
                $.ajax({
                    url: "http://ppms.paipaioa.com/wht/admin/getAll.php?category=" + id + "&page=" + which + "&count=" + count,
                    dataType: "jsonp"
                }).done(function(defaultData) {
                    new Template.render("goods-list", defaultData["content"], "template-goods");
                    //分页导航
                    var pagesNum = Math.ceil(parseInt(defaultData["count"]) / count);
                    var pageNavgations = []
                    for (var i = 0; i < pagesNum; i++) {
                        pageNavgations[i] = '<a href="#category/' + id + '/' + (i + 1) + '/' + count + '" class="page_navgation_item">' + (i + 1) + '</a>'
                    }
                    $("#page-navgation").html(pageNavgations.join(""));
                    $("#page-navgation .page_navgation_item").eq(which - 1).addClass("current");

                })
            })
        },
        searchPage: function(keyword, which, count) {
            isShowReturn();
            $.get('app/views/home.html', function(templateData) {
                $module.html(templateData);
                $.ajax({
                    url: "http://ppms.paipaioa.com/wht/admin/search.php?page=" + which + "&count=" + count + "&keyword=" + keyword,
                    dataType: "jsonp"
                }).done(function(defaultData) {
                    new Template.render("goods-list", defaultData["content"], "template-goods");
                    //分页导航
                    var pagesNum = Math.ceil(parseInt(defaultData["count"]) / count);
                    var pageNavgations = []
                    for (var i = 0; i < pagesNum; i++) {
                        pageNavgations[i] = '<a href="#search/' + keyword + '/' + (i+1) + '/' + count + '" class="page_navgation_item">' + (i + 1) + '</a>'
                    }
                    $("#page-navgation").html(pageNavgations.join(""));
                    $("#page-navgation .page_navgation_item").eq(which - 1).addClass("current");

                })
            })
        },
        page: function(which, count) {
            isShowReturn();
            $.get('app/views/home.html', function(templateData) {
                $module.html(templateData);
                $.ajax({
                    url: "http://ppms.paipaioa.com/wht/admin/getAll.php?page=" + which + "&count=" + count,
                    dataType: "jsonp"
                }).done(function(defaultData) {
                    new Template.render("goods-list", defaultData["content"], "template-goods");
                    //分页导航
                    var pagesNum = Math.ceil(parseInt(defaultData["count"]) / count);
                    var pageNavgations = []
                    for (var i = 0; i < pagesNum; i++) {
                        pageNavgations[i] = '<a href="#page/' + (i + 1) + '/' + count + '" class="page_navgation_item">' + (i + 1) + '</a>'
                    }
                    console.log(defaultData)
                    $("#page-navgation").html(pageNavgations.join(""));
                    $("#page-navgation .page_navgation_item").eq(which - 1).addClass("current");

                })
            })
        },
        about: function() {
            isShowReturn();
            $.get('app/views/about.html', function(templateData) {
                $module.html(templateData);
            });
        },
        create: function() {
            isShowReturn();
            $module.load('app/views/create.html');
        },
        single: function(id) {
            isShowReturn();
            $.get('app/views/single.html', function(templateData) {
                $module.html(templateData);
                $.ajax({
                    url: "http://ppms.paipaioa.com/wht/admin/getAll.php",
                    data: {
                        "id": id
                    },
                    dataType: "jsonp"
                }).done(function(data) {
                    new Template.render("page", data.content, "template-page");
                    if (data.content[0]["demoAddress"].length > 0) {
                        $(".page_demo").show();
                        //二维码
                        var qrcode = new QRCode(document.getElementById("qrcode"), {
                            width: 150,
                            height: 150
                        });
                        qrcode.makeCode(data.content[0]["demoAddress"]);
                    }
                    if (data.content[0]["summary"].length > 0) {
                        $(".page_summary").show();
                    }
                    if (data.content[0]["videoUrl"].length > 0) {
                        $(".page_video").show();
                    } else {
                        $(".page_img").show();
                    }
                });
            });
        },
        edit: function(id) {
            isShowReturn();
            $module.load('app/views/edit.html');
            $.ajax({
                url: "http://ppms.paipaioa.com/wht/admin/getAll.php",
                data: {
                    "id": id
                },
                dataType: "jsonp"
            }).done(function(data) {
                //alert("数据保存成功")
                new Template.render("edit", data.content, "template-edit");

                videoUrl = data.content[0]["videoUrl"];
                videoSize = data.content[0]["videoSize"];
                imgUrl = data.content[0]["imgUrl"];
                imgSize = data.content[0]["imgSize"];

                if (data.content[0]["category"] == "2") {
                    $("input[name='category'][value='2']").prop("checked", true);
                } else {
                    $("input[name='category'][value='1']").prop("checked", true);
                }

                if (imgUrl.length > 0) {
                    var imgUrlSuffix = imgUrl.substr(imgUrl.lastIndexOf("."));
                    $("#img-preview-img").attr("src", imgUrl.split(imgUrlSuffix)[0] + "_560x315" + imgUrlSuffix);
                    $("#img-result").html(imgUrl + '&nbsp;(' + parseInt(imgSize) / 1000 + 'kb)');
                }
                if (videoUrl.length > 0) {
                    $("#video-result").html(videoUrl + '&nbsp;(' + parseInt(videoSize) / 1000 + 'kb)');
                }
            });

        }
    });
    var App = function() {
        this.init();
    }
    App.prototype = {
        init: function() {
            var router = new AppRouter();
            Backbone.history.start();
        }
    }
    return App;
});
