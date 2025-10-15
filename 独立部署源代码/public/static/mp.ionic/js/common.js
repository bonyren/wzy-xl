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
}
GLOBAL.func.isValidUrl = function(urlString){
    var urlPattern = new RegExp('^(https?:\\/\\/)?'+ // validate protocol
        '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'+ // validate domain name
        '((\\d{1,3}\\.){3}\\d{1,3}))'+ // validate OR ip (v4) address
        '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // validate port and path
        '(\\?[;&a-z\\d%_.~+=-]*)?'+ // validate query string
        '(\\#[-a-z\\d_]*)?$','i'); // validate fragment locator
    return !!urlPattern.test(urlString);
}