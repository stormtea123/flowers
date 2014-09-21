//模板引擎
define(function() {
    //判断数组
    if(!Array.isArray) {
      Array.isArray = function(arg) {
        return Object.prototype.toString.call(arg) === '[object Array]';
      };
    }
    var templateEngine = function(element, data, template) {
        this.template = document.getElementById(arguments[2]).innerHTML;
        this.element = document.getElementById(arguments[0]);
        this.data = arguments[1];
        this.templateFormat = this.template.replace(/^\s*/gm, "").replace(/\n/gm, "");
        this.init();
    }
    templateEngine.prototype = {
        init: function() {
            var that = this;
            if (Array.isArray(this.data)) {
                var len = this.data.length;
                var dataArray = [];
                for (var i = 0; i < len; i++) {
                    var result = that.templateFormat.replace(/{{([A-Za-z]+)}}/g, function(a, b) {
                        return that.data[i][b];
                    })
                    //{{imgUrl_240x360}}
                    if (/{{(\w+)(_\d+x\d+)}}/g.test(result)){
                        result = result.replace(/{{(\w+)(_\d+x\d+)}}/g,function(a,b,c){
                            //http://media.w3.org/2010/05/sintel/poster_240x360.png
                            var imgUrl = that.data[i][b];
                            var imgUrlSuffix = imgUrl.substr(imgUrl.lastIndexOf("."));
                            return imgUrl.split(imgUrlSuffix)[0]+c+imgUrlSuffix;
                        })
                    }
                    //附件大小{{imgSize/1000}}
                    if (/{{(\w+)\/(\d+)}}/g.test(result)){
                        result = result.replace(/{{(\w+)\/(\d+)}}/g,function(a,b,c){
                            return parseInt(that.data[i][b])/1000;
                        })
                    }
                    
                    dataArray.push(result);
                }
                //console.log(dataArray)
                this.element.innerHTML = dataArray.join("");
            } else {
                var result = this.template.replace(/{{(\w+)}}/g, function(a, b) {
                    return this.data[b];
                })
                this.element.innerHTML = result;
            }
        }
    }

    return {
        render: templateEngine
    }
});