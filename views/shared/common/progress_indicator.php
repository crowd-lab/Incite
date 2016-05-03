<?php
	queue_css_file(array('bootstrap', 'style', 'bootstrap.min'));
?>

<head>
	<script type="text/javascript">
		$(document).ready(function () {
	        var pathname = window.location.pathname;
	        var numberOfTasksCompleted = 0;

	        <?php
	        	$document_id = $this->document_metadata->id;

	        	if (!empty(getNewestTranscriptionForDocument($document_id))) {
	        		echo 'numberOfTasksCompleted++;';
	        	}

	        	if (hasTaggedTranscription($document_id)) {
	        		echo 'numberOfTasksCompleted++;';
	        	}

	        	if (!empty(getNewestSubjectsForDocument($document_id))) {
	        		echo 'numberOfTasksCompleted++;';
	        	}
	        ?>

	        styleShadowsAndGlyphsAndLinksFor(numberOfTasksCompleted);

	        if (pathname.indexOf("/transcribe/") > -1) {
	        	styleForTranscribe(numberOfTasksCompleted);
	        } else if (pathname.indexOf("/tag/") > -1) {
	        	styleForTag(numberOfTasksCompleted);
	        } else if (pathname.indexOf("/connect/") > -1) {
	        	styleForConnect(numberOfTasksCompleted);
	        } else {
	        	alert("Using progress indicator on incorrect page, please contact the developers");
	        }
	    });

		/*
		* Styles the nav to show the user they are transcribing
		*/
	    function styleForTranscribe(numberOfTasksCompleted) {
	    	if (numberOfTasksCompleted === 0) {
	    		$("#progress-indicator-bar-active").width("33.33%");
		    	$("#transcribe-progress-section").addClass("progress-shadow");

		    	$("#transcribe-progress-section").prop('title', 'Finish transcribing to move on to the next task');
		    	$("#transcribe-progress-glyph-span").css("color", "#F0AD4E");
	    	} else {
	    		var successIndicatorBarInactiveWidth = 0;

	    		while (numberOfTasksCompleted > 1) {
	    			successIndicatorBarInactiveWidth += 33.33;
	    			numberOfTasksCompleted--;
	    		}

	    		$("#success-indicator-bar").width(String(successIndicatorBarInactiveWidth) + "%");
	    		$("#success-indicator-bar-active-transcribe").width("33.33%");
	    	}
	    }

	    /*
		* Styles the nav to show the user they are tagging
		*/
	    function styleForTag(numberOfTasksCompleted) {
	    	if (numberOfTasksCompleted === 1) {
	    		$("#success-indicator-bar").width("33.33%");
		    	$("#progress-indicator-bar-active").width("33.33%");
		    	$("#tag-progress-section").addClass("progress-shadow");

		    	$("#tag-progress-section").prop('title', 'Finish tagging to move on to the next task, connecting');

		    	$("#tag-progress-glyph-span").css("color", "#F0AD4E");
	    	} else {
	    		$("#success-indicator-bar").width("33.33%");
		    	$("#success-indicator-bar-active").width("33.33%");

		    	if (numberOfTasksCompleted === 3) {
		    		$("#success-indicator-bar-connect").width("33.33%");
		    	}
	    	}
	    }

	    /*
		* Styles the nav to show the user they are connecting
		*/
	    function styleForConnect(numberOfTasksCompleted) {
	    	if (numberOfTasksCompleted === 2) {
	    		$("#success-indicator-bar").width("66.66%");
		    	$("#progress-indicator-bar-active").width("33.33%");
		    	$("#connect-progress-section").addClass("progress-shadow");

		    	$("#connect-progress-section").prop('title', 'Once you finish connecting this document all tasks are completed');

		    	$("#connect-progress-glyph-span").css("color", "#F0AD4E");
	    	} else {
	    		$("#success-indicator-bar").width("66.66%");
		    	$("#success-indicator-bar-active").width("33.33%");
	    	}
	    }

	    /*
	     * Styles the bar such that everything is green and checked, adds a delay
	     * so user can see the effect
	     */
	    function styleProgressIndicatorForCompletion() {
	    	$("#success-indicator-bar").width("100%");
	    	$("#progress-indicator-bar-active").width("0%");

	    	$("#transcribe-progress-section").addClass("success-shadow");
	    	$("#tag-progress-section").addClass("success-shadow");
	    	$("#connect-progress-section").addClass("success-shadow");

	    	$("#transcribe-progress-glyph-span").removeClass("glyphicon-unchecked");
	    	$("#transcribe-progress-glyph-span").addClass("glyphicon-check");
	    	$("#transcribe-progress-glyph-span").css("color", "#5CB85C");

	    	$("#tag-progress-glyph-span").removeClass("glyphicon-unchecked");
	    	$("#tag-progress-glyph-span").addClass("glyphicon-check");
	    	$("#tag-progress-glyph-span").css("color", "#5CB85C");

	    	$("#connect-progress-glyph-span").removeClass("glyphicon-unchecked");
	    	$("#connect-progress-glyph-span").addClass("glyphicon-check");
	    	$("#connect-progress-glyph-span").css("color", "#5CB85C");
	    }

	    /*
	     * Change the glyphicon to green and complete depending on the number of tasks completed
	     * Adds success shadows to the sections as appropriate
	     * Makes sections clickable with links as appropriate  
	     */
	     function styleShadowsAndGlyphsAndLinksFor(numberOfTasksCompleted) {
	     	var documentSpecificPartOfLocation = window.location.href.split('/').pop();

	     	$("#tag-progress-section").prop('title', 'You must finish transcribing before you can begin tagging');
		    $("#connect-progress-section").prop('title', 'You must finish transcribing and tagging before you can begin connecting');

	     	if (numberOfTasksCompleted > 0) {
	     		$("#transcribe-progress-glyph-span").removeClass("glyphicon-unchecked");
	    		$("#transcribe-progress-glyph-span").addClass("glyphicon-check");
	    		$("#transcribe-progress-glyph-span").css("color", "#5CB85C");
	    		$("#transcribe-progress-section").addClass("success-shadow");
	    		$("#transcribe-progress-section").click(function() {
	    			window.location.href = "<?php echo getFullInciteUrl(); ?>" + '/documents/transcribe/' + documentSpecificPartOfLocation;
	    		});
	    		$('#transcribe-progress-section').css('cursor', 'pointer');
	    		$("#transcribe-progress-section").prop('title', 'Transcribing Complete - Click to Edit');
	     	} 

	     	if (numberOfTasksCompleted > 1) {
	     		$("#tag-progress-glyph-span").removeClass("glyphicon-unchecked");
	    		$("#tag-progress-glyph-span").addClass("glyphicon-check");
	    		$("#tag-progress-glyph-span").css("color", "#5CB85C");
	    		$("#tag-progress-section").addClass("success-shadow");
	    		$("#tag-progress-section").click(function() {
	    			window.location.href = "<?php echo getFullInciteUrl(); ?>" + '/documents/tag/' + documentSpecificPartOfLocation;
	    		});
	    		$('#tag-progress-section').css('cursor', 'pointer');
	    		$("#tag-progress-section").prop('title', 'Tagging Complete - Click to Edit');
	     	}

	     	if (numberOfTasksCompleted > 2) {
	     		$("#connect-progress-glyph-span").removeClass("glyphicon-unchecked");
	    		$("#connect-progress-glyph-span").addClass("glyphicon-check");
	    		$("#connect-progress-glyph-span").css("color", "#5CB85C");
	    		$("#connect-progress-section").addClass("success-shadow");
	    		$("#connect-progress-section").click(function() {
	    			window.location.href = "<?php echo getFullInciteUrl(); ?>" + '/documents/connect/' + documentSpecificPartOfLocation;
	    		});
	    		$('#connect-progress-section').css('cursor', 'pointer');
	    		$("#connect-progress-section").prop('title', 'Connecting Complete - Click to Edit');
	     	}
	     }
	</script>
