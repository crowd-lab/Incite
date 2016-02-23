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
    <?php
        include(dirname(__FILE__) . '/../common/task_header.php');
    ?>
    <div class="container-fluid">
        <div class="container-fluid" styl="padding-left: 0px; padding-right: 0px;">
            <div class="col-md-6" id="work-zone">
                <?php
                    include(dirname(__FILE__) . '/../common/document_viewer_section_with_transcription.php');
                ?>
            </div>

            <div class="col-md-6">
                <p class="header-step">
                    <i>Step 1 of 2: Read the summary of the following document(s), which all contain the common tags: <?php echo implode(', ', $this->entities);  ?></i>
                    <span class="glyphicon glyphicon-info-sign step-instruction-glyphicon"
                        aria-hidden="true" data-trigger="hover"
                        data-toggle="popover" data-html="true"
                        data-title="<strong>Reading document summaries</strong>" 
                        data-content="<?php echo "Mouse over a document's image to trigger a popover with the summary of that document." ?>" 
                        data-placement="bottom" data-id="<?php echo $transcription->id; ?>">
                    </span>
                </p>
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
                <p class="header-step">
                    <i>Step 2 of 2: Answer the following question</i>
                    <span class="glyphicon glyphicon-info-sign step-instruction-glyphicon"
                        aria-hidden="true" data-trigger="hover"
                        data-toggle="popover" data-html="true"
                        data-title="<strong>Answering yes/no questions about a document</strong>" 
                        data-content="<?php echo "After completing step 1 and looking at the above documents answer the question below by clicking the 'yes' button or the 'no' button." ?>" 
                        data-placement="right" data-id="<?php echo $transcription->id; ?>">
                    </span>
                </p>
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
        .discussion-seperation-line {
            margin-top: 70px;
        }

        .true-false-button {
            width: 50%;
        }
    </style>

</body>
</html>
