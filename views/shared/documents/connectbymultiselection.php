<!DOCTYPE html>
<html lang="en">
<?php
include(dirname(__FILE__).'/../common/header.php');
?>


    <!-- Page Content -->
    <script type="text/javascript">
        $(function ()
        {
            getNewComments(<?php echo $this->connection->id; ?>);
        });
    </script>
    <div class="container">

        <div id="task_description" style="text-align: center;">
            <h2 style="text-align: center;">Connect</h2>
            <span style="text-align: center;">The system has found some document(s) related to the document on the left. Please help check if they share the same subject.
            </span>
        </div>
        <br>
        <div class="container-fluid">
            <div class="col-md-6" id="work-zone">
                <div style="position: fixed; width: 35%;" id="work-view">
                    <h4>Information:</h4>
                        <div>Title: <?php echo metadata($this->connection, array('Dublin Core', 'Title')); ?></div>
                        <div>Date: <?php echo metadata($this->connection, array('Dublin Core', 'Date')); ?></div>
                        <div>Location: <?php echo metadata($this->connection, array('Item Type Metadata', 'Location')); ?></div>
                        <div>Description: <?php echo metadata($this->connection, array('Dublin Core', 'Description')); ?></div>
                    <h4>Transcription:</h4>
                    <div>
<?php foreach ($this->category_colors as $category => $color): ?>
                        <span style="background-color:<?php echo $color; ?>;"><?php echo ucfirst(strtolower($category)); ?></span>
<?php endforeach; ?>
                    </div>
                    <div style="border-style: solid;" name="transcribe_text" rows="20" id="transcribe_copy" style="width: 100%;"><?php print_r($this->transcription); ?></div>
                    <div class="wrapper">
                        <div id="document_img" class="viewer"></div>
                    </div>
                    <button type="button" class="btn btn-default" id="show">Document</button>
                    <button type="button" class="btn btn-default" id="hide">Transcription</button>
                </div>
            </div>
            <div class="col-md-6">
                <h4>Please find out the subjects that this document talks about.</h4>

                <form id="subject-form" method="post">
<?php foreach ((array)$this->subjects as $subject): ?>
                    <input type="checkbox" name="subjects[]" value="<?php echo $subject['id']; ?>">  
                    <label><a data-toggle="popover" data-trigger="hover" data-title="Definition" data-content="<?php echo $subject['definition']; ?>"><?php echo $subject['name']; ?></a></label>
                    <br>
<?php endforeach; ?>
                    <input type="checkbox" name="no_subjects" value="-1">  
                    <label>None</label>
                    <br>
                    <input type="hidden" name="query_str" value="<?php echo (isset($this->query_str) ? $this->query_str : ""); ?>">  
                    <button type="submit" class="btn btn-primary pull-right">Submit</button>
                </form>
                <br>
                <br>
                <br>
                <div id="container">
                    <h3> Discussion </h3>
                    <div id="onLogin">
<?php if (isset($_SESSION['Incite']['IS_LOGIN_VALID']) && $_SESSION['Incite']['IS_LOGIN_VALID'] == true /** && is_permitted **/): ?>
                    <textarea name="comment_text" cols="60" rows="10" id="comment" placeholder="Your comment"></textarea>
                    <button type="button" class="btn btn-default" onclick="submitComment(<?php echo $this->connection->id; ?>)">Submit</button>
<?php else: ?>
                    Please login or signup to join the discussion!
<?php endif; ?>
                    </div>
                    <ul id="comments">
                    </ul>
                </div>
            </div> 
        </div>




  
</div>

    </div>
    <!-- /.container -->

    <!-- jQuery Version 1.11.1 -->

    <!-- Bootstrap Core JavaScript -->
    <script>
$(document).ready(function(){
    $('[data-toggle="popover"]').popover({ trigger: "hover" });
    $("#document_img").hide();

    $("#hide").click(function(){
        $("#document_img").hide();
        $("#transcribe_copy").show();
    });
    $("#show").click(function(){
        $("#document_img").show();
        $("#transcribe_copy").hide();
    });
    $(document).on('click', 'button', function (event)
    {
        if (event.target.name === "reply")
        {
            var NewContent = '<form id="reply-form" method="POST"><textarea name="transcribe_text" cols="60" rows="10" id="replyBox' + event.target.id.substring(5) + '" placeholder="Your Reply"></textarea><button type="button" onclick="submitReply(event<?php echo ', '.$this->connection->id; ?>)" class="btn btn-default" id="submit' + event.target.id.substring(5) + '" value="' + event.target.value + '">Submit</button></form>';
            $("#" + event.target.id).after(NewContent);
            $("#" + event.target.id).remove();
        }
    });
});
</script>
    <script type="text/javascript">
            $('#work-zone').ready(function() {
                $('#work-view').width($('#work-zone').width());
            });
            $(document).ready(function(){

                var iv2 = $("#document_img").iviewer({
                    src: "<?php echo $this->connection->getFile()->getProperty('uri'); ?>"
                });
                $('.viewer').height($(window).height()-$('#transcribe_copy')[0].getBoundingClientRect().top-50);
                $('#transcribe_copy').height($(window).height()-$('#transcribe_copy')[0].getBoundingClientRect().top-50);
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
