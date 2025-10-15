/**
 * 
 */
function fetchCategories(){
	return new Promise((resolve, reject) => {
		wx.request({
			url: getApp().globalData.api_host + '/mp/uni_app/subjectCategories.html',
			data:{
				session_key: getApp().globalData.session_key
			},
			success:function(res){
				if(res.statusCode != 200){
					reject(res.statusCode);
				}
				if(res.data.code == 1){
					resolve(res.data.data);
				}else{
					reject(res.data.msg);
				}
			},
			fail:function(res){
				//网络连接错误
				reject(res.errMsg);
			}
		});
	});
}
function fetchSubjects(categoryId=0, kw=''){
	return new Promise((resolve, reject) => {
		wx.request({
			url: getApp().globalData.api_host + '/mp/uni_app/subjectsInCategory.html',
			data:{
				session_key: getApp().globalData.session_key,
				categoryId:categoryId,
				kw:kw
			},
			success:function(res){
				if(res.statusCode != 200){
					reject(res.statusCode);
				}
				if(res.data.code == 1){
					resolve(res.data.data);
				}else{
					reject(res.data.msg);
				}
			},
			fail:function(res){
				reject(res.errMsg);
			}
		});
	});
}
function fetchSubject(id){
	return new Promise((resolve, reject) => {
		wx.request({
			url: getApp().globalData.api_host + '/mp/uni_app/subjectDetail.html',
			data:{
				session_key: getApp().globalData.session_key,
				id:id
			},
			success:function(res){
				if(res.statusCode != 200){
					reject(res.statusCode);
				}
				if(res.data.code == 1){
					resolve(res.data.data);
				}else{
					reject(res.data.msg);
				}
			},
			fail:function(res){
				reject(res.errMsg);
			}
		});
	});
}
/****************************************************************************************************/
function createOrder(id, orderNo=''){
	return new Promise((resolve, reject) => {
		wx.request({
			url: getApp().globalData.api_host + '/mp/uni_app/createOrder.html',
			data:{
				session_key: getApp().globalData.session_key,
				id:id,
				order_no:orderNo
			},
			success:function(res){
				if(res.statusCode != 200){
					reject(res.statusCode);
				}
				if(res.data.code == 1){
					resolve(res.data.data);
				}else{
					reject(res.data.msg);
				}
			},
			fail:function(res){
				reject(res.errMsg);
			}
		});
	});
}
function queryOrder(orderNo=''){
	return new Promise((resolve, reject) => {
		wx.request({
			url: getApp().globalData.api_host + '/mp/uni_app/queryOrder.html',
			data:{
				session_key: getApp().globalData.session_key,
				order_no:orderNo
			},
			success:function(res){
				if(res.statusCode != 200){
					reject(res.statusCode);
				}
				if(res.data.code == 1){
					resolve(res.data.data);
				}else{
					reject(res.data.msg);
				}
			},
			fail:function(res){
				reject(res.errMsg);
			}
		});
	});
}
function regenOrder(orderNo=''){
	return new Promise((resolve, reject) => {
		wx.request({
			url: getApp().globalData.api_host + '/mp/uni_app/regenOrder.html',
			data:{
				session_key: getApp().globalData.session_key,
				order_no:orderNo
			},
			success:function(res){
				if(res.statusCode != 200){
					reject(res.statusCode);
				}
				if(res.data.code == 1){
					resolve(res.data.data);
				}else{
					reject(res.data.msg);
				}
			},
			fail:function(res){
				reject(res.errMsg);
			}
		});
	});
}
function generateOrderPay(orderNo=''){
	return new Promise((resolve, reject) => {
		wx.request({
			url: getApp().globalData.api_host + '/mp/uni_app/generateOrderPay.html',
			data:{
				session_key: getApp().globalData.session_key,
				order_no:orderNo
			},
			success:function(res){
				if(res.statusCode != 200){
					reject(res.statusCode);
				}
				if(res.data.code == 1){
					resolve(res.data.data);
				}else{
					reject(res.data.msg);
				}
			},
			fail:function(res){
				reject(res.errMsg);
			}
		});
	});
}
function queryOrderPay(orderNo=''){
	return new Promise((resolve, reject) => {
		wx.request({
			url: getApp().globalData.api_host + '/mp/uni_app/queryOrderPay.html',
			data:{
				session_key: getApp().globalData.session_key,
				order_no:orderNo
			},
			success:function(res){
				if(res.statusCode != 200){
					reject(res.statusCode);
				}
				if(res.data.code == 1){
					resolve(res.data.data);
				}else{
					reject(res.data.msg);
				}
			},
			fail:function(res){
				reject(res.errMsg);
			}
		});
	});
}
function applyAuthorizeCode(orderNo='', code=''){
	return new Promise((resolve, reject) => {
		wx.request({
			url: getApp().globalData.api_host + '/mp/uni_app/applyAuthorizeCode.html',
			data:{
				session_key: getApp().globalData.session_key,
				order_no:orderNo,
				code:code
			},
			success:function(res){
				if(res.statusCode != 200){
					reject(res.statusCode);
				}
				if(res.data.code == 1){
					resolve(res.data.data);
				}else{
					reject(res.data.msg);
				}
			},
			fail:function(res){
				reject(res.errMsg);
			}
		});
	});
}
export const subjectService = {
	fetchCategories,
	fetchSubjects,
	fetchSubject,
	createOrder,
	queryOrder,
	regenOrder,
	generateOrderPay,
	queryOrderPay,
	applyAuthorizeCode
};
