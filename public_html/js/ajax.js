var xhr = null;

function doAjax(getPost, url, data, doBefore, doSuccess) {
	if (xhr != null) {
		xhr.abort();
		xhr = null;
	}

	if (window.location.host != "localhost") {
		url = "https://incupedev.000webhostapp.com/" + url
	}

	console.log(url);

	xhr = $.ajax({
		type: getPost,
		url: url,
		data: data,
		cache: false,
		beforeSend: function () {
			doBefore();
		},
		success: function (responseText) {
			doSuccess(responseText);
			xhr = null;
		},
		error: function () {
			// FAILED REQUEST MAY BE DUE TO ABORTED AJAX CALL
		}
	});
}