<!DOCTYPE html>
<html lang="en">
<head>

	<?php

	include(dirname(__FILE__) . '/../common/header.php');
	?>

	<script src="../../plugins/Incite/views/shared/javascripts/js.cookie.js"></script>
	<script type="text/javascript">

	$(document).ready(function () {

		$('#forgot-submit-btn').on('click', attemptToSendNewPassword);
		var cookieval = Cookies.get('name');
		if(cookieval !== undefined){
			document.getElementById("forgotpwUsername").value = cookieval;Cookies.remove('name');
		}

	});


	/**
	Sends a request to create a new password, update database, and send email to the user
	*/

	function setNewPasswordThenEmailRequest(){

		var request = $.ajax({
			type: "POST",
			url: "<?php echo getFullInciteUrl().'/ajax/newpw'; ?>",
			data: {"username": $('#forgotpwUsername').val()},
			success: function (response) {
				data = response.trim();
				if (data === "true") {
					createAlertInForgotPW("Email with a new password should be in your inbox soon!", false);

					setTimeout(function () {
						location.reload();
					}, 1500);

				}
				else if(data === "notexist"){
					createAlertInForgotPW("Please check your username again.", true);
					console.log("Please check your username");
				}
				else{
					console.log('Password not successfully changed.'+ data);

				}
			},
			error: function (e) {
				console.log(e.message);
			}
		});
		return request;
	};


/**
Creating an alert below the form
*/
	function createAlertInForgotPW(displayMessage, isError){
		var loginDiv = document.getElementById("error-display");
		if (document.getElementById("errorView") !== null)
		{
			var x = document.getElementById("errorView");
			loginDiv.removeChild(x);
		}
		var usernameError = document.createElement('div');
		var textNode = document.createTextNode(displayMessage);
		usernameError.style.textAlign = "center";
		usernameError.appendChild(textNode);

		usernameError.id = "errorView";

		if (isError) {
			usernameError.className = "alert alert-block alert-danger messages error";
		} else {
			usernameError.className = "alert alert-block alert-success messages status";
		}

		var submitButton = document.getElementById("forgot-submit-btn");
		loginDiv.insertBefore(usernameError, submitButton);
	};

/**
* Method for checking whether a user input something in the field or not.
*/
function attemptToSendNewPassword() {
		if ($('#forgotpwUsername').val() !== "" ) {
			setNewPasswordThenEmailRequest();

		}
		else{
			createAlertInForgotPW("Enter your Email", true);
		}

	};


	</script>
</head>

<body>

	<div class="col-md-4 col-md-offset-4">

		<div class="contents">

			<form>
				<div class="form-group">
					<label class="control-label">What's your email?
					</label>
					<input type="text" class="form-control" id="forgotpwUsername" name="email">
				</div>

			</form>
			<div class="footer" id="error-display" display="block">
				<button type="button" class="btn btn-primary" id="forgot-submit-btn">Send me a new password!</button>
			</div>
		</div>

	</div>
</body>
</html>
