<?php
	queue_css_file(array('bootstrap', 'style', 'bootstrap.min'));
?>

<head>
	<script type="text/javascript">
		var selectTab = function (tabToSelect, tabToUnselect) {
		    tabToSelect.addClass("active");
		    tabToUnselect.removeClass("active");
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

		    $('.viewer').height($(window).height()-$('#transcribe_copy')[0].getBoundingClientRect().top-10-$(".navbar-fixed-bottom").height());
		    
	        $('#transcribe_copy').height($(window).height()-$('#transcribe_copy')[0].getBoundingClientRect().top-10-$(".navbar-fixed-bottom").height());

	        $("#document_img").iviewer({
	            src: "<?php echo $this->tag->getFile()->getProperty('uri'); ?>",
	            zoom_min: 1,
	            zoom: "fit"
        	});
		});
	</script>
</head>

<body>
	<div style="position: fixed;" id="work-view">
        <div class="document-header">
            <span class="document-title" title="<?php echo metadata($this->tag, array('Dublin Core', 'Title')); ?>">
                <b>Title:</b> <?php echo metadata($this->tag, array('Dublin Core', 'Title')); ?>
            </span>
            <span class="document-additional-info" 
                data-toggle="popover" data-html="true" data-trigger="hover" 
                data-title="Additional Information" 
                data-content="<?php echo "<strong>Date:</strong> " 
                        . metadata($tag, array('Dublin Core', 'Date')) 
                        . "<br><br> <strong>Location:</strong> " 
                        . metadata($this->tag, array('Item Type Metadata', 'Location')) 
                        . "<br><br> <strong>Description:</strong> " 
                        . metadata($this->tag, array('Dublin Core', 'Description')); ?>" 
                data-placement="bottom" data-id="<?php echo $tag->id; ?>">
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

        <div style="border: 1px solid; overflow: scroll;" name="transcribe_text" rows="10" id="transcribe_copy" style="width: 100%;">
            <?php print_r($this->transcription); ?>
        </div>

        <div class="wrapper">
            <div id="document_img" class="viewer"></div>
        </div>
    </div>
</body>

<style>
</style>