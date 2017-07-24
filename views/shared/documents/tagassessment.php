<!DOCTYPE html>
<html lang="en">
    <head>
        <?php
            $_SESSION['Incite']['tutorial_tag'] = true;
            $groupid = $this->groupid;
            $groupAssessStatus = "group".$groupid;
        		$_SESSION['Incite']['assessment_tag'][$groupAssessStatus] = true;
            include(dirname(__FILE__) . '/../common/header.php');
            include(dirname(__FILE__) . '/../common/progress_indicator.php');

            $category_object = getAllCategories();
            $category_id_name_table = getSubcategoryIdAndNames();
            $sub_dic = SubcatDic();
            $sub_dic[-1] = "empty";
            $category_name_id_table = getCategoryNameAndId();
            $tag_list = findAllTagsFromGoldStandard($this->document_metadata->id);
            $ans_list = findAllAnswersFromGoldStandard(findTaggedTransIDFromGoldStandard($this->document_metadata->id));
            $sub_list = findAllSubs($this->document_metadata->id);
            $answer_pack = answerPack($this->document_metadata->id);
        ?>

        <script type="text/javascript">
            var msgbox;
            var comment_type = 1;
            var tags_list = {};
            var copy_dic = tags_list;
            var entities_array = [];
            var question_array;

            function updateTagsAjaxRequest() {
              var request = $.ajax({
                type: "POST",
                url: "<?php echo getFullInciteUrl().'/ajax/uploadtags'; ?>",
                data: {'entities': entities_array, 'tagged_doc': $('#transcribe_copy').html(), 'questions': question_array},
                success: function (response) {

                }
              });
            }
        </script>
    </head>

    <body> <!-- Page Content -->
        <?php
            include(dirname(__FILE__) . '/../common/task_header.php');
        ?>

        <div class="container-fluid">
            <div class="col-md-5" id="work-zone">
              <?php
                  include(dirname(__FILE__) . '/../common/document_viewer_section_with_transcription.php');
              ?>

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
                                <select id = "social_selector" class="form-control">
                                    <option>What social group?</option>
                                    <option>White Americans</option>
                                    <option>African Americans</option>
                                    <option>Foreigners</option>
                                    <option>Abolitionists</option>
                                    <option>Not specified</option>
                                </select>
                                <select id = "gender-selector" class="form-control">
                                    <option>What gender?</option>
                                    <option>Male</option>
                                    <option>Female</option>
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
              <br>
              <ul class="nav nav-tabs nav-justified nav-pills">
                <li class="active" ><a >Tags</a></li>
              </ul>
                  <div class="tab-pane active" id="tags">
                    <br>
                    <b>Color Meaning: <span class="wrong" style="display:inline-block;width:16px">&nbsp;</span>=mismatching answers, <span class="insert" style="display:inline-block;width:16px">&nbsp;</span>=historians' supplemental answers, No color = matched answer</b>
                    <br>
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
                    <b>Color Meaning: <span class="wrong" style="display:inline-block;width:16px">&nbsp;</span>=mismatching answers, <span class="insert" style="display:inline-block;width:16px">&nbsp;</span>=historians' supplemental answers, No color = matched answer</b>
                    <br>
                    <br>
                    <div = id = "right_questions">
                      <table class = "rightTable" id = "urquestions">
                        <tr>
                          <th>Question</th>
                          <th>Answer</th>
                        </tr>
                        <tr>
                          <td>Date of Publication</td>
                        </tr>
                        <tr>
                          <td>Location of Publication</td>
                        </tr>
                        <tr>
                          <td>Location of Content</td>
                        </tr>
                        <tr>
                          <td>Period</td>
                        </tr>
                        <tr>
                          <td>Social Group</td>
                        </tr>
                        <tr>
                          <td>Gender</td>
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

<?php
    include(dirname(__FILE__) . '/../common/task_comments_section.php');
