<!DOCTYPE html>
<html lang="en">
    <?php
    include(dirname(__FILE__) . '/../common/header.php');
//$this->transcription must exist because controller has ensured it. If it doesn't exist, then controller should've redirected it to the right place!
    
    ?>
    <script type="text/javascript">

        function getNewComments()
        {
            var documentId = <?php echo $this->transcription->id; ?>;
            var request = $.ajax({
                type: "POST",
                url: "http://localhost/m4j/incite/ajax/getcommentsdoc",
                data: {documentId: documentId},
                success: function (data)
                {
                    var commentsArray = JSON.parse(data);


                    for (var i = 0; i < commentsArray.length; i++)
                    {

                        var databaseDate = new Date(commentsArray[i]['question_timestamp']);


                        var format = compareDates(databaseDate);
                        var commentsArrayObject = {commentsArray};
                        var isSignedIn = $.ajax({
                            type: 'POST',
                            url: 'http://localhost/m4j/incite/ajax/issignedin',
                            async: false,
                            data: {loopVar: i, commentArray: commentsArray, format: format},
                            success: appendNewComment
                        });
                    }
                }
            });
        }
        function appendNewComment(dataArray)
        {
            
            var parsedData = JSON.parse(dataArray);
            var commentsArray = parsedData[2];
            var format = parsedData[3];
            var i = parseInt(parsedData[1]);
            var boolean = parsedData[0];

            var commentsArrayReplies = commentsArray[i]['question_replies'];
            var commentsArrayRepliesTimestamp = commentsArray[i]['question_replies_timestamp'];
            var commentsArrayRepliesUserData = commentsArray[i]['question_replies_user_data'];
            if (boolean)
            {
                var dynamicLi = document.createElement('li');
                dynamicLi.className = "cmmnt";
                var dynamicDiv = document.createElement('div');
                dynamicDiv.className = "cmmnt-content";
                dynamicDiv.innerHTML = '<header><a href="javascript:void(0);" class="userlink">' + commentsArray[i]['user_info'][0] + '</a> - <span class="pubdate">' + format + '</span></header><p>' + commentsArray[i]['question_text'] + '</p>';

                if (commentsArrayReplies != null && commentsArrayReplies.length > 0)
                {
                    var string = "<ul><li><header><a href='javascript:void(0);' class='userlink'>";
                    for (var j = 0; j < commentsArrayReplies.length; j++)
                    {
                        var databaseDate = new Date(commentsArrayRepliesTimestamp[j]);
                        string += commentsArrayRepliesUserData[j][0] + '</a> - <span class="pubdate">' + compareDates(databaseDate) + '</span></header><p>' + commentsArrayReplies[j] + "</p></li>";
                        if (j != commentsArrayReplies.length - 1)
                        {
                            string += "<li><header><a href='javascript:void(0);' class='userlink'>";
                        }
                    }
                    string += "</ul>"
                    dynamicDiv.innerHTML += (string + '<button type="button" name="reply" class="btn btn-default reply-comment" id="reply' + i + '" value="' + commentsArray[i]['question_id'] + '">Reply</button>');
                } else
                {
                    dynamicDiv.innerHTML += '<button type="button" name="reply" class="btn btn-default reply-comment" id="reply' + i + '" value="' + commentsArray[i]['question_id'] + '">Reply</button>';
                }

                dynamicLi.appendChild(dynamicDiv);
                document.getElementById("comments").appendChild(dynamicLi);
            } else
            {
                var dynamicLi = document.createElement('li');
                dynamicLi.className = "cmmnt";
                var dynamicDiv = document.createElement('div');
                dynamicDiv.className = "cmmnt-content";
                dynamicDiv.innerHTML = '<header><a href="javascript:void(0);" class="userlink">' + commentsArray[i]['user_info'][0] + '</a> - <span class="pubdate">' + format + '</span></header><p>' + commentsArray[i]['question_text'] + '</p>';
                dynamicLi.appendChild(dynamicDiv);
                document.getElementById("comments").appendChild(dynamicLi);
            }
        }
        function compareDates(databaseDate)
        {
            var currentDate = new Date();
            var differenceDate = Math.ceil((currentDate.getTime() - databaseDate.getTime()) / 1000);
            var format = "posted ";
            if (differenceDate < 60)
            {
                format += differenceDate + " second ago";
            } else if (differenceDate < 3600)
            {
                format += (new Date).clearTime().addSeconds(differenceDate).toString('m') + " minutes ago";
            } else if (differenceDate < 86400)
            {
                if (differenceDate < 7200)
                {
                    format += (new Date).clearTime().addSeconds(differenceDate).toString('H') + " hour ago";
                } else
                {
                    format += (new Date).clearTime().addSeconds(differenceDate).toString('H') + " hours ago";
                }
            }
            else if (differenceDate < 31540000)
            {
                if (differenceDate < 2629746)
                {
                    if (parseInt((new Date).clearTime().addSeconds(differenceDate).toString('d')) - 1 == (new Date).toString('d'))
                    {
                        format += "1 day ago";
                    }
                    else
                    {
                        format += (parseInt((new Date).clearTime().addSeconds(differenceDate).toString('d')) - parseInt((new Date).toString('d'))) + " days ago";
                    }
                } 
                else
                {
                    format += (new Date).clearTime().addSeconds(differenceDate).toString('M') + " months ago";
                }
            } else
            {
                if (differenceDate < 63080000)
                {
                    format += " 1 year ago";
                } else
                {
                    if ((new Date).clearTime().addSeconds(differenceDate).toString('YY').charAt(0) == '0')
                    {
                        format += (new Date).clearTime().addSeconds(differenceDate).toString('YY').charAt(1) + " year ago";
                    } else
                    {
                        format += (new Date).clearTime().addSeconds(differenceDate).toString('YY') + " year ago";
                    }
                }
            }
            return format;
        }

        $(function ()
        {
            getNewComments();
        });
    </script>


    <!-- Page Content -->
    <div class="container">

        <div class="row">
            <div class="col-md-8">
            </div>
            <div class="col-md-4">
                <button type="button" class="btn btn-default">Subscribe</button>
                <button type="button" class="btn btn-default">Guide</button>
            </div>
        </div>
        <div class="container">
            <div class="col-md-6" id="work-zone">
                <div style="position: fixed; width: 35%;" id="work-view">
                    <div>Title: <?php echo metadata($this->transcription, array('Dublin Core', 'Title')); ?></div>
                    <div>Date: <?php echo metadata($this->transcription, array('Dublin Core', 'Date')); ?></div>
                    <div>Location: <?php echo metadata($this->transcription, array('Item Type Metadata', 'Location')); ?></div>
                    <div>Description: <?php echo metadata($this->transcription, array('Dublin Core', 'Description')); ?></div>
                    <div class="wrapper">
                        <div id="viewer2" class="viewer"></div>
