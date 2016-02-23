<!DOCTYPE html>
<html lang="en">
    <?php
        include(dirname(__FILE__) . '/../common/header.php');
        include(dirname(__FILE__) . '/../common/progress_indicator.php');
        //$this->transcription must exist because controller has ensured it. If it doesn't exist, then controller should've redirected it to the right place!
    ?>

    <script type="text/javascript">
        var msgbox;
        var comment_type = 0;
    </script>

    <!-- Page Content -->
    <?php
        include(dirname(__FILE__) . '/../common/task_header.php');
    ?>
    <div class="container-fluid">
        <?php
            include(dirname(__FILE__) . '/../common/document_viewer_section_without_transcription.php');
        ?>

        <div class="col-md-6" id="submit-zone">
            <form method="post" id="transcribe-form">
                <p class="header-step" style="margin-bottom: 13px;">
                    <i>Step 1 of 3: Transcribe</i>
                    <span class="glyphicon glyphicon-info-sign step-instruction-glyphicon"
                        aria-hidden="true" data-trigger="hover"
                        data-toggle="popover" data-html="true"
                        data-viewport="#transcribe-form"
                        data-title="<strong>Transcribing a document</strong>" 
                        data-content="<?php echo "<ul>"
                        . "<li>Copy the text exactly as is, including misspellings and abbreviations.</li>"
                        . "<li>You don't need to account for formatting (e.g. spacing, line breaks, alignment).</li>"
                        . "<li>If you can't make out a word replace it with '[illegible]'.</li>" 
                        . "<li>If you are uncertain about a word surround it with square brackets, e.g. '[town?]'</li>"?>" 
                        data-placement="bottom" data-id="<?php echo $transcription->id; ?>">
                    </span>
                </p>
                <textarea id="transcription" name="transcription" rows="15" placeholder="Provide a 1:1 transcription of the document"></textarea>
                <p class="step">
                    <i>Step 2 of 3: Summarize</i>
                    <span class="glyphicon glyphicon-info-sign step-instruction-glyphicon"
                        aria-hidden="true" data-trigger="hover"
                        data-toggle="popover" data-html="true"
                        data-viewport="#transcribe-form"
                        data-title="<strong>Summarizing a document</strong>" 
                        data-content="<?php echo "Using your own wording, summarize the document in 1-2 sentences." ?>" 
                        data-placement="bottom" data-id="<?php echo $transcription->id; ?>">
                    </span>
                </p>
                <textarea id="summary" name="summary" rows="5" placeholder="Provide a 1-2 sentence summary of the document"></textarea>
                <div class="form-group">
                    <p class="step">
                        <i>Step 3 of 3: Select the tone of the document</i>
                        <span class="glyphicon glyphicon-info-sign step-instruction-glyphicon"
                            aria-hidden="true" data-trigger="hover"
                            data-toggle="popover" data-html="true"
                            data-viewport="#transcribe-form"
                            data-title="<strong>Selecting document tone</strong>" 
                            data-content="<?php echo "Choose the tone from the dropdown that most accurately categorizes the overall tone of the document." ?>" 
                            data-placement="bottom" data-id="<?php echo $transcription->id; ?>">
                        </span>
                    </p>
                    <select id="tone" class="form-control" name="tone">
                        <option value="informational" default selected>Informational</option>
                        <option value="anxiety">Anxiety</option>
                        <option value="optimism">Optimism</option>
                        <option value="sarcasm">Sarcasm</option>
                        <option value="pride">Pride</option>
                        <option value="aggression">Aggression</option>
                    </select>
                </div>
                <button id="submit_transcription" type="button" class="btn btn-primary">Submit</button>
                <input type="hidden" name="query_str" value="<?php echo (isset($this->query_str) ? $this->query_str : ""); ?>">  
            </form>

            <br>
            <hr size=2 class="discussion-seperation-line">

            <?php
                include(dirname(__FILE__) . '/../common/task_comments_section.php');
            ?>
        </div> 
    </div>
    <!-- /.container -->
    <script type="text/javascript">
        $(document).ready(function () {
            $('#submit_transcription').on('click', function(e) {
                if ($('#transcription').val() === "") {
                    notifyOfErrorInForm('Please provide a transcription of the document');
                    return;
                }
                if ($('#summary').val() === "") {
                    notifyOfErrorInForm('Please provide a summary of the document');
                    return;
                }
                if ($('#tone').val() === "") {
                    notifyOfErrorInForm('Please select the tone of the document');
                    return;
                }
                $('#transcribe-form').submit();
            });

            <?php
                if (isset($_SESSION['incite']['message'])) {
                    echo "notifyOfSuccessfulActionNoTimeout('" . $_SESSION["incite"]["message"] . "');";
                    unset($_SESSION['incite']['message']);
                }
            ?>
        });
    </script>

    <style>
        #submit_transcription {
            float: right;
        }

        #transcription {
            width: 100%;
        }

        #summary {
            width: 100%; 
            height: 66px;
        }

        .discussion-seperation-line {
            margin-top: 35px;
            margin-bottom: 0px;
        }
    </style>

</body>

</html>