?>
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
    var all_answer = <?php echo json_encode($answer_pack).";\n" ?>

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
            $("[data-toggle=popover]").popover();
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
      if (date == "")
        date = "You did not answer this question"
      var location = $('#place-detail').val();
      if (location == "")
        location = "You did not answer this question"
      var pointed_location = $('#location-detail').val();
      if (pointed_location == "")
        pointed_location = "You did not answer this question"
      var period = $('#period-selector').val();
      if (period == "What period?")
        period = "You did not answer this question"
      var social = $('#social_selector').val();
      if (social == "What social group?")
        social = "You did not answer this question"
      var gender = $('#gender-selector').val();
      if (gender == "What gender?")
        gender = "You did not answer this question"
      var selec = $("#urquestions tr");
      question_array = {'1': date, '2': location, '3': pointed_location, '4': period, '5':social, '6': gender};
      var question_len = Object.keys(question_array).length;
      var checkmark = '<img src="<?php echo getFullOmekaUrl(); ?>plugins/Incite/views/shared/images/checkMark.png" height = "20" width = "20" >'+ '&nbsp;&nbsp;&nbsp;';
      var crossmark = '<img src="<?php echo getFullOmekaUrl(); ?>plugins/Incite/views/shared/images/wrong.png" height = "20" width = "20" >' + '&nbsp;&nbsp;&nbsp;';
      for (var i = 1; i < question_len + 1; i++) {
        var ansList = all_answer[i];
        var a = ansList["a"][question_array[i]];
        var t = ansList["c"]["true"];
        if (a == null) {
          var correct = "";
          var pop_over = "<ol style='margin-left: 45px;'>";
          for (var j = 0; j < t.length; j++) {
            correct = correct + t[j]["a"] + "  ";
            pop_over = pop_over + '<li><insert>' + t[j]["a"]+ "</insert>: " + t[j]["ex"] +'</li>';
          }
          pop_over += "</ol>";
            $($(selec)[i]).append('<td>' + '<p> Your answer: <br /><ul>' + crossmark + '<wrong>' + question_array[i] + "</wrong> (Your answer did not match with historians' answer below)</ul>"+ "Historians' answer:" +'<br />' + pop_over + '</td><p>');
        }
        else {
          if (a["t"] == "true") {
            var pop_over = "<ol style='margin-left: 45px;'>";
            for (var j = 0; j < t.length; j++) {
              if (question_array[i] == t[j]["a"])
                pop_over = pop_over + '<li>' + checkmark + '<insert>'+ t[j]["a"] + ": <ul>" +t[j]["ex"] +'</ul></insert></li>';
              else
                pop_over = pop_over + '<li>' + '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' +'<insert>' + t[j]["a"]+ ": <ul>" +t[j]["ex"] +'</ul></insert></li>';
            }
            pop_over += "</ol>";
            $($(selec)[i]).append('<td>Your answer: <br /><ul>' + checkmark + question_array[i] + " (Your answer matched with historians' answer below)</ul>"+ "Historians' answer:" + pop_over +'</td>');
          }
          else {
            var pop_over = "<ol style='margin-left: 45px;'>";
            for (var j = 0; j < t.length; j++) {
              pop_over = pop_over + '<li><insert>'+t[j]["a"]+ ": <ul>" +t[j]["ex"] +'</ul></insert></li>';
            }
            pop_over += "</ol>";
            var wrong_ans = question_array[i];
            $($(selec)[i]).append('<td>' + '<p>Your answer: <br /><ul>' + crossmark +'<wrong>' + wrong_ans + "</wrong> (Your answers did not match historians' answer) </ul>"+ "Historians' answer:"  + '<br/><insert>' + pop_over + '</insert>' + '</p></td>');
            correct = "";
          }
        }
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
        var select = $(sub1).find("option:selected");
        var value_array = [];
        var value;
        if ($(select).length == 0) {
          value = -1;
          subcategories_array.push(value);
          value_array.push(value);
        }
        else {
          select.each(function(){
            value = $(this).val();
            subcategories_array.push(value);
            value_array.push(value);
          });
        }
        var cat = class_name.split(" ")[0];
        var exist = processTag(tagName);
        var edited_category = cat.charAt(0).toUpperCase() + cat.slice(1);
        entities_array.push({"entity": tagName, "category": category_name_to_id_table[edited_category], "subcategory": subcategories_array, "details": detail});

        if (exist) {


          if (edited_category == category_id_to_name_table[tags_list[tagName]]) { //If the categories matched
            //subs = subs + subcat_dic[value_array[value_array.length - 1]];
            var correct_sub_array = subcat_list[tagName];
            //Create users input subs
            var subs = "";
            var user_input_sub_array = [];
            //convert sub ids in value_array to sub names in user_input_sub_array
            for (var i = 0; i < value_array.length; i++) {
              user_input_sub_array.push(subcat_dic[value_array[i]]);
            }

            for (var i = 0; i < value_array.length - 1; i++) {
              if (jQuery.inArray(subcat_dic[value_array[i]], correct_sub_array) != -1)
                subs = subs + subcat_dic[value_array[i]] + " ,";
              else {
                subs = subs + "<wrong>" + subcat_dic[value_array[i]] + "</wrong> ,";
              }
            }
            if (value_array.length > 0 && jQuery.inArray(subcat_dic[value_array[value_array.length - 1]], correct_sub_array) != -1)
              subs = subs + subcat_dic[value_array[value_array.length - 1]];
            else
              subs = subs + "<wrong>" + subcat_dic[value_array[i]] + "</wrong>";
            //Create correct subs
            var correct_subs = "";

            var correct_and_notinput_sub = []; //store the subs that correct but have not been chosen by users
            for (var i = 0; i < correct_sub_array.length; i++) {
              if (jQuery.inArray(correct_sub_array[i], user_input_sub_array) == -1)
                correct_and_notinput_sub.push(correct_sub_array[i]);
            }
            for (var i = 0; i < correct_and_notinput_sub.length - 1; i++) {
              correct_subs = correct_subs + "<insert>" + correct_and_notinput_sub[i] + "</insert> ,";
            }
            if (correct_and_notinput_sub.length > 0) {
              correct_subs = correct_subs + "<insert>" + correct_and_notinput_sub[correct_and_notinput_sub.length - 1] + "</insert>";
            }
            $("#urtable").append("<tr><td>"+ tagName +"</td><td>" + edited_category + "</td><td>" + subs + "&nbsp&nbsp&nbsp" + correct_subs + "</td></tr>");

          }
          else {//If categories don't match
          /*
            var correct_subs = "";
            var correct_sub_array = subcat_list[tagName];
            for (var i = 0; i < correct_sub_array.length - 1; i++) {
              correct_subs = correct_subs + "<insert>" + correct_sub_array[i] + "</insert> ,";
            }
            correct_subs = correct_subs + "<insert>" + correct_sub_array[correct_sub_array.length - 1]+ "</insert>";

            //user input subs
            var subs = "";
            for (var i = 0; i < value_array.length - 1; i++) {
              subs = subs + subcat_dic[value_array[i]] + " ,";
            }
            subs = subs + subcat_dic[value_array[value_array.length - 1]];

            if (value_array[0] == -1) {
              if (correct_subs == empty)
                $("#urtable").append("<tr><td>"+  tagName + "</td><td>" + "<wrong>" + edited_category + "</wrong>" + "</td><td><wrong>empty</wrong>&nbsp&nbsp&nbsp" + correct_subs + "</td></tr>");
              else
              $("#urtable").append("<tr><td>"+  tagName + "</td><td>" + "<wrong>" + edited_category + "</wrong>" + "</td><td><wrong>empty</wrong>&nbsp&nbsp&nbsp" + correct_subs + "</td></tr>");
            }
            else {


              $("#urtable").append("<tr><td>"+ tagName +"</td><td><wrong>" + edited_category + "</wrong>&nbsp&nbsp&nbsp<insert>" + category_id_to_name_table[copy_dic[tagName]] + "</insert></td><td><wrong>" + subs + "</wrong>&nbsp&nbsp&nbsp<insert>" + correct_subs + "</insert></td></tr>");
              }
              */
              var correct_sub_array = subcat_list[tagName];
              //Create users input subs
              var subs = "";
              var user_input_sub_array = [];
              //convert sub ids in value_array to sub names in user_input_sub_array
              for (var i = 0; i < value_array.length; i++) {
                user_input_sub_array.push(subcat_dic[value_array[i]]);
              }

              for (var i = 0; i < value_array.length - 1; i++) {
                if (jQuery.inArray(subcat_dic[value_array[i]], correct_sub_array) != -1)
                  subs = subs + subcat_dic[value_array[i]] + " ,";
                else {
                  subs = subs + "<wrong>" + subcat_dic[value_array[i]] + "</wrong> ,";
                }
              }
              if (value_array.length > 0 && jQuery.inArray(subcat_dic[value_array[value_array.length - 1]], correct_sub_array) != -1)
                subs = subs + subcat_dic[value_array[value_array.length - 1]];
              else
                subs = subs + "<wrong>" + subcat_dic[value_array[i]] + "</wrong>";
              //Create correct subs
              var correct_subs = "";

              var correct_and_notinput_sub = []; //store the subs that correct but have not been chosen by users
              for (var i = 0; i < correct_sub_array.length; i++) {
                if (jQuery.inArray(correct_sub_array[i], user_input_sub_array) == -1)
                  correct_and_notinput_sub.push(correct_sub_array[i]);
              }
              for (var i = 0; i < correct_and_notinput_sub.length - 1; i++) {
                correct_subs = correct_subs + "<insert>" + correct_and_notinput_sub[i] + "</insert> ,";
              }
              if (correct_and_notinput_sub.length > 0) {
                correct_subs = correct_subs + "<insert>" + correct_and_notinput_sub[correct_and_notinput_sub.length - 1] + "</insert>";
              }
              $("#urtable").append("<tr><td>"+ tagName +"</td><td><wrong>" + edited_category + "</wrong>&nbsp&nbsp&nbsp<insert>" + category_id_to_name_table[copy_dic[tagName]] + "</insert></td><td>" + subs + "&nbsp&nbsp&nbsp" + correct_subs + "</td></tr>");
          }

          delete copy_dic[tagName];
        }
        else {
          if (value_array[0] == -1) {
            $("#urtable").append("<tr><td>"+ "<wrong>" + tagName + "</wrong>" + "</td><td>" + "<wrong>" + edited_category + "</wrong>" + "</td><td>empty</td></tr>");
          }
          else {
            var subs = "";
            for (var i = 0; i < value_array.length - 1; i++) {
              subs = subs + subcat_dic[value_array[i]] + " ,";
            }
            subs = subs + subcat_dic[value_array[value_array.length - 1]];
            $("#urtable").append("<tr><td>"+ "<wrong>" + tagName + "</wrong>" + "</td><td>" + "<wrong>" + edited_category + "</wrong>" + "</td><td><wrong>" + subs + "</wrong>&nbsp&nbsp&nbsp<insert>empty</insert></td></tr>");
          }
        }
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
      padding 0 10px;
      padding-left: 10px;
    }

    #right_questions {
      padding 0 10px;
      padding-left: 10px;
    }

    table .rightTable {
      font-family: arial, sans-serif;
      border-collapse: collapse;
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
