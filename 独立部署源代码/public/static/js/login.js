 /**
     * 提示框
     * @param text
     * @param icon
     * @param hideAfter
     */
  function showMsg(text, icon, hideAfter) {

    var heading = "提示";
    
    $.toast({
        text: text,//消息提示框的内容。
        heading: heading,//消息提示框的标题。
        icon: icon,//消息提示框的图标样式。
        showHideTransition: 'fade',//消息提示框的动画效果。可取值：plain，fade，slide。
        allowToastClose: true,//是否显示关闭按钮。(true 显示，false 不显示)
        hideAfter: hideAfter,//设置为false则消息提示框不自动关闭.设置为一个数值则在指定的毫秒之后自动关闭消息提框
        stack: 1,//消息栈。同时允许的提示框数量
        position: 'mid-center',//消息提示框的位置：bottom-left, bottom-right,bottom-center,top-left,top-right,top-center,mid-center。
        textAlign: 'left',//文本对齐：left, right, center。
        loader: true,//是否显示加载条
        //bgColor: '#FF1356',//背景颜色。
        //textColor: '#eee',//文字颜色。
        loaderBg: '#ffffff',//加载条的背景颜色。
        beforeShow: function(){
        },
        afterShown: function () {
        },
        beforeHide: function () {
        },
        afterHidden: function () {
        }

        /*toast事件
        beforeShow 会在toast即将出现之前触发
        afterShown 会在toast出现后触发
        beforeHide 会在toast藏起来之前触发
        afterHidden 会在toast藏起来后被触发
        */
    });
}
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