</head>

<body>
	<nav class="navbar navbar-default navbar-fixed-bottom">
		<div class="container progress-indicator-container">
			<div class="progress">
				<div class="progress-bar progress-bar-success progress-bar-striped active" id="success-indicator-bar-active-transcribe" style="width: 0%">
					<span class="sr-only"></span>
				</div>

				<div class="progress-bar progress-bar-success" id="success-indicator-bar" style="width: 0%">
					<span class="sr-only"></span>
				</div>

				<div class="progress-bar progress-bar-success progress-bar-striped active" id="success-indicator-bar-active" style="width: 0%">
					<span class="sr-only"></span>
				</div>

				<div class="progress-bar progress-bar-success" id="success-indicator-bar-connect" style="width: 0%">
					<span class="sr-only"></span>
				</div>

				<div class="progress-bar progress-bar-warning progress-bar-striped active" id="progress-indicator-bar-active" 
					style="width:0%">
					<span class="sr-only"></span>
				</div>
			</div>

			<!-- The comments around the divs below are a hack to avoid the spacing between inline-block elements -->
			<div class="task-section" id="transcribe-progress-section">
				<p class="task-description vertical-align">
					Transcribe
					<span class="glyphicon glyphicon-unchecked" id="transcribe-progress-glyph-span" aria-hidden="true"></span>
				</p>
			</div><!--
			--><div class="task-section" id="tag-progress-section">
				<p class="task-description vertical-align">
					Tag
					<span class="glyphicon glyphicon-unchecked" id="tag-progress-glyph-span" aria-hidden="true"></span>
				</p>
			</div><!--
			--><div class="task-section" id="connect-progress-section">
				<p class="task-description vertical-align">
					Connect
					<span class="glyphicon glyphicon-unchecked" id="connect-progress-glyph-span" aria-hidden="true"></span>
				</p>
			</div>
		</div>
	</nav>
</body>

<style>
    body {
        padding-bottom: 70px;
        /* Required padding for .navbar-fixed-bottom.*/
    }

    .progress {
    	height: 10px;
    	margin-bottom: 0px;
    }

    .progress-indicator-container {
    	width: 100%; 
    	height: 100%; 
    	padding: 0px;
    }

    .task-section {
    	width: 33.33%;
    	border-right: 2px solid black;
    	display: inline-block;
    	height: 80%;
    	text-align: center;
    	cursor: help;
    }

    .task-description {
    	font-size: 17px;
    }

    #connect-progress-section {
    	border-right: none;
    }

    .vertical-align {
		position: relative;
		top: 50%;
		-webkit-transform: translateY(-50%);
		-ms-transform: translateY(-50%);
		transform: translateY(-50%);
	}

	.progress-shadow {
		-moz-box-shadow:    inset 0 0 15px #F0AD4E;
   		-webkit-box-shadow: inset 0 0 15px #F0AD4E;
   		box-shadow:         inset 0 0 15px #F0AD4E;
	}

	.success-shadow {
		-moz-box-shadow:    inset 0 0 15px #5CB85C;
   		-webkit-box-shadow: inset 0 0 15px #5CB85C;
   		box-shadow:         inset 0 0 15px #5CB85C;
	}
</style>