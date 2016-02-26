<!DOCTYPE html>
<html lang="en">
<head>
    <?php
        include(dirname(__FILE__) . '/../common/header.php');
    ?>

    <script type="text/javascript">
        var filters = ["transcribe", "tag", "connect", "discuss"];

        $(document).ready(function () {
            <?php
                if (isset($_SESSION['incite']['message'])) {
                    echo "notifyOfSuccessfulActionNoTimeout('" . $_SESSION["incite"]["message"] . "');";
                    unset($_SESSION['incite']['message']);
                }
            ?>

            $('#create-group-form').hide();

            addListenersToOverview();
            populateGroups();
            populateActivityOverview();
            populateActivityFeed();
            addGroupCreateOrJoinListeners();
        });

        function addListenersToOverview() {
            $('.overview-section').click(function(event) {
                var id = this.id;

                if (id === "transcribe-overview-section") {
                    $(this).toggleClass("transcribe-color");
                    toggleFilter("transcribe");
                } else if (id === "tag-overview-section") {
                    $(this).toggleClass("tag-color");
                    toggleFilter("tag");
                } else if (id === "connect-overview-section") {
                    $(this).toggleClass("connect-color");
                    toggleFilter("connect");
                } else if (id === "discuss-overview-section") {
                    $(this).toggleClass("discuss-color");
                    toggleFilter("discuss");
                }
            });
        }

        function toggleFilter(filter) {
            if (filters.indexOf(filter) > -1) {
                filters.splice(filters.indexOf(filter), 1);
            } else {
                filters.push(filter);
            }

            filterActivityFeed();
        }

        function filterActivityFeed() {
            $("#userprofile-activity-feed-table tr").each(function(index, row) {
                if (filters.length === 0) {
                    $(row).show();
                    return;
                }

                if (filters.indexOf("transcribe") > -1 && $(row).hasClass("transcribe-color") ||
                    filters.indexOf("tag") > -1 && $(row).hasClass("tag-color") ||
                    filters.indexOf("connect") > -1 && $(row).hasClass("connect-color") ||
                    filters.indexOf("discuss") > -1 && $(row).hasClass("discuss-color")) {
                    $(row).show();
                } else {
                    if (!$(row).hasClass("activity-feed-table-header")) {
                        $(row).hide();
                    }
                }
            });
        }

        function populateGroups() {
            <?php foreach ((array) $this->groups as $group): ?>
                $('#groups-list').append(createGroupLink("<?php echo $group['name']; ?>", <?php echo $group['id']; ?>));
            <?php endforeach; ?>
        };

        function createGroupLink(groupname, groupid) {
            return $('<span class="group-link"><a href="<?php echo getFullInciteUrl(); ?>/groups/view/'+groupid+'" target="_BLANK">' + groupname + '</a></span>');
        };

        function populateActivityOverview() {
            $("#number-transcribed").html("<?php echo count($this->transcribed_docs); ?>" + " document(s)");
            $("#number-tagged").html("<?php echo count($this->tagged_docs); ?>" + " document(s)");
            $("#number-connected").html("<?php echo count($this->connected_docs); ?>" + " document(s)");
            $("#number-discussed").html("<?php echo count($this->discussions); ?>" + " discussion(s)");
        };

        function populateActivityFeed() {
            <?php foreach ((array)$this->activities as $activity): ?>
                generateAndAppendRow($("#userprofile-activity-feed-table"), "<?php echo $activity['activity_type']; ?>", "<?php echo (($activity['activity_type'] === 'Discuss') ? $activity['discussion_title'] : $activity['document_title']); ?>", <?php echo (($activity['activity_type'] === 'Discuss') ? $activity['discussion_id'] : $activity['document_id']); ?>, "<?php echo $activity['time']; ?>");
            <?php endforeach; ?>
        };

        function generateAndAppendRow(table, task, docTitle, docID, date) {
            var emptyRow = $('<tr>' + 
                '<td><span class="task-data">' + task + '</span></td>' + 
                '<td><span class="document-data"><a href="<?php echo getFullInciteUrl(); ?>'+
                (task === 'Discuss' ? '/discussions/discuss/' : '/documents/view/') +
                docID+'" target="_BLANK">' + docTitle + '</a></span></td>' +
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

        function addGroupCreateOrJoinListeners() {
            $('#join-group-tab').click(function () {
                $("#create-group-form").hide();
                $("#join-group-table").show();
                selectTab($("#join-group-tab"), $("#create-group-tab"));
            });

            $('#create-group-tab').click(function () {
                $("#join-group-table").hide();
                $("#create-group-form").show();
                selectTab($("#create-group-tab"), $("#join-group-tab"));
            });
        };

        function selectTab(tabToSelect, tabToUnselect) {
            tabToSelect.addClass("active");
            tabToUnselect.removeClass("active");
        };
    </script>

    <style> 
        #join-or-create-info-container {
            padding: 15px;
        }

        #group-create-or-join-tabs {
            border-bottom: 1px solid #EEEEEE;
        }

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
            cursor: pointer;
        }

        .task-description {
            font-size: 17px;
            position: relative;
            top: 7px;
        }

        .activity-title {
            text-align: center;
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

        #group-name-input {
            width: 300px;
            margin-bottom: 7px;
        }

        .nav-tabs > li {
            width: 50%;
            margin-top: 20px;
            text-align: center;
        }

        #group-create-submit-btn {
            display: block;
            width: 390px;
            margin: 0 auto;
        }

        #create-group-form {
            text-align: center;
        }

        #join-group-table {
            border-top-style: hidden;
        }
    </style>
