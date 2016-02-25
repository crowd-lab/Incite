<!DOCTYPE html>
<html lang="en">
<head>
    <?php
        include(dirname(__FILE__) . '/../common/header.php');
    ?>

    <script type="text/javascript">
        $(document).ready(function () {
            $('#manage-users-table').hide();

            <?php
                if (isset($_SESSION['incite']['message'])) {
                    echo "notifyOfSuccessfulActionNoTimeout('" . $_SESSION["incite"]["message"] . "');";
                    unset($_SESSION['incite']['message']);
                }

                //if ($_SESSION['Incite']['USER_DATA']['email'] == $this->group['creator']['email']) {
                    //echo "addGroupOwnerControls();";
                //}

                if (true) {
                    echo "addGroupOwnerControls();";
                }
            ?>

            populateGroupMembers();
            populateActivityFeed();
            colorEveryOtherActivityFeedRowGrey();
        });

        function populateGroupMembers() {
            var span;

            <?php foreach ((array)$this->users as $user): ?>
                span = $('<span class="group-member-link"></span>');
                span.append(createProfileLink("<?php echo $user['email']; ?>", <?php echo $user['id']; ?>));
                $("#groupprofile-list-of-members").append(span);
            <?php endforeach; ?>

        };

        function createProfileLink(username, userid) {
            return $('<a href="<?php echo getFullInciteUrl(); ?>/users/view/'+userid+'" target="_BLANK">' + username + '</a>')
        };

        function populateActivityFeed() {
            var table; 

            <?php foreach ((array)$this->users as $user): ?>
                table = generateAndAppendUserRow($("#groupprofile-activity-feed-table"), "<?php echo $user['email']; ?>", <?php echo $user['id']; ?>);
                generateAndAppendUserActivityRow(table, "Transcribed", <?php echo $user['transcribed_doc_count']; ?>);
                generateAndAppendUserActivityRow(table, "Tagged", <?php echo $user['tagged_doc_count']; ?>);
                generateAndAppendUserActivityRow(table, "Connected", <?php echo $user['connected_doc_count']; ?>);
                generateAndAppendUserActivityRow(table, "Discussed", <?php echo $user['discussion_count']; ?>);
            <?php endforeach; ?>
        };

        function generateAndAppendUserRow(table, username, userid) {
            var userRow = $('<tr class="user-row">' + 
                '<td class="user-table-data"><span class="user-data"></span></td>' + 
                '<td class="embedded-user-table-cell"><table class="user-table"></table</td>' +
                '</tr>');

            userRow.find(".user-data").append(createProfileLink(username, userid));
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
            generateAndAppendInviteUsersLink();
            generateAndAppendGroupOrManagementTabs();

            <?php foreach ((array)$this->users as $user): ?>
                generateAndAppendManagementTableRows("<?php echo $user['email']; ?>", <?php echo $user['id']; ?>);
            <?php endforeach; ?>    

            generateAndAppendManagementTableRows("Seth", 0, 1);
            generateAndAppendManagementTableRows("Sethh", -1, 2);
            generateAndAppendManagementTableRows("Sethhh", -2, 3);

            colorEveryOtherManagementRowGrey();
            addManagementTableActionListeners();
        };

        function generateAndAppendInviteUsersLink() {
            var inviteUsersLink = $('<a id="invite-new-users-link" href="mailto:?subject=Come join my Mapping the Fourth group, <?php echo $this->group["name"] ?>!&body=<?php echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>%0D%0A%0D%0AFollow the above link to visit the group page and then click the button that says \'Request to join group\'!">Invite new users</a>');
        
            $('#groupprofile-header').append(inviteUsersLink);
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

        function generateAndAppendManagementTableRows(username, status, id) {
            var statusName, glyphicon;

            if (status > -1) {
                statusName = "Group Member";
                glyphicon = $('<td><span title="Remove user from group" id="remove-user" class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span><span title="Ban user from group" id="ban-user" class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span></td></td>');
            } else if (status === -1) {
                statusName = "Requested to join";
                glyphicon = $('<td><span title="Add user to group" id="approve-user" class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span><span title="Ban user from group" id="ban-user-from-request" class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span></td>');
            } else if (status === -2) {
                statusName = "Blocked";
                glyphicon = $('<td><span title="Unban user from group" id="unban-user" class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span></td>');
            }

            var row = $('<tr class="management-row">' + 
                '<td><span>' + username + '</span></td>' + 
                '<td><span>' + statusName + '</span></td>' +
                '</tr>');

            row.append(glyphicon);

            row.data("ID", id);

            $('#manage-users-table').append(row);
        };

        function colorEveryOtherManagementRowGrey() {
            $("#manage-users-table").find(".management-row:even").css("background-color", "#eee");
        };

        function addManagementTableActionListeners() {
            $('#remove-user').addClass('blue-color');
            $('#approve-user').addClass('green-color');
            $('#unban-user').addClass('blue-color');
            $('#ban-user').addClass('red-color');
            $('#ban-user-from-request').addClass('red-color');

            $('#remove-user').click(removeUserFromGroup);
            $('#approve-user').click(addUserToGroup);
            $('#unban-user').click(unbanUser);
            $('#ban-user').click(banUser);
            $('#ban-user-from-request').click(banUser);

            function banUser() {
                var row = $(this).parent().parent();
                var userId = row.data("ID");
                //Make ajax here to do action
                window.location.reload();
            };

            function unbanUser() {
                var row = $(this).parent().parent();
                var userId = row.data("ID");

                //Make ajax here to do action
                window.location.reload();
            };

            function addUserToGroup() {
                var row = $(this).parent().parent();
                var userId = row.data("ID");
                //Make ajax here to do action
                window.location.reload();
            };

            function removeUserFromGroup() {
                var row = $(this).parent().parent();
                var userId = row.data("ID");
                //Make ajax here to do action
                window.location.reload();
            };
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

        #groupprofile-overview-details {
            text-align: left;
            position: relative;
            left: 15%;
        }

        .group-member-link {
            margin-right: 5px;
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
    </style>
</head>
    
<body>
    <div id="groupprofile-header">
        <?php
            echo '<h1> Group: ' . $this->group['name'] . '</h1>';
            echo '<h3> Group Owner: <a href="" target="_BLANK">'.$this->group['creator']['email'].'</a></h3>';
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

