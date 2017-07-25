<!DOCTYPE html>
<html lang="en">

	<?php
		$_SESSION['Incite']['tutorial_trans'] = true;
		$groupid = $this->groupid;
		$groupAssessStatus = "group".$groupid;
		$_SESSION['Incite']['assessment_trans'][$groupAssessStatus] = true;
		include(dirname(__FILE__) . '/../common/header.php');
		include(dirname(__FILE__) . '/../common/progress_indicator.php');
		$tone = getTone($this->document_metadata->id);
		$toneReason = getToneReason($this->document_metadata->id);
	?>

	<script type="text/javascript">
		var answer_tone = <?php echo json_encode($tone).";\n" ?>
		var tone_reason = <?php echo json_encode($toneReason).";\n" ?>
		var msgbox;
		var comment_type = 0;
		var textArea;
		var textToCheck;
		textToCheck = "Hello World";
		textArea = document.getElementById("transcription-textarea");

		function updateTransAjaxRequest() {
    	var request = $.ajax({
        type: "POST",
        url: "<?php echo getFullInciteUrl().'/ajax/finddiff'; ?>",
        data: {'userTranscription': $('#transcription-textarea').val()},
				success: function (response) {
					$("#userText").append(response);
        }
    });
	}


	function saveTransAjaxRequest() {
		var request = $.ajax({
			type: "POST",
			url: "<?php echo getFullInciteUrl().'/ajax/savetrans'; ?>",
			data: {'transcription': $('#transcription-textarea').val(), 'summary': $('#summary-textarea').val(), 'tone': $('#tone-selector').val()},
			success: function (response) {
				console.log(response);
			}
	});
}

	function resize() {
			$('#work-view').width($('#work-zone').width());
	}
	</script>

	<!-- Page Content -->
	<?php
		include(dirname(__FILE__) . '/../common/task_header.php');
	?>
	<?php
			include(dirname(__FILE__) . '/../common/document_viewer_section_without_transcription.php');
	?>

	<div class="container-fluid">
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

				<div class="container">
				  <!-- Trigger the modal with a button -->

				  <!-- Modal -->
				  <div class="modal modal-wide fade" id="myModal" role="dialog">
				    <div class="modal-dialog">

				      <!-- Modal content-->
				      <div class="modal-content">
				        <div class="modal-header">
				          <h4 class="modal-title">Results</h4>
				        </div>
				        <div class="modal-body">
									<ul class="nav nav-justified nav-pills">
										<br>
										<li class="active"><a>Transcription</a></li>
										<br>
										<b>Color Meaning: <span class="del" style="display:inline-block;width:16px">&nbsp;</span>=mismatching answers, <span class="ins" style="display:inline-block;width:16px">&nbsp;</span>=historians' supplimental answers, No color = matched answer</b>
									  <br>
									</ul>

	                    <div id="trans">
												<br>
	                      <div id = "userText"></div>
												<div style = "clear: both">
													<br>
													</div>
	                    </div>
											<br>

									<ul class="nav nav-justified nav-pills">
										<li class="active"><a>Tone</a></li>

									</ul>

	                    <div class="tab-pane" id="tones">
												<br>
												<b>Color Meaning: <span class="del" style="display:inline-block;width:16px">&nbsp;</span>=mismatching answers, <span class="ins" style="display:inline-block;width:16px">&nbsp;</span>=historians' supplemental answers, No color = matched answer</b>
											  <br>
												<br>
												<div id = "right_tone"></div>
	                    </div>
											<br>
				        </div>
								<div class = "clearfix"></div>
				        <div class="modal-footer">
				          <button type="button" id = "closeButton" class="btn btn-default" data-dismiss="modal">Close</button>
				        </div>
				      </div>

				    </div>
  				</div>

					</div>



				<br>
				<hr size=2 class="discussion-seperation-line">
				<?php
						include(dirname(__FILE__) . '/../common/task_comments_section.php');
				?>
<!--
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
			-->
			</div>
			<!--
		</body>
	-->
	</div>

    <!-- /.container -->
    <script type="text/javascript">
        $(document).ready(function () {
					$("#closeButton").click(function(){
						window.location = '<?php echo getFullInciteUrl().'/documents/transcribe/'.$this->doc_id; ?>';
					});
            $('[data-toggle="tooltip"]').tooltip();
            $('#submit_transcription').on('click', function(e) {
							saveTransAjaxRequest();
							UserTone();
							updateTransAjaxRequest();
							$('#myModal').modal({backdrop: 'static', keyboard: false, show: true});
							/*
							$("#rightText").append("THE FOURTH OF JULY AT SHREVEPORT – We learn from the Southwestern that it is the purpose of the military companies there to celebrate the Fourth of July by a general review, grand parade and dinner. It says:The Yankees have robbed us of too much already. We have no idea of giving up the national anniversary—not a bit of it. The Fourth of July is ours. The declaration of independence declared and reiterated the doctrine for which we are to-day fighting. It was drafted by a southern man and advocated by Washington and a host of other southern heroes. The Shreveport Sentinels have appointed a committee to consult with similar committees to be appointed by the artillery company—the Summer Grove cavalry and the Keachi company, for the purpose of carrying out this laudable purpose. Long live the Confederacy, and huzza for the old Fourth of July.");
							*/
							$('#submit_transcription').prop("disabled", "true");
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

				function UserTone () {
					var checkmark = '<img src="<?php echo getFullOmekaUrl(); ?>plugins/Incite/views/shared/images/checkMark.png" height = "20" width = "20" >'+ '&nbsp;&nbsp;&nbsp;';
					var crossmark = '<img src="<?php echo getFullOmekaUrl(); ?>plugins/Incite/views/shared/images/wrong.png" height = "20" width = "20" >' + '&nbsp;&nbsp;&nbsp;';
					if ($('#tone-selector').val() == answer_tone)
						$("#right_tone").append("Your answer: <ul>"+ checkmark + $('#tone-selector').val() + " (Your answer matched with historians answers below)</ul>" +"Historians' answer:<br /><ul>" + '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' + "<ins>" + answer_tone + "</b></ins>: "+ tone_reason + "</ul>");
					else
						$("#right_tone").append("Your answer: <ul>" + crossmark + "<del>" + $('#tone-selector').val() +"</del> (Your answer did not match with historians answers below)</ul>Historians' answer:<br /><ul>" + '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' + "<ins>" + answer_tone + "</b></ins>: "+ tone_reason + "</ul>");

				}
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

    </script>


    <style>
		html, body {
			max-width: 100%;
			overflow-x: hidden;
		}

				#work-zone {
					overflow: hidden;
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

        .tour-backdrop,
    .tour-step-background {
        z-index: 3;


    }
		ins {

	    background: #A8E6CF;
	    text-decoration: none;
		}
		del {

	    background: #FAB5C2;
	    text-decoration: none;
		}

		.ins {
			background:#A8E6CF;
			}
		.del {
	    background: #FAB5C2;
			}

	    #userText {
	      width: 100%;
	      float:right;
	      padding 0 10px;
				padding-left: 10px;
				padding-right: 5px;
	    }

			#right_tone {
				width: 100%;
	      float:right;
	      padding 0 10px;
				padding-left: 10px;
			}

			.modal.modal-wide .modal-dialog {
		    width: 80%;
		  }

.nav nav-pills {
	width: 100%;
}
			.result_title {
/*<li  class = "active" id="transcribe-tab">Transcription</li>*/
/*<li  id="tone-tab">Tone</li>*/
			}

    </style>
</body>

</html>
