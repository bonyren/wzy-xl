(function($){
    function buildAttaches($target, options){
        if(options.readOnly){
            var url = sprintf('/index.php/index/upload/viewAttaches.html?attachmentType=%d&externalId=%d&uiStyle=%d&callback=%s&prompt=%s&fit=%d',
                options.attachmentType,
                options.externalId,
                options.uiStyle,
                options.callback,
                encodeURIComponent(options.prompt),
                options.fit?1:0);
        }else{
            var url = sprintf('/index.php/index/upload/attaches.html?attachmentType=%d&externalId=%d&uiStyle=%d&callback=%s&prompt=%s&fit=%d',
                options.attachmentType,
                options.externalId,
                options.uiStyle,
                options.callback,
                encodeURIComponent(options.prompt),
                options.fit?1:0);
        }

        var $panel =$('<div data-options="border:false,' +
                'minimizable:false,' +
                'maximizable:false,' +
                'fit:' + (options.fit?'true,':'false,') +
                'href:\'' + url + '\'">' +
                '</div>').appendTo($target);

        $panel.panel();
        //$.parser.parse($panel);
    }
    function buildAttachesComplex($target, options){
        options.readOnly = options.readOnly ? 1 : 0;
        options.fit = options.fit ? 1 : 0;
        options.title = options.title ? options.title : '';
        var url = '/index.php/index/upload/attachesComplex.html?' + $.param(options);
        var $panel =$('<div data-options="border:false,\
                minimizable:false,\
                maximizable:false,\
                fit:' + (options.fit?'true':'false') + ',\
                href:\'' + url + '\'"> \
                </div>').appendTo($target);
        $panel.panel();
        //$.parser.parse($panel);
    }
    $.fn.attaches = function(options){
        if(typeof options == 'string'){
            var params = [];
            for(var i=1; i<arguments.length; i++){
                params.push(arguments[i]);
            }
            $.fn.attaches.methods[options].apply(this, params);
            return this;
        }
        options = options || {};
        this.each(function(){
            var newOptions = $.extend({}, $.fn.attaches.defaults, $.fn.attaches.parseOptions(this), options);
            var $target = $(this);
            buildAttaches($target, newOptions);
        });
        return this;
    }
    $.fn.attachesComplex = function(options){
        if(typeof options == 'string'){
            var params = [];
            for(var i=1; i<arguments.length; i++){
                params.push(arguments[i]);
            }
            $.fn.attaches.methods[options].apply(this, params);
            return this;
        }
        options = options || {};
        this.each(function(){
            var newOptions = $.extend({}, $.fn.attaches.defaults, $.fn.attaches.parseOptions(this), options);
            var $target = $(this);
            buildAttachesComplex($target, newOptions);
        });
        return this;
    }
    $.fn.attaches.methods = {
    }
    $.fn.attaches.parseOptions = function(target) {
        return $.extend({}, $.parser.parseOptions(target, ["attachmentType", "externalId", "callback", "uiStyle", "readOnly", "prompt", "fit", {
            attachmentType: "number",
            externalId: "number",
            uiStyle: "number",
            callback: "string",
            readOnly: "boolean",
            prompt: "string",
            fit: "boolean"
        }]));
    };
    $.fn.attaches.defaults = {
        attachmentType:1,
        externalId:0,
        uiStyle:1,
        callback:'',
        readOnly:false,
        prompt:'',
        attachesFit:false
    }
    $.parser.plugins.push('attaches');
    $.parser.plugins.push('attachesComplex');
})(jQuery);
/******************************************************************************************************************/
(function($) {
	function buildAddItem(target, row, options){
			//构建dom
			var $item = $(`<li data-id="${row.id}"></li>`).addClass('easyui-imagelist-item');
			var $img = $(`<img src="${row.src}" title="${row.title}" style="height:${options.imageHeight}px">`);
			var $title = $(`<p class="easyui-imagelist-item-title">${row.title}</p>`);
			var $remove = $(`<a href="javascript:void(0)" class="easyui-imagelist-item-remove icon-clear"></a>`);
			$item.append($img);
			$item.append($title);
			$item.append($remove);
			//注册事件                    
			$item.off('click').on('click', function() {
				options.onClick.call(target, row);
			});
			$remove.off('click').on('click', function(){
				$(this).parent('.easyui-imagelist-item').remove();
				options.onDelete.call(target, row);
				return false;
			});
			target.append($item);
	}
	function build(target, options, data) {
		if(data && data.length > 0) {
			$.each(data, function(index, row) {
				buildAddItem(target, row, options);
			});
			if(options.onComplete) {
				options.onComplete.call(target, data);
			}
		}
	}

	$.fn.imagelist = function(options) {
		if(typeof options == "string") {
			//方法调用
			var params = [];
			for(var i = 1; i < arguments.length; i++) {
				params.push(arguments[i]);
			}
			this.each(function() {
				$.fn.imagelist.methods[options].apply(this, params);
			});
			return this;
		}
		//构建
		options = options || {};
		
		return this.each(function() {
			var domData = $.data(this, "imagelist");
			var newOptions;
			if(domData) {
				newOptions = $.extend(domData.options, options);
				domData.options = newOptions;
			} else {
				newOptions = $.extend({}, $.fn.imagelist.defaults, $.fn.imagelist.parseOptions(this), options);
				$.data(this, "imagelist", {
					options: newOptions
				});
			}
			var target = $(this);
			target.addClass('easyui-imagelist');
			if(newOptions.url) {
				$.ajax({
					type: "get",
					url: newOptions.url,
					dataType: 'json',
					success: function(data) {
						if(newOptions.onLoadSuccess) {
							newOptions.onLoadSuccess.call(target, data);
						}
						build(target, newOptions, data);
					}
				});
			}else if(newOptions.data && newOptions.data.length > 0) {
				build(target, newOptions, newOptions.data);
			}
		});
	}

	$.fn.imagelist.methods = {
		addItem: function(id, title, src){
			var domData = $.data(this, "imagelist");
			buildAddItem($(this), {id:id, title:title, src:src}, domData.options);
		},
		removeItem: function(id){
			$(this).find('.easyui-imagelist-item').each(function(){
				if(id == $(this).data('id')){
					$(this).remove();
					return false;
				}
			});
		},
		clear: function(){
			//清空
			$(this).html('');
		}
	}

	$.fn.imagelist.parseOptions = function(target) {
		return $.extend({}, $.parser.parseOptions(target, ["data", "url", "imageHeight", {
			data: "array",
			url: "string",
			imageHeight: "number"
		}]));
	};
	$.fn.imagelist.defaults = {
		data: [],
		url: '',
		imageHeight: 100, 
		onLoadSuccess: function(data) {},
		onComplete:function(data){},
		onClick:function(item){
			console.log('onClick', item);
		},
		onDelete:function(item){
			console.log('onDelete', item);
		}
	}
	$.parser.plugins.push('imagelist');
})(jQuery);