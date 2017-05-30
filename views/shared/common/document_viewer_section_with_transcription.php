<head>
	<script type="text/javascript">
        function resize() {
    					$('#work-view').width($('#work-zone').width());
        }
        function migrateTaggedDocumentsFromV1toV2() {
            $('#transcribe_copy em').each( function (idx) {
                $(this).addClass('tagged-text');
            });
        }
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
            migrateTaggedDocumentsFromV1toV2();
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
	            src: "<?php echo $this->image_url; ?>",
	            zoom_min: 1,
	            zoom: "fit"
        	});

            buildPopoverContent();
		});

        function buildPopoverContent() {
            var content = '';
            var date = <?php echo sanitizeStringInput(metadata($this->document_metadata, array('Dublin Core', 'Date'))); ?>.value;
            var location = <?php echo sanitizeStringInput(metadata($this->document_metadata, array('Item Type Metadata', 'Location'))); ?>.value;
            var source = <?php echo sanitizeStringInput(metadata($this->document_metadata, array('Dublin Core', 'Source'))); ?>.value;
            var contributor = <?php echo sanitizeStringInput(metadata($this->document_metadata, array('Dublin Core', 'Contributor'))); ?>.value;
            var rights = <?php echo sanitizeStringInput(metadata($this->document_metadata, array('Dublin Core', 'Rights'))); ?>.value;

            if (date) {
                content += '<strong>Date: </strong>' + date + '<br><br>';
            }

            if (location) {
                content += '<strong>Location: </strong>' + location + '<br><br>';
            }

            if (source) {
                content += '<strong>Source: </strong>' + source + '<br><br>';
            }

            if (contributor) {
                content += '<strong>Contributor: </strong>' + contributor + '<br><br>';
            }

            if (rights) {
                content += '<strong>Rights: </strong>' + rights + '<br><br>';
            } else {
                content += '<strong>Rights: </strong>Public Domain<br><br>';
            }


            if (content) {
                //cut off the last <br><br>
                content = content.slice(0, -8);

                $('#document-info-glphicon').attr('data-content', content);
            } else {
                $('#document-info-glphicon').attr('data-content', "No available document information, sorry!");
            }
        }
	</script>
</head>

<body onresize="resize()">
	<div style="position: fixed;" id="work-view">
        <div class="document-header">
            <span class="document-title" title="<?php echo metadata($this->document_metadata, array('Dublin Core', 'Title')); ?>">
                <b>Title:</b> <?php echo metadata($this->document_metadata, array('Dublin Core', 'Title')); ?>
            </span>
            <span id="document-info-glphicon" class="glyphicon glyphicon-info-sign"
                data-toggle="popover" data-html="true" data-trigger="hover"
                data-viewport=".document-header" aria-hidden="true"
                data-title="<strong>Document Information</strong>" 
                data-placement="bottom" data-id="<?php echo $this->document_metadata->id; ?>">
            </span>
        </div> 
        
        <div id="tabs-and-legend-container">
            <ul class="nav nav-tabs document-display-type-tabs">
                <li role="presentation" class="active" id="hide"><a href="#">Transcription</a></li>
                <li role="presentation" id="show"><a href="#">Document</a></li>
            </ul>

            <div id="legend-container">
                <span><b>Legend: </b></span>
                <?php $all_categories = getAllCategories(); ?>
                <?php foreach ((array)$all_categories as $category): ?>
                    <em class="<?php echo strtolower($category['name']); ?> legend-item"><?php echo ucfirst(strtolower($category['name'])); ?></em>
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
	#work-view {
        position: fixed; 
        margin-top: -30px;
    }

	.document-header {
    }

	.document-title {
        font-size: 25px; 
        position: relative; 
        top: -5px;
        overflow: hidden;
        display: inline-block;
        max-width: 90%;
        height: 32px;
        white-space: nowrap;
        text-overflow: ellipsis;
    }

     #document-info-glphicon {
        color: #337AB7; 
        font-size: 20px;
        top: -8px;
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
        font-size: 12px;
        position: relative;
        top: 5px;
    }
</style>
