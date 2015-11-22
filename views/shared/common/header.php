<?php
queue_css_file(array('bootstrap', 'style', 'bootstrap.min', 'jquery.iviewer', 'bootstrap-multiselect', 'leaflet', 'jquery.jqtimeline'));
$db = get_db();
require_once(dirname(__FILE__) . '/../../../controllers/Incite_Users_Table.php');
?>

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Mapping the 4th</title>
    <?php echo js_tag('jquery'); ?>
    <?php echo js_tag('bootstrap.min'); ?>
    <?php echo js_tag('jquery-ui'); ?>
    <?php echo js_tag('jquery.mousewheel'); ?>
    <?php echo js_tag('jquery.iviewer'); ?>
    <?php echo js_tag('bootstrap-multiselect'); ?>
    <?php echo js_tag('leaflet'); ?>
    <?php echo js_tag('jquery.jqtimeline'); ?>
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
        $(document).ready(function()
        {
            $("#signup-tab").on('click', function()
            {
                if (document.getElementById("errorMessage") !== null)
                {
                    var x = document.getElementById("errorMessage");
                    var usernameDiv = document.getElementById("modal-footer");
                    usernameDiv.removeChild(x);
                }
            });
        });
        $(document).ready(function () {
            $('#login-button').on('click', function (e) {
                if ($('#login-tab').hasClass('active')) {
                    if ($('#username').val() !== "" && $('#password').val() !== "") {
                        //do login
                        var request = $.ajax({
                            type: "POST",
                            url: "http://localhost/m4j/incite/ajax/login",
                            data: {"username": $('#username').val(), "password": $('#password').val()},
                            success: function (data) {
                                if (data == "true") {
                                    //alert("successful login");
                                    var loginDiv = document.getElementById("modal-footer");
                                    if (document.getElementById("errorMessage") !== null)
                                    {
                                        var x = document.getElementById("errorMessage");
                                        loginDiv.removeChild(x);
                                    }
                                    var usernameError = document.createElement('div');
                                    var textNode = document.createTextNode("Login Successful!");
                                    usernameError.style.textAlign = "center";
                                    usernameError.appendChild(textNode);
                                    
                                    usernameError.id = "errorMessage";
                                    usernameError.className = "alert alert-block alert-success messages status";
                                    var submitButton = document.getElementById("login-button");
                                    loginDiv.insertBefore(usernameError, submitButton);
                                    
                                    
                                    setTimeout(function()
                                    {
                                        $('#login-signup-dialog').modal('hide');
                                        loginDiv.removeChild(usernameError);
                                    }, 2000);
                                    var getDataArray = $.ajax({
                                        type: "POST",
                                        url: "http://localhost/m4j/incite/ajax/getdata",
                                        success: function (data)
                                        {
                                            var dataArray = JSON.parse(data);
                                            //console.log(dataArray);


                                            $('ul[class="nav navbar-nav navbar-right"]').append('<li><a href="user_profile!">' + dataArray[1] + '</a></li>');
                                            $('ul[class="nav navbar-nav navbar-right"]').append('<li><a onclick = "logout()">Logout</a></li>');
                                            
                                            $('ul[class="nav navbar-nav navbar-right"] li').eq(0).remove();

                                        }
                                    })


                                } else {
                                    //alert("wrong username or password");
                                    
                                    var loginDiv = document.getElementById("modal-footer");
                                    if (document.getElementById("errorMessage") !== null)
                                    {
                                        var x = document.getElementById("errorMessage");
                                        loginDiv.removeChild(x);
                                    }
                                    var usernameError = document.createElement('div');
                                    var textNode = document.createTextNode("Wrong Username or Password");
                                    usernameError.style.textAlign = "center";
                                    usernameError.appendChild(textNode);
                                    
                                    usernameError.id = "errorMessage";
                                    usernameError.className = "alert alert-block alert-danger messages error";
                                    var submitButton = document.getElementById("login-button");
                                    loginDiv.insertBefore(usernameError, submitButton);
                                }
                            },
                            error: function (e) {
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
                            data: {"username": $('#newUsername').val(), "password": $('#newPassword').val(), "fName": $('#firstName').val(), "lName": $('#lastName').val(), "priv": 1, "exp": 1},
                            success: function (data) {
                                console.log('signup');
                                console.log(data);
                                if (data == "true") 
                                {
                                    alert("successful signup and login");
                                    $('#login-signup-dialog').modal('hide');
                                    var getDataArray = $.ajax({
                                        type: "POST",
                                        url: "http://localhost/m4j/incite/ajax/getdata",
                                        success: function (data)
                                        {
                                            var dataArray = JSON.parse(data);
                                            //console.log(dataArray);


                                            $('ul[class="nav navbar-nav navbar-right"]').append('<li><a href="user_profile!">' + dataArray[1] + '</a></li>');
                                            $('ul[class="nav navbar-nav navbar-right"]').append('<li><a onclick = "logout()">Logout</a></li>');
                                            
                                            $('ul[class="nav navbar-nav navbar-right"] li').eq(0).remove();
                                           

                                        }
                                    })
                                    
                                    
                                } else {
                                    alert("Unable to Sign Up!");
                                }
                            },
                            error: function (e) {
                                console.log(e.message);
                            }
                        });
                    } else {
                        alert('all fields are required');
                    }
                }
            });
        });
        function logout()
        {
            var request = $.ajax({
                type: "POST",
                url: "http://localhost/m4j/incite/ajax/logout",
                success: function () {
                    alert("Logout Successful!");
                    $('ul[class="nav navbar-nav navbar-right"] li').eq(0).remove();
                    $('ul[class="nav navbar-nav navbar-right"]').append('<li><a href="" data-toggle="modal" data-target="#login-signup-dialog">Login/Sign-up</a></li>');
                    $('ul[class="nav navbar-nav navbar-right"] li').eq(0).remove();
                },
                error: function (e) {
                    console.log(e.message);
                }
            });
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
                        <li class="active" id="login-tab"><a href="#tab1" data-toggle="tab">Login</a></li>
                        <li id="signup-tab"><a href="#tab2" data-toggle="tab">Sign-up</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab1">
                            <form>
                                <div class="form-group">
                                    <label for="recipient-name" class="control-label">Username (email):</label>
                                    <input type="text" class="form-control" id="username" name="username">
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
                <div class="modal-footer" id="modal-footer">
                    <button type="button" class="btn btn-primary" id="login-button">Submit</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <a class="navbar-brand" href="/m4j/incite">Incite of Mapping the Fourth</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <form class="navbar-form navbar-left" role="search" action="/m4j/incite/discover">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Location" name="location">
                            <input type="text" class="form-control" placeholder="Time" name="time">
                            <input type="text" class="form-control" placeholder="Keywords" name="keywords">
                            <select class="form-control" name="task">
                                <option value="random">Select a task</option>
                                <option value="transcribe">Transcribe</option>
                                <option value="tag">Tag</option>
                                <option value="connect">Connect</option>
                                <option value="discuss">Discuss</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-default">Discover</button>
                    </form>
                </ul>
                <?php if (isset($_SESSION['Incite']['IS_LOGIN_VALID']) && $_SESSION['Incite']['IS_LOGIN_VALID'] == true): ?>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="user_profile!"><?php echo $_SESSION['Incite']['USER_DATA'][1]; //first name   ?></a></li>
                        <li><a onclick = 'logout()'>Logout</a></li>
                    </ul>
                <?php else: ?>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a>Welcome Guest</a></li>
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
