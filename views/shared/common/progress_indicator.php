<?php
	queue_css_file(array('bootstrap', 'style', 'bootstrap.min'));
?>

<head>
	<script type="text/javascript">
		$(document).ready(function () {
	        var pathname = window.location.pathname;

	        if (pathname.indexOf("/transcribe/") > -1) {
	        	styleForTranscribe();
	        } else if (pathname.indexOf("/tag/") > -1) {
	        	styleForTag();
	        } else if (pathname.indexOf("/connect/") > -1) {
	        	styleForConnect();
	        } else {
	        	alert("Using progress indicator on incorrect page");
	        }
	    });

		/*
		* Styles the nav to show the user they are transcribing
		*/
	    function styleForTranscribe() {
	    	$("#progress-indicator-bar").width("33.33%");

	    	$("#transcribe-progress-section").addClass("progress-shadow");

	    	$("#transcribe-progress-section").prop('title', 'Finish transcribing to move on to the next task');
	    	$("#tag-progress-section").prop('title', 'You must finish transcribing before you can begin tagging');
	    	$("#connect-progress-section").prop('title', 'You must finish transcribing and tagging before you can begin connecting');

	    	$("#transcribe-progress-glyph-span").css("color", "#F0AD4E");
	    }

	    /*
		* Styles the nav to show the user they are tagging
		*/
	    function styleForTag() {
	    	$("#success-indicator-bar").width("33.33%");
	    	$("#progress-indicator-bar").width("33.33%");

	    	$("#transcribe-progress-section").addClass("success-shadow");
	    	$("#tag-progress-section").addClass("progress-shadow");

	    	$("#transcribe-progress-section").prop('title', 'This document has been successfully transcribed!');
	    	$("#tag-progress-section").prop('title', 'Finish tagging to move on to the next task');
	    	$("#connect-progress-section").prop('title', 'You must finish tagging before you can begin connecting');
	    	
	    	$("#transcribe-progress-glyph-span").removeClass("glyphicon-unchecked");
	    	$("#transcribe-progress-glyph-span").addClass("glyphicon-check");
	    	$("#transcribe-progress-glyph-span").css("color", "#5CB85C");

	    	$("#tag-progress-glyph-span").css("color", "#F0AD4E");
	    }

	    /*
		* Styles the nav to show the user they are connecting
		*/
	    function styleForConnect() {
	    	$("#success-indicator-bar").width("66.66%");
	    	$("#progress-indicator-bar").width("33.33%");

	    	$("#transcribe-progress-section").addClass("success-shadow");;
	    	$("#tag-progress-section").addClass("success-shadow");;
	    	$("#connect-progress-section").addClass("progress-shadow");;

	    	$("#transcribe-progress-section").prop('title', 'This document has been successfully transcribed!');
	    	$("#tag-progress-section").prop('title', 'This document has been successfully tagged!');
	    	$("#connect-progress-section").prop('title', 'Once you finish connecting this document you are done');
	    	
	    	$("#transcribe-progress-glyph-span").removeClass("glyphicon-unchecked");
	    	$("#transcribe-progress-glyph-span").addClass("glyphicon-check");
	    	$("#transcribe-progress-glyph-span").css("color", "#5CB85C");

	    	$("#tag-progress-glyph-span").removeClass("glyphicon-unchecked");
	    	$("#tag-progress-glyph-span").addClass("glyphicon-check");
	    	$("#tag-progress-glyph-span").css("color", "#5CB85C");

	    	$("#connect-progress-glyph-span").css("color", "#F0AD4E");
	    }
	</script>
</head>

<body>
	<nav class="navbar navbar-default navbar-fixed-bottom">
		<div class="container progress-indicator-container">
			<div class="progress">
				<div class="progress-bar progress-bar-success" id="success-indicator-bar" style="width: 0%">
					<span class="sr-only"></span>
				</div>

				<div class="progress-bar progress-bar-warning progress-bar-striped active" id="progress-indicator-bar" 
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