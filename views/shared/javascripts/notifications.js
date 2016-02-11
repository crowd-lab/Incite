function notifyOfSuccessfulActionNoTimeout(displayMessage) {
	notif({
		msg: "<b>Success: </b> " + displayMessage,
		type: "success",
		clickable: true,
		autohide: false,
		multiline: true,
		width: 550
    });
};

function notifyOfSuccessfulActionWithTimeout(displayMessage) {
	notif({
		msg: "<b>Success: </b> " + displayMessage,
		type: "success",
		timeout: 2000,
		multiline: true,
		width: 550
    });
};

function notifyOfErrorInForm(errorMessage) {
	notif({
		msg: "<b>Error: </b> " + errorMessage,
		type: "error",
		timeout: 5000,
		width: 550,
		multiline: true
	});
};

function notifyOfRedirect(displayMessage) {
	notif({
		msg: displayMessage,
		type: "info",
		clickable: true,
		autohide: false,
		multiline: true,
		width: 550
	});
};