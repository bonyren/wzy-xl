// pages/home.js
const app = getApp()
import { homeService } from '../../services/home.js';
function requestData(that) {
	wx.showLoading({
		title: '加载中'
	});
	homeService.homeConfig().then(function (data) {
		that.setData({
			homeConfig: data
		});
		return homeService.subjectsPopular();
	}).then(function (data) {
		that.setData({
			subjectsPopular: [].concat(data)
		});
		return homeService.subjectsFeatured();
	}).then(function (data) {
		that.setData({
			subjectsFeatured: [].concat(data)
		});
		that.setData({
			uiDataReady: true
		});
	}).catch(err => {
		wx.showModal({
			title: '错误提示',
			content: JSON.stringify(err),
			showCancel: false,
			complete() {
			}
		});
	}).finally(() => {
		wx.hideLoading();
	});
}
Page({
	data: {
		uiDataReady: false,
		homeConfig: { shortcuts: [] },
		subjectsPopular: [],
		subjectsFeatured: []
	},

	/**
	 * 生命周期函数--监听页面加载
	 */
	onLoad(options) {
		var that = this;
		requestData(that);
	},

	/**
	 * 生命周期函数--监听页面初次渲染完成
	 */
	onReady() {

	},

	/**
	 * 生命周期函数--监听页面显示
	 */
	onShow() {

	},

	/**
	 * 生命周期函数--监听页面隐藏
	 */
	onHide() {

	},

	/**
	 * 生命周期函数--监听页面卸载
	 */
	onUnload() {

	},

	/**
	 * 页面相关事件处理函数--监听用户下拉动作
	 */
	onPullDownRefresh() {
		//requestData();
	},

	/**
	 * 页面上拉触底事件的处理函数
	 */
	onReachBottom() {

	},

	/**
	 * 用户点击右上角分享
	 */
	onShareAppMessage() {
		return {
			title: '为之易专业心理测量平台',
			imageUrl: '/images/logo.png'
		};
	},
	onShareTimeline() {
		return {
			title: '为之易专业心理测量平台',
			imageUrl: '/images/logo.png'
		};
	},
	onShortcutClick(e) {
		let categoryId = e.currentTarget.dataset.categoryId;
		let url = e.currentTarget.dataset.url;
		getApp().globalData.default_category_id = categoryId;
		wx.switchTab({
			url: url
		});
	},
	/**
	 * 换一批
	 */
	onSubjectPopularSwitch() {
		var that = this;
		wx.showLoading({
			title: '加载中'
		});
		homeService.subjectsPopular().then(function (data) {
			that.setData({
				subjectsPopular: [].concat(data)
			});
		}).catch(err => {
			wx.showModal({
				title: '错误提示',
				content: JSON.stringify(err),
				showCancel: false,
				complete() {
				}
			});
		}).finally(() => {
			wx.hideLoading();
		});
	},
	onSubjectFeaturedRefresh() {
		var that = this;
		wx.showLoading({
			title: '加载中'
		});
		homeService.subjectsFeatured().then(function (data) {
			that.setData({
				subjectsFeatured: [].concat(data)
			});
		}).catch(err => {
			wx.showModal({
				title: '错误提示',
				content: JSON.stringify(err),
				showCancel: false,
				complete() {
				}
			});
		}).finally(() => {
			wx.hideLoading();
		});
	},
	onSearchKw(e) {
		var that = this;
		let kw = e.detail.value.trim();
		if (kw == '') {
			return;
		}
		getApp().globalData.home_search_kw = kw;
		wx.switchTab({
			url: '/pages/index/index',
		});
	},
	onSearchKwClear(e) {
	},
	onSubjectClick(e) {
		let subjectId = e.currentTarget.dataset.subjectId;
		wx.navigateTo({
			url: `/pages/detail/detail?id=${subjectId}`
		});
	}
})