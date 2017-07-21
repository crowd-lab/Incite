<!DOCTYPE html>
<html lang="en">
<head>
    <?php
        $_SESSION['Incite']['tutorial_conn'] = true;
        $groupid = $this->groupid;
        $groupAssessStatus = "group".$groupid;
        $_SESSION['Incite']['assessment_conn'][$groupAssessStatus] = true;
        include(dirname(__FILE__).'/../common/header.php');
        include(dirname(__FILE__).'/../common/progress_indicator.php');

        $category_object = getAllCategories();
        //$subject_from_gold_standard = findAllRatingsFromGoldStandard(findTaggedTransIDFromGoldStandard($this->document_metadata->id));
        $subject_from_gold_standard = findAllRatingsFromGoldStandard($this->document_metadata->id);
        $explain_list = explainDic($this->document_metadata->id);
    ?>

    <!-- Page Content -->
    <script type="text/javascript">
        var comment_type = 2;
        var theme_ratings = ["Not useful","Somewhat useful","Useful", "Very useful", "Extremely useful"];
        var answer_ratings = ["Very useful","Very useful","Very useful", "Very useful", "Very useful"];
        var concept_to_id = {"Religion":1, "White Supremacy":2, "Racial Equality":3, "Gender Equality/Inequality":4, "Human Equality":5, "Self Goverment":6, "America as a Global Beacon":7, "Celebration of Revolutionary Generation":8, "White Southerners":9};
        var upload_rating = [];
        function resize() {
					$('#work-view').width($('#work-zone').width());
				}
        function updateRatingsAjaxRequest() {
          var request = $.ajax({
            type: "POST",
            url: "<?php echo getFullInciteUrl().'/ajax/uploadratings'; ?>",
            data: {'ratings': upload_rating},
            success: function (response) {
              //console.log(response);
              //alert(response);
            }
          });
        }
    </script>
</head>
<body id = "main-body" onresize="resize()">
    <?php
        include(dirname(__FILE__) . '/../common/task_header.php');
    ?>
    <div class="container-fluid">
        <div class="container-fluid" style="padding: 0px;">

            <div class="col-md-6" id="work-zone">
              <?php
                   include(dirname(__FILE__) . '/../common/document_viewer_section_with_transcription.php');
               ?>