<!--                        <img src="<?php echo $this->transcription->getFile()->getProperty('uri'); ?>" alt="<?php echo metadata($this->transcription, array('Dublin Core', 'Title')); ?>">
                        -->
                    </div>
                </div>
            </div>
            <div class="col-md-6" id="submit-zone">
                <form method="post" id="transcribe-form">
                    <textarea name="transcription" rows="15" style="width: 100%;" placeholder="Transcription"></textarea>
                    <textarea name="summary" rows="5" style="width: 100%;" placeholder="Summary"></textarea>
                    <div class="form-group">
                        <label class="control-label">Tone of the document:</label>
                        <select class="form-control" name="tone">
                            <option value=""></option>
                            <option value="anxiety">Anxiety</option>
                            <option value="optimism">Optimism</option>
                            <option value="sarcasm">Sarcasm</option>
                            <option value="pride">Pride</option>
                            <option value="aggression">Aggression</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-default">Done</button>
                </form>

                <div id="container">
                    <h3> Discussion </h3>
                    <ul id="comments">

                    </ul>
                    <div id="onLogin">
<?php if (isset($_SESSION['Incite']['IS_LOGIN_VALID']) && $_SESSION['Incite']['IS_LOGIN_VALID'] == true /** && is_permitted * */): ?>

                            <form id="discuss-form" method="POST">
                                <textarea name="transcribe_text" cols="60" rows="10" id="comment" placeholder="Your comment"></textarea>
                                <button type="button" class="btn btn-default" onclick="submitComment()">Submit</button>
                            </form>

<?php else: ?>
                            Please login or signup to join the discussion!

                        <?php endif; ?>
                    </div>
                </div>
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
                    var NewContent = '<form id="reply-form" method="POST"><textarea name="transcribe_text" cols="60" rows="10" id="replyBox' + event.target.id.substring(5) + '" placeholder="Your Reply"></textarea><button type="button" onclick="submitReply(event)" class="btn btn-default" id="submit' + event.target.id.substring(5) + '" value="' + event.target.value + '">Submit</button></form>';
                    $("#" + event.target.id).after(NewContent);
                    $("#" + event.target.id).remove();
                }
            });
        });

        $('#work-zone').ready(function () {
            $('#work-view').width($('#work-zone').width());
        });
        var $ = jQuery;
        $(document).ready(function () {

            var iv2 = $("#viewer2").iviewer(
                    {
                        src: "<?php echo $this->transcription->getFile()->getProperty('uri'); ?>"
                    });

        });
        $('.viewer').height($(window).height() - $('.viewer')[0].getBoundingClientRect().top - 60);
        $('#transcribe_copy').height($(window).height() - $('.viewer')[0].getBoundingClientRect().top - 60);

        function submitReply(event)
        {
            var replyText = document.getElementById('replyBox' + event.target.id.substring(6)).value;
            var questionID = parseInt(event.target.value);
            var documentId = <?php echo $this->transcription->id; ?>;
            var request = $.ajax({
                type: "POST",
                url: "http://localhost/m4j/incite/ajax/postreply",
                data: {replyText: replyText, originalQuestionId: questionID, documentId: documentId},
                success: function (data)
                {
                    $("#comments").empty();
                    getNewComments();
                    notif({
                       msg: "Successfully Posted Reply",
                       type: "success",
                       timeout: 2000
                    });
                    document.getElementById('replyBox' + event.target.id.substring(6)).value = "";
                }
            });
        }
        function submitComment()
        {
            var commentText = document.getElementById('comment').value;
            var documentId = <?php echo $this->transcription->id; ?>;
            var request = $.ajax({
                type: "POST",
                url: "http://localhost/m4j/incite/ajax/postcomment",
                data: {documentId: documentId, commentText: commentText, type: 0},
                success: function ()
                {
                    $("#comments").empty();
                    getNewComments();
                    notif({
                       msg: "Successfully Posted Comment",
                       type: "success",
                       timeout: 2000
                    });
                    document.getElementById('comment').value = "";
                }
            })
        }

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