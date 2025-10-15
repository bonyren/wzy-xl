var GLOBAL = {};
GLOBAL.namespace = function(str){
    var arr = str.split("."),o=GLOBAL;
    for(var i=((arr[0] == 'CACHE')?1:0); i<arr.length; i++){
        o[arr[i]] = o[arr[i]] || {};
        o=o[arr[i]];
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
}
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
}
GLOBAL.func.dateFilter = function(date){
    if(date == '0000-00-00' || date == '1970-01-01'){
        return '';
    }
    return date;
}
GLOBAL.func.dateTimeFilter = function(dateTime){
    if(dateTime == '0000-00-00 00:00:00' || dateTime == '1970-01-01 00:00:00'){
        return '';
    }
    return dateTime;
}
GLOBAL.func.addUrlParam = function(url, name, value){
    url += url.indexOf('?') === -1?'?':'&';
    url += encodeURIComponent(name) + '=' + encodeURIComponent(value);
    return url;
}
GLOBAL.func.escapeALinkStringParam = function(str) {
    if(!str){
        return str;
    }
    //转换半角单引号
    str = str.replace(/'/g, "\\'");
    str = str.replace(/"/g, "");
    return str;
}
GLOBAL.func.formatBoolean = function(val){
    if(val){
        return '<span class="badge badge-success">是</span>';
    }else{
        return '<span class="badge badge-warning">否</span>';
    }
};

(function (doc, win) {
    var docEl = doc.documentElement,
        tid,
        recalc = function () {
            var clientWidth = docEl.clientWidth;
            if (!clientWidth) return;
            if(clientWidth>=640){
                docEl.style.fontSize = '40px';
            }else{
                docEl.style.fontSize = 40 * (clientWidth / 750) + 'px';
            }
        };

    if (!doc.addEventListener) return;

     win.addEventListener('resize', function() {
        clearTimeout(tid);
        tid = setTimeout(recalc, 300);
    }, false);
    win.addEventListener('pageshow', function(e) {
        if (e.persisted) {
            clearTimeout(tid);
            tid = setTimeout(recalc, 300);
        }
    }, false);
    recalc();
})(document, window);

$(function() {
    $("body").height( $(window).height() );
    var clientHeight = document.body.clientHeight;
    var scrollObj = document.getElementsByClassName('weui-tab__bd')[0]
    if(!scrollObj) return;
    scrollObj.addEventListener('scroll', function(e) {
        e.preventDefault();
        var scrollTop = $(this).scrollTop();
        // console.log(scrollTop);
        if(scrollTop > clientHeight*0.3) {
            $('#toTop').show();
        }else{
            $('#toTop').hide();
        }
    },false)
    // 回到顶部
    $('#toTop').click(function(){
        var _obj = document.getElementsByClassName('weui-tab__bd')[0]
        $(_obj).animate({scrollTop:0}, 500);
    });
})

function getDay(day) {
    let today = new Date();
    let targetDay_milliseconds = today.getTime() + 1000*60*60*24*day;

    today.setTime(targetDay_milliseconds)//以毫秒设置today对象

    let target_year = today.getFullYear();
    let target_month = handleMonth(today.getMonth() + 1);
    let target_date = handleMonth(today.getDate())
    let target_day = handleWeek(today.getDay());

    return target_day + ' | ' + target_month + '-' + target_date;
}
function getWeekDay(day){
    let today = new Date();
    let targetDay_milliseconds = today.getTime() + 1000*60*60*24*day;

    today.setTime(targetDay_milliseconds)//以毫秒设置today对象
    return today.getDay();
}

function handleMonth(_mon) {
    let m = _mon;
    if(_mon.toString().length == 1){
        m = '0' + _mon
    }
    return m;
}

function handleWeek(_day){
    let _weekday = '';
    switch (_day) {
        case 0:
            _weekday = '周日';
            break;
        case 1:
            _weekday = '周一';
            break;
        case 2:
            _weekday = '周二';
            break;
        case 3:
            _weekday = '周三';
            break;
        case 4:
            _weekday = "周四";
            break;
        case 5:
            _weekday = '周五';
            break;
        case 6:
            _weekday = '周六';
            break;
        default:
            _weekday = '错误'
            break;
    }
    return _weekday;
}

function cellphoneExist(url, data) {
    var flag = true;
    $.ajax({
        type: 'POST',
        async:  false, 
        url: url,
        data: data,
        dataType: 'json',
        success: function(data){
            if (data.code == 2002) {
                flag = true;
            } else {
                flag = false;
            }
        },
        error: function(err){
            showTipMsg('当前网络不可用，请检查网络！');
        }
    });
    return flag;
}

function showTipMsg(msg) {
    $('.ui-poptips-cnt').html(msg);
    $('.ui-poptips').css('display', '-webkit-box');
    setTimeout(function(){
       $('.ui-poptips').fadeOut();
    }, 1000);
}

function showTipMsgNotClose(html) {
    $('.ui-poptips-cnt').replaceWith(html);
    $('.ui-poptips').css('display', '-webkit-box');
}

function showAlert(msg) {
    $('.ui-alert-bd').html(msg);
    $('.ui-alert').addClass('show');
}

function formatRate(num) {
    var demical = num.toString().split('.')[1];
    if (demical == undefined) {
        return num;
    } else {
        if (demical.length == 1) {
            return num.toFixed(1);
        } else {
            return num.toFixed(2);
        }   
    }
}
  
Date.prototype.format = function (format) {  
    var o = {  
        'M+': this.getMonth() + 1,  
        'd+': this.getDate(),  
        'h+': this.getHours(),  
        'm+': this.getMinutes(),  
        's+': this.getSeconds(),  
        'q+': Math.floor((this.getMonth() + 3) / 3),  
        'S': this.getMilliseconds()  
    }  
    if (/(y+)/.test(format)) {  
        format = format.replace(RegExp.$1, (this.getFullYear() + '').substr(4 - RegExp.$1.length));  
    }  
    for (var k in o) {  
        if (new RegExp('(' + k + ')').test(format)) {  
            format = format.replace(RegExp.$1, RegExp.$1.length == 1 ? o[k] : ('00' + o[k]).substr(('' + o[k]).length));  
        }  
    }  
    return format;  
}  
 
function getFormatDateByLong(l, pattern) {  
    return new Date(l).format(pattern); 
}  

function getFormatDateByTime(date,pattern) {
	var l = new Date(date).getTime();
	return new Date(l).format(pattern); 
}
  
function clearBox(errowmsg) {
    errowmsg.innerHTML = '';
}
function onlyEng(obj) {
    obj.value = obj.value.replace( /\D/g,'');
}
function numLetter(obj) {
    obj.value = obj.value.replace(/[^a-zA-Z0-9]/g,'');
} 
function telKeyPress(obj) {
    obj.value = obj.value.replace( /\D/g,'');
}
function telKeyUp(obj) {
    obj.value = obj.value.replace( /\D/g,'');
}
function smsKeyPress(obj) {
    obj.value = obj.value.replace( /\D/g,'');
}
function smsKeyUp(obj) {
    obj.value = obj.value.replace( /\D/g,'');
}
function pwdKeyPress(obj) {
    obj.value = obj.value.replace(/[^a-zA-Z0-9]/g,'');
}

function pwdKeyUp(obj) {
    obj.value = obj.value.replace(/[^a-zA-Z0-9]/g,'');
}

function visaKeyUp(obj) {
    obj.value = obj.value.replace( /\D/g,'');
}
function visaKeyPress(obj) {
    obj.value = obj.value.replace( /\D/g,'');
}

var fScrollTopHeight = function(){
    return document.documentElement && document.documentElement.scrollTop || document.body && document.body.scrollTop || 0;
};

var fClientHeight = function(){
    var clientHeight = 0;
    if (document.body.clientHeight && document.documentElement.clientHeight) {
        clientHeight = (document.body.clientHeight < document.documentElement.clientHeight) ? document.body.clientHeight : document.documentElement.clientHeight;
    } else {
        clientHeight = (document.body.clientHeight > document.documentElement.clientHeight) ? document.body.clientHeight : document.documentElement.clientHeight;
    }
    return clientHeight;
};

var fBodyHeight = function(){
    return Math.max(document.body.scrollHeight,document.documentElement.scrollHeight);
};

function AddDays(date,days) {
    var nd = new Date(date);
    nd = nd.valueOf();
    nd = nd + days * 24 * 60 * 60 * 1000;
    nd = new Date(nd);
    var y = nd.getFullYear();
    var m = nd.getMonth() + 1;
    var d = nd.getDate();
    if(m <= 9) m = '0' + m;
    if(d <= 9) d = '0' + d; 
    var cdate = y + '-' + m + '-' + d;
    return cdate;
}
function beforeSend(XMLHttpRequest) {
	var accessToken;
    if ($.cookie('accessToken')) {
        accessToken = $.cookie('accessToken');
        XMLHttpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        XMLHttpRequest.setRequestHeader('Authorization', 'Bearer ' + accessToken);
        XMLHttpRequest.setRequestHeader('Param', 'code=' + accessToken);
    }
}

$(function(){
    $('.ui-alert-sure').on('tap',function(){
        $('.ui-alert').removeClass('show');
    });
    $('.rate').each(function(){
        var str = $.trim($(this).text());
        var rate = new Number(str.substring(0, str.length - 1));
        var result = formatRate(rate);
        $(this).html(result + '%');
    });
    $('.increaseInterest').each(function(){
        var str = $.trim($(this).text());
        var rate = new Number(str);
        var result = formatRate(rate);
        $(this).html(result);
    })
});