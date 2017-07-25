<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    include(dirname(__FILE__) . '/../common/header.php');
    ?>

    <script type="text/javascript">
    var isGroupOwner = false;

    $(document).ready(function () {
        <?php
        if ($_SESSION['Incite']['USER_DATA']['email'] == $this->group['creator']['email']) {
            echo "isGroupOwner = true;";
        }
        ?>

        $('#manage-users-table').hide();
        populateGroupMembers();
        populateActivityFeed();
        colorEveryOtherActivityFeedRowGrey();

        <?php
        if (isset($_SESSION['incite']['message'])) {
            echo "notifyOfSuccessfulActionNoTimeout('" . $_SESSION["incite"]["message"] . "');";
            unset($_SESSION['incite']['message']);
        }

        if ($_SESSION['Incite']['Guest']) {
            echo "styleForGuest();";
        } else if ($_SESSION['Incite']['USER_DATA']['email'] == $this->group['creator']['email']) {
            echo "addGroupOwnerControls();";
        } else {
            $isMember = false;
            $hasRequested = false;
            $isBanned = false;

            foreach((array)$this->users as $user) {
                if ($_SESSION['Incite']['USER_DATA']['email'] == $user['email']) {
                    if ($user['privilege'] > -1) {
                        $isMember = true;
                    } else if ($user['privilege'] == -1) {
                        $hasRequested = true;
                    } else if ($user['privilege'] == -2) {
                        $isBanned = true;
                    }
                }
            }

            if (!$isMember && !$isBanned) {
                echo "stylePageForNonMember();";
            }

            if (!$isMember && !$isBanned && $hasRequested) {
                echo "disableRequestButton();";
            }

            if ($isBanned) {
                echo "styleForBannedUser();";
            }
        }
        ?>
    });

    function populateGroupMembers() {
        var span;

        <?php foreach ((array)$this->acceptedUsers as $user): ?>
        if ($("#groupprofile-list-of-members span").length === 0) {
            span = $('<span class="group-member-link"></span>');
        } else {
            span = $('<span class="group-member-link">, </span>');
        }

        span.append(createProfileLink(<?php echo sanitizeStringInput($user['email']); ?>.value, <?php echo $user['id']; ?>));
        $("#groupprofile-list-of-members").append(span);
        <?php endforeach; ?>

        //create link for group owner
        $('#group-owner').append(createProfileLink(<?php echo sanitizeStringInput($this->group['creator']['email']); ?>.value, <?php echo $this->group['creator']['id'] ?>));
    };

    function createProfileLink(username, userid) {
        return $('<a href="<?php echo getFullInciteUrl(); ?>/users/view/'+userid+'" target="_BLANK">' + username + '</a>')
    };

    function createProfileLinkWithRealName(firstName, lastName, username, userid) {
        if (firstName || lastName) {
            return $('<a href="<?php echo getFullInciteUrl(); ?>/users/view/'+userid+'" target="_BLANK">' + firstName + ' ' + lastName + ' (' + username + ')</a>')
        } else {
            return $('<a href="<?php echo getFullInciteUrl(); ?>/users/view/'+userid+'" target="_BLANK">' + username + '</a>')
        }
    };

    function populateActivityFeed() {
        var table;

        <?php foreach ((array)$this->acceptedUsers as $user): ?>
        if (isGroupOwner) {
            table = generateAndAppendUserRow($("#groupprofile-activity-feed-table"), <?php echo sanitizeStringInput($user['first_name']); ?>.value, <?php echo sanitizeStringInput($user['last_name']); ?>.value, <?php echo sanitizeStringInput($user['email']); ?>.value, <?php echo $user['id']; ?>);
        } else {
            table = generateAndAppendUserRow($("#groupprofile-activity-feed-table"), null, null, <?php echo sanitizeStringInput($user['email']); ?>.value, <?php echo $user['id']; ?>);
        }

        generateAndAppendUserActivityRow(table, "Transcribed", <?php echo $user['transcribed_doc_count']; ?>);
        generateAndAppendUserActivityRow(table, "Tagged", <?php echo $user['tagged_doc_count']; ?>);
        generateAndAppendUserActivityRow(table, "Connected", <?php echo $user['connected_doc_count']; ?>);
        generateAndAppendUserActivityRow(table, "Discussed", <?php echo $user['discussion_count']; ?>);
        <?php endforeach; ?>
    };

    function generateAndAppendUserRow(table, firstName, lastName, username, userid) {
        var userRow = $('<tr class="user-row">' +
        '<td class="user-table-data"><span class="user-data"></span></td>' +
        '<td class="embedded-user-table-cell"><table class="user-table"></table</td>' +
        '</tr>');

        userRow.find(".user-data").append(createProfileLinkWithRealName(firstName, lastName, username, userid));
        table.append(userRow);
        return userRow.find(".user-table");
    };

    function generateAndAppendUserActivityRow(table, task, number) {
        var row;
        if (task === "Discussed") {
            row = $('<tr>' +
            '<td><span class="task-data">' + task + '</span></td>' +
            '<td><span class="number-data">' + number + ' discussion(s)</span></td>' +
            '</tr>');
        } else {
            row = $('<tr>' +
            '<td><span class="task-data">' + task + '</span></td>' +
            '<td><span class="number-data">' + number + ' document(s)</span></td>' +
            '</tr>');
        }


        if (task === "Transcribed") {
            row.addClass("transcribe-color");
        } else if (task === "Tagged") {
            row.addClass("tag-color");
        } else if (task === "Connected") {
            row.addClass("connect-color");
        } else if (task === "Discussed") {
            row.addClass("discuss-color");
        }

        table.append(row);
    };

    function colorEveryOtherActivityFeedRowGrey() {
        $("#groupprofile-activity-feed-table").find(".user-row:even").css("background-color", "#eee");
    };

    function addGroupOwnerControls() {
        generateAndAppendOwnerGlyph();
        generateAndAppendInviteUsersLink();
        generateAndAppendGroupInstructionsInput();
        generateAndAppendGroupOrManagementTabs();

        <?php foreach ((array)$this->users as $user): ?>
        generateAndAppendManagementTableRows(<?php echo sanitizeStringInput($user['first_name']); ?>.value, <?php echo sanitizeStringInput($user['last_name']); ?>.value, <?php echo sanitizeStringInput($user['email']); ?>.value, "<?php echo $user['privilege']; ?>", "<?php echo $user['id']; ?>");
        <?php endforeach; ?>

        colorEveryOtherManagementRowGrey();
        addManagementTableActionListeners();
    };

    function generateAndAppendOwnerGlyph() {
        var glyphicon = $('<span title="You are the group owner" id="owner-glyph" class="glyphicon glyphicon-star" aria-hidden="true"></span>');

        $('#group-owner').append(glyphicon);
    };

    function generateAndAppendInviteUsersLink() {
        var inviteUsersLink = $('<a id="invite-new-members-link" href="mailto:?subject=Come join my Mapping the Fourth group,' +
        <?php echo sanitizeStringInput($this->group['name']); ?>.value.replace(/['"]+/g, '') +
        '!&body=<?php echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>%0D%0A%0D%0AFollow the above link to visit the group page and then click the button that says \'Request to join group\'!">Invite New Members</a>');

        $('#groupprofile-overview-title').after(inviteUsersLink);
    };

    function generateAndAppendGroupInstructionsInput() {
        var groupInstructionsInput = $('<form>' +
        '<span><strong>Group Instructions: </strong></span>' +
        '<div>' +
        '<textarea id="group-instructions-textarea" placeholder="Group instructions will be shown to all group members when they are transcribing, tagging and connecting"></textarea>' +
        '<button type="button" onclick="saveGroupInstructionsAjaxRequest()" id="save-group-instructions-btn" class="btn btn-primary">' +
        'Save' +
        '<span id="save-instructions-glyphicon" class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span>' +
        '</button>' +
        '</div>' +
        '</form>');

        var currentInstructions = <?php echo sanitizeStringInput($this->group['instructions']); ?>.value;

        if (currentInstructions) {
            groupInstructionsInput.find('#group-instructions-textarea').val(currentInstructions);
        }

        $('#groupprofile-overview-details').append(groupInstructionsInput);
        $('#group-instructions-textarea').height($('#group-instructions-textarea')[0].scrollHeight);

    };

    function generateAndAppendGroupOrManagementTabs() {
        var groupActivityOrManagementTabs = $('<ul class="nav nav-tabs" id="group-or-management-tabs">' +
        '<li id="group-activity-tab" class="active"><a>Group Activity</a></li>' +
        '<li id="management-tab"><a>Manage Group Members</a></li>' +
        '</ul>');

        $('#group-activity-title').remove();
        $('#groupprofile-activity-feed').prepend(groupActivityOrManagementTabs);

        addGroupOrManagementTabListeners();
    };

    function addGroupOrManagementTabListeners() {
        $('#group-activity-tab').click(function () {
            $("#manage-users-table").hide();
            $("#groupprofile-activity-feed-table").show();
            selectTab($("#group-activity-tab"), $("#management-tab"));
        });

        $('#management-tab').click(function () {
            $("#groupprofile-activity-feed-table").hide();
            $("#manage-users-table").show();
            selectTab($("#management-tab"), $("#group-activity-tab"));
        });
    };

    function selectTab(tabToSelect, tabToUnselect) {
        tabToSelect.addClass("active");
        tabToUnselect.removeClass("active");
    };

    function generateAndAppendManagementTableRows(firstName, lastName, username, status, id) {
        var statusName, glyphicon;

        status = parseInt(status);
        id = parseInt(id);

        //don't add the owner of the group to the table
        if (id === <?php echo $this->group['creator']['id'] ?>) {
            return;
        }
        var font_size = 13; //px
        var spacing = 40; //%

        var info = generateManagementTableThirdColumn(status, font_size, spacing);
        statusName = info.stat;
        glyphicon = info.gly;

        var row = $('<tr class="management-row">' +
        '<td><span>' + firstName + ' ' + lastName + ' (' + username + ')</span></td>' +
        '<td><span>' + statusName + '</span></td>' +
        '</tr>');

        row.append(glyphicon);

        row.data("ID", id);
        row.data("Username", username);

        $('#manage-users-table').append(row);
    };

    function colorEveryOtherManagementRowGrey() {
        $("#manage-users-table").find(".management-row").css("background-color", "#ffffff");
        $("#manage-users-table").find(".management-row:even").css("background-color", "#eee");
    };

    function generateManagementTableThirdColumn(status, font_size, spacing){
        var statusName;
        var buttons;
        if (status > -1) {
            statusName = "Group Member";
            buttons = $('<td><span style="font-size: '+font_size+'px;" title="Remove user from group" class="remove-user btn btn-danger btn-sm" aria-hidden="true">Remove</span><span style="width: '+spacing+'%; display:inline-block;"></span><span style=" font-size: '+font_size+'px;" title="Ban user from group" class="ban-user btn btn-warning btn-sm" >Ban</span></td></td>');
        } else if (status === -1) {
            statusName = "Requested to join";
            buttons = $('<td><span style="font-size: '+font_size+'px;" title="Add user to group" class="approve-user btn btn-success btn-sm" aria-hidden="true">Approve</span><span style="width: '+(spacing)+'%; display:inline-block;"></span><span style="font-size: '+font_size+'px;" title="Ban user from group" class="ban-user btn btn-warning btn-sm" aria-hidden="true"> Ban</span></td>');
        } else if (status === -2) {
            statusName = "Banned";
            buttons = $('<td><span style="font-size: '+font_size+'px;" title="Unban user from group" class="unban-user btn btn-info btn-sm" aria-hidden="true">Unban</span></td>');
        }
        return {stat: statusName, gly: buttons};

    }

    function addManagementTableActionListeners() {
        //off removes the eventHandler that existed before.
        $('.remove-user').off('click').on('click',removeUserFromGroup);
        $('.approve-user').off('click').on('click',addUserToGroup);
        $('.unban-user').off('click').on('click',unbanUser);
        $('.ban-user').off('click').on('click',banUser);

        function banUser() {
            var row = $(this).parent().parent();
            var userId = row.data("ID");
            changePrivilegeOfUserAjaxRequest(userId, -2, row);
        };

        function unbanUser() {
            var row = $(this).parent().parent();
            var userId = row.data("ID");
            changePrivilegeOfUserAjaxRequest(userId, 0, row);

        };

        function addUserToGroup() {
            var row = $(this).parent().parent();
            var userId = row.data("ID");
            changePrivilegeOfUserAjaxRequest(userId, 0,row);
        };

        function removeUserFromGroup() {
            var row = $(this).parent().parent();
            var userId = row.data("ID");
            var username = row.data("Username");
            if (confirm('Are you sure you want to remove '+username+' from the group?')) {
                removeUserFromGroupAjaxRequest(userId, row);
            }

        };
    };

    function reGenerateManagementTableThirdColumn(status, row){
        var glyphicon;
        var statusName;
        var info = generateManagementTableThirdColumn(status, 13, 40);
        statusName = info.stat;
        glyphicon = info.gly;
        row.unbind("click");
        row.append(glyphicon);
        row.children().eq(2).remove();
        row.children().eq(1).find("span").html(statusName);
        addManagementTableActionListeners();

    }

    function saveGroupInstructionsAjaxRequest() {
        var instructions = $('#group-instructions-textarea').val();

        var request = $.ajax({
            type: "POST",
            url: "<?php echo getFullInciteUrl().'/ajax/setgroupinstructions'; ?>",
            data: {"groupId": <?php echo $this->group['id'] ?>, "instructions": instructions},
            success: function (response) {
                location.reload();
            }
        });
    };

    function changePrivilegeOfUserAjaxRequest(userId, privilege, row) {
        var request = $.ajax({
            type: "POST",
            url: "<?php echo getFullInciteUrl().'/ajax/changegroupmemberprivilege'; ?>",
            data: {"groupId": <?php echo $this->group['id'] ?>, "privilege": privilege, "userId": userId},
            success: function (response) {
                console.log(response);
                var msgshow = "";
                if(privilege == 0){
                    msgshow = "Successfully added to the group.";

                }
                if(privilege == -2){
                    msgshow = "Banned from the group.";
                }
                notif({
                    type: "info",
                    msg: msgshow,
                    position: 'right',
                    width: 550,
                    multiline: true,
                    autohide: true,
                    clickable: true
                });
                reGenerateManagementTableThirdColumn(privilege, row);
            }
        });
    };

    function removeUserFromGroupAjaxRequest(userId, row) {
        var request = $.ajax({
            type: "POST",
            url: "<?php echo getFullInciteUrl().'/ajax/removememberfromgroup'; ?>",
            data: {"groupId": <?php echo $this->group['id'] ?>, "userId": userId},
            success: function (response) {
                console.log(response);
                //removing the row
                row.remove();
                colorEveryOtherManagementRowGrey();
            }
        });
    };

    function stylePageForNonMember() {
        $('#groupprofile-activity-feed').remove();

        generateAndAppendRequestToJoinButton();
    };

    function generateAndAppendRequestToJoinButton() {
        var button = $('<button class="btn btn-primary horizontal-align" id="request-button">Request to Join Group</button>');

        $(button).click(function() {
            requestToJoinGroupAjaxRequest();
            disableRequestButton();
        });

        $('#groupprofile-activity-container').append(button);
    };

    function requestToJoinGroupAjaxRequest(groupId) {
        var request = $.ajax({
            type: "POST",
            url: "<?php echo getFullInciteUrl().'/ajax/addgroupmember'; ?>",
            data: {"groupId": <?php echo $this->group['id'] ?>, "privilege": -1},
            success: function (response) {
                console.log(response);
            }
        });
    };

    function disableRequestButton() {
        $('#request-button').addClass("disabled");
        $('#request-button').html("Request Pending..");
        $('#request-button').click(function(){
            //so it isn't possible to manually remove disabled class and then resend request
        });
    };

    function styleForBannedUser() {
        $('#groupprofile-activity-container').hide();

        var bannedText = $('<p style="color: red; text-align: center;">You have been banned from this group, sorry!</p>');
        $('body').append(bannedText);
    };

    function styleForGuest() {
        $('#groupprofile-activity-container').hide();

        var pleaseLogInText = $('<p style="color: red; text-align: center;">Please log in to be able to view groups!</p>');
        $('body').append(pleaseLogInText);
    };
    </script>

    <style>
    .glyphicon {
        cursor: pointer;
        margin-right: 5px;
    }

    #groupprofile-header {
        text-align: center;
    }

    #groupprofile-activity-container {
        width: 80%;
    }

    .horizontal-align {
        margin: 0 auto;
    }

    .header-seperation-line {
        margin-top: 0px;
        margin-bottom: 10px;
    }

    hr {
        margin-top: 10px;
        margin-bottom: 30px
    }

    .overview-section {
        width: 25%;
        border: 1px solid black;
        display: inline-block;
        height: 100%;
        text-align: center;
        cursor: help;
    }

    .activity-title {
        text-align: center;
    }

    #groupprofile-overview {
        text-align: center;
    }

    #groupprofile-overview-details {
        text-align: left;
        position: relative;
        left: 15%;
    }

    .transcribe-color {
        background-color: #F7F2C5;
    }

    .tag-color {
        background-color: #F7D1C5;
    }

    .connect-color {
        background-color: #C9D1F8;
    }

    .discuss-color {
        background-color: #C5F7EB;
    }

    #groupprofile-activity-feed-table > tbody > tr > th {
        width: 50%;
        text-align: center;
    }

    #manage-users-table > tbody > tr > th {
        width: 33%;
        text-align: center;
    }

    tr {
        text-align: center;
    }

    .user-table-data {
        font-size: 30px !important;
        vertical-align: middle !important;
    }

    .user-table {
        width: 100%;
    }

    .embedded-user-table-cell {
        padding-left: 0px !important;
        padding-right: 0px !important;
    }

    #group-or-management-tabs {
        text-align: center;
        border-bottom: none;
    }

    .nav-tabs > li {
        width: 50%;
    }

    #owner-glyph {
        position: relative;
        top: 3px;
        margin-left: 5px;
    }

    #invite-new-members-link {
        font-size: 18px;
    }

    #request-button {
        display: block;
        width: 70%;
        height: 50px;
        font-size: 18px;
    }

    #group-instructions-textarea {
        position: relative;
        top: 6px;
        height: 200px;
        width: 70%;
        margin-right: 3px;
    }

    #save-instructions-glyphicon {
        margin-right: 0px;
        margin-left: 3px;
    }

    #save-group-instructions-btn {
        position: relative;
        bottom: 4px;
    }
    </style>
