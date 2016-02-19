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

            populateGroups();
            populateActivityOverview();
            populateActivityFeed();
        });

        function populateGroups() {
            var link = createGroupLink("Class 101");
            $("#groups-list").append(link);
            
            var link = createGroupLink("Class 201");
            $("#groups-list").append(link);
        };

        //TODO generate user profile link from username and whatever else you need
        function createGroupLink(groupname) {
            return $('<span class="group-link"><a href="" target="_BLANK">' + groupname + '</a></span>');
        };

        function populateActivityOverview() {
            $("#number-transcribed").html("Echo number here " + "documents");
            $("#number-tagged").html("Echo number here " + "documents");
            $("#number-connected").html("Echo number here " + "documents");
            $("#number-discussed").html("Echo number here " + "documents");
        };

        function populateActivityFeed() {
            //TODO put this in a loop for each activity
            generateAndAppendRow($("#userprofile-activity-feed-table"), "Discuss", "MyDoc", 5, "Feb 1");
            generateAndAppendRow($("#userprofile-activity-feed-table"), "Tag", "MyDoc", 5, "Feb 1");
            generateAndAppendRow($("#userprofile-activity-feed-table"), "Connect", "MyDoc", 5, "Feb 1");
            generateAndAppendRow($("#userprofile-activity-feed-table"), "Transcribe", "MyDoc", 5, "Feb 1");
        };

        //TODO generate document link from docID
        function generateAndAppendRow(table, task, docTitle, docID, date) {
            var emptyRow = $('<tr>' + 
                '<td><span class="task-data">' + task + '</span></td>' + 
                '<td><span class="document-data"><a href="" target="_BLANK">' + docTitle + '</a></span></td>' +
                '<td><span class="date-data">' + date + '</span></td>' +
                '</tr>');

            if (task === "Transcribe") {
                emptyRow.addClass("transcribe-color");
            } else if (task === "Tag") {
                emptyRow.addClass("tag-color");
            } else if (task === "Connect") {
                emptyRow.addClass("connect-color");
            } else if (task === "Discuss") {
                emptyRow.addClass("discuss-color");
            }

            table.append(emptyRow);
        };
    </script>

    <style> 
        #userprofile-header {
            text-align: center;
        }

        #userprofile-activity-container {
            width: 80%;
        }

        .group-link {
            margin-right: 5px;
        }

        .horizontal-align {
            margin: 0 auto;
        }

        th {
            width: 33%;
            text-align: center;
        }

        tr {
            text-align: center;
        }

        hr {
            margin-top: 40px;
            margin-bottom: 40px
        }

        .overview-section {
            width: 25%;
            border: 1px solid black;
            display: inline-block;
            height: 100%;
            text-align: center;
            cursor: help;
        }

        .task-description {
            font-size: 17px;
            position: relative;
            top: 7px;
        }

        .activity-title {
            text-align: center;
        }

        #userprofile-activity-overview-title {

        }

        #transcribe-overview-section {
            -moz-box-shadow:    inset 0 0 15px #F7F2C5;
            -webkit-box-shadow: inset 0 0 15px #F7F2C5;
            box-shadow:         inset 0 0 15px #F7F2C5;
        }

        #tag-overview-section {
            -moz-box-shadow:    inset 0 0 15px #F7D1C5;
            -webkit-box-shadow: inset 0 0 15px #F7D1C5;
            box-shadow:         inset 0 0 15px #F7D1C5;
        }

        #connect-overview-section {
            -moz-box-shadow:    inset 0 0 15px #C9D1F8;
            -webkit-box-shadow: inset 0 0 15px #C9D1F8;
            box-shadow:         inset 0 0 15px #C9D1F8;
        }

        #discuss-overview-section {
            -moz-box-shadow:    inset 0 0 15px #C5F7EB;
            -webkit-box-shadow: inset 0 0 15px #C5F7EB;
            box-shadow:         inset 0 0 15px #C5F7EB;
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
    </style>
</head>
    
<body>
    <div id="userprofile-header">
        <?php
            echo '<h1> Username: '. $_SESSION['Incite']['USER_DATA']['email'] . '</h1>';
        ?>
        <div>
            <p id="groups-list">Belongs to groups: </p>
        </div>
    </div>

    <br>

    <div class="container-fluid horizontal-align" id="userprofile-activity-container">

        <hr size=2 style="margin-top: 0px;">

        <div id="userprofile-activity-overview">
            <h2 class="activity-title" id="userprofile-activity-overview-title">Activity Overview</h2>

            <div class="overview-section" id="transcribe-overview-section">
                <p class="task-description">
                    Transcribed:
                </p>
                <p id="number-transcribed">0 documents<p>
            </div><!--
            --><div class="overview-section" id="tag-overview-section">
                <p class="task-description">
                    Tagged:
                </p>
                <p id="number-tagged">0 documents<p>
            </div><!--
            --><div class="overview-section" id="connect-overview-section">
                <p class="task-description">
                    Connected:
                </p>
                <p id="number-connected">0 documents<p>
            </div><!--
            --><div class="overview-section" id="discuss-overview-section">
                <p class="task-description">
                    Discussed:
                </p>
                <p id="number-discussed">0 documents<p>
            </div>
        </div>

        <br>
        <hr size=2>

        <div id="userprofile-activity-feed">
            <h2 class="activity-title">Activity Feed</h2>
            <table class="table" id="userprofile-activity-feed-table">
                <tr>
                    <th>
                        Task
                    </th>
                    <th>
                        Document
                    </th>
                    <th>
                        Date 
                    </th>
                </tr>
            </table>
        <div>
    </div>
</body>
</html>

