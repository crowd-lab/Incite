<head>
	<script type="text/javascript">
		var pathname = window.location.pathname;

		$(document).ready(function () {
			var currentTask = "";

			if (pathname.indexOf("/transcribe/") > -1) {
		    	currentTask = "Transcribe";
		    } else if (pathname.indexOf("/tag/") > -1) {
		    	currentTask = "Tag";
		    } else if (pathname.indexOf("/connect/") > -1) {
		    	currentTask = "Connect";
		    } else if (pathname.indexOf("/view/") > -1) {
		    	currentTask = "View Document Details"; 
		    } else {
		    	alert("Using task headers on a non-task page");
		    }

            <?php if ($this->is_being_edited): ?>
                currentTask += ' - Editing';   
            <?php endif; ?>

        	$(".task-header").html(currentTask); 
        });
	</script>
</head>

<body>
	<div id="task_description">
        <h1 class="task-header">Loading..</h1>
        <?php
            include(dirname(__FILE__) . '/../common/group_instructions_section.php');
        ?>
    </div>
</body>

<style>
	.task-header {
        text-align: center; 
        margin-top: 5px;
    }

    #task_description {
        text-align: center;
        margin-bottom: 40px; 
    }
</style>
