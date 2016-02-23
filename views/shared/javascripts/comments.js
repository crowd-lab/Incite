var documentId = null;

function getNewComments(docId)
{
    if (documentId == null && docId != null)
        documentId = docId;
    //var documentId = <?php echo $this->transcription->id; ?>;

    $('#comments').empty();
    var request = $.ajax({
        type: "POST",
        url: fullInciteUrl+"/ajax/getcommentsdoc",
        data: {documentId: documentId},
        success: function (data)
        {
            var commentsArray = JSON.parse(data);

            console.log(commentsArray);
            for (var i = 0; i < commentsArray.length; i++)
            {
                var databaseDate = new Date(commentsArray[i]['question_timestamp']);
                var format = compareDates(databaseDate);
                var commentsArrayObject = {commentsArray};
                var isSignedIn = $.ajax({
                    type: 'POST',
                    url: fullInciteUrl+"/ajax/issignedin",
                    async: false,
                    data: {loopVar: i, commentArray: commentsArray, format: format},
                    success: appendNewComment
                });
            }
        }
    });
}
function commentTypeToTypeName(type)
{
    if (type == 0)
        return "Transcribing";
    else if (type == 1)
        return "Tagging";
    else if (type == 2)
        return "Connecting";
    else if (type == 3) 
        return "Viewing";
    else
        return "Unknown";
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
    var commentType = commentsArray[i]['question_type'];
    if (boolean)
    {
        var dynamicLi = document.createElement('li');
        dynamicLi.className = "cmmnt";
        var dynamicDiv = document.createElement('div');
        dynamicDiv.className = "cmmnt-content";
        var label_color = 'gray';
        if (commentType == 0)  //transcribe
            label_color = 'red';
        else if (commentType == 1) //tag
            label_color = 'blue';
        else if (commentType == 2) //connect
            label_color = 'green';
        else if (commentType == 2) //viewing
            label_color = 'yellow';
        else
            label_color = 'gray';
        dynamicDiv.innerHTML = '<header><a href="'+fullInciteUrl+'/users/view/'+commentsArray[i]['user_info'][5]+'" class="userlink">' + commentsArray[i]['user_info'][0] + '</a> - <span class="pubdate">' + format + '</span> while <span style="background-color: '+label_color+';" class="comment-type label label-primary label-pill" data-commenttype="'+commentType+'">'+commentTypeToTypeName(commentType)+'</span></header><p>' + commentsArray[i]['question_text'] + '</p>';

        if (commentsArrayReplies != null && commentsArrayReplies.length > 0)
        {
            for (var j = 0; j < commentsArrayReplies.length; j++)
            {
                var string = "<ul style='list-style: none;'><li><header><a href='"+fullInciteUrl+"/users/view/"+commentsArrayRepliesUserData[j][5]+"' class='userlink'>";
                var databaseDate = new Date(commentsArrayRepliesTimestamp[j]);
                string += commentsArrayRepliesUserData[j][0] + '</a> - <span class="pubdate">commented ' + compareDates(databaseDate) + '</span></header><p>' + commentsArrayReplies[j] + "</p></li>";
                if (j != commentsArrayReplies.length - 1)
                {
                    string += "<li><header><a href='javascript:void(0);' class='userlink'>";
                }
            }
            string += "</ul>"
            dynamicDiv.innerHTML += (string + '<button type="button" name="reply" class="btn btn-default reply-comment" id="reply' + i + '" value="' + commentsArray[i]['question_id'] + '">Reply</button>');
        } else {
            dynamicDiv.innerHTML += '<button type="button" name="reply" class="btn btn-default reply-comment" id="reply' + i + '" value="' + commentsArray[i]['question_id'] + '">Reply</button>';
        }

        dynamicLi.appendChild(dynamicDiv);
        document.getElementById("comments").appendChild(dynamicLi);
    } else {
        var dynamicLi = document.createElement('li');
        dynamicLi.className = "cmmnt";
        var dynamicDiv = document.createElement('div');
        dynamicDiv.className = "cmmnt-content";
        dynamicDiv.innerHTML = '<header><a href="javascript:void(0);" class="userlink">' + commentsArray[i]['user_info'][0] + '</a> - <span class="pubdate">' + format + '</span></header><p>' + commentsArray[i]['question_text'] + '</p>';
        if (commentsArrayReplies != null && commentsArrayReplies.length > 0)
        {
            var string = "<ul style='list-style: outside none none;'><li><header><a href='javascript:void(0);' class='userlink'>";
            for (var j = 0; j < commentsArrayReplies.length; j++)
            {
                var databaseDate = new Date(commentsArrayRepliesTimestamp[j]);
                string += commentsArrayRepliesUserData[j][0] + '</a> - <span class="pubdate">commented ' + compareDates(databaseDate) + '</span></header><p>' + commentsArrayReplies[j] + "</p></li>";
                if (j != commentsArrayReplies.length - 1)
                {
                    string += "<li><header><a href='javascript:void(0);' class='userlink'>";
                }
            }
            string += "</ul>"
            dynamicDiv.innerHTML += string;
        }
        dynamicLi.appendChild(dynamicDiv);
        document.getElementById("comments").appendChild(dynamicLi);
    }
}
function compareDates(databaseDate)
{
    var currentDate = new Date();
    var differenceDate = Math.ceil((currentDate.getTime() - databaseDate.getTime()) / 1000);
    var format = "";
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


function submitReply(event, documentId)
{
    var replyText = document.getElementById('replyBox' + event.target.id.substring(6)).value;
    var questionID = parseInt(event.target.value);
    //var documentId = <?php echo $this->transcription->id; ?>;
    var request = $.ajax({
        type: "POST",
        url: fullInciteUrl+"/ajax/postreply",
        data: {replyText: replyText, originalQuestionId: questionID, documentId: documentId},
        success: function (data)
        {
            $("#comments").empty();
            //getNewComments(<?php echo $this->transcription->id; ?>);
            getNewComments(documentId);
            notif({
               msg: "Successfully Posted Reply",
               type: "success",
               timeout: 2000
            });
            //document.getElementById('replyBox' + event.target.id.substring(6)).value = "";
        }
    });
}
function submitComment(documentId)
{
    var commentText = document.getElementById('comment').value;
    //var documentId = <?php echo $this->transcription->id; ?>;
    console.log(documentId + " " + commentText + " " + comment_type);

    var request = $.ajax({
        type: "POST",
        url: fullInciteUrl+"/ajax/postcomment",
        data: {documentId: documentId, commentText: commentText, type: comment_type},
        success: function ()
        {
            $("#comments").empty();
            //getNewComments(<?php echo $this->transcription->id; ?>);
            getNewComments(documentId);
            notif({
               msg: "Successfully Posted Comment",
               type: "success",
               timeout: 2000
            });
            document.getElementById('comment').value = "";
        },
        error: function (e) {
            console.log(e);
        }
    })
}
