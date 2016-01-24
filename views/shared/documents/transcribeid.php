<!DOCTYPE html>
<html lang="en">
    <?php
    include(dirname(__FILE__) . '/../common/header.php');
//$this->transcription must exist because controller has ensured it. If it doesn't exist, then controller should've redirected it to the right place!
    
    ?>
    <script type="text/javascript">
        $(function ()
        {
            getNewComments(<?php echo $this->transcription->id; ?>);
        });
    </script>


    <!-- Page Content -->
    <div class="container-fluid">
        <div class="col-md-6" id="work-zone">
            <div style="position: fixed; width: 35%;" id="work-view">
                <div>Title: <?php echo metadata($this->transcription, array('Dublin Core', 'Title')); ?></div>
                <div>Date: <?php echo metadata($this->transcription, array('Dublin Core', 'Date')); ?></div>
                <div>Location: <?php echo metadata($this->transcription, array('Item Type Metadata', 'Location')); ?></div>
                <div>Description: <?php echo metadata($this->transcription, array('Dublin Core', 'Description')); ?></div>
                <br>
                <div class="wrapper">
                    <div id="viewer2" class="viewer"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6" id="submit-zone">
            <form method="post" id="transcribe-form">
                <textarea id="transcription" name="transcription" rows="15" style="width: 100%;" placeholder="Transcription"></textarea>
                <textarea id="summary" name="summary" rows="5" style="width: 100%;" placeholder="Summary"></textarea>
                <div class="form-group">
                    <label class="control-label">Tone of the document:</label>
                    <select id="tone" class="form-control" name="tone">
                        <option value=""></option>
                        <option value="anxiety">Anxiety</option>
                        <option value="optimism">Optimism</option>
                        <option value="sarcasm">Sarcasm</option>
                        <option value="pride">Pride</option>
                        <option value="aggression">Aggression</option>
                    </select>
                </div>
                <button id="submit_transcription" type="button" class="btn btn-default">Done</button>
            </form>

            <div id="container">
                <h3> Discussion </h3>
                <div id="onLogin">
<?php if (isset($_SESSION['Incite']['IS_LOGIN_VALID']) && $_SESSION['Incite']['IS_LOGIN_VALID'] == true /** && is_permitted * */): ?>

                        <form id="discuss-form" method="POST">
                            <textarea name="transcribe_text" cols="60" rows="10" id="comment" placeholder="Your comment"></textarea>
                            <button type="button" class="btn btn-default" onclick="submitComment(<?php echo $this->transcription->id; ?>)">Submit</button>
                        </form>

<?php else: ?>
                        Please login or signup to join the discussion!

                    <?php endif; ?>
                </div>
                <ul id="comments">
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

        });


    </script>
    <style>
        .viewer
        {
            width: 100%;
            border: 1px solid black;
            position: relative;
        }

        .wrapper
        {
            overflow: hidden;
        }
    </style>

</body>

</html>
