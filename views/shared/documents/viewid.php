<!DOCTYPE html>
<html lang="en">
<head>
    <?php
        include(dirname(__FILE__) . '/../common/header.php');
    ?>

    <script type="text/javascript">
        var comment_type = 3;
        var fullInciteUrl = "<?php echo getFullInciteUrl(); ?>";
        var documentId = "<?php echo $this->documentId; ?>";

        $(document).ready(function () {
            <?php
                if (isset($_SESSION['incite']['message'])) {
                    echo "notifyOfSuccessfulActionNoTimeout('" . $_SESSION["incite"]["message"] . "');";
                    unset($_SESSION['incite']['message']);
                }
            ?>
        });

        function addFinishedTask(type) {
            var unfinishedTask = $('<li><p><span class="glyphicon glyphicon-check" aria-hidden="true"></span>Document has been ' + type + '</p></li>');

            $('#tasks-list').append(unfinishedTask);
        }

        function addUnfinishedTask(typeWithAnEd, type) {
            var link = fullInciteUrl + "/documents/" + type.toLowerCase() + "/" + documentId;

            var finishedTask = $('<li><p><span class="glyphicon glyphicon-unchecked" aria-hidden="true"></span>Document has not been ' + typeWithAnEd + '..  <a href="">' + type + ' it now!</a></p></li>');

            finishedTask.find('a').attr("href", link);

            $('#tasks-list').append(finishedTask);
        }
    </script>

    <style>
        .header-step {
            margin-top: -25px;
        }

        #tasks-list {
            list-style-type: none;
        }

        .glyphicon {
            margin-right: 5px;
        }

        .glyphicon-check {
            color: #5CB85C;
        }

        .glyphicon-unchecked {
            color: #F0AD4E;
        }
    </style>
</head>
    
<body>
    <?php
        include(dirname(__FILE__) . '/../common/task_header.php');
    ?>
    <div class="container-fluid">
        <div class="container-fluid" style="padding: 0px;">

            <div class="col-md-6" id="work-zone">
               <?php
                    include(dirname(__FILE__) . '/../common/document_viewer_with_all_tasks.php');
                ?>
            </div>

            <div class="col-md-6" id="right-column">
                <p class="header-step">
                    <i>Document Tasks</i>
                    <span class="glyphicon glyphicon-info-sign step-instruction-glyphicon"
                        aria-hidden="true" data-trigger="hover"
                        data-toggle="popover" data-html="true"
                        data-viewport="#right-column";
                        data-title="<strong>Viewing Tasks</strong>" 
                        data-content="<?php echo "See below for details about which document tasks have been completed thus far." ?>" 
                        data-placement="bottom">
                    </span>
                </p>

                <div id="document-progress-review-section">
                    <ul id="tasks-list">
                        <?php
                            if ($this->hasTranscription) {
                                echo '<script type="text/javascript">addFinishedTask("transcribed");</script>';
                            } else {
                                echo '<script type="text/javascript">addUnfinishedTask("transcribed", "Transcribe"); 
                                    $("#transcriptionTab").hide();
                                    $("#taggedTranscriptionTab").hide();
                                    $("#connectTab").hide();</script>';
                            }

                            if ($this->hasTaggedTranscription) {
                                echo '<script type="text/javascript">addFinishedTask("tagged");</script>';
                            } else {
                                echo '<script type="text/javascript">addUnfinishedTask("tagged","Tag");
                                    $("#taggedTranscriptionTab").hide();
                                    $("#connectTab").hide();</script>';
                            }

                            if ($this->hasBeenConnected) {
                                echo '<script type="text/javascript">addFinishedTask("connected");</script>';
                            } else {
                                echo '<script type="text/javascript">addUnfinishedTask("connected","Connect");
                                    $("#connectTab").hide();</script>';
                            }
                        ?>
                    </ul>
                </div>

                <?php
                    include(dirname(__FILE__) . '/../common/task_comments_section.php');
                ?>
            </div> 

        </div>
    </div>

</body>
</html>

