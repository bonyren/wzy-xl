/* globals Page */
const app = getApp()
import { subjectService } from '../../services/subject.js';
function requestData(that) {
	wx.showLoading({
		title: '加载中'
	});
	subjectService.fetchSubject(that.data.subjectId).then(function (data) {
		data.current_price = Number(data.current_price).toFixed(2);
		that.setData({
			subjectData: data,
			uiDataReady: true
		});
	}).catch(err => {
		wx.showModal({
			title: '错误提示',
			content: JSON.stringify(err),
			showCancel: false,
			complete() {
				wx.navigateBack();
			}
		});
	}).finally(() => {
		wx.hideLoading();
	});
}
Page({
	data: {
		uiDataReady: false,
		reportDemoImagesVisible: false,
		subjectId: 0,
		subjectData: {
			name: '',
			subtitle: '',
			current_price: '',
			image_url: '',
			banner_img: '',
			subject_desc: '',
			subject_tip: '',
			team_brief: '',
			items: 0,
			participants: 0,
			order_no: '',
			report_demo_images: []
		}
	},
	onLoad(paramObj) {
		var that = this;
		this.setData({
			subjectId: paramObj.id
		});
		//requestData(this);
	},
	onShow() {
		requestData(this);
	},
	onHome() {
		wx.switchTab({
			url: '/pages/home/home',
		});
	},
	onReportDemo() {
		this.setData({
			reportDemoImagesVisible: true
		});
	},
	onReportDemoImagesClose() {
		this.setData({
			reportDemoImagesVisible: false
		});
	},
	onTestBegin() {
		var that = this;
		if (that.data.subjectData.subject_tip.trim() != '') {
			//测前提示
			wx.showModal({
				title: '提示',
				content: that.data.subjectData.subject_tip.trim(),
				showCancel: false,
				complete: (res) => {
					wx.navigateTo({
						url: `/pages/test/test?subjectId=${that.data.subjectId}&orderNo=${that.data.subjectData.order_no}`
					});
				}
			});
		} else {
			wx.navigateTo({
				url: `/pages/test/test?subjectId=${this.data.subjectId}&orderNo=${this.data.subjectData.order_no}`
			});
		}
	},
	onReady() {
		// 监听页面初次渲染完成的生命周期函数
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
	},
	onShareAppMessage() {
		return {
			title: this.data.subjectData.name,
			imageUrl: this.data.subjectData.image_url
		};
	},
	onShareTimeline() {
		return {
			title: this.data.subjectData.name,
			imageUrl: this.data.subjectData.image_url
		};
	},
});
