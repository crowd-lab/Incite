<head>
	<?php
		$currentURL = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        if (strpos($currentURL, "/view/") !== false) {
            $currentTask = $this->document;
        } else {
            echo "Not on view page";
            die();
        }
	?>

	<script type="text/javascript">
        var selectTab = function (tabToSelect, tabsToUnselect) {
            tabToSelect.addClass("active");

            tabsToUnselect.forEach(function(tab) {
                tab.removeClass("active");
            });
        };

        $('#work-zone').ready(function() {
            $('#work-view').width($('#work-zone').width());
        });

        $(document).ready(function () {
            $('[data-toggle="popover"]').popover({trigger: "hover"});

            $("#transcribe_copy").hide();
            $("#tagged_transcribe_copy").hide();
            $("#connect_subjects_copy").hide();

            $("#transcriptionTab").click(function () {
                $("#document_img").hide();
                $("#transcribe_copy").show();
                $("#tagged_transcribe_copy").hide();
                $("#connect_subjects_copy").hide();
                selectTab($("#transcriptionTab"), [$("#documentTab"), $("#taggedTranscriptionTab"), $("#connectTab")]);
            });

            $("#documentTab").click(function () {
                $("#document_img").show();
                $("#transcribe_copy").hide();
                $("#tagged_transcribe_copy").hide();
                $("#connect_subjects_copy").hide();
                selectTab($("#documentTab"), [$("#transcriptionTab"), $("#taggedTranscriptionTab"), $("#connectTab")]);
            });

            $("#taggedTranscriptionTab").click(function () {
                $("#document_img").hide();
                $("#transcribe_copy").hide();
                $("#tagged_transcribe_copy").show();
                $("#connect_subjects_copy").hide();
                selectTab($("#taggedTranscriptionTab"), [$("#documentTab"), $("#transcriptionTab"), $("#connectTab")]);
            });

            $("#connectTab").click(function () {
                $("#document_img").hide();
                $("#transcribe_copy").hide();
                $("#tagged_transcribe_copy").hide();
                $("#connect_subjects_copy").show();
                selectTab($("#connectTab"), [$("#documentTab"), $("#taggedTranscriptionTab"), $("#taggedTranscriptionTab")]);
            });

            $('.viewer').height($(window).height()-$('#transcribe_copy')[0].getBoundingClientRect().top-250);

            $('#transcribe_copy').height($(window).height()-$('#transcribe_copy')[0].getBoundingClientRect().top-250);
            $('#tagged_transcribe_copy').height($(window).height()-$('#transcribe_copy')[0].getBoundingClientRect().top-250);
            $('#connect_subjects_copy').height($(window).height()-$('#transcribe_copy')[0].getBoundingClientRect().top-250);

            $("#document_img").iviewer({
                src: "<?php echo $this->image_url; ?>",
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
                data-title="<strong>Document Information</strong>" 
                data-content="<?php echo "<strong>Title:</strong> "
                    	. metadata($currentTask, array('Dublin Core', 'Title'))
                        . "<br><br> <strong>Date:</strong> " 
                        . metadata($currentTask, array('Dublin Core', 'Date')) 
                        . "<br><br> <strong>Location:</strong> " 
                        . metadata($currentTask, array('Item Type Metadata', 'Location')) 
                        . "<br><br> <strong>Description:</strong> " 
                        . metadata($currentTask, array('Dublin Core', 'Description')); ?>" 
                data-placement="bottom" data-id="<?php echo $currentTask->id; ?>">
            </span>
        </div> 
        
        <div id="legend-container">
            <span><b>Legend: </b></span>
            <?php foreach ((array)$this->category_colors as $category => $color): ?>
                <em class="<?php echo strtolower($category); ?> legend-item"><?php echo ucfirst(strtolower($category)); ?></em>
            <?php endforeach; ?>
        </div>

        <div id="tabs-and-legend-container">
            <ul class="nav nav-tabs document-display-type-tabs">
                <li role="presentation" class="active" id="documentTab"><a href="#">Document</a></li>
                <li role="presentation" id="transcriptionTab"><a href="#">Transcription</a></li>
                <li role="presentation" id="taggedTranscriptionTab"><a href="#">Tagged Transcription</a></li>
                <li role="presentation" id="connectTab"><a href="#">Connected Subjects</a></li>
            </ul>
        </div>

        <div style="border: 1px solid; overflow: scroll;" name="transcribe_text" rows="10" id="transcribe_copy" style="width: 100%;">
            <?php print_r($this->transcription); ?>
        </div>

        <div style="border: 1px solid; overflow: scroll;" name="tagged_transcribe_text" rows="10" id="tagged_transcribe_copy" style="width: 100%;">
            <?php print_r($this->taggedTranscription); ?>
        </div>

        <div style="border: 1px solid; overflow: scroll;" name="connect_subjects_text" rows="10" id="connect_subjects_copy" style="width: 100%;">
            <div id="subjects-list">
                <h3 id="positive-subjects-header">Subjects marked as relating to this document</h3>
                <?php 
                    forEach($this->subjects as $subject) {
                        if ($subject['is_positive']) {
                            echo "<p class='positive-subject'>".$subject['subject_name']."<p>";
                        }
                    }
                ?>

                <hr size=2>

                <h3 id="negative-subjects-header">Subjects marked as not relating to this document</h3>
                <?php 
                    forEach($this->subjects as $subject) {
                        if (!$subject['is_positive']) {
                            echo "<p class='negative-subject'>".$subject['subject_name']."<p>";
                        }
                    }
                ?>
            </div>
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
        margin-top: 10px;
        margin-bottom: 10px;
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

    #subjects-list {
        text-align: center;
    }

    #positive-subjects-header {
        color: #5CB85C;
    }

    #negative-subjects-header {
        color: #D9534F;
    }
</style>