</head>
    
<body>
    <div id="userprofile-header">
        <?php
            echo '<h1> Username: '. $this->user['email'] . '</h1>';
        ?>

        <div>
            <p id="groups-list">Belongs to group(s): </p>
        </div>
    </div>

    <div class="container-fluid horizontal-align" id="userprofile-activity-container">

        <div id="group-creation-and-join-container">
            <ul class="nav nav-tabs" id="group-create-or-join-tabs">
                <li id="join-group-tab" class="active"><a>Join a group</a></li>
                <li id="create-group-tab"><a>Create a group</a></li>
            </ul>

            <div id="join-or-create-info-container">
                <table class="table" id="join-group-table">
                    <tr>
                        <th>
                            Group Name
                        </th>
                        <th>
                            Group Creator
                        </th>
                        <th>
                            Request to Join
                        </th>
                    </tr>
                </table>

                <form id="create-group-form">
                    <span>Group name:</span>
                    <input id="group-name-input" type="text" name="field" placeholder="Ex: Mr. Smith's History Class" />
                    <button id="group-create-submit-btn" class="btn btn-primary">Create Group</button>
                </form>
            </div>
        </div>

        <hr size=2 style="margin-top: 0px;">

        <div id="userprofile-activity-overview">
            <h2 class="activity-title" id="userprofile-activity-overview-title">Activity Overview</h2>
            <p class="activity-title">Select sections below to filter the activity feed</p>

            <div class="overview-section transcribe-color" id="transcribe-overview-section">
                <p class="task-description">
                    Transcribed:
                </p>
                <p id="number-transcribed">0 documents<p>
            </div><!--
            --><div class="overview-section tag-color" id="tag-overview-section">
                <p class="task-description">
                    Tagged:
                </p>
                <p id="number-tagged">0 documents<p>
            </div><!--
            --><div class="overview-section connect-color" id="connect-overview-section">
                <p class="task-description">
                    Connected:
                </p>
                <p id="number-connected">0 documents<p>
            </div><!--
            --><div class="overview-section discuss-color" id="discuss-overview-section">
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
                <tr class="activity-feed-table-header">
                    <th>
                        Task
                    </th>
                    <th>
                        Document/Discussion
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

