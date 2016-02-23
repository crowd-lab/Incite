<head>
	<?php
		$currentURL = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

		if (strpos($currentURL, "/transcribe/") !== false) {
		    $currentTaskID = $this->transcription->id;
		} else if (strpos($currentURL, "/tag/") !== false) {
		    $currentTaskID = $this->tag->id;
		} else if (strpos($currentURL, "/connect/") !== false) {
		    $currentTaskID = $this->connection->id;
		} else if (strpos($currentURL, "/view/") !== false) {
            $currentTaskID = $this->document->id;
        } else {
			echo "Comments not on a task page";
			die();
		}
	?>

	<script type="text/javascript">
		$(function () {
            getNewComments(<?php echo $currentTaskID; ?>);
        });

		$(document).on('click', 'button', function(event) {
            if (event.target.name === "reply")
            {
                var NewContent = '<div class="reply-container"><form id="reply-form" method="POST"><textarea name="transcribe_text" cols="60" rows="10" class="reply-box" id="replyBox' + event.target.id.substring(5) + '" placeholder="Your Reply"></textarea><button type="button" onclick="submitReply(event<?php echo ', '.$currentTaskID; ?>)" class="btn btn-default submit-reply" id="submit' + event.target.id.substring(5) + '" value="' + event.target.value + '">Post Reply</button></form></div>';
                $("#" + event.target.id).after(NewContent);
                $("#" + event.target.id).remove();
            }
        });
	</script>
</head>

<body>
	<div id="container" class="comments-section-container">
        <h3> Comment </h3>
        <div id="onLogin">
			<?php if (isset($_SESSION['Incite']['IS_LOGIN_VALID']) && $_SESSION['Incite']['IS_LOGIN_VALID'] == true /** && is_permitted * */): ?>	
                <form id="discuss-form" method="POST">
                    <textarea name="transcribe_text" cols="60" rows="10" id="comment" class="comment-textarea" placeholder="Your comment"></textarea>
                    <button type="button" class="btn btn-default submit-comment-btn" 
                    	onclick="submitComment(<?php echo $currentTaskID; ?>)">
                    	Post Comment
                    </button>
                </form>
			<?php else: ?>
                Please login or signup to join the discussion!
            <?php endif; ?>
        </div>
        <br>
        <br>
        <ul id="comments" class="comments-list"></ul>
    </div>
</body>

<style>
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

    .reply-comment {
    	margin-bottom: 15px;
    }
</style>