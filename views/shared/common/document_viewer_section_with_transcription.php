<head>
	<?php
		$currentURL = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

		if (strpos($currentURL, "/tag/") !== false) {
		    $currentTask = $this->tag;
		} else if (strpos($currentURL, "/connect/") !== false) {
		    $currentTask = $this->connection;
		} else {
			echo "Not on a connection or tagging page";
			die();
		}
	?>

	<script type="text/javascript">
		var selectTab = function (tabToSelect, tabToUnselect) {
		    tabToSelect.addClass("active");
		    tabToUnselect.removeClass("active");
		};

		var setLegendWidth = function() {
			$('#legend-container').width(
				$('#tabs-and-legend-container').width()
				-
				$(".document-display-type-tabs").width()
				-
				7 //so it doesn't overflow
			); 
		};

		$('#work-zone').ready(function() {
		    $('#work-view').width($('#work-zone').width());
		});

		$(document).ready(function () {
		    $('[data-toggle="popover"]').popover({trigger: "hover"});
		    $("#document_img").hide();

		    $("#hide").click(function () {
		        $("#document_img").hide();
		        $("#transcribe_copy").show();
		        selectTab($("#hide"), $("#show"));
		    });

		    $("#show").click(function () {
		        $("#document_img").show();
		        $("#transcribe_copy").hide();
		        selectTab($("#show"), $("#hide"));
		    });

		    setLegendWidth();

		    $('.viewer').height($(window).height()-$('#transcribe_copy')[0].getBoundingClientRect().top-10-$(".navbar-fixed-bottom").height());

	        $('#transcribe_copy').height($(window).height()-$('#transcribe_copy')[0].getBoundingClientRect().top-10-$(".navbar-fixed-bottom").height());

	        $("#document_img").iviewer({
	            src: "<?php echo $currentTask->getFile()->getProperty('uri'); ?>",
	            zoom_min: 1,
	            zoom: "fit"
        	});
		});
	</script>
</head>

<body>
	<div style="position: fixed;" id="work-view">
        <div class="document-header">
            <span class="document-title" title="<?php echo metadata($currentTask, array('Dublin Core', 'Title')); ?>">
                <b>Title:</b> <?php echo metadata($currentTask, array('Dublin Core', 'Title')); ?>
            </span>
            <span id="document-info-glphicon" class="glyphicon glyphicon-info-sign"
                data-toggle="popover" data-html="true" data-trigger="hover"
                data-viewport=".document-header" aria-hidden="true"
                data-title="Document Information" 
                data-content="<?php echo "<strong>Title:</strong> "
                    	. metadata($currentTask, array('Dublin Core', 'Title'))
                		. "<br><br> <strong>Date:</strong> " 
                        . metadata($currentTask, array('Dublin Core', 'Date')) 
                        . "<br><br> <strong>Location:</strong> " 
                        . metadata($currentTask, array('Item Type Metadata', 'Location')) 
                        . "<br><br> <strong>Description:</strong> " 
                        . metadata($currentTask, array('Dublin Core', 'Description')); ?>" 
                data-placement="right" data-id="<?php echo $currentTask->id; ?>">
            </span>
        </div> 
        
        <div id="tabs-and-legend-container">
            <ul class="nav nav-tabs document-display-type-tabs">
                <li role="presentation" class="active" id="hide"><a href="#">Transcription</a></li>
                <li role="presentation" id="show"><a href="#">Document</a></li>
            </ul>

            <div id="legend-container">
                <span><b>Legend: </b></span>
                <?php foreach ((array)$this->category_colors as $category => $color): ?>
                    <em class="<?php echo strtolower($category); ?> legend-item"><?php echo ucfirst(strtolower($category)); ?></em>
                <?php endforeach; ?>
            </div>
        </div>

        <div style="border: 1px solid; overflow: scroll;" name="transcribe_text" rows="10" id="transcribe_copy" style="width: 100%;">
            <?php print_r($this->transcription); ?>
        </div>

        <div class="wrapper">
            <div id="document_img" class="viewer"></div>
        </div>
    </div>
</body>

<style>
	.document-header {
        margin-top: -30px;
    }

	.document-title {
        font-size: 25px; 
        position: relative; 
        top: -5px;
        overflow: hidden;
        display: inline-block;
        max-width: 70%;
        height: 30px;
        white-space: nowrap;
        text-overflow: ellipsis;
    }

     #document-info-glphicon {
        color: #337AB7; 
        font-size: 20px;
        top: -6px;
    }

    .popover {
    	max-width: 100%;
    }

    #legend-container {
        display: inline-block; 
        position: relative; 
        top: 10px;
        text-align: right;
    }

    .viewer {
        width: 100%;
        border: 1px solid black;
        position: relative;
    }

    .wrapper {
        overflow: hidden;
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

    .document-display-type-tabs {
        display: inline-block; 
        vertical-align: top;
    }
</style>