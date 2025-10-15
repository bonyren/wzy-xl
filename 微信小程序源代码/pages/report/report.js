/* globals Page */
import {common} from '../../services/common.js';
import {testService} from '../../services/test.js';
function requestData(that){
    wx.showLoading({
        title: '加载中'
    });
    testService.generateReport(that.data.orderNo).then(function(data){
        if(data.showPay){
            wx.redirectTo({
                url: `/pages/pay/pay?orderNo=${that.data.orderNo}`
            });
            return;
        }
        that.setData({
            showPay: data.showPay,
            order: data.order,
            reportUrl: data.report_url
        });
        that.setData({
            uiDataReady:true
        });
    }).catch(err=>{
        if(err == 401){
            common.goToLogin(that.data.currentPageUrl);
            return;
        }
        wx.showModal({
            title: '错误提示',
            content: JSON.stringify(err),
            showCancel: false,
            complete(){
                wx.navigateBack();
            }
        });
    }).finally(()=>{
        wx.hideLoading();
    });
}
Page({
    data: {
        currentPageUrl: '',
        uiDataReady: false,
        orderNo: '',
        showPay: true,
        order: {order_amount:null},
        reportUrl: ''
    },
    onLoad(options) {
        this.data.currentPageUrl = common.genCurrentPageUrl('/pages/report/report', options);
        this.setData({
            orderNo: options.orderNo
        });
        requestData(this);
    },
    onReady() {
        // 监听页面初次渲染完成的生命周期函数
    },
    onShow() {
        // 监听页面显示的生命周期函数
    },
    onHide() {
        // 监听页面隐藏的生命周期函数
    },
    onUnload() {
        // 监听页面卸载的生命周期函数
    },
    onPullDownRefresh() {
        // 监听用户下拉动作
    },
    onReachBottom() {
        // 页面上拉触底事件的处理函数
    }
});
