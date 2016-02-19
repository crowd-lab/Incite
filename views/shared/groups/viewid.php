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

            populateGroupMembers();
            populateActivityFeed();
            colorEveryOtherActivityFeedRowGrey();
        });

        function populateGroupMembers() {
            //TODO generate a link for each user and append it
            var span = $('<span class="group-member-link"></span>');
            span.append(createProfileLink("Seth"));
            $("#groupprofile-list-of-members").append(span);

            var span = $('<span class="group-member-link"></span>');
            span.append(createProfileLink("adss"));
            $("#groupprofile-list-of-members").append(span);
        };

        //TODO generate user profile link from username and whatever else you need
        function createProfileLink(username) {
            return $('<a href="" target="_BLANK">' + username + '</a>')
        };

        function populateActivityFeed() {
            //TODO put this in a loop for each user in group
            var table1 = generateAndAppendUserRow($("#groupprofile-activity-feed-table"), "Seth");

                //TODO for each user, put a nested loop here that fills in their activity data
                generateAndAppendUserActivityRow(table1, "Transcribed", 2);
                generateAndAppendUserActivityRow(table1, "Tagged", 2);
                generateAndAppendUserActivityRow(table1, "Connected", 2);
                generateAndAppendUserActivityRow(table1, "Discussed", 2);
        };

        function generateAndAppendUserRow(table, username) {
            var userRow = $('<tr class="user-row">' + 
                '<td class="user-table-data"><span class="user-data"></span></td>' + 
                '<td class="embedded-user-table-cell"><table class="user-table"></table</td>' +
                '</tr>');

            userRow.find(".user-data").append(createProfileLink(username));
            table.append(userRow);
            return userRow.find(".user-table");
        };

        function generateAndAppendUserActivityRow(table, task, number) {
            var emptyRow = $('<tr>' + 
                '<td><span class="task-data">' + task + '</span></td>' + 
                '<td><span class="number-data">' + number + ' documents</span></td>' +
                '</tr>');


            if (task === "Transcribed") {
                emptyRow.addClass("transcribe-color");
            } else if (task === "Tagged") {
                emptyRow.addClass("tag-color");
            } else if (task === "Connected") {
                emptyRow.addClass("connect-color");
            } else if (task === "Discussed") {
                emptyRow.addClass("discuss-color");
            }

            table.append(emptyRow);
        };

        function colorEveryOtherActivityFeedRowGrey() {
            $("#groupprofile-activity-feed-table").find(".user-row:even").css("background-color", "#eee");
        }
    </script>

    <style> 
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

        th {
            width: 50%;
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
    </style>
</head>
    
<body>
    <div id="groupprofile-header">
        <?php
            echo '<h1> Group: ' . $_SESSION['Incite']['USER_DATA']['first_name'] . '</h1>';
            echo '<h3> Group Owner: <a href="" target="_BLANK">' . $_SESSION['Incite']['USER_DATA']['email'] . '</a></h3>';
        ?>
    </div>

    <br>

    <div class="container-fluid horizontal-align" id="groupprofile-activity-container">

        <hr size=2 class="header-seperation-line">

        <div id="groupprofile-overview">
            <h2 class="activity-title" id="groupprofile-overview-title">Group Overview</h2>
            <div id="groupprofile-overview-details">
                <p id="groupprofile-list-of-members"><strong>Members: </strong></p>
                <p id="groupprofile-date-created"><strong>Date Created: </strong></p>
            </div>
        </div>

        <br>
        <hr size=2>

        <div id="groupprofile-activity-feed">
            <h2 class="activity-title">Group Activity</h2>
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
        <div>
    </div>
</body>
</html>

