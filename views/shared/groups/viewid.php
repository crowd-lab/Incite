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
            var emptyRow;
                if (task === "Discussed") {
                    emptyRow = $('<tr>' + 
                    '<td><span class="task-data">' + task + '</span></td>' + 
                    '<td><span class="number-data">' + number + ' discussion(s)</span></td>' +
                    '</tr>');
                } else {
                    emptyRow = $('<tr>' + 
                    '<td><span class="task-data">' + task + '</span></td>' + 
                    '<td><span class="number-data">' + number + ' document(s)</span></td>' +
                    '</tr>');
                }


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
            echo '<h1> Group: Group ' . $this->group_id . '</h1>';
            echo '<h3> Group Owner: <a href="" target="_BLANK">Unknown</a></h3>';
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

