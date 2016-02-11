<!DOCTYPE html>
<html lang="en">
<head>
    <?php
        include(dirname(__FILE__).'/../common/header.php');
        include(dirname(__FILE__).'/../common/progress_indicator.php');

        $category_object = getAllCategories();
    ?>

    <!-- Page Content -->
    <script type="text/javascript">
        var comment_type = 2;
        $(function () {   
            getNewComments(<?php echo $this->connection->id; ?>);
        });
    </script>
</head>
<body>
    <div id="task_description">
        <h1 class="task-header">Connect</h1>
    </div>
    <div class="container-fluid">
        <div class="container-fluid" style="padding: 0px;">

            <div class="col-md-6" id="work-zone">
                <div style="position: fixed;" id="work-view">
                    <div class="document-header">
                        <span class="document-title" title="<?php echo metadata($this->connection, array('Dublin Core', 'Title')); ?>">
                            <b>Title:</b> <?php echo metadata($this->connection, array('Dublin Core', 'Title')); ?>
                        </span>
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

                    <div id="tabs-and-legend-container">
                        <ul class="nav nav-tabs document-display-type-tabs">
                            <li role="presentation" class="active" id="hide"><a href="#">Transcription</a></li>
                            <li role="presentation" id="show"><a href="#">Document</a></li>
                        </ul>

                        <div id="tag-legend">
                            <span><b>Legend: </b></span>
                            <?php foreach ((array)$this->category_colors as $category => $color): ?>
                                <em class="<?php echo strtolower($category); ?> legend-item"><?php echo ucfirst(strtolower($category)); ?></em>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div style="border: 1px solid black; overflow: scroll;" name="transcribe_text" rows="20" id="transcribe_copy" style="width: 100%;">
                        <?php print_r($this->transcription); ?>
                    </div>
                    <div class="wrapper">
                        <div id="document_img" class="viewer"></div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <p class="header-step"><i>Step 1 of 1: Mark all categories that apply to this document</i></p>

                <form id="subject-form" method="post">
                    <?php foreach ((array)$this->subjects as $subject): ?>
                                        <input type="checkbox" class="subject-checkbox" name="subjects[]" value="<?php echo $subject['id']; ?>">  
                                        <label><a data-toggle="popover" data-trigger="hover" data-title="Definition" data-content="<?php echo $subject['definition']; ?>"><?php echo $subject['name']; ?></a></label>
                                        <br>
                    <?php endforeach; ?>
                    <input type="checkbox" class="none-checkbox" name="no_subjects" value="-1">  
                    <label>None</label>
                    <br>
                    <input type="hidden" name="query_str" value="<?php echo (isset($this->query_str) ? $this->query_str : ""); ?>">  
                    <button type="button" id="submit-selection-btn" class="btn btn-primary pull-right">Submit</button>
                </form>

                <hr size=2 class="discussion-seperation-line">

                <div id="container">
                    <h3> Comment </h3>
                    <div id="onLogin">
                        <?php if (isset($_SESSION['Incite']['IS_LOGIN_VALID']) && $_SESSION['Incite']['IS_LOGIN_VALID'] == true /** && is_permitted **/): ?>
                            <textarea name="comment_text" cols="60" rows="10" id="comment" class="comment-textarea" placeholder="Your comment"></textarea>
                            <button type="button" class="btn btn-default submit-comment-btn" onclick="submitComment(<?php echo $this->connection->id; ?>)">Post Comment</button>
                        <?php else: ?>
                            Please login or signup to join the discussion!
                        <?php endif; ?>
                    </div>
                    <br>
                    <br>
                    <ul id="comments" class="comments-list">
                    </ul>
                </div>
            </div> 

        </div>
    </div>
    <!-- /.container -->

    <!-- jQuery Version 1.11.1 -->

    <!-- Bootstrap Core JavaScript -->
    <script>
        $('#work-zone').ready(function() {
            $('#work-view').width($('#work-zone').width());
        });

        $(document).ready(function() {
            $('[data-toggle="popover"]').popover({ trigger: "hover" });
            $("#document_img").hide();

            var selectTab = function (tabToSelect, tabToUnselect) {
                tabToSelect.addClass("active");
                tabToUnselect.removeClass("active");
            };

            $("#hide").click(function(){
                $("#document_img").hide();
                $("#transcribe_copy").show();
                selectTab($("#hide"), $("#show"));
            });

            $("#show").click(function(){
                $("#document_img").show();
                $("#transcribe_copy").hide();
                selectTab($("#show"), $("#hide"));
            });

            $(".subject-checkbox").on('click', function(e) {
                $(".none-checkbox").prop('checked', false);
            });

            $(".none-checkbox").on('click', function(e) {
                $(".subject-checkbox").each(function(index, checkbox) {
                    $(this).prop('checked', false);
                });
            });

            $(document).on('click', 'button', function (event)
            {
                if (event.target.name === "reply")
                {
                    var NewContent = '<div class="reply-container"><form id="reply-form" method="POST"><textarea name="transcribe_text" cols="60" rows="10" class="reply-box" id="replyBox' + event.target.id.substring(5) + '" placeholder="Your Reply"></textarea><button type="button" onclick="submitReply(event<?php echo ', '.$this->connection->id; ?>)" class="btn btn-default submit-reply" id="submit' + event.target.id.substring(5) + '" value="' + event.target.value + '">Post Reply</button></form>';
                    $("#" + event.target.id).after(NewContent);
                    $("#" + event.target.id).remove();
                }
            });

            $('.viewer').height($(window).height()-$('#transcribe_copy')[0].getBoundingClientRect().top-10-$(".navbar-fixed-bottom").height());
            $('#transcribe_copy').height($(window).height()-$('#transcribe_copy')[0].getBoundingClientRect().top-10-$(".navbar-fixed-bottom").height());
            $("#document_img").iviewer({
                src: "<?php echo $this->connection->getFile()->getProperty('uri'); ?>",
                zoom_min: 1,
                zoom: "fit"
            });

            <?php
                if (isset($_SESSION['incite']['message'])) {
                    echo "notifyOfSuccessfulActionNoTimeout('" . $_SESSION["incite"]["message"] . "');";
                    unset($_SESSION['incite']['message']);
                }
            ?>

            $("#submit-selection-btn").click(function() {
                if ($('input[type="checkbox"]:checked').length === 0) {
                    notifyOfErrorInForm("At least one category must be selected")
                    return;
                }

                //from progress_indicator.php
                styleProgressIndicatorForCompletion();

                $("#subject-form").submit();
            });
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
            display: inline-block;
            overflow: hidden;
            width: 75%;
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

        .viewer {
            width: 100%;
            border: 1px solid black;
            position: relative;
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
            margin-top: 60px;
        }

        .legend-item {
            border-radius: 6px;
            padding: 2px;
            font-size: 13px;
            box-sizing: border-box;
            box-shadow: 2px 2px 2px #888;
        }

        #tabs-and-legend-container {
            overflow: hidden;
            height: 42px;
        }

        #tag-legend {
            display: inline-block; 
            position: relative; 
            top: 10px;
            width: 70%;
            text-align: right;
        }

        .document-display-type-tabs {
            display: inline-block; 
            vertical-align: top;
            width: 29%;
        }

        .submit-reply {
            float: right;
        }

        .reply-box {
            margin-bottom: 10px;
            width: 100%;
            height: 80px;
        }

        .reply-container {
            width: 50%;
            margin-bottom: 30px;
        }

        .tagged-text {
            border-radius: 6px;
            padding: 2px;
            font-size: 15px;
            box-sizing: border-box;
            box-shadow: 2px 2px 2px #888;
        }
    </style>
</body>
</html>
