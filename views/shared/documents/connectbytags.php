<!DOCTYPE html>
<html lang="en">
<head>
    <?php
        include(dirname(__FILE__).'/../common/header.php');
        include(dirname(__FILE__).'/../common/progress_indicator.php');
    ?>


    <!-- Page Content -->
    <script type="text/javascript">
        var msgbox;
        var comment_type = 2;
    </script>
</head>

<body>
    <div id="task_description">
        <h1 class="task-header">Connect</h1>
    </div>
    <div class="container-fluid">
        <div class="container-fluid" styl="padding-left: 0px; padding-right: 0px;">
            <div class="col-md-6" id="work-zone">
                <?php
                    include(dirname(__FILE__) . '/../common/document_viewer_section_with_transcription.php');
                ?>
            </div>

            <div class="col-md-6">
                <p class="header-step"><i>Step 1 of 2: Read the summary of the following document(s), which all contain the common tags: <?php echo implode(', ', $this->entities);  ?></i></p>
                <?php foreach((array)$this->related_documents as $document): ?>
                    <div class="col-md-4">
                        <a data-toggle="popover" data-placement="bottom" title="Summary" data-content="<?php echo metadata($document, array('Dublin Core', 'Description')); ?>">
                            <img src="<?php echo $document->getFile()->getProperty('uri'); ?>" class="thumbnail img-responsive">
                        </a>
                        <h4 style=""><?php echo metadata($document, array('Dublin Core', 'Title')); ?></h4>
                    </div>
                <?php endforeach; ?>
                <div class="clearfix"></div>
                <br>
                <br>
                <br>
                <p class="header-step"><i>Step 2 of 2: Answer the following question </i></p>
                <h4>Does the document on the left talk about <a href="" data-toggle="popover" title="Definition" data-content="<?php echo $this->subject_definition; ?>"><?php echo $this->subject; ?></a>?</h4>
                <form method="post">
                    <button type="submit" class="btn btn-success pull-right true-false-button" name="connection" value="true">Yes</button>
                    <button type="submit" class="btn btn-danger pull-right true-false-button" name="connection" value="false">No</button>
                    <input type="hidden" name="subject" value="<?php echo $this->subject_id; ?>" />
                    <input type="hidden" name="query_str" value="<?php echo (isset($this->query_str) ? $this->query_str : ""); ?>" />
                </form>
                
                <hr size=2 class="discussion-seperation-line">

                <?php
                    include(dirname(__FILE__) . '/../common/task_comments_section.php');
                ?>
            </div> 
        </div>
    </div>
    <!-- /.container -->

    <!-- jQuery Version 1.11.1 -->

    <!-- Bootstrap Core JavaScript -->
    <script>
        $(document).ready(function() {
            <?php
                if (isset($_SESSION['incite']['message'])) {
                    echo "notifyOfSuccessfulActionNoTimeout('" . $_SESSION["incite"]["message"] . "');";
                    unset($_SESSION['incite']['message']);
                }
            ?>

            $(".true-false-button").click(function() {
                //from progress_indicator.php
                styleProgressIndicatorForCompletion();
            });
        });
    </script>   
    <style>
        .task-header {
            text-align: center; 
            margin-bottom: 40px; 
            margin-top: 0px;
        }

        #task_description {
            text-align: center;
        }

        .step {
            margin-top: 10px;
        }

        .header-step {
            margin-top: -32px;
        }

        .wrapper {
            overflow: hidden;
        }

        .location {
            background-color: #FFFFBA;
        }

        .organization {
            background-color: #BAE1FF;
        }

        .person {
            background-color: #FFD3B6;
        }

        .event {
            background-color: #A8E6CF;
        }

        .unknown {
            background-color: #FF8B94;
        }

        .discussion-seperation-line {
            margin-top: 70px;
        }

        .tagged-text {
            border-radius: 6px;
            padding: 2px;
            font-size: 15px;
            box-sizing: border-box;
            box-shadow: 2px 2px 2px #888;
        }

        .true-false-button {
            width: 50%;
        }
    </style>

</body>
</html>
