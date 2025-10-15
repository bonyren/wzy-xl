// pages/category/category.js
const app = getApp();
import { categoryService } from '../../services/category.js';
import { subjectService } from '../../services/subject.js';
Page({
	/**
	 * 页面的初始数据
	 */
	data: {
		categoryId: 0,
		categoryName: '',
		categoryImage: '',
		subjects: []
	},

	/**
	 * 生命周期函数--监听页面加载
	 */
	onLoad(options) {
		let that = this;
		var categoryId = options.categoryId;
		that.data.categoryId = categoryId;
		wx.showLoading({
			title: '加载中'
		});
		categoryService.fetchCategory(categoryId).then(data=>{
			that.setData({
				categoryName: data.name,
				categoryImage: data.img_url
			});
			wx.setNavigationBarTitle({
				title: that.data.categoryName + '量表'
			});
			return subjectService.fetchSubjects(categoryId);
		}).then(function (data) {
			data.forEach((item) => {
				item.current_price = Number(item.current_price).toFixed(2);
			});
			that.setData({
				subjects: [].concat(data)
			});
		}).catch(err => {
			wx.showToast({
				title: err,
				icon: 'error'
			});
		}).finally(() => {
			wx.hideLoading();
		});
	},
	onSubjectClick(e) {
		let subjectId = e.currentTarget.dataset.subjectId;
		wx.navigateTo({
			url: `/pages/detail/detail?id=${subjectId}`
		});
	},
	onMoreSubjects(e){
		wx.switchTab({
			url: '/pages/home/home',
		});
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
			title: this.data.categoryName,
			imageUrl: this.data.categoryImage
		};
	},
	onShareTimeline(){
		return {
			title: this.data.categoryName,
			imageUrl: this.data.categoryImage
		};
	}
})