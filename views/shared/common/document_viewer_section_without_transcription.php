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

                $('#document-info-glyphicon').attr('data-content', content);
            } else {
                $('#document-info-glyphicon').attr('data-content', "No available document information, sorry!");
            }
        }
    </script>
</head>

<body>
	<div class="col-md-6" id="work-zone">
        <div id="work-view">
            <div class="document-header">
                <span class="document-title" title="<?php echo metadata($this->document_metadata, array('Dublin Core', 'Title')); ?>" ><b>Title:</b> <?php echo metadata($this->document_metadata, array('Dublin Core', 'Title')); ?></span>
                <span class="glyphicon glyphicon-info-sign" id="document-info-glyphicon"
                	aria-hidden="true" data-trigger="hover"
                    data-toggle="popover" data-html="true"
                    data-viewport=".document-header"  
                    data-title="<strong>Document Information</strong>" 
                    data-placement="bottom" data-id="<?php echo $this->document_metadata->id; ?>">
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
        margin-top: -39px;
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
    }

    .document-title {
        font-size: 25px; 
        position: relative; 
        top: -5px;
        display: inline-block;
        overflow: hidden;
        max-width: 90%;
        height: 32px;
        white-space: nowrap;
        text-overflow: ellipsis;
    }

    #document-info-glyphicon {
        color: #337AB7; 
        font-size: 20px;
        top: -8px;
    }

    .popover {
    	max-width: 100%;
    }
</style>
