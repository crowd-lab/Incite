<head>
	<script type="text/javascript">
        $('#work-zone').ready(function () {
            $('#work-view').width($('#work-zone').width());
        });

        $(document).ready(function () {
        	 $('[data-toggle="popover"]').popover({trigger: "hover"});

			$('.viewer').height($(window).height() - $('.viewer')[0].getBoundingClientRect().top - 10 - $(".navbar-fixed-bottom").height());

            $("#viewer2").iviewer({
                src: "<?php echo $this->image_url; ?>",
                zoom_min: 1,
                zoom: "fit"
            });
        });
    </script>
</head>

<body>
	<div class="col-md-6" id="work-zone">
        <div id="work-view">
            <div class="document-header">
                <span class="document-title" title="<?php echo metadata($this->transcription, array('Dublin Core', 'Title')); ?>" ><b>Title:</b> <?php echo metadata($this->transcription, array('Dublin Core', 'Title')); ?></span>
                <span class="glyphicon glyphicon-info-sign" id="document-info-glyphicon"
                	aria-hidden="true" data-trigger="hover"
                    data-toggle="popover" data-html="true"
                    data-viewport=".document-header"  
                    data-title="Document Information" 
                    data-content="<?php echo "<strong>Title:</strong> "
                    		. metadata($transcription, array('Dublin Core', 'Title'))
                    		. "<br><br> <strong>Date:</strong> " 
                            . metadata($transcription, array('Dublin Core', 'Date')) 
                            . "<br><br> <strong>Location:</strong> " 
                            . metadata($this->transcription, array('Item Type Metadata', 'Location')) 
                            . "<br><br> <strong>Description:</strong> " 
                            . metadata($this->transcription, array('Dublin Core', 'Description')); ?>" 
                    data-placement="right" data-id="<?php echo $transcription->id; ?>">
                </span>
            </div> 
            <div class="wrapper">
                <div id="viewer2" class="viewer"></div>
            </div>
        </div>
    </div>
</body>

<style>
	#work-view {
        position: fixed; 
        width: 35%;
    }

    .viewer {
        width: 100%;
        border: 1px solid black;
        position: relative;
    }

    .wrapper {
        overflow: hidden;
        margin-top: 7px
    }

    .document-header {
        margin-top: -39px;
    }

    .document-title {
        font-size: 25px; 
        position: relative; 
        top: -5px;
        display: inline-block;
        overflow: hidden;
        max-width: 70%;
        height: 30px;
        white-space: nowrap;
        text-overflow: ellipsis;
    }

    #document-info-glyphicon {
        color: #337AB7; 
        font-size: 20px;
        top: -6px;
    }

    .popover {
    	max-width: 100%;
    }
</style>
