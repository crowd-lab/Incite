<!DOCTYPE html>
<html lang="en">
    <head>
        <?php
            $_SESSION['Incite']['tutorial_tag'] = true;
            $_SESSION['Incite']['assessment_tag'] = true;
            include(dirname(__FILE__) . '/../common/header.php');
            include(dirname(__FILE__) . '/../common/progress_indicator.php');

            $category_object = getAllCategories();
            $category_id_name_table = getSubcategoryIdAndNames();
            $sub_dic = SubcatDic();
            $sub_dic[-1] = "empty";
            $category_name_id_table = getCategoryNameAndId();
            $tag_list = findAllTagsFromGoldStandard(731);
            $ans_list = findAllAnswersFromGoldStandard(findTaggedTransIDFromGoldStandard(731));
            $sub_list = findAllSubs(731);
        ?>

        <script type="text/javascript">
            var msgbox;
            var comment_type = 1;
            var tags_list = {};
            var copy_dic = tags_list;
            var entities_array = [];
            var question_array;
        </script>
    </head>

    <body> <!-- Page Content -->
        <?php
            include(dirname(__FILE__) . '/../common/task_header.php');
        ?>

        <div class="container-fluid">
            <div class="col-md-5" id="work-zone">
<head>
	<script type="text/javascript">

  function updateTagsAjaxRequest() {
    var request = $.ajax({
      type: "POST",
      url: "<?php echo getFullInciteUrl().'/ajax/uploadtags'; ?>",
      data: {'entities': entities_array, 'tagged_doc': $('#transcribe_copy').html(), 'questions': question_array},
      success: function (response) {
        alert(response);
      }
    });
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
/*
		$('#work-zone').ready(function() {
		    $('#work-view').width($('#work-zone').width());
		});
*/
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

		    //setLegendWidth();

		    $('.viewer').height($(window).height()-$('#transcribe_copy')[0].getBoundingClientRect().top-10-$(".navbar-fixed-bottom").height());

	        $('#transcribe_copy').height($(window).height()-$('#transcribe_copy')[0].getBoundingClientRect().top-10-$(".navbar-fixed-bottom").height());

	        $("#document_img").iviewer({
	            src: "<?php echo getFullOmekaUrl(); ?>plugins/Incite/views/shared/images/assess1.png",
	            zoom_min: 1,
	            zoom: "fit"
        	});

            buildPopoverContent();
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
<div id="work-view" >
        <div class="document-header" id = "header" style = "position: relative">
            <span class="document-title" title="Incite Tutorial - Tag">
                <b>Title:</b> The Fourth of July at Shreveport - Tag
            </span>

            <span id="document-info-glphicon" class="glyphicon glyphicon-info-sign"
                data-toggle="popover" data-html="true" data-trigger="hover"
                data-viewport=".document-header" aria-hidden="true"
                data-title="<strong>Document Information</strong>"
                data-placement="bottom" data-id="">
            </span>

        </div>

        <div id="tabs-and-legend-container" >
            <ul class="nav nav-tabs document-display-type-tabs">
                <li role="presentation" class="active" id="hide"><a href="#">Transcription</a></li>
                <li role="presentation" id="show"><a href="#">Document</a></li>
            </ul>

            <div id="legend-container" >
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
        width: 40%;
    }

	.document-header {
    width: 100%;
    margin-left: 20px;
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
        width: 60%;
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

    .tour-backdrop,
.tour-step-background {
    z-index: 3;

}
</style>
            </div>

            <div class="col-md-7">
                <div id="tagging-container">
                    <p class="header-step"><i>Step 1 of 3: Verify and expand existing tags</i></p>
                    <a id="view-revision-history-link" style="display: none;">View Revision History...  </a>
                    <table class="table" id="entity-table">
                        <tr>
                            <th>
                                Tag
                                <span class="glyphicon glyphicon-info-sign step-instruction-glyphicon"
                                    aria-hidden="true" data-trigger="hover"
                                    data-toggle="popover" data-html="true"
                                    data-viewport="#tagging-container";
                                    data-title="<strong>Creating a tag</strong>"

                                    <?php if ($this->is_being_edited): ?>
                                        data-content="<?php echo "Tags in this upper table were generated or confirmed accurate by some other user. If no tags are present, then whoever completed tagging this document felt no tags were necessary. You can always delete the tags present here via the trash can icon. Add more tags by highlighting them in the transcription to the left." ?>"
                                    <?php else: ?>
                                        data-content="<?php echo "Tags in this upper table are computer generated. If no tags are present, then the computer did not find anything it could tag accurately." ?>"
                                    <?php endif; ?>
                                    data-placement="bottom">
                                </span>
                            </th>
                            <th>
                                Category
                                <span class="glyphicon glyphicon-info-sign step-instruction-glyphicon"
                                    aria-hidden="true" data-trigger="hover"
                                    data-toggle="popover" data-html="true"
                                    data-viewport="#tagging-container";

                                    data-title="<strong>Selecting a category</strong>"
                                    <?php if ($this->is_being_edited): ?>
                                        data-content="<?php echo "Please confirm that the categories for these tags are accurate, feel free to change them as you see fit." ?>"
                                    <?php else: ?>
                                        data-content="<?php echo "The computer has tried to identify the category of this tag, please ensure it is accurate and change it if needed." ?>"
                                    <?php endif; ?>
                                    data-placement="bottom">
                                </span>
                            </th>
                            <th>
                                Subcategory
                                <span class="glyphicon glyphicon-info-sign step-instruction-glyphicon"
                                    aria-hidden="true" data-trigger="hover"
                                    data-toggle="popover" data-html="true"
                                    data-viewport="#tagging-container";

                                    data-title="<strong>Selecting a subcategory</strong>"
                                    <?php if ($this->is_being_edited): ?>
                                        data-content="<?php echo "Please confirm that all tags have an appropriate subcategory, feel free to change the subcategory as you see fit." ?>"
                                    <?php else: ?>
                                        data-content="<?php echo "The computer has tried to identify the subcategories for these tags, please ensure they are accurate and change them if needed." ?>"
                                    <?php endif; ?>
                                    data-placement="bottom">
                                </span>
                            </th>
                            <th>
                                Details
                                <span class="glyphicon glyphicon-info-sign step-instruction-glyphicon"
                                    aria-hidden="true" data-trigger="hover"
                                    data-toggle="popover" data-html="true"
                                    data-viewport="#tagging-container";

                                    data-title="<strong>Adding details</strong>"
                                    <?php if ($this->is_being_edited): ?>
                                        data-content="<?php echo "Please confirm the additional details of each tag, feel free to edit them as you see fit." ?>"
                                    <?php else: ?>
                                        data-content="<?php echo "Add any details you feel are appropriate for the tag. You need not repeat information that can be gained from the tag name, category or selected subcategories." ?>"
                                    <?php endif; ?>
                                    data-placement="bottom">
                                </span>
                            </th>
                            <th>Not a tag?</th></tr>
                    </table>
                    <br>
                    <p class="step"><i>Step 2 of 3: Add missing tags by highlighting words in the transcription on the left. You may skip this step if you do not see any missing tags</i></p>
                    <table class="table" id="user-entity-table">
                        <tr>
                            <th>
                                Tag
                                <span class="glyphicon glyphicon-info-sign step-instruction-glyphicon"
                                    aria-hidden="true" data-trigger="hover"
                                    data-toggle="popover" data-html="true"
                                    data-title="<strong>Creating a tag</strong>"

                                    data-viewport="#tagging-container";
                                    data-content="<?php echo "Add any tags that aren't present in the upper table by highlighting the word(s) you want to tag in the transcription to the left." ?>"
                                    data-placement="bottom">
                                </span>
                            </th>
                            <th>
                                Category
                                <span class="glyphicon glyphicon-info-sign step-instruction-glyphicon"
                                    aria-hidden="true" data-trigger="hover"
                                    data-toggle="popover" data-html="true"
                                    data-viewport="#tagging-container";
                                    data-title="<strong>Selecting a category</strong>"
                                    data-content="<?php echo "For the given tag, select the category which it falls into most easily. A tag must have a category other than empty to be accepted." ?>"
                                    data-placement="bottom">
                                </span>
                            </th>
                            <th>
                                Subcategory
                                <span class="glyphicon glyphicon-info-sign step-instruction-glyphicon"
                                    aria-hidden="true" data-trigger="hover"
                                    data-toggle="popover" data-html="true"
                                    data-viewport="#tagging-container";
                                    data-title="<strong>Selecting a subcategory</strong>"
                                    data-content="<?php echo "For the given tag and category, select the appropriate subcategories, if any. If it doesn't fall into any subcategories simply leave none selected." ?>"
                                    data-placement="bottom">
                                </span>
                            </th>
                            <th>
                                Details
                                <span class="glyphicon glyphicon-info-sign step-instruction-glyphicon"
                                    aria-hidden="true" data-trigger="hover"
                                    data-toggle="popover" data-html="true"
                                    data-viewport="#tagging-container";
                                    data-title="<strong>Adding details</strong>"
                                    data-content="<?php echo "Add any details you feel are appropriate for the tag. You need not repeat information that can be gained from the tag name, category or selected subcategories." ?>"
                                    data-placement="bottom">
                                </span>
                            </th>
                            <th>Not a tag?</th></tr>
                        <tr>
                    </table>
                    <p class="step"><i>Step 3 of 3: Based on the document on the left and its metadata, please answer the following questions.</i></p>
                    <table class="table" id = "choice-table">
                        <tr><th>Questions</th><th>Answers</th></tr>
                        <tr><td>When was this document produced?</td><td><input type="text" id = "date-detail" placeholder="YYYY-MM-DD"></td></tr>
                        <tr><td>Where was this document produced?</td><td><input type="text" id = "place-detail" placeholder="location"></td></tr>
                        <tr><td>Based on your reading, what location does this document tell you most about?</td><td><input type="text" id = "location-detail" placeholder="City, State, or region"></td></tr>
                        <tr><td style="vertical-align: middle;">Based your reading of the document, what period does this document tell you most about? (contextualize)</td>
                            <td>
                                <select id = "period-selector" class="form-control">
                                    <option>What period?</option>
                                    <option>Pre Civil war (year range, - 1861)</option>
                                    <option>Civil war (year range, 1861 - 1865)</option>
                                    <option>Post Civil war (year range, 1865 - )</option>
                                    <option>Unclear</option>
                                </select>
                            </td>
                        </tr>
                        <tr><td style="vertical-align: middle;">From whose perspectives (or say view points) was this document produced?</td>
                            <td>
                                <select id = "race-selector" class="form-control">
                                    <option>What socail group?</option>
                                    <option>White</option>
                                    <option>African American</option>
                                    <option>Foreigner</option>
                                    <option>Abolitionist</option>
                                    <option>Not specified</option>
                                </select>
                                <select id = "gender-selector" class="form-control">
                                    <option>What gender?</option>
                                    <option>Male</option>
                                    <option>Female</option>
                                    <option>Not specified</option>
                                </select>
                                <select id = "occupation-selector" class="form-control">
                                    <option>What occupation?</option>
                                    <option>Soldier</option>
                                    <option>Civilian</option>
                                    <option>Not specified</option>
                                </select>
                            </td>
                        </tr>
                    </table>
                    <br>
                    <button type="submit" class="btn btn-primary pull-right" id="confirm-button">Submit</button>
                    <form id="entity-form" method="post">
                        <input id="entity-info" type="hidden" name="entities" />
                        <input id="tagged-doc" type="hidden" name="tagged_doc" />
                        <input id="trans-id" type="hidden" name="transcription_id" value="<?php echo $this->transcription_id; ?>" />
                        <input type="hidden" name="query_str" value="<?php echo (isset($this->query_str) ? $this->query_str : ""); ?>">
                    </form>

                </div>
                <hr size=2 class="discussion-seperation-line">

                <?php
                    include(dirname(__FILE__) . '/../common/revision_history_for_task_id_pages.php');
                ?>


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

    <div class="container">
      <!-- Trigger the modal with a button -->

      <!-- Modal -->
      <div class="modal modal-wide fade" id="myModal" role="dialog">
        <div class="modal-dialog">

          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Results</h4>
            </div>
            <div class="modal-body">
              <b>Color Meaning: <span class="wrong" style="display:inline-block;width:16px">&nbsp;</span>=wrong, <span class="insert" style="display:inline-block;width:16px">&nbsp;</span>=inserted correct answers</b>
              <br>
              <br>
              <ul class="nav nav-tabs nav-justified nav-pills">
                  <li class="active" ><a >Tags</a></li>
              </ul>
                  <div class="tab-pane active" id="tags">
                    <br>
                    <div id = "userTags">

                      <table class = "rightTable" id = "urtable">
                        <tr>
                          <th>Tag</th>
                          <th>Category</th>
                          <th>Subcategory</th>
                        </tr>
                      </table>
                    </div>
                    <div class = "clearfix"></div>
                    <br>
                  </div>
                  <ul class="nav nav-justified nav-pills">
                      <li class="active"><a > Questions</a></li>
                  </ul>
                  <div class="tab-pane" id="questions">
                    <br>

                    <div = id = "right_questions">
                      <table class = "rightTable" id = "urquestions">
                        <tr>
                          <th>Question</th>
                          <th>Answer</th>
                        </tr>
                        <tr>
                          <td>Date</td>
                        </tr>
                        <tr>
                          <td>Location</td>
                        </tr>
                        <tr>
                          <td>point-out Location</td>
                        </tr>
                        <tr>
                          <td>Period</td>
                        </tr>
                        <tr>
                          <td>Race</td>
                        </tr>
                        <tr>
                          <td>Gender</td>
                        </tr>
                        <tr>
                          <td>Occupation</td>
                        </tr>
                      </table>
                    </div>
                    <div class = "clearfix"></div>
                  </div>
                  <br>
            </div>
            <div class="modal-footer">
              <button type="button" id = "tagClose"class="btn btn-default" data-dismiss="modal">Close</button>
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
    <!-- End work container -->

<script type="text/javascript">

    $("#tagClose").click(function(){
      window.location = '<?php echo getFullInciteUrl().'/documents/tag/'.$this->doc_id; ?>';
    });
    //Global variable to store categories/counters
    var categories = <?php echo json_encode($category_object).";\n"; ?>
    // alert(categories[2]['subcategory'].length);
    var category_id_to_name_table = <?php echo json_encode($category_id_name_table).";\n"; ?>
    var category_name_to_id_table = <?php echo json_encode($category_name_id_table).";\n" ?>
    var correct_tag_list = <?php echo json_encode($tag_list).";\n" ?>
    var answer_list = <?php echo json_encode($ans_list).";\n" ?>
    var tagid_id_counter = <?php echo (isset($this->tag_id_counter) ? $this->tag_id_counter : "0"); ?>;
    var subcat_list = <?php echo json_encode($sub_list).";\n" ?>
    var subcat_dic = <?php echo json_encode($sub_dic).";\n" ?>

    function set_tag_id_counter() {
        var max_id = 0;
        $('#entity-table tr').each( function (idx) {
            if (parseInt(this.dataset.tagid) > max_id) {
                max_id = parseInt(this.dataset.tagid);
            }
        });
        max_id++;
        if (max_id > tagid_id_counter)
            tagid_id_counter = max_id;
    }

    function addUserTag(text, span_id) {
        var new_entity = $('<tr id="tag_id_'+span_id+'_table" data-tagid="'+span_id+'"><td><span class="entity-name">'+text+'</span></td><td><select class="category-select" id="categorySelect"></select></td><td><select class="subcategory-select" multiple="multiple"></select></td><td><input class="form-control entity-details" id="detail" type="text" value=""></td><td><button type="button" class="btn btn-default remove-entity-button" aria-label="Left Align" id="addTrashButton"><span class="glyphicon glyphicon-trash" aria-hidden="true" id="addTrashButton"></span></button></td></tr>');

        new_entity.find('.subcategory-select').multiselect({
            enableFiltering: true,
            filterBehavior: 'text',
            checkboxName: 'multiselect[]',
            enableCaseInsensitiveFiltering: true,
            disableIfEmpty: true,
            numberDisplayed: 1
        });
        new_entity.find('.category-select').append('<option value="0">&nbsp;</option>');

        <?php foreach ($category_object as $id => $content) {
            echo "new_entity.find('.category-select').append(\"<option value='".$id."'>".$content["name"]."</option>\");";
        }?>

        new_entity.find('.category-select').multiselect({
            disableIfEmpty: true
        });

        $('.category-select').multiselect('rebuild');
        $('#user-entity-table').append(new_entity);
        new_entity.closest('tr').find('.subcategory-select').multiselect('rebuild');
    }

    function addExistingTags() {
        $('#transcribe_copy em').each(function (idx) {
            tagid_id_counter++;
            var new_entity = $('<tr id="'+this.id+'_table" data-tagid="'+(""+this.id).replace("tag_id_", "")+'"><td><span class="entity-name">'+$(this).text()+'</span></td><td><select class="category-select '+this.className+'"></select></td><td><select class="subcategory-select" multiple="multiple"></select></td><td><input class="form-control entity-details" type="text" value=""></td><td><button type="button" class="btn btn-default remove-entity-button" aria-label="Left Align" id="trashButton"><span class="glyphicon glyphicon-trash" aria-hidden="true" id="trashButton"></span></button></td></tr>');
            new_entity.find('.subcategory-select').multiselect({
                enableFiltering: true,
                filterBehavior: 'text',
                checkboxName: 'multiselect[]',
                enableCaseInsensitiveFiltering: true,
                disableIfEmpty: true,
                numberDisplayed: 1
            });

            <?php foreach ($category_object as $id => $content) {
                echo "new_entity.find('.category-select').append(\"<option value='".$id."'>".$content["name"]."</option>\");";
                //echo "new_entity.find('.category-select').append(\"<option value='".$category_object[$i]["id"]."'>".$category_object[$i]["name"]."</option>\");";
            }
            ?>
            new_entity.find('.category-select').multiselect({
                disableIfEmpty: true
            });
            $('.category-select').multiselect('rebuild');

            $('#entity-table').append(new_entity);
            new_entity.closest('tr').find('.subcategory-select').multiselect('rebuild');
            //Select previsouly selected category
            $.each(categories, function (idx) {
                if (new_entity.find('.category-select').hasClass(this['name'].toLowerCase())) {
                    new_entity.find('.category-select option[value='+idx+']').attr('selected', 'selected');
                }
            });
            new_entity.find('.category-select').multiselect('rebuild');
            var subcategory_menu = new_entity.find('.subcategory-select');
            $(subcategory_menu).empty();
            if (new_entity.find('.category-select').val() != 0) {
                $.each(categories[new_entity.find('.category-select').val()]['subcategory'], function (idx) {
                    subcategory_menu.append('<option value="'+this['subcategory_id']+'">'+this["subcategory"]+'</option>').multiselect('rebuild');
                });
            } else {
                subcategory_menu.multiselect('rebuild');
            }

            if (typeof this.dataset.subs !== typeof undefined && this.dataset.subs !== false && this.dataset.subs !== "") {
                var selected_subs = this.dataset.subs.split(',');
                for (i = 0; i < selected_subs.length; i++) {
                    $('#'+this.id+'_table .subcategory-select option[value='+selected_subs[i]+']').attr('selected', 'selected');
                }
                    $('#'+this.id+'_table .subcategory-select').multiselect('rebuild');
            }
            if (this.dataset.details !== "") {
                $('#'+this.id+'_table .entity-details').val(this.dataset.details);
            }

        });
    }


    $(document).ready(function () {
        addExistingTags();
        migrateTaggedDocumentsFromV1toV2();
        set_tag_id_counter();

        <?php if ($this->is_being_edited): ?>
            styleForEditing();
        <?php endif; ?>

        $('#user-entity-table').on('click', '.remove-entity-button', function (e) {
            $(this).parent().parent().remove();
        });

        $('#user-entity-table').on('change', '.category-select', function (e) {
            var subcategory_menu = $(this).closest('tr').find('.subcategory-select');
            subcategory_menu.find('option').remove().end();
            if ($(this).val() != 0 && categories[$(this).val()]['subcategory'].length > 0) {
                $.each(categories[$(this).val()]['subcategory'], function (idx) {
                    subcategory_menu.append('<option value="'+this['subcategory_id']+'">'+this["subcategory"]+'</option>').multiselect('rebuild');
                });
            } else {
                subcategory_menu.multiselect('rebuild');
            }
            $('#tag_id_'+$(this).parent().parent().attr('data-tagid')).removeClass();
            var selected_category = $(this).find('option[value='+$(this).val()+']').text().toLowerCase();
            if (selected_category !== '\xa0') {
                $('#tag_id_'+$(this).parent().parent().attr('data-tagid')).addClass( selected_category + " tagged-text");
            } else {
                $('#tag_id_'+$(this).parent().parent().attr('data-tagid')).addClass("unknown tagged-text");
            }
        });

        $('#user-entity-table').on('click', '.remove-entity-button', function (e) {
            $('#tag_id_'+$(this).parent().parent().attr('data-tagid')).contents().unwrap();
            $(this).parent().parent().remove();
        });

        $('#entity-table').on('click', '.remove-entity-button', function (e) {
            $('#tag_id_'+$(this).parent().parent().attr('data-tagid')).contents().unwrap();
            $(this).parent().parent().remove();
        });

        $('#entity-table').on('change', '.category-select', function (e) {
            var subcategory_menu = $(this).closest('tr').find('.subcategory-select');
            subcategory_menu.find('option').remove().end();
            if ($(this).find('option:selected').text() !== "" && categories[$(this).val()]['subcategory'].length > 0) {
                $.each(categories[$(this).val()]['subcategory'], function (idx) {
                    subcategory_menu.append('<option value="'+this['subcategory_id']+'">'+this["subcategory"]+'</option>').multiselect('rebuild');
                });
            } else {
                subcategory_menu.multiselect('rebuild');
            }
            $('#tag_id_'+$(this).parent().parent().attr('data-tagid')).removeClass();
            var selected_category = $(this).find('option[value='+$(this).val()+']').text().toLowerCase();
            $('#tag_id_'+$(this).parent().parent().attr('data-tagid')).addClass( selected_category + " tagged-text");
        });

        $('#confirm-button').on('click', function (e) {
            if ($('.category-select option:selected[value=0]').length > 0) {
                notifyOfErrorInForm('Tag category cannot be empty at Step 2 of 2.');
                return;
            }

            $('#myModal').modal({backdrop: 'static', keyboard: false, show: true});

            var len = Object.keys(correct_tag_list).length;
            for(var i = 0; i < len; i++) {
              var key = Object.keys(correct_tag_list)[i];
              var index = correct_tag_list[key];
              //$("#rightTags tbody").append("<tr><td>" + key + "</td><td>" + category_id_to_name_table[index] + "</td><td></td><td></td></tr>");
              tags_list[key] = index;
            }

            fillQuestions();
            updateTagsAjaxRequest();
            $('#confirm-button').prop("disabled", "true");
        });

        $('.subcategory-select').each(function (idx) {
            $(this).multiselect({
                enableFiltering: true,
                filterBehavior: 'text',
                checkboxName: 'multiselect[]',
                enableCaseInsensitiveFiltering: true,
                disableIfEmpty: true,
                numberDisplayed: 1
            });
        });

        $('.category-select').each(function (idx) {
            $(this).multiselect({
                disableIfEmpty: true
            });
        });

        //Bug fix - can't tag anything outside the transcription
        $('#transcribe_copy').on('mousedown', function (e) {
            document.getSelection().removeAllRanges();
        });

        //Add entities by selection
        $('#transcribe_copy').on('mouseup', function (e) {
            var parentOffset = $(this).offset();
            var relX = e.pageX - parentOffset.left;
            var relY = e.pageY - parentOffset.top;

            //Bug fix - make sure the selection is still within the transcription on mouseup
            //so you can't tag anything outside the transcription. Can't just check for hover here
            //due to selection weirdness
            if (relX >= 0 && relY >= 0) {
                var tag_selection = document.getSelection();
                var tag_text = tag_selection.toString();
                var needs_extra_whitespace = tag_text.charAt(tag_text.length - 1) === ' ';
                tag_text = tag_text.trim();

                if (tag_selection.rangeCount && tag_text !== "") {
                    if (tag_text.length > 30) {
                        alert ('The length of a tag should be shorter than 30 characters');
                        return;
                    }
                    var tag_range = tag_selection.getRangeAt(0);
                    var tag_em = document.createElement('em');
                    tag_em.id = 'tag_id_'+tagid_id_counter;
                    tag_em.className = 'unknown tagged-text';
                    tag_em.appendChild(document.createTextNode(tag_text));
                    tag_range.deleteContents();

                    //If the user selects a tag with whitespace it will be trimmed, reinsert a space outside the tag
                    if (needs_extra_whitespace) {
                        tag_range.insertNode(document.createElement('p').appendChild(document.createTextNode("\u00A0")));
                    }
                    tag_range.insertNode(tag_em);
                    addUserTag(tag_text, tagid_id_counter++);
                    tag_selection.removeAllRanges();
                }
            }
        });

        $('#transcribe_copy').on('mouseenter', 'em', function (e) {
            $('#'+this.id+'_table').toggleClass(this.className.split(" ")[0]);
            var view_baseline = $('#work-view').position().top;
            var vis_top = $('#work-view').offset().top;
            var vis_bottom = vis_top+$('#work-view').height();
            var tag_top = $("#"+this.id+'_table').offset().top;
            var tag_bottom = tag_top+$("#"+this.id+'_table').height();

            if (tag_top < vis_top) {
                $('html, body').animate({
                    scrollTop: $('#'+this.id+'_table').offset().top-view_baseline
                }, 500);
            } else if (tag_bottom > vis_bottom) {
                $('html, body').animate({
                    scrollTop: $('#'+this.id+'_table').offset().top-view_baseline-(vis_bottom-vis_top)+(tag_bottom-tag_top)
                }, 500);
            } else {
                //element is already in the visible area
            }

        });

        $('#transcribe_copy').on('mouseleave', 'em', function (e) {
            $('#'+this.id+'_table').toggleClass(this.className.split(" ")[0]);
        });

        <?php
            if (isset($_SESSION['incite']['message'])) {
                echo "notifyOfSuccessfulActionNoTimeout('" . $_SESSION["incite"]["message"] . "');";
                unset($_SESSION['incite']['message']);
            }
        ?>
    });

    function styleForEditing() {
        addRevisionHistoryListeners();
    }

    function fillQuestions() {
      var date = $('#date-detail').val();
      var location = $('#place-detail').val();
      var pointed_location = $('#location-detail').val();
      var period = $('#period-selector').val();
      var race = $('#race-selector').val();
      var gender = $('#gender-selector').val();
      var occupation = $('#occupation-selector').val();
      var selec = $("#urquestions tr");
      question_array = {'1': date, '2': location, '3': pointed_location, '4': period, '5':race, '6': gender, '7': occupation};
      var question_len = Object.keys(question_array).length;
      for (var i = 1; i < question_len + 1; i++) {
        if (question_array[i] == answer_list[i])
          $($(selec)[i]).append('<td>' + date + '</td>');
        else if (question_array[i] == '')
          $($(selec)[i]).append('<td>' + '<wrong>' + question_array[i] + ' </wrong>' + '<insert>' + answer_list[i] + '</insert>' + '</td>');
        else
          $($(selec)[i]).append('<td>' + '<wrong>' + question_array[i] + ' </wrong>' + "&nbsp&nbsp&nbsp" + '<insert>' + answer_list[i] + '</insert>' + '</td>');

      }

      $("#transcribe_copy .tagged-text").each(function(){
        var subcategories_array = [];
        var tagName = this.innerHTML;
        var class_name = this.className;
        var id_name = this.id;
        var id_index = id_name.slice(7);
        var table_id = "#" + id_name + "_table td";
        var sub1 = $(table_id)[2];
        var sub2 = $(sub1).find("li.active");
        var sub3 = $(table_id)[3];
        var detail = $($(sub3).find("input")).val();
        /*
        sub2.each(function() {
          var value = $($(this).find("input")).val();
          if (value == "") {
            value = -1;
          }
          console.log(value);
          //subcategories_array.push(value);
        });
        */
        var select = $(sub1).find("option:selected");
        var value;
        if ($(select).length == 0) {
          value = -1;
          subcategories_array.push(value);
        }
        else {
          select.each(function(){
            value = $(this).val();
            subcategories_array.push(value);
          });
        }
        //$($($("#tag_id_0_table td")[2]).find('option:selected')[1]).val()
        var cat = class_name.split(" ")[0];
        var exist = processTag(tagName);
        var edited_category = cat.charAt(0).toUpperCase() + cat.slice(1);
        entities_array.push({"entity": tagName, "category": category_name_to_id_table[edited_category], "subcategory": subcategories_array, "details": detail});

        if (exist){
          if (edited_category == category_id_to_name_table[tags_list[tagName]]) {
            if (subcat_dic[value] == subcat_list[tagName])
              $("#urtable").append("<tr><td>"+ tagName +"</td><td>" + edited_category + "</td><td>" + subcat_dic[value] + "</td></tr>");
            else
              $("#urtable").append("<tr><td>"+ tagName +"</td><td>" + edited_category + "</td><td><wrong>" + subcat_dic[value] + "</wrong>&nbsp&nbsp&nbsp<insert>" + subcat_list[tagName] + "</insert></td></tr>");
          }
          else
            $("#urtable").append("<tr><td>"+ tagName +"</td><td><wrong>" + edited_category + "</wrong>&nbsp&nbsp&nbsp<insert>" + category_id_to_name_table[copy_dic[tagName]] + "</insert></td><td><wrong>" + subcat_list[key] + "</wrong>&nbsp&nbsp&nbsp<insert>" + subcat_list[tagName] + "</insert></td></tr>");
          delete copy_dic[tagName];
        }
        else
          $("#urtable").append("<tr><td>"+ "<wrong>" + tagName + "</wrong>" + "</td><td>" + "<wrong>" + edited_category + "</wrong>" + "</td><td><wrong>" + subcat_dic[value] + "</wrong></td></tr>");
      });

      if (copy_dic.length != 0) {
        for (var key in copy_dic) {
          $("#urtable").append("<tr><td>"+ "<insert>" + key + "</wrong>" + "</td><td>" + "<insert>" + category_id_to_name_table[copy_dic[key]] + "</insert>" + "</td><td><insert>" + subcat_list[key] + "</insert></td></tr>");
        }
      }

     }

     function processTag(tagName) {
       var tag = tags_list[tagName];
       if (tag == null)
          return false;
       else {
          return true;
        }
     }

    function addRevisionHistoryListeners() {
        $('#view-revision-history-link').show();

        $('#view-revision-history-link').click(function(e) {
            $('#tagging-container').hide();
            $('#revision-history-container').show();
        });

        $('#view-editing-link').click(function(e) {
            $('#revision-history-container').hide();
            $('#tagging-container').show();
        });
    }

</script>

<style>
    .discussion-seperation-line {
        margin-top: 100px;
    }

    #tagging-container {
        padding-right: 0px;
        margin-top: -32px;
    }

    .comments-section-container {
        padding-left: 15px;
    }


    #revision-history-container {
        padding-left: 1.5%;
    }

    #view-revision-history-link {
        position: absolute;
        right: 0;
        cursor: pointer;
        margin-top: -32px;
    }
    #userTags {
      /*width: 100%;*/
      padding 0 10px;
      padding-left: 10px;
    }

    #right_questions {
      /*width: 100%;*/
      padding 0 10px;
      padding-left: 10px;
    }

    table .rightTable{
    font-family: arial, sans-serif;
    border-collapse: collapse;
    /*width: 100%;*/
    }

    .rightTable td, .rightTable th {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
    }

    .rightTable tr:nth-child(odd) {
    background-color: #dddddd;
  }

  .modal.modal-wide .modal-dialog {
    width: 80%;
  }

  wrong {
    color: red;
    background: #fdd;
    text-decoration: none;
  }

  insert {
    color: green;
    background: #dfd;
    text-decoration: none;
  }

  .wrong {
    color: red;
    background: #fdd;
    text-decoration: none;
  }

  .insert {
    color: green;
    background: #dfd;
    text-decoration: none;
  }



</style>

</body>

</html>
