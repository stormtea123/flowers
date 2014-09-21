define([
    'jquery',
    'underscore',
    'backbone',
    'template'
], function($, _, Backbone, Template) {
	var $module = $('#main');
	//高亮菜单
	(function(){
		var willindex = 0; 
		$("#nav a").each(function(i,element){
			if(i>0 && location.href.indexOf(element.href)>-1) {
				willindex = i;
				return false;
			}
		})
		$("#nav li").eq(willindex).addClass("current").siblings("li").removeClass("current");
		$("#nav").delegate("li","click",function(event){
			$("#nav li").eq($(this).index()).addClass("current").siblings("li").removeClass("current");
		})
	})();
	$module.delegate(".goods_item", "mouseover", function(event) {
	    $(this).addClass("current").siblings("li").removeClass("current");
	});
	$module.delegate(".goods_item", "mouseleave", function(event) {
	    $(this).removeClass("current")
	});
	//返回按钮
	$("#return").click(function(event){
		event.preventDefault();
		history.go(-1);
	});

	//删除
	$module.delegate(".goods_delete", "click", function(event) {
	    event.preventDefault();
	    alert("抱歉！您没有权限删除");
	});
	//创建数据
	$module.delegate(".save", "click", function(event) {
	    event.preventDefault();
	    var annexData = {
	        videoUrl : videoUrl,
	        videoSize : videoSize,
	        imgUrl : imgUrl,
	        imgSize : imgSize
	    };

	    if (/edit/.test(location.href)) {
	    	$.ajax({
	    	    type:"POST",
	    	    url: "http://ppms.paipaioa.com/wht/admin/update.php?id="+location.href.match(/edit\/(\d+)/)[1],
	    	    data: $("#create-form").serialize() + '&' + $.param(annexData),
	    	    dataType: "jsonp"
	    	}).done(function(data) {
	    	    alert("数据保存成功")
	    	});
	    } else {
	    	$.ajax({
	    	    type:"POST",
	    	    url: "http://ppms.paipaioa.com/wht/admin/createData.php",
	    	    data: $("#create-form").serialize() + '&' + $.param(annexData),
	    	    dataType: "jsonp"
	    	}).done(function(data) {
	    	    alert("数据保存成功")
	    	});
	    }
	    
	})
	//搜索
	$("#search_input").bind("keyup",function(event){
		//event.preventDefault();
		if (event.keyCode == "13"){
			var keyword = $(this).val();
			categoryNum = 0;
			$.ajax({
			    url: "http://ppms.paipaioa.com/wht/admin/search.php?page=1&count="+count+"&keyword=" + keyword,
			    dataType: "jsonp"
			}).done(function(data) {
				if (parseInt(data["count"])>0){
					$.get('app/views/home.html', function(templateData) {
						$module.html(templateData);
						//分页导航
						var pagesNum = Math.ceil(parseInt(data["count"]) / count);
						var pageNavgations = []
						for (var i = 0; i < pagesNum; i++) {
						    pageNavgations[i] = '<a href="#search/'+keyword+'/' + (i + 1) + '/' + count + '" class="page_navgation_item">' + (i + 1) + '</a>'
						}
						$("#page-navgation").html(pageNavgations.join(""));
						$("#page-navgation .page_navgation_item").eq(0).addClass("current");
						//渲染数据
						new Template.render("goods-list", data["content"], "template-goods");
					})
				} else {
					alert("抱歉没有搜索到结果!")
				}
			    
			});
		}
	})
})