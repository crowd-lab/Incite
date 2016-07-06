<!DOCTYPE html>
<html lang="en">
<head>
    <?php
        include(dirname(__FILE__) . '/../common/header.php');
    ?>

    <script type="text/javascript">

        $(document).ready(function () {
            <?php
                if (isset($_SESSION['incite']['message'])) {
                    echo "notifyOfSuccessfulActionNoTimeout('" . $_SESSION["incite"]["message"] . "');";
                    unset($_SESSION['incite']['message']);
                }

            ?>
            $('#edit-submit-btn').on('click', attemptToEditProfile);
            populateGroups();
            redirectToProfilePage();

        });


        function populateGroups() {
            var span;

            <?php foreach ((array) $this->groups as $group): ?>

                if ($("#groups-list span").length === 0) {
                    span = $('<span class="group-member-link"></span>');
                } else {
                    span = $('<span class="group-member-link">, </span>');
                }

                span.append(createGroupLink(<?php echo sanitizeStringInput($group['name']); ?>.value, <?php echo $group['id']; ?>));

                $('#groups-list').append(span);
            <?php endforeach; ?>
        };

        function createGroupLink(groupname, groupid) {
            return $('<span class="group-link"><a href="<?php echo getFullInciteUrl(); ?>/groups/view/'+groupid+'" target="_BLANK">' + groupname + '</a></span>');
        };




    	/**
    	* Send back to the profile page when "Cancel" button is clicked.
    	*/
    	function redirectToProfilePage(){
    		$('#cancel-btn').click(function(event){
    			var url = "<?php echo getFullInciteUrl() ?>";
    			window.location.href = url;
    		});
    	};


    	/**
    	* Ajax method that send requst to update profile info.
    	*/
    	function editProfileAjaxRequest() {
    		var request = $.ajax({
    			type: "POST",
    			url: "<?php echo getFullInciteUrl().'/ajax/editaccount'; ?>",
    			data: {"password":
    			$('#editNewPassword').val(), "fName": $('#editNewFirstName').val(), "lName": $('#editNewLastName').val()},
    			success: function (response) {
    				data = response.trim();
    				console.log(response);
    				if (data === "true") {

    					createAlertInEditProfile("User informattion succesfully changed", false);

    					setTimeout(function () {
    						location.reload();
    					}, 1500);
    				} else {
    					createAlertInEditProfile("User information NOT succesfully changed", true);
    				}
    			},
    			error: function(e) {
    				console.log(e.message);
    			}

    		});
    	};
    	/**
    	Creating an alert below the form
    	*/
    		function createAlertInEditProfile(displayMessage, isError){
    			var loginDiv = document.getElementById("editprofile-error-display");
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

    			var submitButton = document.getElementById("edit-submit-btn");
    			loginDiv.insertBefore(usernameError, submitButton);
    		};
    	/**
    	* Check to see if user entered all the information that
    	*/
    	function attemptToEditProfile() {
    		if ($('#editNewPassword').val() !== "" && $('#editNewConfirmPassword').val() !== "" && $('#editNewFirstName').val() !== "" && $('#editNewLastName').val() !== "") {
    			if ($('#editNewPassword').val() !== $('#editNewConfirmPassword').val()) {
    				createAlertInEditProfile('"Password" and "Confirm Password" fields do not match', true);
    				return;
    			}
    			editProfileAjaxRequest();
    		}
    		else{
    			createAlertInEditProfile("All fields are required", true);
    		}
    	};

    </script>

    <style>

        #userprofile-header {
            text-align: center;
        }

        .group-link {
            margin-right: 0px;
        }



    </style>
</head>

<body>
  <div id="userprofile-header">
        <?php

        echo '<h1> Email: '. $this->user['email'] . '</h1>';
        ?>


        <div>
            <p id="groups-list">Belongs to group(s): </p>
        </div>
    </div>
    <?php if($this->user['id'] == $_SESSION['Incite']['USER_DATA']['id']){?>

		<div class="col-md-4 col-md-offset-4">

			<div class="contents">
				<?php
				$email = $this->user['email'];
				$first = $this->user['first_name'];
				$last = $this->user['last_name'];
				?>


				<form>
					<div class="form-group">
						<label for="message-text" class="control-label">First Name:</label>
						<input type="text" class="form-control" id="editNewFirstName" name="firstName" value="<?php echo $first ?>">
					</div>
					<div class="form-group">
						<label for="message-text" class="control-label">Last Name:</label>
						<input type="text" class="form-control" id="editNewLastName" name="lastName" value="<?php echo $last ?>" >
					</div>
                    <div class="form-group">
                        <label for="message-text" class="control-label">Password:</label>
                        <input type="password" class="form-control" id="editNewPassword" name="password" placeholder="Enter your password to confirm any changes">
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="control-label">Confirm Password:</label>
                        <input type="password" class="form-control" id="editNewConfirmPassword" name="confirmPassword" placeholder="Enter the password again for verification">
                    </div>
					<div class="footer" id="editprofile-error-display" display="block">
						<button type="button" class="btn btn-primary" id="edit-submit-btn" style="float: left; display:inline;">Edit Profile</button>
					</div>
				</form>

			</div>

			<button type="button" class="btn btn-primary" id="cancel-btn" style="float: right; display:inline;" >Cancel</button>

		</div>
		<?php } ?>

</body>
</html>
