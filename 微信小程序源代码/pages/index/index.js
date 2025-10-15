/**
 * @file index.js
 * @author swan
 */
const app = getApp()
import {subjectService} from '../../services/subject.js';
Page({
    data: {
        uiDataReady:false,
        scrollTop: 0,
        categoryId:0,
        kw:'',
        categories: [],
        subjects:[],
        subjectsShow:[],
        page:1,
        pageSize:10,
        searchingKw:false
    },
    // 监听页面加载的生命周期函数
    onLoad() {
    },
    onShow(){
        var that = this;
        that.setData({
          categoryId:getApp().globalData.default_category_id || 0,
          kw: getApp().globalData.home_search_kw || ''
        });
        that.setData({
          searchingKw: that.data.kw == ''?false:true
        });
        //reset
        getApp().globalData.default_category_id = 0;
        getApp().globalData.home_search_kw = '';

        wx.showLoading({
            title: '加载中'
        });
        subjectService.fetchCategories().then(function(data){
            that.setData({
                categories: [].concat(data)
            });
            return subjectService.fetchSubjects(that.data.categoryId, that.data.kw);
        }).then(function(data){
            data.forEach((item)=>{
                item.current_price = Number(item.current_price).toFixed(2);
            });
            that.setData({
                page:1,
                subjects: [].concat(data)
            });
            that.setData({
                subjectsShow: that.data.subjects.slice(0, that.data.page*that.data.pageSize),
                uiDataReady: true
            });
        }).catch(err=>{
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
    onSearchKw: function(e){
        //console.log('onSearchKw', e.detail.value);
        var that = this;
        let kw = e.detail.value.trim();
        if(kw == ''){
            this.setData({
                searchingKw: false,
                scrollTop:0,
                categoryId:0,
                kw: ''
            });
        }else{
            this.setData({
                searchingKw: true,
                scrollTop:0,
                categoryId:0,
                kw: kw
            });
        }
        wx.showLoading({
            title: '加载中'
        });
        subjectService.fetchSubjects(that.data.categoryId, that.data.kw).then(function(data){
            data.forEach((item)=>{
                item.current_price = Number(item.current_price).toFixed(2);
            });
            that.setData({
                page:1,
                subjects: [].concat(data)
            });
            that.setData({
                subjectsShow: that.data.subjects.slice(0, that.data.page*that.data.pageSize),
                uiDataReady: true
            });
        }).catch(err=>{
            wx.showModal({
                title: '错误提示',
                content: String(err),
                showCancel: false,
                complete(){
                }
            });
        }).finally(()=>{
            wx.hideLoading();
        });
    },
    onSearchKwClear:function(){
        console.log('onSearchKwClear');
        var that = this;
        this.setData({
            searchingKw: false,
            scrollTop:0,
            categoryId:0,
            kw:''
        });
        wx.showLoading({
            title: '加载中'
        });
        subjectService.fetchSubjects(that.data.categoryId, that.data.kw).then(function(data){
            data.forEach((item)=>{
                item.current_price = Number(item.current_price).toFixed(2);
            });
            that.setData({
                page:1,
                subjects: [].concat(data)
            });
            that.setData({
                subjectsShow: that.data.subjects.slice(0, that.data.page*that.data.pageSize),
                uiDataReady: true
            });
        }).catch(err=>{
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
    clickTab: function (e) {
        var that = this;
        this.setData({
            categoryId: e.currentTarget.dataset.category,
            kw: ''
        });
        wx.showLoading({
            title: '加载中'
        });
        subjectService.fetchSubjects(that.data.categoryId, that.data.kw).then(function(data){
            data.forEach((item)=>{
                item.current_price = Number(item.current_price).toFixed(2);
            });
            that.setData({
                subjects: [].concat(data),
                page: 1
            });
            that.setData({
                subjectsShow: that.data.subjects.slice(0, that.data.page*that.data.pageSize)
            });
        }).catch(err=>{
            wx.showToast({
                title:err,
                icon:'error'
            });
        }).finally(()=>{
            wx.hideLoading();
        });
    },
    onSubjectClick(e){
        let subjectId = e.currentTarget.dataset.subjectId;
        wx.navigateTo({
            url: `/pages/detail/detail?id=${subjectId}`
        });
    },
    onShowMoreSubjects(e){
        if(this.data.subjects.length >= this.data.subjectsShow.length){
            this.setData({
                page: this.data.page + 1,
            });
            this.setData({
                subjectsShow: this.data.subjects.slice(0, this.data.page*this.data.pageSize)
            });
        }
    },
    onShareAppMessage() {
      return {
        title: '为之易心理测量-量表分类',
        imageUrl: '/images/logo.png'
      };
    },
    onShareTimeline(){
      return {
        title: '为之易心理测量-量表分类',
        imageUrl: '/images/logo.png'
      };
    }
})