<!--
<head>
	<script type="text/javascript">

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



      $('#reasoning').keyup(function() {
        var text_length = $('#reasoning').val().length;

        $('#word-counting').text(text_length);

      });

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

		    //setLegendWidth();

		    $('.viewer').height($(window).height()-$('#transcribe_copy')[0].getBoundingClientRect().top-10-$(".navbar-fixed-bottom").height());

	        $('#transcribe_copy').height($(window).height()-$('#transcribe_copy')[0].getBoundingClientRect().top-10-$(".navbar-fixed-bottom").height());

	        $("#document_img").iviewer({
	            src: "<?php echo getFullOmekaUrl(); ?>plugins/Incite/views/shared/images/assess1.png",
	            zoom_min: 1,
	            zoom: "fit"
        	});

            //buildPopoverContent();
		});

        function buildPopoverContent() {
          var content = '';
          var date = "1861-06-29";
          var location = "Louisiana - Orleans Parish - New Orleans";
          var source = "Daily True Delta";
          var contributor = "";
          var rights = "American Antiquarian Society";

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

<body>
	<div id="work-view">
        <div class="document-header">
            <span class="document-title" title="Incite Tutorial - Connect">
                <b>Title:</b> The Fourth of July at Shreveport - Connect
            </span>
            <span id="document-info-glphicon" class="glyphicon glyphicon-info-sign"
                data-toggle="popover" data-html="true" data-trigger="hover"
                data-viewport=".document-header" aria-hidden="true"
                data-title="<strong>Document Information</strong>"
                data-placement="bottom" data-id="">
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
        width: 60%;
        text-align: right;
    }

    .viewer {
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
-->
            </div>

            <div class="col-md-6" id="connecting-work-area" style = "clear: both;">
                <div id="connecting-container">
                    <p class="header-step">
                        <i>Step 1 of 1: What themes in the following could this document help a historian research/inve    stigate? Please rate based on usefulness</i>
                        <a id="view-revision-history-link" style="display: none;">View Revision History...  </a>
                        <span class="glyphicon glyphicon-info-sign step-instruction-glyphicon"
                            aria-hidden="true" data-trigger="hover"
                            data-toggle="popover" data-html="true"
                            data-viewport="#subject-form";
                            data-title="<strong>Marking Categories</strong>"
                            data-content="<?php echo "Simply choose all of the categories you think apply to this document. If none apply, select 'None of the above topics applied'." ?>"
                            data-placement="bottom" data-id="<?php echo $transcription->id; ?>">
                        </span>
                    </p>

                    <form id="subject-form" method="post">
                        <table class="table" id="en-table">
                            <thead>
                                <td>Themes</td>
                                <td>Not useful</td>
                                <td>Somewhat useful</td>
                                <td>Useful</td>
                                <td>Very useful</td>
                                <td>Extremely useful</td>
                            </thead>
                        <?php foreach ((array)$this->subjects as $subject): ?>
                            <tr>
                                <td><label><a data-toggle="popover" data-trigger="hover" data-title="Definition" data-content="<?php echo $subject['definition']; ?>"><?php echo $subject['name']; ?></a></label></td>
                                <td><input type="radio" name="subject<?php echo $subject['id']; ?>" value="0"></td>
                                <td><input type="radio" name="subject<?php echo $subject['id']; ?>" value="1"></td>
                                <td><input type="radio" name="subject<?php echo $subject['id']; ?>" value="2"></td>
                                <td><input type="radio" name="subject<?php echo $subject['id']; ?>" value="3"></td>
                                <td><input type="radio" name="subject<?php echo $subject['id']; ?>" value="4"></td>
                            </tr>
                        <?php endforeach; ?>
                        </table>
                        <p class="header-step">
                            <i>Step 2 of 2: Please provide your reasoning for your above choices.</i>
                        </p>
                        <textarea style="width:100%;" name="reasoning" rows="5" id = "reasoning"></textarea>
                        <p style = "float: right">Character Count: <span id = "word-counting">0</span></p>
                        <br>
                        <br>
                        <input type="hidden" name="connection_type" value="multiscale">
                        <input type="hidden" name="query_str" value="<?php echo (isset($this->query_str) ? $this->query_str : ""); ?>">

                    </form>
                    <div class = "clearfix"></div>
                      <button type="button" id="submit-selection-btn" class="btn btn-primary pull-right">Submit</button>
                </div>

                <?php
                    include(dirname(__FILE__) . '/../common/revision_history_for_task_id_pages.php');
                ?>

                <hr size=2 class="discussion-seperation-line">
                <?php
                    include(dirname(__FILE__) . '/../common/task_comments_section.php');
                ?>
<body>
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
        <br>
        <br>
        <ul id="comments" class="comments-list"></ul>
    </div>
-->
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
              <ul class="nav nav-tabs nav-justified nav-pills">
                  <li class="active"><a href="#themes" data-toggle="tab">Themes Rating</a></li>
              </ul>
              <div id = "themes">
                <br>
                <b>Color Meaning: <span class="wrong" style="display:inline-block;width:16px">&nbsp;</span>=mismatching answers, <span class="insert" style="display:inline-block;width:16px">&nbsp;</span>=historians' supplemental answers, No color = matched answer</b>
                <br>
                <br>
              <div id = "userPart">

                <table class = "theme" id = "userTheme">
                  <tr>
                    <th>Theme</th>
                    <th>Rating</th>
                  </tr>
                  <?php foreach ((array)$this->subjects as $subject): ?>
                      <tr>
                          <td><label><a data-toggle="popover" data-trigger="hover" data-title="Definition" data-content="<?php echo $subject['definition']; ?>"><?php echo $subject['name']; ?></a></label></td>
                      </tr>
                  <?php endforeach; ?>
                </table>
              </div>
              <div class = "clearfix"></div>
            </div>
            </div>
            <div class="modal-footer">
              <button type="button" id = "connectClose" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </div>

        </div>
      </div>

      </div>


</body>

<style>
	.submit-reply {
        float: right;
    }

    .reply-box {
        margin-bottom: 10px;
        width: 100%;
        height: 80px;
    }

    .reply-container {
        width: 50%;
        margin-bottom: 30px;
    }

    .comment-textarea {
        width: 100%;
        height: 80px;
        margin-bottom: 10px;
    }

    .submit-comment-btn {
        float: right;
    }

    .comments-list {
        list-style: none;
        padding-left: 0;
    }

    .reply-comment {
    	margin-bottom: 15px;
    }
</style>
            </div>
        </div>
    </div>
    <!-- /.container -->

    <!-- jQuery Version 1.11.1 -->

    <!-- Bootstrap Core JavaScript -->
    <script>

    $("#connectClose").click(function(){
      window.location = '<?php echo getFullInciteUrl().'/documents/connect/'.$this->doc_id; ?>';
    });
      var rating_list = <?php echo json_encode($subject_from_gold_standard).";\n" ?>
      var ex_list = <?php echo json_encode($explain_list).";\n" ?>
        $(document).ready(function() {
            <?php
                if (isset($_SESSION['incite']['message'])) {
                    echo "notifyOfSuccessfulActionNoTimeout('" . $_SESSION["incite"]["message"] . "');";
                    unset($_SESSION['incite']['message']);
                }
            ?>

            addButtonAndCheckboxListeners();

            <?php if ($this->is_being_edited): ?>
                styleForEditing();
            <?php endif; ?>


        });

        function addButtonAndCheckboxListeners() {
            $("#submit-selection-btn").click(function() {

                //from progress_indicator.php
                //styleProgressIndicatorForCompletion();
                var completed = true;
                for (var i = 1; i < $( "form input:radio" ).length/5 + 1; i++) {
                  var first = "[name=subject";
                  var second = first.concat(i);
                  var final = second.concat("]:checked");
                  if ($($(final)).length == 0) {
                    completed = false;
                  }
                }

                if(completed == false) {
                  alert("There is at least one theme which has not been rated!");
                }

                if (completed == true) {
                  var len = Object.keys(rating_list).length;
                  for(var i = 1; i < len + 1; i++) {
                    $($("#goldTheme tr")[i]).append("<td>" + theme_ratings[rating_list[i]-1] + "</td>");
                  }
                  retrieveRating();
                  updateRatingsAjaxRequest();
                  $('#myModal').modal({backdrop: 'static', keyboard: false, show: true});
                  $('#submit-selection-btn').prop("disabled", "true");
                  $("[data-toggle=popover]").popover();
               }
                //$("#subject-form").submit();
            });

            $(".subject-checkbox").on('click', function(e) {
                $(".none-checkbox").prop('checked', false);
            });

            $(".none-checkbox").on('click', function(e) {
                $(".subject-checkbox").each(function(index, checkbox) {
                    $(this).prop('checked', false);
                });
            });
        }

        function retrieveRating() {
          var i = 0;
          var checkmark = '<img src="<?php echo getFullOmekaUrl(); ?>plugins/Incite/views/shared/images/checkMark.png" height = "20" width = "20" >'+ '&nbsp;&nbsp;&nbsp;';
          var crossmark = '<img src="<?php echo getFullOmekaUrl(); ?>plugins/Incite/views/shared/images/wrong.png" height = "20" width = "20" >' + '&nbsp;&nbsp;&nbsp;';
          $("#en-table tbody tr").each(function() {
            var title = $(this).find('a').html();
            var value = $(this).find('[type=radio]:checked').val();
            upload_rating.push({"concept_id": concept_to_id[title], "rank": value});
            if (theme_ratings[value] == theme_ratings[rating_list[i + 1]-1]) {
              $($("#userTheme tr")[i + 1]).append("<td>Your answer:<br /><ul>" + checkmark + theme_ratings[value] + "  (Your answer matched with historians' answers below)</ul>Historians' answer: " + "<ol style='margin-left: 45px;'><li>" + checkmark + "<insert>" + theme_ratings[rating_list[i + 1]-1] + "</insert>: "+ ex_list[i + 1] + "</li></ol></td>");
              }

            else {
              $($("#userTheme tr")[i + 1]).append("<td>Your answer:<br /><ul>" + crossmark + "<wrong>" + theme_ratings[value] + "</wrong>  (Your answer did not match with historians' answers below)</ul>" + "Historians' answer: " + "<br /><ol style='margin-left: 45px;'><li><insert>" + theme_ratings[rating_list[i + 1]-1] + "</insert>: "+ ex_list[i + 1] + "</li></ol></td>");
              }
            i++;
          });
        }


        function styleForEditing() {
            checkPositiveSubjects();
            addRevisionHistoryListeners();
        }

        function checkPositiveSubjects() {
            var hasNoPositiveSubjects = true;

            <?php foreach ((array)$this->newest_n_subjects as $subject): ?>
                <?php if ($subject['is_positive']): ?>
                    hasNoPositiveSubjects = false;

                    $(".subject-checkbox").each(function() {
                        if ($(this).val() === String(<?php echo $subject['subject_id']; ?>)) {
                            $(this).prop('checked', true);
                        }
                    });
                <?php endif; ?>
            <?php endforeach; ?>

            if (hasNoPositiveSubjects) {
                $(".none-checkbox").prop('checked', true);
            }
        }

        function addRevisionHistoryListeners() {
            $('#view-revision-history-link').show();

            $('#view-revision-history-link').click(function(e) {
                $('#connecting-container').hide();
                $('#revision-history-container').show();
            });

            $('#view-editing-link').click(function(e) {
                $('#revision-history-container').hide();
                $('#connecting-container').show();
            });
        }

    </script>

    <style>
        .discussion-seperation-line {
            margin-top: 60px;
        }

        #view-revision-history-link {
            position: absolute;
            right: 0;
            cursor: pointer;
        }

        #connecting-work-area {
            margin-top: -32px;
            position: relative;
            float: right;
        }
/*
        #rightPart {
          width: 50%;
          padding 0 10px;
          float:left;
          padding-right: 10px;
        }
        */
        #userPart {
          width: 100%;
          /*border-left: 1px solid #ccc;*/
          float:right;
          padding 0 10px;
          padding-left: 10px;
        }

        table .theme{
        font-family: arial, sans-serif;
        border-collapse: collapse;
        width: 100%;
        }

        .theme td, .theme th {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;
        }

        .theme tr:nth-child(odd) {
        background-color: #dddddd;
      }

        .tooltip {
            position: fixed;
        }


        .modal.modal-wide .modal-dialog {
          width: 80%;
        }

        wrong {

          background: #FAB5C2;
          text-decoration: none;
        }

        insert {

          background: #A8E6CF;
          text-decoration: none;
        }

        .wrong {

          background: #FAB5C2;
          text-decoration: none;
        }

        .insert {

          background: #A8E6CF;
          text-decoration: none;
        }


    </style>
</body>
</html>
