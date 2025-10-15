function genCurrentPageUrl(path, options = {}){
	if(path == ''){
		return '';
	}
	let queryString = '';
	for (var key in options) {
		if (queryString) queryString += '&';
		queryString += key;
		queryString += '=';
		queryString += encodeURIComponent(options[key]);
	}
	if (queryString) {
		return path + '?' + queryString;
	} else {
		return path;
	}
}
function goToLogin(goBackPath=''){
	if (goBackPath == '') {
		wx.navigateTo({
			url: '/pages/login/login?goBackPath='
		});
	} else {
		wx.redirectTo({
			url: '/pages/login/login?goBackPath=' + encodeURIComponent(goBackPath)
		});
	}
}
export const common = {
	genCurrentPageUrl,
	goToLogin
};