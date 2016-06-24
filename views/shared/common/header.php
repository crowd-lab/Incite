<?php
require_once(dirname(__FILE__) . '/../../../controllers/Incite_Helpers.php');
queue_css_file(array('bootstrap', 'style', 'bootstrap.min', 'jquery.iviewer', 'bootstrap-multiselect', 'leaflet', 'jquery.jqtimeline', 'daterangepicker', 'notifIt', 'image-picker', 'bootstrap-dialog.min', 'task_styles'));
$db = get_db();

require_once(dirname(__FILE__) . '/../../../controllers/Incite_Users_Table.php');
require_once(dirname(__FILE__) . '/../../../controllers/Incite_Env_Setting.php');
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
  <?php echo js_tag('moment.min'); ?>
  <?php echo js_tag('daterangepicker'); ?>
  <?php echo js_tag('date'); ?>
  <?php echo js_tag('notifIt'); ?>
  <?php echo js_tag('image-picker.min'); ?>
  <?php echo js_tag('comments'); ?>
  <?php echo js_tag('notifications'); ?>
  <?php echo js_tag('bootstrap-dialog.min'); ?>
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

  #header-dropdown-menu{
    margin-left: -15px;
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
  </style>

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
  <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->
  <script src="../../plugins/Incite/views/shared/javascripts/js.cookie.js"></script>
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
    $("a#forgotpw").bind("click", function() {
      Cookies.set('name', $('#username').val());
    });

  });


  function deleteAlertFromLoginModal() {
    if (document.getElementById("errorMessage") !== null) {
      var x = document.getElementById("errorMessage");
      var usernameDiv = document.getElementById("modal-footer");
      usernameDiv.removeChild(x);
    }
  };

  function createAlertInLoginModal(displayMessage, isError){
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
          createAlertInLoginModal("Successful signup and login! \nYou will be a receiving confirmation email soon.", false);

          setTimeout(function () {
            location.reload();
          }, 1500);

          sendRegisterConfirmationEmailRequest($('#newUsername').val());

        } else if (data === "exists") {
          createAlertInLoginModal("Username already exists", true);
        } else {

          createAlertInLoginModal("Unable to sign up!" + data, true);
        }
      },
      error: function (e) {
        console.log(e.message);
      }
    });
  };

  /*
  Ajax requst to send a register confirmation email to the user.
  Creates subject and body and passes it as data.
  Returns true if email was sent successfully. False if not.
  */
  function sendRegisterConfirmationEmailRequest(email){
    var subject = "Welcome to Mapping the Fourth!"
    var body = "Welcome to Mapping the Fourth " + email + "!";
    var request = $.ajax({
      type: "POST",
      url: "<?php echo getFullInciteUrl().'/ajax/sendemail'; ?>",
      data: {"username": email, "body": body, "subject": subject},
      success: function (response) {
        data = response.trim();
        if (data === "true") {
          console.log('successfully sent mail');
        }
        else{
          console.log('NOT successfully sent mail');
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
  <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container-fluid">
      <!-- Brand and toggle get grouped for better mobile display -->
      <div class="navbar-header">
        <!-- <a class="navbar-brand" href="<?php echo getFullInciteUrl(); ?>">Mapping the Fourth</a> -->


        <ul class="nav navbar-nav">
          <li class="dropdown">
            <a href="#" class="navbar-brand dropdown" data-toggle="dropdown">Mapping the Fourth</a>
            <ul class="dropdown-menu" id="header-dropdown-menu"role="menu">
              <li><a href="<?php echo getFullInciteUrl(); ?>">Home</a></li>
              <li class="divider" id="DropdownDivider"></li>
              <!-- <li><a href=" <?php //echo getFullInciteUrl(); ?>/index/about">About</a></li> -->
              <li><a target="_blank" href="http://www.civilwar.vt.edu/wordpress/mapping-the-fourth-of-july-in-the-civil-war-era/">About</a></li>
              <li><a href="<?php echo getFullInciteUrl(); ?>/help/students">Students</a></li>
              <li><a href="<?php echo getFullInciteUrl(); ?>/help/teachers">Teachers/professors</a></li>
              <li class="divider" id="DropdownDivider"></li>
              <li><a href="#">Terms of Use</a></li>
              <li><a href="#">Privacy Policy</a></li>
            </ul>
          </li>
        </ul>
      </div>

      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <form class="navbar-form navbar-left" role="search" action="<?php echo getFullInciteUrl(); ?>/discover">
          <div class="form-group">
            <input id="location" type="text" class="form-control" placeholder="Location" name="location">
            <input style="width: 190px;" id="time_picker" type="text" class="form-control" placeholder="Time" name="time">
            <input id="keywords" type="text" class="form-control" placeholder="Keywords" name="keywords">
            <select class="form-control" name="task">
              <option value="random">Select a task</option>
              <option value="transcribe" <?php if (isset($task) && $task == "transcribe") echo ' selected'; ?>>Transcribe</option>
              <option value="tag"<?php if (isset($task) && $task == "tag") echo ' selected'; ?>>Tag</option>
              <option value="connect"<?php if (isset($task) && $task == "connect") echo ' selected'; ?>>Connect</option>
              <option value="discuss"<?php if (isset($task) && $task == "discuss") echo ' selected'; ?>>Discuss</option>
            </select>

          </div>
          <button id="navbar_search_button" type="submit" class="btn btn-default">
            Search <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
          </button>
        </form>

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
              style="height: 34px;">
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
            <button id="login_modal" type="button" class="btn btn-default navbar-btn" data-toggle="modal" data-target="#login-signup-dialog">Login/Sign-up</button>
          <?php endif; ?>
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

            </form>
            <a href="<?php echo getFullInciteUrl() . '/users/forgot'?>" id="forgotpw">forgot password?</a>

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
