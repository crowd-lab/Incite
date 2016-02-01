<!DOCTYPE html>
<html lang="en">
<?php
include(dirname(__FILE__).'/../common/header.php');
?>


    <!-- Page Content -->
    <script type="text/javascript">
    var msgbox;
        $(function ()
        {
            getNewComments(<?php echo $this->connection->id; ?>);
        });
    </script>
    <div id="task_description">
        <h1 class="task-header">Connect</h1>
    </div>
    <div class="container">
        <div class="container-fluid">
            <div class="col-md-6" id="work-zone">
                <div style="position: fixed; width: 35%;" id="work-view">
                    <div class="document-header">
                        <span class="document-title"><b>Title:</b> <?php echo metadata($this->connection, array('Dublin Core', 'Title')); ?></span>
                        <span class="document-additional-info" 
                            data-toggle="popover" data-html="true" data-trigger="hover" 
                            data-title="Additional Information" 
                            data-content="<?php echo "<strong>Date:</strong> " 
                                    . metadata($this->connection, array('Dublin Core', 'Date')) 
                                    . "<br><br> <strong>Location:</strong> " 
                                    . metadata($this->connection, array('Item Type Metadata', 'Location')) 
                                    . "<br><br> <strong>Description:</strong> " 
                                    . metadata($this->connection, array('Dublin Core', 'Description')); ?>" 
                            data-placement="bottom" data-id="<?php echo $this->connection->id; ?>">
                            More about this document..
                        </span>
                    </div> 
                    <div>
                    Legends:
<?php foreach ((array)$this->category_colors as $category => $color): ?>
                    <span class="<?php echo strtolower($category); ?>"><?php echo ucfirst(strtolower($category)); ?></span>
<?php endforeach; ?>
                    </div>
                    <div style="border-style: solid; overflow: scroll;" name="transcribe_text" rows="20" id="transcribe_copy" style="width: 100%;"><?php print_r($this->transcription); ?></div>
                    <div class="wrapper">
                        <div id="document_img" class="viewer"></div>
                    </div>
                    <button type="button" class="btn btn-default" id="show">Document</button>
                    <button type="button" class="btn btn-default" id="hide">Transcription</button>
                </div>
            </div>
            <div class="col-md-6">
                <p class="header-step"><i>Step 1 of 2: Read the summary of the following document(s), which all contain tags: <?php echo implode(', ', $this->entities);  ?></i></p>
<?php foreach((array)$this->related_documents as $document): ?>
                <div class="col-md-4">
                    <a data-toggle="popover" title="Summary" data-content="<?php echo metadata($document, array('Dublin Core', 'Description')); ?>">
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
                    <button type="submit" class="btn btn-default pull-right" name="connection" value="true">Yes</button>
                    <button type="submit" class="btn btn-default pull-right" name="connection" value="false">No</button>
                    <input type="hidden" name="subject" value="<?php echo $this->subject_id; ?>" />
                    <input type="hidden" name="query_str" value="<?php echo (isset($this->query_str) ? $this->query_str : ""); ?>" />
                </form>
                <div class="clearfix"></div>
                <div id="container">
                    <h3> Discussion </h3>
                    <div id="onLogin">
<?php if (isset($_SESSION['Incite']['IS_LOGIN_VALID']) && $_SESSION['Incite']['IS_LOGIN_VALID'] == true /** && is_permitted **/): ?>
                            <textarea name="comment_text" cols="60" rows="10" id="comment" class="comment-textarea" placeholder="Your comment"></textarea>
                            <button type="button" class="btn btn-default submit-comment-btn" onclick="submitComment(<?php echo $this->connection->id; ?>)">Post Comment</button>
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
                $('.viewer').height($(window).height()-$('#transcribe_copy')[0].getBoundingClientRect().top-50);
                $('#transcribe_copy').height($(window).height()-$('#transcribe_copy')[0].getBoundingClientRect().top-50);
                $("#document_img").iviewer({
                    src: "<?php echo $this->connection->getFile()->getProperty('uri'); ?>",
                    zoom_min: 1,
                    zoom: "fit"
                });
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
        #task_description {
            text-align: center;
        }
        .step {
            margin-top: 10px;
        }

        .header-step {
            margin-top: -32px;
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
    .location {
        background-color: yellow;
    }
    .organization {
        background-color: red;
    }
    .person {
        background-color: orange;
    }
    .event {
        background-color: green;
    }
    .unknown {
        background-color: gray;
    }
        .discussion-seperation-line {
            margin-top: 40px;
        }
        </style>

</body>

</html>
