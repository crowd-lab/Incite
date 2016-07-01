<?php
    require_once(dirname(__FILE__) . '/../../../controllers/Incite_Helpers.php');
    queue_css_file(array('bootstrap', 'style', 'bootstrap.min', 'jquery.iviewer', 'bootstrap-multiselect', 'leaflet', 'jquery.jqtimeline', 'daterangepicker', 'notifIt', 'image-picker', 'bootstrap-dialog.min', 'task_styles', 'bootstrap-tour.min'));
    $db = get_db();

    require_once(dirname(__FILE__) . '/../../../controllers/Incite_Users_Table.php');
    require_once(dirname(__FILE__) . '/../../../controllers/Incite_Env_Setting.php');
    require_once(dirname(__FILE__) . '/../../../controllers/Incite_Session.php');
    require_once(dirname(__FILE__) . '/../../../controllers/Incite_Search.php');
    setup_session();

    $previous_search_results = getSearchQuerySpecifiedViaGetAsArray();
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
    <?php echo js_tag('js.cookie'); ?>
    <?php echo js_tag('jquery-ui'); ?>
    <?php echo js_tag('jquery.mousewheel'); ?>
    <?php echo js_tag('jquery.iviewer'); ?>
    <?php echo js_tag('bootstrap-multiselect'); ?>
    <?php echo js_tag('leaflet'); ?>
    <?php echo js_tag('jquery.jqtimeline'); ?>
    <?php echo js_tag('moment.min'); ?>
    <?php echo js_tag('daterangepicker'); ?>
    <?php echo js_tag('date'); ?>
    <?php echo js_tag('notifIt'); ?>
    <?php echo js_tag('image-picker.min'); ?>
    <?php echo js_tag('comments'); ?>
    <?php echo js_tag('notifications'); ?>
    <?php echo js_tag('bootstrap-dialog.min'); ?>
    <?php echo js_tag('bootstrap-tour.min'); ?>
    <?php echo head_css(); ?>

     <?php
        function loadWorkingGroupInstructions() {
            $groupsWhosInstructionsHaveBeenSeenByUser = getGroupInstructionsSeenByUserId($_SESSION['Incite']['USER_DATA']['id']);

            $workingGroupId = 0;
            $workingGroupHasInstructions = false;
            if (isset($_SESSION['Incite']['USER_DATA']['working_group']['id'])) {
                $workingGroupId = $_SESSION['Incite']['USER_DATA']['working_group']['id'];
            }

            foreach((array)getGroupsByUserId($_SESSION['Incite']['USER_DATA']['id']) as $group) {
                if ($group['instructions'] != '' && $workingGroupId == $group['id']) {
                    $workingGroupHasInstructions = true;

                    if (in_array($group['id'], $groupsWhosInstructionsHaveBeenSeenByUser)) {
                        echo 'addGroupInstructionSection(' . sanitizeStringInput($group['name']) . '.value, ' . sanitizeStringInput($group['instructions']) . '.value, false);';
                    } else {
                        echo 'addGroupInstructionSection(' . sanitizeStringInput($group['name']) . '.value, ' . sanitizeStringInput($group['instructions']) . '.value, true);';
                        echo 'changeWorkingGroupInfoIcon(true);';
                    }
                }
            }

            if (!$workingGroupHasInstructions) {
                echo 'styleInstructionsModalToBeEmpty();';
            }
        }

        function markWorkingGroupInstructionsAsSeen() {
            $workingGroupId = 0;
            if (isset($_SESSION['Incite']['USER_DATA']['working_group']['id'])) {
                $workingGroupId = $_SESSION['Incite']['USER_DATA']['working_group']['id'];
            }

            if ($workingGroupId > 0) {
                echo "updateSeenInstructionsAjaxRequest(" . $workingGroupId . ");";
            }
        }
    ?>

    <!-- Custom CSS -->
    <style>
        #user_profile {
            background:none!important;
            border:none;
            padding:0!important;
            font: inherit;
            color: #9D9D9D;
            height: 34px;
        }

        #navbar-account-interaction-area {
            margin-left: 20px;
        }

        #user_profile:hover {
            color: white;
        }

        #user-dropdown-menu {
            right: -15px;
        }

        #working-group-interaction-area {
            text-align: center;
            padding-right: 20px;
            border-right: 1px solid grey;
            height: 50px;
        }

        body {
            padding-top: 70px;
            /* Required padding for .navbar-fixed-top. Remove if using .navbar-static-top. Change if height of navigation changes. */
        }

        .instructions-alert-icon-in-modal {
            float: right;
            position: relative;
            bottom: 27px;
            right: 80px;
        }

        .group-instructions-header {
            margin-top: 5px;
        }

        #instructions-modal-current-group-info-header {
            text-align: center;
        }

        .nav-dropdown-control {
            margin-left: 15px;
            margin-right: 15px;
        }

        .dropdown.dropdown-lg .dropdown-menu {
            margin-top: -1px;
            padding: 6px 20px;
        }
        .input-group-btn .btn-group {
            display: flex !important;
        }
        .btn-group .btn {
            border-radius: 0;
            margin-left: -1px;
        }
        .btn-group .btn:last-child {
            border-top-right-radius: 4px;
            border-bottom-right-radius: 4px;
        }
        .btn-group .form-horizontal .btn[type="submit"] {
          border-top-left-radius: 4px;
          border-bottom-left-radius: 4px;
        }
        .form-horizontal .form-group {
            margin-left: 0;
            margin-right: 0;
        }
        .form-group .form-control:last-child {
            border-top-left-radius: 4px;
            border-bottom-left-radius: 4px;
        }

        a.navbar-links:hover {
            font-weight: bold;
        }
    </style>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script>
        var msgbox;
        var fullInciteUrl = "<?php echo getFullInciteUrl(); ?>";

        function closeMsgBox() {
            msgbox.close();
        }

        function openMsgBox() {
            msgbox.open();
        }

        function addGroupInstructionSection(groupName, groupInstructions, isNew) {
            if (isNew) {
                var section = $('<span class="label label-danger instructions-alert-icon-in-modal" aria-hidden="true">New</span><p class="group-instructions-header"><strong>Working Group:</strong> ' + groupName + '</p>' +
                    '<p class="group-instructions-body"><strong>Instructions:</strong> ' + groupInstructions + '</p>');
            } else {
                var section = $('<p class="group-instructions-header"><strong>Working Group:</strong> ' + groupName + '</p>' +
                    '<p class="group-instructions-body"><strong>Instructions:</strong> ' + groupInstructions + '</p>');
            }

            $('#instructions-modal-body').append(section);
        }

        function styleInstructionsModalToBeEmpty() {
            var section = $('<p> Either your current working group has not yet added instructions or you have no working group! </p>');

            $('#instructions-modal-body').append(section);
        }

        function changeWorkingGroupInfoIcon(isNew) {
            if (isNew) {
                $('#working-group-info-glyphicon').removeClass('glyphicon-info-sign')
                    .addClass('glyphicon-exclamation-sign')
                    .css('color', '#D9534F');
            } else {
                $('#working-group-info-glyphicon').removeClass('glyphicon-exclamation-sign')
                    .addClass('glyphicon-info-sign')
                    .css('color', '#9D9D9D');
            }

        }

        function updateSeenInstructionsAjaxRequest(groupId) {
            var request = $.ajax({
                type: "POST",
                url: "<?php echo getFullInciteUrl().'/ajax/addseeninstructions'; ?>",
                data: {"userId": <?php echo $_SESSION['Incite']['USER_DATA']['id'] ?>, "groupId": groupId},
                success: function (response) {
                    $(".instructions-alert-icon-in-modal").remove();
                    changeWorkingGroupInfoIcon(false);
                }
            });
        }

        <?php
            if (isset($_GET['time'])) {
                $time_segs = explode(' - ', $_GET['time']);
                $start_time = $time_segs[0];
                $end_time   = $time_segs[1];
            }
        ?>

        $(document).ready(function () {

          $("a#forgotpw").bind("click", function() {
            Cookies.set('name', $('#username').val());
          });

           <?php loadWorkingGroupInstructions(); ?>

            $('#time_picker').daterangepicker({
                locale     : { format: 'YYYY-MM-DD'},
                "startDate": "<?php echo (isset($start_time) ? $start_time : "1830-01-01"); ?>",   //could be dynamic or user's choice
                "endDate"  : "<?php echo (isset($end_time) ? $end_time : "1870-12-31"); ?>",   //could be dynamic or user's choice
                "minDate"  : "1830-01-01",
                "maxDate"  : "1870-12-31",
                "opens"    : "center"
            }, function (start, end, label) {
            });

            $("#signup-tab").on('click', deleteAlertFromLoginModal);
            $("#login-tab").on('click', deleteAlertFromLoginModal);
            $("#login_modal").on('click', deleteAlertFromLoginModal);

            $("#instructions-dialog").on('hide.bs.modal', function() {
                <?php
                    markWorkingGroupInstructionsAsSeen();
                ?>
            });

            $('#location').val(<?php echo (isset($_GET['location']) ? sanitizeStringInput($_GET['location']) : sanitizeStringInput("")); ?>.value);
            $('#keywords').val(<?php echo (isset($_GET['keywords']) ? sanitizeStringInput($_GET['keywords']) : sanitizeStringInput("")); ?>.value);

            $('#login-button').on('click', attemptToLoginOrSignup);

            $('#navbar-search-btn').on('click', function (e) {
                $('#adv-search-btn').click();
            });

            $('#adv-search-btn').on('click', function (e) {
                $('#keywords').val($('#pre-keywords').val());

                //check dates
                if (new Date($('#navbar-time-from').val()) > new Date ($('#navbar-time-to').val())) {
                    notif({
                        type: "warning",
                        msg: "<b>Warning:</b> \"from\" time cannot be later than \"to\" time!",
                        position: "right"
                    });
                    return;
                }
                $('#navbar-form').submit();
            });

            $('#pre-keywords').on('keyup', function(e) {
                if (e.which == 13) { //enter key
                    $('#adv-search-btn').click();
                }
            });

            $('#navbar-signup-button').on('click', function (e) {
                $('#signup-tab a').click();
            });

            $('#navbar-login-button').on('click', function (e) {
                $('#login-tab a').click();
            });
        });

        function deleteAlertFromLoginModal() {
            if (document.getElementById("errorMessage") !== null) {
                var x = document.getElementById("errorMessage");
                var usernameDiv = document.getElementById("modal-footer");
                usernameDiv.removeChild(x);
            }
        };

        function createAlertInLoginModal(displayMessage, isError) {
            var loginDiv = document.getElementById("modal-footer");
            if (document.getElementById("errorMessage") !== null)
            {
                var x = document.getElementById("errorMessage");
                loginDiv.removeChild(x);
            }
            var usernameError = document.createElement('div');
            var textNode = document.createTextNode(displayMessage);
            usernameError.style.textAlign = "center";
            usernameError.appendChild(textNode);

            usernameError.id = "errorMessage";

            if (isError) {
                usernameError.className = "alert alert-block alert-danger messages error";
            } else {
                usernameError.className = "alert alert-block alert-success messages status";
            }

            var submitButton = document.getElementById("login-button");
            loginDiv.insertBefore(usernameError, submitButton);
        };

        function attemptToLoginOrSignup() {
            if ($('#login-tab').hasClass('active')) {
                if ($('#username').val() !== "" && $('#password').val() !== "") {
                    loginAjaxRequest();
                } else {
                    createAlertInLoginModal("Username and Password are both required", true);
                }
            } else { //then #signup-tab is active
                if ($('#newUsername').val() !== "" && $('#newPassword').val() !== "" && $('#confirmPassword').val() !== "" && $('#firstName').val !== "" && $('#lastName').val() !== "") {
                    //do signup
                    if ($('#newPassword').val() !== $('#confirmPassword').val()) {
                        createAlertInLoginModal('"Password" and "Confirm Password" fields do not match', true);
                        return;
                    }
                    signupAjaxRequest();
                } else {
                    createAlertInLoginModal('All fields are required', true);
                }
            }
        };

        function loginAjaxRequest() {
            var request = $.ajax({
                type: "POST",
                url: "<?php echo getFullInciteUrl().'/ajax/login'; ?>",
                data: {"username": $('#username').val(), "password": $('#password').val()},
                success: function (response) {
                    data = response.trim();

                    if (data == "true") {
                        createAlertInLoginModal("Login successful!", false);

                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    } else {
                        createAlertInLoginModal("Wrong username or password", true);
                    }
                },
                error: function (e) {
                    console.log(e.message);
                }
            });
        };

        function signupAjaxRequest() {
            var request = $.ajax({
                type: "POST",
                url: "<?php echo getFullInciteUrl().'/ajax/createaccount'; ?>",
                data: {"username": $('#newUsername').val(), "password": $('#newPassword').val(), "fName": $('#firstName').val(), "lName": $('#lastName').val(), "priv": 1, "exp": 1},
                success: function (response) {
                    data = response.trim();

                    if (data === "true") {
                        createAlertInLoginModal("Successful signup and login!", false);

                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    } else if (data === "exists") {
                        createAlertInLoginModal("Username already exists", true);
                    } else {
                        createAlertInLoginModal("Unable to sign up!", true);
                    }
                },
                error: function (e) {
                    console.log(e.message);
                }
            });
        };

        //onclick set in html
        function logoutAjaxRequest() {
            var request = $.ajax({
                type: "POST",
                url: "<?php echo getFullInciteUrl().'/ajax/logout'; ?>",
                success: function ()
                {
                    notifyOfSuccessfulActionWithTimeout("You've logged out!");

                    setTimeout(function () {
                        location.reload();
                    }, 1000);
                },
                error: function (e) {
                    console.log(e.message);
                }
            });
        };
    </script>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" style="background-color: #ffffff; border-bottom-color: #B2B1B1; height: 68px;" role="navigation">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <a href="<?php echo getFullInciteUrl(); ?>" class="navbar-left" style=""><img src="<?php echo getFullOmekaUrl(); ?>/plugins/Incite/views/shared/images/m4j-brand.png" style="max-height: 65px; margin-right: 5px; margin-top: 2px;"></a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1" style="margin-top: 6px;">
                <ul class="nav navbar-nav navbar-right" style="position: relative; right: 15px;">
                    <?php if (isset($_SESSION['Incite']['IS_LOGIN_VALID']) && $_SESSION['Incite']['IS_LOGIN_VALID'] == true): ?>
                        <li id="working-group-interaction-area">
                            <?php
                                include(dirname(__FILE__) . '/working_group_selector.php');
                            ?>
                        </li>
                    <?php endif; ?>

                    <li class="dropdown" id="navbar-account-interaction-area">
                        <?php if (isset($_SESSION['Incite']['IS_LOGIN_VALID']) && $_SESSION['Incite']['IS_LOGIN_VALID'] == true): ?>
                            <button id="user_profile" type="button"
                                    class="btn btn-default navbar-btn dropdown-toggle" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false"
                                    style="height: 34px; color: #8BB7C8;">
                                <?php echo $_SESSION['Incite']['USER_DATA']['first_name']; ?>
                                <span class="glyphicon glyphicon-user" aria-hidden="true"></span>
                            </button>
                            <ul class="dropdown-menu" id="user-dropdown-menu">
                                <?php if (isset($_SESSION['Incite']['USER_DATA']['id'])): ?>
                                    <li><a href="<?php echo getFullInciteUrl() . '/users/view/' . $_SESSION['Incite']['USER_DATA']['id']; ?>">Profile</a></li>
                                <?php else: ?>
                                    <li class="disabled"><a href="#">Profile</a></li>
                                <?php endif; ?>
                                <li class="divider"></li>
                                <li><a href="#" onclick="logoutAjaxRequest()">Logout</a></li>
                            </ul>
                        <?php else: ?>
                            <a href="" style="color: #8BB7C8; font-size: 110%; margin-top: -8px; padding-left: 10px; padding-right: 10px; padding-top: 20px;"; id="login_modal" class="" data-toggle="modal" data-target="#login-signup-dialog">
                                <button id="navbar-login-button" class="btn btn-success">Login</button>
                                <button id="navbar-signup-button" class="btn btn-info">Signup</button>
                            </a>
                        <?php endif; ?>
                    </li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