</head>

<body>
    <div id="groupprofile-header">
        <?php
            echo '<h1> Group: ' . $this->group['name'] . '</h1>';
            echo '<h3 id="group-owner">Group Owner: </h3>';
        ?>
    </div>

    <br>

    <div class="container-fluid horizontal-align" id="groupprofile-activity-container">

        <hr size=2 class="header-seperation-line">

        <div id="groupprofile-overview">
            <h2 class="activity-title" id="groupprofile-overview-title">Group Overview</h2>
            <div id="groupprofile-overview-details">
                <p id="groupprofile-list-of-members"><strong>Member(s): </strong></p>
                <p id="groupprofile-date-created"><strong>Date Created: <?php echo $this->group['created_time']; ?></strong></p>
            </div>
        </div>

        <br>
        <hr size=2>

        <div id="groupprofile-activity-feed">
            <h2 class="activity-title" id="group-activity-title">Group Activity</h2>

            <table class="table" id="groupprofile-activity-feed-table">
                <tr>
                    <th>
                        User
                    </th>
                    <th>
                        Activity Table
                    </th>
                </tr>
            </table>

            <table class="table" id="manage-users-table">
                <tr>
                    <th>
                        User
                    </th>
                    <th>
                        Status
                    </th>
                    <th>
                        Available Actions
                    </th>
                </tr>
            </table>
            <div>
            </div>
        </body>
        </html>
