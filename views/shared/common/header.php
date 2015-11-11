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
    

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">


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
        function loginForm()
        {
            if (document.getElementById('passwordForm').style.visibility == "visible")
            {
                document.getElementById('passwordForm').style.visibility = "hidden";
            }
            else
            {
                document.getElementById('passwordForm').style.visibility = "visible";
            }
            
        }
        function newAccountForm()
        {
            if (document.getElementById('accountForm').style.visibility == "visible")
            {
                document.getElementById('accountForm').style.visibility = "hidden";
            }
            else
            {
                document.getElementById('accountForm').style.visibility = "visible";
            }
            
        }
        function checkPassword()
        {
            var username = document.getElementById('username').value;
            var password = document.getElementById('password').value;
            
            if (username != "" && password != "")
            {
                var request = $.ajax({
                type: "POST",
                url: "http://localhost/m4j/incite/ajax/login",
                data: {username: username, password: password},
                success: function(data) {
			//called when successful
			if (data)
                        {
                            alert("successful login");
                            document.getElementById('loginButton').style.visiblity = 'hidden';
                            document.getElementById('loginButton').style.display = 'none';
                            document.getElementById('accountButton').style.visiblity = "hidden";
                            document.getElementById('accountButton').style.display = 'none';
                            document.getElementById('passwordForm').style.visibility = "hidden";
                            document.getElementById('accountForm').style.visibility = "hidden";
                        }
                        else
                        {
                            alert("wrong username or password");
                        }
		  },
		  error: function(e) {
			//called when there is an error
			console.log(e.message);
		  }
		});

            }
        }
        function createAccount()
        {
            var username = document.getElementById('newEmail').value;
            var password = document.getElementById('newPassword').value;
            var confirmPassword = document.getElementById('confirmPassword').value;
            var firstName = document.getElementById('fName').value;
            var lastName = document.getElementById('lName').value;
            if (password != confirmPassword)
            {
                alert ("Passwords do not match");
            }
            else if (password == "" || firstName == "" || lastName == "" || username == "")
            {
                alert("Please fill in all fields");
            }
            else
            {
                var request = $.ajax({
                type: "POST",
                url: "http://localhost/m4j/incite/ajax/createaccount",
                data: {username: username, password: password, fName: firstName, lName: lastName, priv: 1, exp: 1},
                success: function(data) {
			//called when successful
			if (data)
                        {
                            alert("successful login");
                            document.getElementById('loginButton').style.visiblity = 'hidden';
                            document.getElementById('loginButton').style.display = 'none';
                            document.getElementById('accountButton').style.visiblity = "hidden";
                            document.getElementById('accountButton').style.display = 'none';
                            document.getElementById('passwordForm').style.visibility = "hidden";
                            document.getElementById('accountForm').style.visibility = "hidden";
                        }
                        else
                        {
                            alert("wrong username or password");
                        }
		  },
		  error: function(e) {
			//called when there is an error
			console.log(e.message);
		  }
		});
            }
        }
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
			<li class="active"><a href="#tab1" data-toggle="tab">Login</a></li>
			<li><a href="#tab2" data-toggle="tab">Sign-up</a></li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane active" id="tab1">
				<form>
				  <div class="form-group">
					<label for="recipient-name" class="control-label">Username (email):</label>
					<input type="text" class="form-control" id="email">
				  </div>
				  <div class="form-group">
					<label for="message-text" class="control-label">Password:</label>
					<input type="text" class="form-control" id="password">
				  </div>
				</form>
			</div>
			<div class="tab-pane" id="tab2">
				<form>
				  <div class="form-group">
					<label for="recipient-name" class="control-label">Username (email):</label>
					<input type="text" class="form-control" id="email">
				  </div>
				  <div class="form-group">
					<label for="message-text" class="control-label">Password:</label>
					<input type="text" class="form-control" id="password">
				  </div>
				  <div class="form-group">
					<label for="message-text" class="control-label">Confirm Password:</label>
					<input type="text" class="form-control" id="password">
				  </div>
				  <div class="form-group">
					<label for="message-text" class="control-label">First Name:</label>
					<input type="text" class="form-control" id="password">
				  </div>
				  <div class="form-group">
					<label for="message-text" class="control-label">Last Name:</label>
					<input type="text" class="form-control" id="password">
				  </div>
				</form>
			</div>
		</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary">Submit</button>
      </div>
    </div>
  </div>
</div>
    <!-- Navigation -->
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
			<ul class="nav navbar-nav navbar-right">
				<li><a href="" data-toggle="modal" data-target="#login-signup-dialog">Login/Sign-up</a></li>
			</ul>

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
