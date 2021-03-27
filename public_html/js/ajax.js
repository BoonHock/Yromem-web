var xhr = null;

function doAjax(getPost, url, data, doBefore, doSuccess) {
	if (xhr != null) {
		xhr.abort();
		xhr = null;
	}

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