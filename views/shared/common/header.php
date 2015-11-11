<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Mapping the 4th</title>
    <?php echo js_tag('jquery'); ?>
    <?php echo js_tag('bootstrap.min'); ?>
    <?php echo head_css(); ?>
    



    <!-- Custom CSS -->
    <style>
        body {
            padding-top: 70px;
            /* Required padding for .navbar-fixed-top. Remove if using .navbar-static-top. Change if height of navigation changes. */
        }
    </style>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
    <script type="text/javascript">
		$(document).ready( function() {
			$('#login-button').on('click', function (e) {
				if ($('#login-tab').hasClass('active')) {
					if ($('#username').val() !== "" && $('#password').val() !== "") {
						//do login
                		var request = $.ajax({
							type: "POST",
							url: "http://localhost/m4j/incite/ajax/login",
							data: {"username": $('#username').val(), "password": $('#password').val()},
							success: function(data) {
								if (data) {
									alert("successful login");
									$('#login-signup-dialog').modal('hide');
								} else {
									alert("wrong username or password");
								}
							},
							error: function(e) {
								console.log(e.message);
							}
						});
					} else { 
						alert('username and password are both required');
					}
				} else { //then #signup-tab is active
					if ($('#newUsername').val() !== "" && $('#newPassword').val() !== "" && $('#confirmPassword').val() !== "" && $('#firstName').val !== "" && $('#lastName').val() !== "") {
						//do signup
                		var request = $.ajax({
							type: "POST",
							url: "http://localhost/m4j/incite/ajax/createaccount",
							data: {"username": username, "password": password, "fName": firstName, "lName": lastName, "priv": 1, "exp": 1},
							success: function(data) {
								if (data) {
									alert("successful login");
									$('#login-signup-dialog').modal('hide');
								} else {
									alert("wrong username or password");
								}
							},
							error: function(e) {
								console.log(e.message);
							}
						});
					} else { 
						alert('all fields are required');
					}
				}
			});
		});
    </script>

<div class="modal fade" id="login-signup-dialog" tabindex="-1" role="dialog" aria-labelledby="login-signup-dialog-label">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="login-signup-dialog-label">User Login/Sign-up</h4>
      </div>
      <div class="modal-body">
		<ul class="nav nav-tabs nav-justified nav-pills">
			<li class="active" id="login-tab"><a href="#tab1" data-toggle="tab">Login</a></li>
			<li id="signup-tab"><a href="#tab2" data-toggle="tab">Sign-up</a></li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane active" id="tab1">
				<form>
				  <div class="form-group">
					<label for="recipient-name" class="control-label">Username (email):</label>
					<input type="text" class="form-control" id="email" name="username">
				  </div>
				  <div class="form-group">
					<label for="message-text" class="control-label">Password:</label>
					<input type="password" class="form-control" id="password" name="password">
				  </div>
				</form>
			</div>
			<div class="tab-pane" id="tab2">
				<form>
				  <div class="form-group">
					<label class="control-label">Username (email):</label>
					<input type="text" class="form-control" id="newUsername" name="email">
				  </div>
				  <div class="form-group">
					<label for="message-text" class="control-label">Password:</label>
					<input type="password" class="form-control" id="newPassword" name="password">
				  </div>
				  <div class="form-group">
					<label for="message-text" class="control-label">Confirm Password:</label>
					<input type="password" class="form-control" id="confirmPassword" name="confirmPassword">
				  </div>
				  <div class="form-group">
					<label for="message-text" class="control-label">First Name:</label>
					<input type="text" class="form-control" id="firstName" name="firstName">
				  </div>
				  <div class="form-group">
					<label for="message-text" class="control-label">Last Name:</label>
					<input type="text" class="form-control" id="lastName" name="lastName">
				  </div>
				</form>
			</div>
		</div>
      </div>
      <div class="modal-footer">
		<button type="button" class="btn btn-primary" id="login-button">Submit</button>
      </div>
    </div>
  </div>
</div>
    <!-- Navigation -->
<!--
    <button type="button" class="btn btn-default" onclick="loginForm()" id="loginButton">Login</button>
    <div id="passwordForm" style="visibility: hidden">
        <form id="passwordForm">
            <br />
            <input type="text" placeholder="Email" id="username">
            <input type="password" placeholder="Password" id="password">
            <input type="button" value="Submit" onclick="checkPassword()">
        </form>
    </div>
    <button type="button" class="btn btn-default" onclick="newAccountForm()" id="accountButton">Create Account</button>
    <div id="accountForm" style="visibility: hidden">
        <form id="accountForm">
            <br />
            <input type="text" placeholder="Email" id="newEmail">
            <input type="text" placeholder="First Name" id="fName">
            <input type="text" placeholder="Last Name" id="lName">
            <input type="password" placeholder="Password" id="newPassword">
            <input type="password" placeholder="Confirm Password" id="confirmPassword">
            <input type="button" value="Submit" onclick="createAccount()">
        </form>
    </div>
-->
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <a class="navbar-brand" href="/m4j/incite">Incite of Mapping the Fourth</a>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav">
            <form class="navbar-form navbar-left" role="search">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Location" name="location">
                    <input type="text" class="form-control" placeholder="Time" name="time">
                    <input type="text" class="form-control" placeholder="Keywords" name="keywords">
                    <select class="form-control" name="task">
                        <option value="">Select a task</option>
                        <option value="transcribe">Transcribe</option>
                        <option value="tag">Tag</option>
                        <option value="connect">Connect</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-default">Discover</button>
            </form>
			</ul>
<?php if (isset($_SESSION['IS_LOGIN_VALID']) && $_SESSION['IS_LOGIN_VALID'] == true): ?>
			<ul class="nav navbar-nav navbar-right">
				<li><a href="user_profile!"><?php echo $_SESSION['USER_DATA'][1]; //first name ?></a></li>
				<li><a href="logout!">Logout</a></li>
			</ul>
<?php else: ?>
			<ul class="nav navbar-nav navbar-right">
				<li><a href="" data-toggle="modal" data-target="#login-signup-dialog">Login/Sign-up</a></li>
			</ul>

<?php endif; ?>

            <!-- discussion
<ul class="nav navbar-nav">
<li>
    <a href="/m4j/incite/documents/transcribe">Transcribe</a>
</li>
<li>
    <a href="/m4j/incite/documents/tag">Tag</a>
</li>
<li>
    <a href="/m4j/incite/documents/connect">Connect</a>
</li>
<li>
    <a href="/m4j/incite/discussions">Discuss</a>
</li>
<li> 
    <a href="./discussions">Discuss</a>
</li>
            -->
            <!-- discover
<li> 
<a href="./discover">Discover</a>
</li>
            -->
            </ul>
        </div>

        <!-- /.navbar-collapse -->
    </div>

    <!-- /.container -->
</nav>
