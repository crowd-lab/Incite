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
            var numberOfTasksCompleted = 0;

            <?php
                if (isset($_SESSION['incite']['message'])) {
                    echo "notifyOfSuccessfulActionNoTimeout('" . $_SESSION["incite"]["message"] . "');";
                    unset($_SESSION['incite']['message']);
                }

                if ($this->hasTranscription) {
                    echo 'numberOfTasksCompleted++;';
                }

                if ($this->hasTaggedTranscriptionForNewestTranscription) {
                    echo 'numberOfTasksCompleted++;';
                }

                if ($this->hasBeenConnected) {
                    echo 'numberOfTasksCompleted++;';
                }
            ?>

            styleForNumberOfTasksCompleted(numberOfTasksCompleted);

            $('#group-instructions-section-header').hide();
        });

        function styleForNumberOfTasksCompleted(numberOfTasksCompleted) {
            if (numberOfTasksCompleted > 0) {
                markTaskCompleted("transcribe", "transcribed");

                if (numberOfTasksCompleted > 1) {
                    markTaskCompleted("tag", "tagged");

                    if (numberOfTasksCompleted > 2) {
                        markTaskCompleted("connect", "connected");
                        $("#success-indicator-bar").width("100%");
                    } else {
                        $("#connectTab").hide();
                        $("#success-indicator-bar").width("66.66%");
                    }
                } else {
                    $("#taggedTranscriptionTab").hide();
                    $("#connectTab").hide();
                    $("#success-indicator-bar").width("33.33%");
                }
            } else {
                $("#transcriptionTab").hide();
                $("#taggedTranscriptionTab").hide();
                $("#connectTab").hide();
            }
        }

        function markTaskCompleted(taskType, taskTypeWithAnEd) {
            $("#" + taskType + "-progress-section").removeClass("progres-shadow").addClass("success-shadow").attr("title", "Document has been " + taskTypeWithAnEd + ", click to edit now!");
            $("#" + taskType + "-progress-glyph-span").removeClass("glyphicon-unchecked").addClass("glyphicon-check");
        }
    </script>

    <style>
        .header-step {
            margin-top: -25px;
            font-size: 25px;
            position: relative;
            top: -8px;
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

        .step-instruction-glyphicon {
            font-size: 20px;
        }

        #discussion-seperation-line {
            margin-top: 30px;
        }

        .vertical-align {
            position: relative;
            top: 50%;
            -webkit-transform: translateY(-50%);
            -ms-transform: translateY(-50%);
            transform: translateY(-50%);
        }

        .task-section {
            width: 100%;
            height: 33.33%;
            border: 1px solid black;
            text-align: center;
            font-size: 40px;
            cursor: pointer;
        }

        #document-progress-section {
            height: 350px;
            background-color: #F8F8F8;
        }

        .progress-shadow {
            -moz-box-shadow:    inset 0 0 10px #F0AD4E;
            -webkit-box-shadow: inset 0 0 10px #F0AD4E;
            box-shadow:         inset 0 0 10px #F0AD4E;
        }

        .success-shadow {
            -moz-box-shadow:    inset 0 0 10px #5CB85C;
            -webkit-box-shadow: inset 0 0 10px #5CB85C;
            box-shadow:         inset 0 0 10px #5CB85C;
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

                <div class="progress" style="height: 48px;">
                    <div class="progress-bar progress-bar-success" id="success-indicator-bar" style="width: 0%;">
                        <span class="sr-only"></span>
                    </div>
                </div>

                <div id="document-progress-section">
                    <div class="task-section progress-shadow" title="Document not yet been transcribed, click to transcribe it now!" id="transcribe-progress-section">
                        <a href="<?php echo getFullInciteUrl(); ?>/documents/transcribe/<?php echo $this->documentId; ?>">
                            <p class="task-description vertical-align">
                                Transcribe
                                <span class="glyphicon glyphicon-unchecked" id="transcribe-progress-glyph-span" aria-hidden="true"></span>
                            </p>
                        </a>
                    </div><!--
                    --><div class="task-section progress-shadow" title="Document not yet been tagged, click to tag it now!" id="tag-progress-section">
                        <a href="<?php echo getFullInciteUrl(); ?>/documents/tag/<?php echo $this->documentId; ?>">
                            <p class="task-description vertical-align">
                                Tag
                                <span class="glyphicon glyphicon-unchecked" id="tag-progress-glyph-span" aria-hidden="true"></span>
                            </p>
                        </a>
                    </div><!--
                    --><div class="task-section progress-shadow" title="Document not yet been connected, click to connect it now!" id="connect-progress-section">
                        <a href="<?php echo getFullInciteUrl(); ?>/documents/connect/<?php echo $this->documentId; ?>">
                            <p class="task-description vertical-align">
                                Connect
                                <span class="glyphicon glyphicon-unchecked" id="connect-progress-glyph-span" aria-hidden="true"></span>
                            </p>
                        </a>
                    </div>
                </div>

                <hr size=2 id="discussion-seperation-line">

                <?php
                    include(dirname(__FILE__) . '/../common/task_comments_section.php');
                ?>
            </div>

        </div>
    </div>

</body>
</html>
