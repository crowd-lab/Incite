<!DOCTYPE html>
<html lang="en">
    <?php
    include(dirname(__FILE__) . '/../common/header.php');
//$this->transcription must exist because controller has ensured it. If it doesn't exist, then controller should've redirected it to the right place!
    
    ?>
    <script type="text/javascript">
    var msgbox;
        $(function ()
        {
            getNewComments(<?php echo $this->transcription->id; ?>);
        });
    </script>


    <!-- Page Content -->
    <div id="task_description">
        <h1 class="task-header">Transcribe</h1>
    </div>
    <br>
    <div class="container-fluid">
        <div class="col-md-6" id="work-zone">
            <div id="work-view">
                <div class="document-header">
                    <span class="document-title"><b>Document Title:</b> <?php echo metadata($this->transcription, array('Dublin Core', 'Title')); ?></span>
                    <span class="document-additional-info" 
                        data-toggle="popover" data-html="true" data-trigger="hover" 
                        data-title="Additional Information" 
                        data-content="<?php echo "<strong>Date:</strong> " 
                                . metadata($transcription, array('Dublin Core', 'Date')) 
                                . "<br><br> <strong>Location:</strong> " 
                                . metadata($this->transcription, array('Item Type Metadata', 'Location')) 
                                . "<br><br> <strong>Description:</strong> " 
                                . metadata($this->transcription, array('Dublin Core', 'Description')); ?>" 
                        data-placement="bottom" data-id="<?php echo $transcription->id; ?>">
                        See more document information..
                    </span>
                </div> 
                <div class="wrapper">
                    <div id="viewer2" class="viewer"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6" id="submit-zone">
            <form method="post" id="transcribe-form">
                <p class="header-step"><i><u>Step 1: Transcribe</u></i></p>
                <textarea id="transcription" name="transcription" rows="15" placeholder="Provide a 1:1 transcription of the document"></textarea>
                <p class="step"><i><u>Step 2: Summarize</u></i></p>
                <textarea id="summary" name="summary" rows="5" placeholder="Provide a 1-2 sentence summary of the document"></textarea>
                <div class="form-group">
                    <p class="step"><i><u>Step 3: Specify Tone</u></i></p>
                    <select id="tone" class="form-control" name="tone">
                        <option value="" default selected>Select the tone of the document</option>
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

            <div id="container">
                <h3> Discussion </h3>
                <div id="onLogin">
<?php if (isset($_SESSION['Incite']['IS_LOGIN_VALID']) && $_SESSION['Incite']['IS_LOGIN_VALID'] == true /** && is_permitted * */): ?>

                        <form id="discuss-form" method="POST">
                            <textarea name="transcribe_text" cols="60" rows="10" id="comment" class="comment-textarea" placeholder="Your comment"></textarea>
                            <button type="button" class="btn btn-default submit-comment-btn" onclick="submitComment(<?php echo $this->transcription->id; ?>)">Post Comment</button>
                        </form>

<?php else: ?>
                        Please login or signup to join the discussion!

                    <?php endif; ?>
                </div>
                <ul id="comments" class="comments-list">
                </ul>
            </div>
        </div> 
    </div>
    <!-- /.container -->
    <script type="text/javascript">
        $(function () {
            //getAllComments();
            $('[data-toggle="popover"]').popover({trigger: "hover"});

            $(document).on('click', 'button', function (event)
            {
                if (event.target.name === "reply")
                {
                    var NewContent = '<form id="reply-form" method="POST"><textarea name="transcribe_text" cols="60" rows="10" id="replyBox' + event.target.id.substring(5) + '" placeholder="Your Reply"></textarea><button type="button" onclick="submitReply(event<?php echo ', '.$this->transcription->id; ?>)" class="btn btn-default" id="submit' + event.target.id.substring(5) + '" value="' + event.target.value + '">Submit</button></form>';
                    $("#" + event.target.id).after(NewContent);
                    $("#" + event.target.id).remove();
                }
            });

            $('#submit_transcription').on('click', function(e) {
                if ($('#transcription').val() === "") {
                    alert('Transcription can not be empty');
                    return;
                }
                if ($('#summary').val() === "") {
                    alert('Summary can not be empty');
                    return;
                }
                if ($('#tone').val() === "") {
                    alert('Tone can not be empty');
                    return;
                }
                $('#transcribe-form').submit();
            });
        });

        $('#work-zone').ready(function () {
            $('#work-view').width($('#work-zone').width());
        });
        var $ = jQuery;
        $(document).ready(function () {
            var iv2 = $("#viewer2").iviewer({
                src: "<?php echo $this->transcription->getFile()->getProperty('uri'); ?>"
            });


            $('.viewer').height($(window).height() - $('.viewer')[0].getBoundingClientRect().top - 10);

<?php
    if (isset($_SESSION['incite']['message'])) {
        echo "msgbox = BootstrapDialog.alert({message:$('<div>".$_SESSION['incite']['message']."</div>')});\n";
        //echo "setTimeout(closeMsgBox, 3000);\n";
        unset($_SESSION['incite']['message']);
    }
?>
        });


    </script>

    <style>
        .document-header {
            margin-top: -30px;
        }

        .document-title {
            font-size: 20px; 
            position: relative; 
            top: -5px;
        }

        .document-additional-info {
            color: #0645AD; 
            float: right;
        }

        .task-header {
            text-align: center; 
            margin-bottom: 40px; 
            margin-top: 0px;
        }

        .step {
            margin-top: 10px;
        }

        .header-step {
            margin-top: -32px;
        }

        .viewer {
            width: 100%;
            border: 1px solid black;
            position: relative;
        }

        .wrapper {
            overflow: hidden;
        }

        #tone option[default] {
            display: none;
        }

        #submit_transcription {
            float: right;
        }

        .comment-textarea {
            width: 100%; 
            height: 80px; 
            margin-bottom: 10px;
        }

        .submit-comment-btn {
            float: right;
        }

        .comments-list {
            list-style: none;
            padding-left: 0;
        }

        #task_description {
            text-align: center;
        }

        #work-view {
            position: fixed; 
            width: 35%;
        }

        #transcription {
            width: 100%;
        }

        #summary {
            width: 100%; 
            height: 66px;
        }

        .discussion-seperation-line {
            margin-top: 40px;
        }
    </style>

</body>

</html>
