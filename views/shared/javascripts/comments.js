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
        error: function (response) {
            console.log('getcomment failed');
        },
        success: function (data)
        {
            var discussionsArray = JSON.parse(data);
            var isSignedIn = false;
            $.ajax({
                type: 'get',
                url: fullInciteUrl+"/ajax/issignedin",
                async: false,
                success: function (response) {
                    if (response.trim() == 'true') {
                        isSignedIn = true;
                    } else {
                        isSignedIn = false;
                    }
                }
            });
            for (var i = 0; i < discussionsArray.length; i++) {
                appendNewDiscussion(discussionsArray[i], isSignedIn);
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
function appendNewDiscussion(discussion, isSignedIn)
{
        var commentType = discussion['discussion_type'];
        var databaseDate = new Date(discussion['discussion_timestamp']);
        var format = compareDates(databaseDate);

        var dynamicLi = document.createElement('li');
        dynamicLi.className = "cmmnt";
        var dynamicDiv = document.createElement('div');
        dynamicDiv.className = "cmmnt-content";
        var label_color = 'gray';
        if (commentType == 0)  //transcribe
            label_color = 'transcribe-color'; //'#D9534F';
        else if (commentType == 1) //tag
            label_color = 'tag-color';
        else if (commentType == 2) //connect
            label_color = 'connect-color';
        else if (commentType == 3) //viewing
            label_color = 'view-color';
        else
            label_color = 'gray';
        dynamicDiv.innerHTML = '<header><a href="'+fullInciteUrl+'/users/view/'+discussion['user_info'][5]+'" class="userlink">' + discussion['user_info'][0] + '</a> - <span class="pubdate">' + format + '</span> while <span class="comment-type label label-primary label-pill '+label_color+'" data-commenttype="'+discussion['discussion_type']+'">'+commentTypeToTypeName(discussion['discussion_type'])+'</span></header><p>' + discussion['discussion_text'] + '</p>';

        var string = ""
        for (var j = 0; j < discussion['discussion_comments'].length; j++)
        {
            string += "<ul style='list-style: none;'><li><header><a href='"+fullInciteUrl+"/users/view/"+discussion['discussion_comment_users'][j][5]+"' class='userlink'>";
            var databaseDate = new Date(discussion['discussion_comment_timestamps'][j]);
            string += discussion['discussion_comment_users'][j][0] + '</a> - <span class="pubdate">commented ' + compareDates(databaseDate) + '</span></header><p>' + discussion['discussion_comments'][j] + "</p></li>";
            if (j != discussion['discussion_comments'].length - 1)
            {
                string += "<li><header><a href='javascript:void(0);' class='userlink'>";
            }
            string += "</ul>"
        }
        if (isSignedIn) {
            string += '<button type="button" name="reply" class="btn btn-default reply-comment" id="reply'+discussion['discussion_id']+'" value="' + discussion['discussion_id'] + '">Reply</button>';
        }
        dynamicDiv.innerHTML += (string);

        dynamicLi.appendChild(dynamicDiv);
        document.getElementById("comments").appendChild(dynamicLi);
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

    var request = $.ajax({
        type: "POST",
        url: fullInciteUrl+"/ajax/postcomment",
        data: {documentId: documentId, commentText: commentText, type: comment_type},
        error: function (response) {
            console.log('postcomment failed');
            console.log(response);
        },
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