<!-- To be added -->
<!--
                    <li class="">
                        <a style="font-size: 125%; color: #8BB7C8;">Browse</a>
                    </li>
-->
                    <li class="">
                      <a class="navbar-links" href="<?php echo getFullInciteUrl(); ?>/help/about" style="font-size: 110%; color: #8BB7C8; padding-left: 10px; padding-right: 10px; padding-top: 20px;">About</a>
                    </li>
                    <li class="">
                      <a class="navbar-links" href="<?php echo getFullInciteUrl(); ?>/help/teachers" style="font-size: 110%; color: #8BB7C8; padding-left: 10px; padding-right: 10px; padding-top: 20px;">Teachers</a>
                  </li>
                  <li class="">
                      <a href="<?php echo getFullInciteUrl();?>/documents/contribute" style="font-size: 150%; padding-left: 10px; padding-right: 10px;"><button style="margin-top: -3px;" class="btn btn-danger">Contribute</button></a>
                    </li>
                    <li>
                        <div class="input-group" id="adv-search" style="width: 261px; margin-top: 12px; margin-right: 0px; margin-left: 15px;">
                            <div class="input-group-btn">
                                <div class="btn-group" role="group">
                                    <div class="dropdown dropdown-lg">
                                        <input style="width: 232px;" type="text" class="form-control" data-toggle="dropdown" placeholder="Search..." name="pre-keywords" id="pre-keywords" value="<?php if (isset($previous_search_results['keywords'])) echo $previous_search_results['keywords']; ?>" />
