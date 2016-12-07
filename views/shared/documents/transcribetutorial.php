<!DOCTYPE html>
<html lang="en">
    <?php
        $_SESSION['Incite']['tutorial_trans'] = true;
        include(dirname(__FILE__) . '/../common/header.php');
        include(dirname(__FILE__) . '/../common/progress_indicator.php');
    ?>

    <script type="text/javascript">
        var msgbox;
        var comment_type = 0;
    </script>
    
    <script type="text/javascript">
        var textArea;
        var textToCheck;
        textToCheck = "Hello World";
        textArea = document.getElementById("transcription-textarea");
        
        
    </script>

    <!-- Page Content -->
    <?php
        include(dirname(__FILE__) . '/../common/task_header.php');
    ?>
    <div class="container-fluid">
<head>
	<script type="text/javascript">
        $('#work-zone').ready(function () {
            $('#work-view').width($('#work-zone').width());
        });

        $(document).ready(function () {
        	 $('[data-toggle="popover"]').popover({trigger: "hover"});

			$('.viewer').height($(window).height() - $('.viewer')[0].getBoundingClientRect().top - 10 - $(".navbar-fixed-bottom").height());

            $("#viewer2").iviewer({
                src: "<?php echo getFullOmekaUrl(); ?>plugins/Incite/views/shared/images/tutorial.jpg",
                zoom_min: 1,
                zoom: "fit"
            });

            buildPopoverContent();
        });

        function buildPopoverContent() {
            var content = '';
            var date = "2016-12-06";
            var location = "Virginia - Montgomery County - Blacksburg";
            var source = "";
            var contributor = "";
            var rights = "Public Domain";

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
            <div class="document-header" id="document-header">
                <span class="document-title" title="Incite Tutorial - Transcribe" ><b>Title:</b> Incite Tutorial - Transcribe</span>
                <span class="glyphicon glyphicon-info-sign" id="document-info-glyphicon"
                	aria-hidden="true" data-trigger="hover"
                    data-toggle="popover" data-html="true"
                    data-viewport=".document-header"
                    data-title="<strong>Document Information</strong>"
                    data-placement="bottom" data-id="">
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
        position: relative;
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

        <div class="col-md-6" id="submit-zone">
            <div id="transcribing-work-area">
            <form method="post" id="transcribe-form">
                <p class="header-step" style="margin-bottom: 13px; position: relative;">
                    <i>Step 1 of 3: Transcribe</i>
                    <span class="glyphicon glyphicon-info-sign step-instruction-glyphicon"
                        aria-hidden="true" data-trigger="hover"
                        data-toggle="popover" data-html="true"
                        data-viewport="#transcribe-form"
                        data-title="<strong>Transcribing a document</strong>"
                        data-content="<?php echo "<ul>"
                        . "<li>Copy the text exactly as is, including misspellings and abbreviations.</li>"
                        . "<li>You don't need to account for formatting (e.g. spacing, line breaks, alignment).</li>"
                        . "<li>If you can't make out a word replace it with '[illegible]'.</li>"
                        . "<li>If you are uncertain about a word surround it with square brackets, e.g. '[town?]'</li>"?>"
                        data-placement="bottom" data-id="<?php echo $transcription->id; ?>">
                    </span>
                    <a id="view-revision-history-link" style="display: none;">View Revision History...  </a>
                </p>

                <textarea id="transcription-textarea" name="transcription" rows="15" placeholder="Provide a 1:1 transcription of the document"></textarea>
                
                <p class="step">
                    <i>Step 2 of 3: Summarize</i>
                    <span class="glyphicon glyphicon-info-sign step-instruction-glyphicon"
                        aria-hidden="true" data-trigger="hover"
                        data-toggle="popover" data-html="true"
                        data-viewport="#transcribe-form"
                        data-title="<strong>Summarizing a document</strong>"
                        data-content="<?php echo "Using your own wording, summarize the document in 1-2 sentences." ?>"
                        data-placement="bottom" data-id="<?php echo $transcription->id; ?>">
                    </span>
                </p>
                <textarea id="summary-textarea" name="summary" rows="5" placeholder="Provide a 1-2 sentence summary of the document"></textarea>
                <div class="form-group" id="tone-selection">
                    <p class="step">
                        <i>Step 3 of 3: Select the tone of the document</i>
                        <span class="glyphicon glyphicon-info-sign step-instruction-glyphicon"
                            aria-hidden="true" data-trigger="hover"
                            data-toggle="popover" data-html="true"
                            data-viewport="#transcribe-form"
                            data-title="<strong>Selecting document tone</strong>"
                            data-content="<?php echo "Choose the tone from the dropdown that most accurately categorizes the overall tone of the document." ?>"
                            data-placement="bottom" data-id="<?php echo $transcription->id; ?>">
                        </span>
                    </p>
                    <select id="tone-selector" class="form-control" name="tone">
                        <option value="informational">Informational</option>
                        <option value="anxiety">Anxiety</option>
                        <option value="optimism" default selected>Optimism</option>
                        <option value="sarcasm">Sarcasm</option>
                        <option value="pride">Pride</option>
                        <option value="aggression">Aggression</option>
                    </select>
                </div>
                <button id="submit_transcription" type="button" class="btn btn-primary">Submit</button>
                <input type="hidden" name="query_str" value="<?php echo (isset($this->query_str) ? $this->query_str : ""); ?>">
            </form>

            <?php
                include(dirname(__FILE__) . '/../common/revision_history_for_task_id_pages.php');
            ?>

            </div>

            <br>
            <hr size=2 class="discussion-seperation-line">

            <body>
                <div id="comment-container" class="comments-section-container">
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
        </div>
    </div>
    <!-- /.container -->
    <script type="text/javascript">
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
            $('#submit_transcription').on('click', function(e) {
                if ($('#transcription-textarea').val() === "") {
                    notifyOfErrorInForm('Please provide a transcription of the document');
                    return;
                }
                if ($('#summary-textarea').val() === "") {
                    notifyOfErrorInForm('Please provide a summary of the document');
                    return;
                }
                if ($('#tone-selector').val() === "") {
                    notifyOfErrorInForm('Please select the tone of the document');
                    return;
                }
                //$('#transcribe-form').submit();
                alert('This will redirect you to assessment document');
            });

            <?php
                if (isset($_SESSION['incite']['message'])) {
                    echo "notifyOfSuccessfulActionNoTimeout('" . $_SESSION["incite"]["message"] . "');";
                    unset($_SESSION['incite']['message']);
                }
            ?>

            <?php if ($this->is_being_edited): ?>
                styleForEditing();
            <?php endif; ?>
        });
        

        function styleForEditing() {
            populateWithLatestTranscriptionData();
            addRevisionHistoryListeners();
        }

        function populateWithLatestTranscriptionData() {
            $('#transcription-textarea').html(<?php echo sanitizeStringInput(isset($this->latest_transcription['transcription']) ? $this->latest_transcription['transcription'] : 'nothing'); ?>.value);
            $('#summary-textarea').html(<?php echo sanitizeStringInput(isset($this->latest_transcription['summary']) ? $this->latest_transcription['summary'] : 'nothing'); ?>.value);
            $('#tone-selector').val('<?php echo isset($this->latest_transcription["tone"]) ? $this->latest_transcription["tone"] : "nothing"; ?>');
        }

        function addRevisionHistoryListeners() {
            $('#view-revision-history-link').show();

            $('#view-revision-history-link').click(function(e) {
                $('#transcribe-form').hide();
                $('#revision-history-container').show();
            });

            $('#view-editing-link').click(function(e) {
                $('#revision-history-container').hide();
                $('#transcribe-form').show();
            });
        }
        
        
        var tour = new Tour({
            
            //onShown: function (tour) {
              //  $(".popover.tour-tour .popover-navigation .btn-group .btn[data-role=next]").prop("disabled", true);
            //}
        //})
            template: "<div class='popover tour'><div class='arrow'></div><h3 class='popover-title'></h3><div class='popover-content'></div><nav class='popover-navigation'><div class='btn-group'><button class='btn btn-default' data-role='prev'>« Prev</button><button class='btn btn-default' data-role='next'>Next »</button></div><button class='btn btn-default btn-end' data-role='end'>End tour</button></nav></div>",
        steps: [
            {
                element: '#work-view',
                title: "Welcome!",
                content: "It looks like you haven’t transcribed a document before. We have a short tutorial to guide you through the process. If you already know all this information, press End Tour now.",
                placement: "right",
            },
            {
                element: '#work-view',
                title: "",
                content: 'This is a historical document from the Civil War era. Right now this document is just an image, but we want to make this text searchable by transcribing it.',
                placement: "right",
                onShown: function() {
                    $(".popover.tour-tour .popover-navigation .btn-group .btn[data-role=end]").prop("disabled", true);
                }
            },
            {
                element: '#document-header',
                title: "More Information",
                content: 'Hovering over the i Icon at the top provides more in-depth information on the document. Try hovering now to see for yourself.',
                placement: "right",
                onShown: function(){
                    $(".popover.tour-tour .popover-navigation .btn-group .btn[data-role=end]").prop("disabled", true);
                    $(".popover.tour-tour .popover-navigation .btn-group .btn[data-role=next]").prop("disabled", true);
                    $('#document-info-glyphicon').one("mouseenter", function() { 
                        tour.next();
                    });
                }
            },
            {
                element: "#document-header",
                title: "Good job!",
                content: 'Good job! Press next to continue.',
                placement: "right",
                onShown: function() {
                    $(".popover.tour-tour .popover-navigation .btn-group .btn[data-role=end]").prop("disabled", true);
                }
                
            },
            {
                element: '#viewer2',
                title: "Scrolling",
                content: 'You can get a better look at the document by using the zoom tools in the bottom left corner. Try using the zoom tool on this document now. ',
                placement: "right",
                onShown: function(){
                    $(".popover.tour-tour .popover-navigation .btn-group .btn[data-role=end]").prop("disabled", true);
                    $(".popover.tour-tour .popover-navigation .btn-group .btn[data-role=next]").prop("disabled", true);
                    $('#zoomer').click(function() { 
                        tour.goTo(5);
                    });
                }
            },
            {
                element: "#viewer2",
                title: "Great!",
                content: 'Great! Lets continue. Please press next to continue.',
                placement: "right",
                onShown: function() {
                    $(".popover.tour-tour .popover-navigation .btn-group .btn[data-role=end]").prop("disabled", true);
                }
            },
            {
                element: "#submit-zone",
                title: "Transcribing Process",
                content: 'The transcribing process includes three steps. <br> 1. Transcribe: Type the contents of the document, word-for-word <br> 2. Summarize: Provide a brief summary of the document <br> 3. Tone: Out of the options available, select the most relevant tone of the document',
                placement: "left",
                onShown: function() {
                    $(".popover.tour-tour .popover-navigation .btn-group .btn[data-role=end]").prop("disabled", true);
                }
            },
            {
                element: "#transcription-textarea",
                title: "Transcription",
                content: 'When transcribing, try your best to be as accurate as possible. If you run into a word or sentence that’s too difficult to read, make an educated guess and move on. <br>Try it for yourself now! Type out every word in the document the best you can. (Welcome to the Incite Tutorial. This tutorial is meant to help you get acquainted <br>with the three main tasks of our website. These tasks include transcribing, tagging, and connecting.)',
                placement: "left",
                onShown: function() {
                    $(".popover.tour-tour .popover-navigation .btn-group .btn[data-role=end]").prop("disabled", true);
                    $(".popover.tour-tour .popover-navigation .btn-group .btn[data-role=next]").prop("disabled", true);
                    $('#transcription-textarea').on("input", function() {
                        
                        if (this.value == 'Welcome to the Incite Tutorial. This tutorial is meant to help you get acquainted with the three main tasks of our website. These tasks include transcribing, tagging, and connecting.') {
                            tour.next();
                        }
                    });
                }
            },
            {
                element: '#transcription-textarea',
                title: "Nice!",
                content: "You've finished the transcription process! Press next to continue.",
                placement: "left",
                onShown: function() {
                    $(".popover.tour-tour .popover-navigation .btn-group .btn[data-role=end]").prop("disabled", true);
                }
                
            },
            {
                element: '#summary-textarea',
                title: "Summarize",
                content: "Now try summarizing the document in 1-2 sentences.  Remember, be brief and try to bring out the most important aspects of the document!",
                placement: "left",
                onShown: function() {
                    $(".popover.tour-tour .popover-navigation .btn-group .btn[data-role=end]").prop("disabled", true);
                    $(".popover.tour-tour .popover-navigation .btn-group .btn[data-role=next]").prop("disabled", true);
                    var counter = 0;
                    $('#summary-textarea').on("keydown", function(event) {
                        
                        if (event.keyCode == 190) {
                            counter = counter + 1;
                            if (counter == 2) {
                                tour.next();
                                
                            }
                            
                            $(".popover.tour-tour .popover-navigation .btn-group .btn[data-role=next]").prop("disabled", false);
                        }
                        
                    });
                }
                
            },
            
            {
                element: '#summary-textarea',
                title: "Almost there!",
                content: "You've finished the part for the summary process! Press next to proceed to the final step of this tutorial.",
                placement: "left",
                onShown: function() {
                    $(".popover.tour-tour .popover-navigation .btn-group .btn[data-role=end]").prop("disabled", true);
                }
                
            },
            {
                element: '#tone-selection',
                title: "Selecting the Tone",
                content: "Now lets try selecting a tone from the dropdown box.",
                placement: "left",
                
                onShown: function() {
                    $(".popover.tour-tour .popover-navigation .btn-group .btn[data-role=end]").prop("disabled", true);
                    $(".popover.tour-tour .popover-navigation .btn-group .btn[data-role=next]").prop("disabled", true);
                    $('#tone-selector').change(function() {
                        if (this.value == 'informational') {
                            tour.next();
                        }
                    });
                }
            },
            {
                element: '#tone-selection',
                title: "Great job! You're almost done!",
                content: "You've finished the tutorial for the entire transcription process. Here are some extra tidbits of information.",
                placement: "left",
                onShown: function() {
                    $(".popover.tour-tour .popover-navigation .btn-group .btn[data-role=end]").prop("disabled", true);
                }
                
            },
            {
                element: '#comment-container',
                title: "Comments",
                content: "Other users may give tips or opinions on a certain document. Make sure to login or sign up to contribute to the discussion!",
                placement: "left",
                onShown: function() {
                    $(".popover.tour-tour .popover-navigation .btn-group .btn[data-role=end]").prop("disabled", true);
                }
                
            },
            
            {
                element: '#work-view',
                title: "Congratulations!",
                content: "You've finished the tutorial for the transcription process! Press End Tour to close this tutorial.",
                placement: "right",
                
            }
        ],
            
        backdrop: true,
        storage: false});
        

        // Initialize the tour
        tour.init();

        // Start the tour
        tour.start(true);
    </script>

    <style>
        #submit-zone {
            margin-top: -32px;
        }
        .btn-end {
            display: none;
        }
        #step-0 .btn-end { display: block; }
        
        #step-14 .btn-end { display: block; }

        #submit_transcription {
            float: right;
        }

        #transcription-textarea {
            width: 100%;
        }

        #summary-textarea {
            width: 100%;
            height: 66px;
        }

        .discussion-seperation-line {
            margin-top: 35px;
            margin-bottom: 0px;
        }

        .tooltip {
            position: fixed;
        }

        #view-revision-history-link {
            position: absolute;
            right: 0;
            cursor: pointer;
        }
        .tour-backdrop,
    .tour-step-background {
        z-index: 3;
    }
        
    </style>
</body>

</html>
