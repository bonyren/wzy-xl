/* globals Page */
import {common} from '../../services/common.js';
import {subjectService} from '../../services/subject.js';
function invokeWeixinPay(data){
    return new Promise((resolve, reject)=>{
        data.config.success = function(res){
          resolve();
        };
        data.config.fail = function(err){
          reject(err);
        };
        wx.requestPayment(data.config);
    });
}
Page({
    data: {
        currentPageUrl: '',
        goBackPath: '',
        uiDataReady: false,
        orderNo: '',
        orderAmount: ''
    },
    onLoad(options) {
        var that = this;
	    this.data.currentPageUrl = common.genCurrentPageUrl('/pages/pay/pay', options);
        that.data.goBackPath = decodeURIComponent(options.goBackPath || '');
        wx.showLoading({
            title: '加载中'
        });
        this.setData({
            orderNo: options.orderNo
        });
        subjectService.queryOrder(this.data.orderNo).then(function(data){
            that.setData({
                orderAmount: data.order_amount,
            });
            that.setData({
                uiDataReady: true
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
    },
    onPay(){
        var that = this;
        wx.showLoading({
            title: '加载中'
        });
        subjectService.generateOrderPay(that.data.orderNo).then(function(data){
            return invokeWeixinPay(data);
        }).then(()=>{
            return subjectService.queryOrderPay(that.data.orderNo);
        }).then(data=>{
            if(data){
                wx.showToast({
                    title: '支付成功',
                    icon: 'success',
                    complete(){
                        if(that.data.goBackPath){
                            wx.redirectTo({
                                url: that.data.goBackPath
                            });
                            return;
                        }
                        wx.redirectTo({
                            url: '/pages/report/report?orderNo='+encodeURIComponent(that.data.orderNo)
                        });
                    }
                });
            }else{
                wx.showToast({
                    title: '支付未完成',
                    icon: 'error'
                });
            }
        }).catch(err=>{
            if(err == 401){
                common.goToLogin(that.data.currentPageUrl);
                return;
            }
            //{errCode: 2, errMsg: '支付取消'}
            console.log('pay fail', err);
            if(typeof err === 'object' && err !== null && err.errMsg == "requestPayment:fail cancel"){
                return;
            }
            wx.showModal({
                title: '错误提示',
                content: JSON.stringify(err),
                showCancel: false,
                complete(){
                }
            });
        }).finally(()=>{
            wx.hideLoading();
        });
		},
		onAuthorizeCode(){
			var that = this;
			wx.showModal({
				title: '请输入授权码',
				editable: true,
				placeholderText: '授权码',
				success:function(res){
					if(res.cancel){
						//取消
						return;
					}
					let code = res.content;
					if(code.trim() == ''){
						wx.showToast({
							title: '授权码不能为空',
							icon: 'error'
						});
						return;
					}
					wx.showLoading({
							title: '加载中'
					});
					subjectService.applyAuthorizeCode(that.data.orderNo, code).then(data=>{
						wx.redirectTo({
							url: '/pages/report/report?orderNo='+encodeURIComponent(that.data.orderNo)
						});
					}, err=>{
						if(err == 401){
							common.goToLogin(that.data.currentPageUrl);
							return;
						}
						wx.showModal({
								title: '错误提示',
								content: JSON.stringify(err),
								showCancel: false,
								complete(){
								}
						});
					}).finally(()=>{
            wx.hideLoading();
					});
				}
			});
		},
    onHome(){
        wx.switchTab({
          url: '/pages/home/home'
        });
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
