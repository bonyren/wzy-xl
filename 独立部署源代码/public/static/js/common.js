var GLOBAL = {};
GLOBAL.namespace = function(str){
    var arr = str.split("."),o=GLOBAL;
    for(var i=((arr[0] == 'CACHE')?1:0); i<arr.length; i++){
        o[arr[i]] = o[arr[i]] || {};
        o=o[arr[i]];
    }
};
/**********************************************************************************************************************/
GLOBAL.namespace('validate');
GLOBAL.validate.mobile = function(value){
    var re = /^1[23456789]\d{9}$/;
    if(re.test(value)){
        return true;
    }else{
        return false;
    }
};
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
GLOBAL.namespace('func');
GLOBAL.func.random = function(min, max){
    var Range = max - min;
    var Rand = Math.random();
    return(min + Math.round(Rand * Range));
};
GLOBAL.func.moneyFormat = function(num){
    if(!num){
        return '';
    }
    if(typeof(num) == 'number'){
        num = num.toFixed(2);
    }
    var numStr = num.toString();
    numStr = numStr.replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
    return numStr;
};
GLOBAL.func.byteFormat = function(num){
    if(!num){
        return '';
    }
    if(typeof(num) == 'number'){
        num = num.toFixed(2);
    }
    var numStr = num.toString();
    numStr = numStr.replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
    numStr = numStr.replace('.00', '');
    return numStr;
};
GLOBAL.func.dateFilter = function(date){
    if(date == '0000-00-00' || date == '1970-01-01'){
        return '';
    }
    return date;
};
GLOBAL.func.dateTimeFilter = function(dateTime){
    if(dateTime == '0000-00-00 00:00:00' || dateTime == '1970-01-01 00:00:00'){
        return '';
    }
    return dateTime;
};
GLOBAL.func.addUrlParam = function(url, name, value){
    url += url.indexOf('?') === -1?'?':'&';
    url += encodeURIComponent(name) + '=' + encodeURIComponent(value);
    return url;
};
GLOBAL.func.escapeALinkStringParam = function(str) {
    if(!str){
        return '';
    }
	//转换半角单引号
	str = str.replace(/'/g, "\\'");
    str = str.replace(/"/g, "");
    str = str.replace(/\\/g, "\\");
	str = str.replace(/\r\n/g, "");
	return str;
};
GLOBAL.func.formatBoolean = function(val){
    if(val){
        return '<span class="badge badge-success">是</span>';
    }else{
        return '<span class="badge badge-warning">否</span>';
    }
};
GLOBAL.func.formatImage = function(val){
    if($.trim(val) == ''){
        //val = '/static/img/empty-image.png';
        return '';
    }
    return '<img class="my-1" src="' + val + '" style="max-width:100px;">';
}
GLOBAL.func.hstyleOpt = function(){
    return DG_ROW_CSS.rowSuc;
};
GLOBAL.func.formatDouble2 = function(val){
    return val.toFixed(2);
};
GLOBAL.func.isValidUrl = function(urlString){
    var urlPattern = new RegExp('^(https?:\\/\\/)?'+ // validate protocol
        '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'+ // validate domain name
        '((\\d{1,3}\\.){3}\\d{1,3}))'+ // validate OR ip (v4) address
        '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // validate port and path
        '(\\?[;&a-z\\d%_.~+=-]*)?'+ // validate query string
        '(\\#[-a-z\\d_]*)?$','i'); // validate fragment locator
    return !!urlPattern.test(urlString);
};
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
var DEFAULT_DB_DATE_VALUE = '0000-00-00';
var DEFAULT_DB_DATETIME_VALUE = '0000-00-00 00:00:00';
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
var DG_ROW_CSS = {
    rowGray: 'color:#999;background-color:#F3F3F3;',
    rowWarn: 'color:#FF0000;background:#FFB90F;',
    rowError: 'color:#FF0000;background:#FFF8DC;',
    rowDel: 'text-decoration:line-through;background:#EEEEEE;',
	rowInfo: 'color: #0c5460;background-color: #d1ecf1;border-color: #bee5eb;',
    rowSuc: 'color:#000000;background-color:#f5f7d6;'
};

function getEasyuiComponentType(target){
	var plugins = $.parser.plugins;
	for(var i=plugins.length-1; i>=0; i--){
		if ($(target).data(plugins[i])){
			return plugins[i];
		}
	}
	return null;
}
var HtmlUtil = {
    /*1.用浏览器内部转换器实现html转码*/
    htmlEncode:function (html){
        /*
        //1.首先动态创建一个容器标签元素，如DIV
        var temp = document.createElement ("div");
        //2.然后将要转换的字符串设置为这个元素的innerText(ie支持)或者textContent(火狐，google支持)
        (temp.textContent != undefined ) ? (temp.textContent = html) : (temp.innerText = html);
        //3.最后返回这个元素的innerHTML，即得到经过HTML编码转换的字符串了
        var output = temp.innerHTML;
        temp = null;
        return output;*/
        return html
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    },
    /*2.用浏览器内部转换器实现html解码*/
    htmlDecode:function (text){
        //1.首先动态创建一个容器标签元素，如DIV
        var temp = document.createElement("div");
        //2.然后将要转换的字符串设置为这个元素的innerHTML(ie，火狐，google都支持)
        temp.innerHTML = text;
        //3.最后返回这个元素的innerText(ie支持)或者textContent(火狐，google支持)，即得到经过HTML解码的字符串了。
        var output = temp.innerText || temp.textContent;
        temp = null;
        return output;
    }
};
GLOBAL.namespace('HelperDialog');
var QT = {
    util:{
        uuid:function(len, radix){
            var chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'.split('');
            var uuid = [], i;
            radix = radix || chars.length;
            if (len) {
                // Compact form
                for (i = 0; i < len; i++) uuid[i] = chars[0 | Math.random()*radix];
            } else {
                // rfc4122, version 4 form
                var r;
                // rfc4122 requires these characters
                uuid[8] = uuid[13] = uuid[18] = uuid[23] = '-';
                uuid[14] = '4';
                // Fill in random data.  At i==19 set the high bits of clock sequence as
                // per rfc4122, sec. 4.1.5
                for (i = 0; i < 36; i++) {
                    if (!uuid[i]) {
                        r = 0 | Math.random()*16;
                        uuid[i] = chars[(i == 19) ? (r & 0x3) | 0x8 : r];
                    }
                }
            }
            return uuid.join('');
        },
    },
    helper:{
        genDialogId:function(dialogId){
            if (!dialogId) {
                dialogId = QT.util.uuid(8);
            }
            if (!$('#'+dialogId).length) {
                $('#dialog-uuid-replace').html('<div id="'+dialogId+'"></div>');
            }
            return $('#'+dialogId);
        },
        dialog:function(title,href,isSubmit,callback,width,heigth,dialogId,icon){
            var $dialog = QT.helper.genDialogId(dialogId?dialogId:'qt-helper-dialog');
            var btns = [];
            if (isSubmit) {
                btns.push({
                    text:'提交',
                    iconCls:iconClsDefs.ok,
                    handler: function(){
                        if (typeof GLOBAL.HelperDialog.submit == 'function') {
                            GLOBAL.HelperDialog.submit(href,$dialog,function () {
                                if (callback) {
                                    callback($dialog);
                                }
                                GLOBAL.HelperDialog = {};
                            });
                        } else {
                            if (callback) {
                                callback($dialog);
                            }
                        }
                    }
                });
            }
            btns.push({
                text:isSubmit?'取消':'关闭',
                iconCls:iconClsDefs.cancel,
                handler: function(){
                    GLOBAL.HelperDialog = {};
                    $dialog.dialog('close');
                    if (!isSubmit && callback) {
                        callback();
                    }
                }
            });
            $dialog.dialog({
                title: title,
                iconCls: icon ? icon : 'fa fa-pencil-square',
                width: width ? width : 1000,
                height: heigth ? heigth : '95%',
                href: href,
                modal: true,
                border:false,
                buttons:btns
            });
            $dialog.dialog('center');
        },
        view:function (params) {
            var options = {
                title:params.title ? params.title : '详情',
                iconCls:params.iconCls ? params.iconCls :'fa fa-eye',
                href:'',
                width:'90%',
                height:'95%',
                modal: true
            };
            if (params.url) {
                options.href = params.url;
            }
            var $dialog;
            if (params.dialog) {
                params.dialog = params.dialog.replace('#','');
                $dialog = QT.helper.genDialogId(params.dialog);
            } else {
                $dialog = QT.helper.genDialogId('qt-helper-dialog');
            }
            options.buttons = [{
                text:'关闭',
                iconCls:iconClsDefs.cancel,
                handler: function(){
                    $dialog.dialog('close');
                }
            }];
            $.extend(options,params);
            $dialog.dialog(options);
            $dialog.dialog('center');
        }
    },
    filePreview:function(attachmentId,newTab){
        if ('undefined' === typeof newTab) {
            newTab = 1;
        }
        var url = SITE_URL + '/index/Attachments/previewAttach?attachmentId='+attachmentId+'&newTab='+newTab;
        if (newTab) {
            window.open(url);
        } else {
            QT.helper.view({title:'附件预览',url:url,width:'100%',height:'100%',dialog:'file-preview-dialog'});
        }
    }
};

(function($){
    function setHeight(target){
        var opts = $(target).textbox('options');
        $(target).next().css({
            height: '',
            minHeight: '',
            maxHeight: ''
        });
        var tb = $(target).textbox('textbox');
        tb.css({
            height: 'auto',
            minHeight: opts.minHeight,
            maxHeight: opts.maxHeight
        });
        tb.css('height', 'auto');
        var height = tb[0].scrollHeight;
        tb.css('height', height+'px');
    }

    function autoHeight(target){
        var opts = $(target).textbox('options');
        var onResize = opts.onResize;
        opts.onResize = function(width,height){
            onResize.call(this, width, height);
            setHeight(target);
        };
        var tb = $(target).textbox('textbox');
        tb.unbind('.tb').bind('keydown.tb keyup.tb', function(e){
            setHeight(target);
        });
        setHeight(target);
    }
    $.extend($.fn.textbox.methods, {
        autoHeight: function(jq){
            return jq.each(function(){
                autoHeight(this);
            });
        }
    });
})(jQuery);