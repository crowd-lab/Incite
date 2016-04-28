<!DOCTYPE html>
<html lang="en">
    <?php
        include(dirname(__FILE__) . '/../common/header.php');
        include(dirname(__FILE__) . '/../common/progress_indicator.php');
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
                <p class="header-step" style="margin-bottom: 13px; position: relative;">
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
                    <a id="view-revision-history-link" style="display: none;">View Revision History...  </a>
                </p>
                
                <textarea id="transcription-textarea" name="transcription" rows="15" placeholder="Provide a 1:1 transcription of the document"></textarea>
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
                <textarea id="summary-textarea" name="summary" rows="5" placeholder="Provide a 1-2 sentence summary of the document"></textarea>
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
                    <select id="tone-selector" class="form-control" name="tone">
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

            <div id="revision-history-container" style="display: none;">
                <div id="revision-history-header-container">
                    <h3 id="revision-history-header">Revision History</h3>
                    <a id="view-editing-link">Return to editing..</a>
                </div>

                <table  style="border: 1px solid black;">
                    <tbody id="revision-history-table-body">
                        <tr>
                            <th>User</th>
                            <th>Edited On</th>
                        </tr>
                        <?php foreach ((array)$this->revision_history as $revision): ?>
                            <tr>
                                <td><a href="<?php echo getFullInciteUrl();?>/users/view/<?php echo $revision['userID']; ?>"><?php echo $revision['userEmail']; ?></a></td>
                                <td><?php echo $revision['timestamp']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

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
            $('[data-toggle="tooltip"]').tooltip();
            $('#submit_transcription').on('click', function(e) {
                if ($('#transcription-textarea').val() === "") {
                    notifyOfErrorInForm('Please provide a transcription of the document');
                    return;
                }
                if ($('#summary-textarea').val() === "") {
                    notifyOfErrorInForm('Please provide a summary of the document');
                    return;
                }
                if ($('#tone-selector').val() === "") {
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

            <?php if ($this->is_being_edited): ?>
                styleForEditing();    
            <?php endif; ?> 
        });

        function styleForEditing() {
            populateWithLatestTranscriptionData();
            addRevisionHistoryListeners();
        }

        function populateWithLatestTranscriptionData() {
            $('#transcription-textarea').html(<?php echo sanitizeStringInput(isset($this->latest_transcription['transcription']) ? $this->latest_transcription['transcription'] : 'nothing'); ?>.value);
            $('#summary-textarea').html(<?php echo sanitizeStringInput(isset($this->latest_transcription['summary']) ? $this->latest_transcription['summary'] : 'nothing'); ?>.value);
            $('#tone-selector').val('<?php echo isset($this->latest_transcription["tone"]) ? $this->latest_transcription["tone"] : "nothing"; ?>');
        }

        function addRevisionHistoryListeners() {
            $('#view-revision-history-link').show();

            $('#view-revision-history-link').click(function(e) {
                $('#transcribe-form').hide();
                $('#revision-history-container').show();
            });

            $('#view-editing-link').click(function(e) {
                $('#revision-history-container').hide();
                $('#transcribe-form').show();
            });
        }
    </script>

    <style>
        #submit_transcription {
            float: right;
        }

        #transcription-textarea {
            width: 100%;
        }

        #summary-textarea {
            width: 100%; 
            height: 66px;
        }

        .discussion-seperation-line {
            margin-top: 35px;
            margin-bottom: 0px;
        }

        .tooltip {
            position: fixed;
        }

        #view-revision-history-link {
            position: absolute;
            right: 0;
            cursor: pointer;
        }

        tr {
            text-align: center;
        }

        th {
            text-align: center;
        }

        table {
            width: 100%;
        }

        #revision-history-container {
            position: relative;
            top: -13px;
        }

        #revision-history-header {
            text-align: center;
            margin-top: -20px;
        }

        #view-editing-link {
            position: absolute;
            top: 0;
            right: 0;
        }
    </style>

</body>

</html>
