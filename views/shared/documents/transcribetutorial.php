<!DOCTYPE html>
<html lang="en">

	<?php
		$_SESSION['Incite']['tutorial_trans'] = true;
		include(dirname(__FILE__) . '/../common/header.php');
		include(dirname(__FILE__) . '/../common/progress_indicator.php');
	?>

	<script type="text/javascript">
	  var info_times = 0;
		var trans_times = 0;
		var zoomer_times = 0;
		var tone_times = 0;
		var sum_times = 0;
		var msgbox;
		var comment_type = 0;
		var textArea;
		textArea = document.getElementById("transcription-textarea");
	</script>

	<!-- Page Content -->
	<?php
		include(dirname(__FILE__) . '/../common/task_header.php');
	?>

	<div class="container-fluid">
		<head>
			<script type="text/javascript">
			function resize() {
				$('#work-view').width($('#work-zone').width());
			}
			$('#work-zone').ready(function () {
				$('#work-view').width($('#work-zone').width());
			});

				$(document).ready(function () {

					$('[data-toggle="popover"]').popover({trigger: "hover"});

					$('.viewer').height($(window).height() - $('.viewer')[0].getBoundingClientRect().top - 10 - $(".navbar-fixed-bottom").height());
					$("#viewer2").iviewer({
						src: "<?php echo getFullOmekaUrl(); ?>/plugins/Incite/views/shared/images/tutorial_img.jpg",
						zoom_min: 1,
						zoom: "fit"
					});

					buildPopoverContent();
				});

				function draw() {
					$('<img id = "pic" src="<?php echo getFullOmekaUrl(); ?>/plugins/Incite/views/shared/images/check.png" height = "100" width = "100" >').appendTo($("#step .popover-content"));
					setTimeout(function(){$( "#pic" ).remove();}, 3000);
				}

				function buildPopoverContent() {
					var content = '';
					var date = "1860-08-06";
					var location = "Germany-Berlin state-Berlin";
					var source = "The Daily Dispatch (Richmond, VA)";
					var contributor = "";
					var rights = "Chronicling America: Historic American Newspapers. Lib. of Congress.";
					if (date) content += '<strong>Date: </strong>' + date + '<br><br>';
					if (location) content += '<strong>Location: </strong>' + location + '<br><br>';
					if (source) content += '<strong>Source: </strong>' + source + '<br><br>';
					if (contributor) content += '<strong>Contributor: </strong>' + contributor + '<br><br>';
					if (rights) content += '<strong>Rights: </strong>' + rights + '<br><br>';
					else content += '<strong>Rights: </strong>Public Domain<br><br>';

					if (content) {
						//cut off the last <br><br>
						content = content.slice(0, -8);
						$('#document-info-glyphicon').attr('data-content', content);
					} else $('#document-info-glyphicon').attr('data-content', "No available document information, sorry!");
				}
			</script>
		</head>

		<body onresize="resize()">
			<div class="col-md-6" id="work-zone">

				<div id="work-view">
					<div class="document-header" id="document-header" style = "position:relative">
						<span class="document-title" title="Incite Tutorial - Transcribe" ><b>Title:</b> Sunday School Celebration in Prussia</span>
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

						<div id = "tran-div">
						<textarea id="transcription-textarea" name="transcription" rows="15" placeholder="Provide a 1:1 transcription of the document"></textarea>
						<p style = "float: right">Character Count: <span id = "counting">0</span></p>
						</div>

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

						<div id = "sum-div">
						<textarea id="summary-textarea" name="summary" rows="5" placeholder="Provide a 1-2 sentence summary of the document"></textarea>
						<p style = "float: right">Character Count: <span id = "s-counting">0</span></p>
						</div>

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
					<br><br>
					<ul id="comments" class="comments-list"></ul>
				</div>
			</div>
		</body>
	</div>


    <!-- /.container -->
    <script type="text/javascript">
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
            $('#submit_transcription').on('click', function(e) {
				window.location = '<?php echo getFullInciteUrl().'/documents/transcribe/'.$this->doc_id; ?>';
            });
						$('#summary-textarea').keyup(function() {
							var text_length = $('#summary-textarea').val().length;
							$('#s-counting').text(text_length);
						});
						$('#transcription-textarea').keyup(function() {
							var text_length = $('#transcription-textarea').val().length;
							$('#counting').text(text_length);
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
						keyboard: false,
            template: "<div class='popover tour'><div class='arrow'></div><h3 class='popover-title'></h3><div class='popover-content'></div><nav class='popover-navigation'><div class='btn-group'><button class='btn btn-default' data-role='prev'>« Prev</button><button class='btn btn-default' data-role='next'>Next »</button></div><button class='btn btn-default btn-end' data-role='end'>End Tour</button></nav></div>",
        steps: [
            {
								element: "#work-view",
                title: "Welcome!",
                content: "It looks like you haven’t transcribed a document before. We have a short tutorial to guide you through the process. <br><br> If you already know this information, press End Tour now, and press the submit button when you want to leave the tutorial.",
                placement: "right",
								onShow: function() {
									$('html, body').css({overflow: 'auto',height: 'auto'}); //restore scrolling
									$("#document-header").css("z-index", "3");
									$("#viewer2").css("z-index", "3");
								},
								onShown: function() {
									$('html, body').css({overflow: 'hidden',height: '100%'});//disable scrolling
									$("#document-header").css("z-index", "1101");
								}
            },
            {
								element: "#work-view",
                title: "Welcome! (continued)",
                content: 'This is a historical document from the Civil War era. Right now this document is just an image, but we want to make this image searchable by transcribing it. This means you will type what the image says.',
                placement: "right",
								onShow: function() {
									$('html, body').css({overflow: 'auto',height: 'auto'}); //restore scrolling
									$("#document-header").css("z-index", "3");
									$("#viewer2").css("z-index", "3");
								},
                onShown: function() {
										$('html, body').css({overflow: 'hidden',height: '100%'});//disable scrolling
										$("#document-header").css("z-index", "1101");
                }
            },
            {
                element: '#document-header',
                title: "More Information",
                content: 'By hovering over the <span class="glyphicon glyphicon-info-sign" id="document-info-glyphicon" style="top: 5px;;"></span> at the top of a document, you will see more in-depth information about the document.<br>',
                placement: "right",
								onShow: function() {
									$("#document-header").css("z-index", "3");
									$('html, body').css({overflow: 'auto',height: 'auto'}); //restore scrolling
								},
                onShown: function(){
										$("#document-header").css("z-index", "1102");
										$('html, body').css({overflow: 'hidden',height: '100%'});//disable scrolling
										$("#document-info-glyphicon").hover(function() {
											info_times++;
										 if(info_times == 1) {
												$('<img id = "pic1"  height = "100" width = "100" >').appendTo($("#step-2 .popover-content"));
												$("#pic1").attr('src', "<?php echo getFullOmekaUrl(); ?>/plugins/Incite/views/shared/images/check.gif?"+ Math.random());
											}
											setTimeout(function(){$( "#pic1" ).remove();}, 3000);
											}
										);
                }
            },
            {
								element: "#work-view",
                title: "Zoom",
                content: 'You can get a better look at the document by using the zoom tools in the bottom left corner. <br>',
                placement: "right",
								onShow: function() {
									$('html, body').css({overflow: 'auto',height: 'auto'}); //restore scrolling
									$("#document-header").css("z-index", "3");
								},
                onShown: function(){
										$('html, body').css({overflow: 'hidden',height: '100%'});//disable scrolling
										$("#document-header").css("z-index", "1101");
										$('.iviewer_common').click(function() {
											zoomer_times++;
											if(zoomer_times == 1) {
												$('<img id = "pic2" height = "100" width = "100" >').appendTo($("#step-3 .popover-content"));
												$("#pic2").attr('src', "<?php echo getFullOmekaUrl(); ?>/plugins/Incite/views/shared/images/check.gif?"+ Math.random());
											}
											setTimeout(function(){$( "#pic2" ).remove();}, 3000);
											}
										);
                }
            },
            {
                element: "#submit-zone",
                title: "Transcribing Process",
                content: 'The transcribing process includes three steps. <br> 1. Transcribe: Type the contents of the document, word-for-word. <br> 2. Summarize: Provide a brief summary of the document. <br> 3. Tone: Out of the options available, select the most relevant tone of the document.',
                placement: "left",
								onShow: function() {
									$('html, body').css({overflow: 'auto',height: 'auto'}); //restore scrolling
									$("#document-header").css("z-index", "3");
									$("#viewer2").css("z-index", "3");
								},
                onShown: function() {
										$('html, body').css({overflow: 'hidden',height: '100%'});//disable scrolling
                }
            },
            {
                element: "#tran-div",
                title: "Transcribe",
                content: 'When transcribing, try your best to be as accurate as possible.  <br> Since this is a tutorial, you can just type in the first sentence of the paragraph. <br><br>Hint: There are 37 characters including space in total for the first sentence!<br>',
                placement: "bottom",
								onShow: function() {
									$('html, body').css({overflow: 'auto',height: 'auto'}); //restore scrolling
									$("#document-header").css("z-index", "3");
								},
							 	onShown: function() {
								 $('html, body').css({overflow: 'hidden',height: '100%'});//disable scrolling
								 $("#viewer2").css("z-index", "1101");
								 $('#transcription-textarea').on("input", function()
									{
										 if (this.value == 'SUNDAY SCHOOL CELEBRATION IN PRUSSIA.')
										 {
											 trans_times++;
 											 if(trans_times == 1) {
 													$('<img id = "pic3" height = "100" width = "100" >').appendTo($("#step-5 .popover-content"));
 													$("#pic3").attr('src', "<?php echo getFullOmekaUrl(); ?>/plugins/Incite/views/shared/images/check.gif?"+ Math.random());
 												}
 											setTimeout(function(){$( "#pic3" ).remove();}, 3000);
										 }
									 }
								 );
								 }
            },
						//Summary part
						{
								element: "#sum-div",
                title: "Summarize",
                content: 'Provide a brief summary of the document.<br>',
                placement: "bottom",
								onShow: function() {
									$('html, body').css({overflow: 'auto',height: 'auto'}); //restore scrolling
									$("#document-header").css("z-index", "3");
								},
                onShown: function() {
									$('html, body').css({overflow: 'hidden',height: '100%'});//disable scrolling
									$("#viewer2").css("z-index", "1101");
									$('#summary-textarea').on("input", function() {
											if (this.value !== '') {
												sum_times++;
												if(sum_times == 1) {
													$('<img id = "pic4" height = "100" width = "100" >').appendTo($("#step-6 .popover-content"));
													$("#pic4").attr('src', "<?php echo getFullOmekaUrl(); ?>/plugins/Incite/views/shared/images/check.gif?"+ Math.random());
												}
												setTimeout(function(){$( "#pic4" ).remove();}, 3000);
										 }
 									 });
                }
            },
						//tone part
						{
                element: "#tone-selector",
                title: "Tone",
                content: 'Out of the options available, select the most relevant tone of the document. <br><br> Hint: “Informational” could be a correct answer.<br>',
                placement: "bottom",
								onShow: function() {
									$('html, body').css({overflow: 'auto',height: 'auto'}); //restore scrolling
									$("#document-header").css("z-index", "3");
								},
                onShown: function() {
									$("#viewer2").css("z-index", "1101");
										$('#tone-selector').change(function() {
										if ($('#tone-selector').val() == 'informational') {
											tone_times++;
											if(tone_times == 1) {
												$('<img id = "pic5"  height = "100" width = "100" >').appendTo($("#step-7 .popover-content"));
												$("#pic5").attr('src', "<?php echo getFullOmekaUrl(); ?>/plugins/Incite/views/shared/images/check.gif?"+ Math.random());
											}
											setTimeout(function(){$( "#pic5" ).remove();}, 3000);
										}
										$('html, body').css({overflow: 'hidden',height: '100%'});//disable scrolling
                    });
                }
            },
						{
							element: '#comment-container',
							title: "Comments",
							content: "Other users may give tips or opinions on a certain document. Make sure to login or sign up to contribute to the discussion!",
							placement: "top",
								onShow: function() {
									$('html, body').css({overflow: 'auto',height: 'auto'}); //restore scrolling
									$("#document-header").css("z-index", "3");
									$("#viewer2").css("z-index", "3");
								},
                onShown: function() {
										$('html, body').css({overflow: 'hidden',height: '150%'});//disable scrolling
                }
            },
						//Congratulations
						{
							  orphan: true,
								element: "#submit_transcription",
                title: "Congratulations",
                content: 'You have completed transcribe tutorial, and you can click the submit to go to the real document that you just chose.',
                placement: "left",
								onShow: function(){
									$('html, body').css({overflow: 'auto',height: 'auto'}); //restore scrolling
									$("#viewer2").css("z-index", "3");
									$("#document-header").css("z-index", "3");
								},
								onShown: function() {
									$('html, body').css({overflow: 'hidden',height: '100%'});//disable scrolling
									$("#viewer2").css("z-index", "6");
                }
            },
        ],
				onEnd: function(tour) {
			     $('html, body').css({overflow: 'auto',height: 'auto'});//restore scrolling
					 window.location = '<?php echo getFullInciteUrl().'/documents/transcribe/'.$this->doc_id; ?>';
			  },
				backdrop: true,
        storage: false});
        // Initialize the tour
        tour.init();
        // Start the tour
        tour.start(true);
    </script>


    <style>
		#work-view {
				position: relative;
				margin-top: -30px;
			}
			.viewer {
				border: 1px solid black;
				position: relative;
			}

			.wrapper {
				overflow: hidden;
				margin-top: 7px;
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
        #submit-zone {
            margin-top: -32px;
        }

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
				#viewer2 {
					background: white;
					margin-left: 10px;
				}

				#document-header {
					margin-left: 10px;
				}

    </style>
</body>

</html>