<!--
                                        <button id="navbar-dropdown-button" style="width: 30px; height: 34px; padding-left: 8px; padding-right: 8px;" type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><span class="caret"></span></button>
-->
                                        <div style="width: 232px;" class="dropdown-menu dropdown-menu-right" role="menu">
                                            <form id="navbar-form" class="form-horizontal" role="form" action="<?php echo getFullInciteUrl(); ?>/discover" method="get">
                                              <div class="form-group">
                                                <label>Filter by</label><br>
                                                <label style="font-size: 80%;">Task type:</label>
                                                <select class="form-control" name="task">
                                                    <option value="all" selected>All</option>
                                                    <option value="transcribe">Transcribe</option>
                                                    <option value="tag">Tag</option>
                                                    <option value="connect">Connect</option>
                                                    <option value="discuss">Discuss</option>
                                                </select>
                                              </div>
                                              <div class="form-group">
                                                <label style="font-size: 80%;">Location:</label>
                                                <input class="form-control" type="text" value="<?php if (isset($previous_search_results['location'])) echo $previous_search_results['location']; ?>" placeholder="anywhere" name="location" />
                                              </div>
                                              <div class="form-group">
                                                <label style="font-size: 80%;">Dates:</label><br>
                                                <input style="font-size: 80%; width: 83px;" class="form-control" type="text" placeholder="1830-01-01" id="navbar-time-from" name="time_from" value="<?php if (isset($previous_search_results['time_from'])) echo $previous_search_results['time_from']; else echo '1830-01-01'; ?>" />
                                                <div style="display: inline-block; float: left; font-size: 100%; margin-left: 5px; margin-right: 5px; margin-top: 5px;"><b> to </b></div>
                                                <input style="font-size: 80%; width: 83px;" class="form-control" type="text" placeholder="1870-12-31" id="navbar-time-to" name="time_to" value="<?php if (isset($previous_search_results['time_to'])) echo $previous_search_results['time_to']; else echo '1870-12-31'; ?>" />
                                              </div>
                                              <button id="adv-search-btn" type="button" class="btn btn-default">Search</button>
                                              <input type="hidden" name="keywords" value="" id="keywords">
                                            </form>
                                        </div>
                                    </div>
                                    <button id="navbar-search-btn" style="width: 30px; padding-left: 8px; padding-right: 8px;" type="button" class="btn btn-default"><span style="font-size: 80%;" class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>

            </div>

            <!-- /.navbar-collapse -->
        </div>

        <!-- /.container -->
    </nav>

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
                                 <a href="<?php echo getFullInciteUrl() . '/users/forgot'?>" id="forgotpw">forgot password?</a>
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

    <div class="modal fade" id="instructions-dialog" tabindex="-1" role="dialog" aria-labelledby="instructions-dialog-label">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="login-signup-dialog-label">Working Group Information</h4>
                </div>
                <div class="modal-body" id="instructions-modal-body">
                    <p><strong>What is a working group? </strong>All task work (transcribing, tagging, connected and discussing) is logged as being done for a specific group. This specific group is called your "working group" and is picked by you via the dropdown in the header. If no working group is selected your task work will not be logged for a specific group, but will still be viewable via your profile page's activity feed.</p>
                    <hr style="margin-top:20px;margin-bottom:20px;"></hr>
                    <h4 id="instructions-modal-current-group-info-header"><u>Your Current Working Group's Instructions</u></h4>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="working-group-dialog" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="working-group-dialog-label">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" id="working-group-modal-cancel-btn" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="working-group-dialog-label">Are you sure you want to change your working group?</h4>
                </div>
                <div class="modal-body" id="working-group-modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" id="working-group-modal-no-btn" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" id="working-group-modal-yes-btn" class="btn btn-primary"  data-dismiss="modal">Yes, change group</button>
                </div>
            </div>
        </div>
    </div>
</body>
