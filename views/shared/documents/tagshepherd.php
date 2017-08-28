<!DOCTYPE html>
<html lang="en">
    <head>
        <?php
            include(dirname(__FILE__) . '/tag_include.php');
            include(dirname(__FILE__) . '/../common/header.php');
            include(dirname(__FILE__) . '/../common/progress_indicator.php');

            $category_object = getAllCategories();
            $category_id_name_table = getSubcategoryIdAndNames();
        ?>

        <script type="text/javascript">
            var msgbox;
            var comment_type = 1;
        </script>
    </head>

    <body> <!-- Page Content -->
        <div class="container-fluid">
            <div class="col-md-5" id="work-zone">
                <?php
                    include(dirname(__FILE__) . '/../common/document_viewer_section_with_transcription.php');
                ?>
            </div>

            <div class="col-md-7">
                <div id="tagging-container">
                    <br>
                        <div class="panel-group" id="phase1-panel-group">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                         <a data-toggle="collapse" href="#phase1-panel" id="phase1-link">Phase 1: Tag</a>
                                    </h4>
                                </div>
                                <div id="phase1-panel" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <p class="header-step">Instruction: Please find all tags for different categories. A tag is an object or event in the world like a place or person. It should uniquely refer to an object or event by its proper name (Hillary Clinton), acronym (IBM), nickname (Opra), abbreviation (Minn.) or description for the event (Oration; see the event category for more event description). If you have questions, you can mouseover <span class="glyphicon glyphicon-info-sign step-instruction-glyphicon"></span> to get more information.</p>
                                        <p class="header-step">Step <?php echo $task_seq; ?>.1a: Verify tags. Add subcategories and details if they are missing. If it's not a tag, delete it.</p>
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
                                        <p class="header-step">Step <?php echo $task_seq; ?>.1b: Add missing tags by highlighting words in the transcription on the left. You may skip this step if you do not see any missing tags</p>
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
                                        </table>
                                        <br>
                                        <br>
                                        <button type="button" class="btn btn-primary pull-right" id="phase1-button">Next</button>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-group" id="phase2-panel-group" style="display: none;">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" href="#phase2-panel" id="phase2-link">Phase 2: Evaluate</a>
                                    </h4>
                                </div>
                                <div id="phase2-panel" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <p class="header-step">In this phase, read your own work and provide the following assessment:</p>
                                        <p><b>Your Work: </b></p>
                                        <p>Your tags: </p>
                                        <table id="taglist" class="table table-striped table-condensed">
                                        <tr><th>Tag</th><th>Category</th><th>Subcategories</th></tr>
                                        </table>
                                        <p><b>Self Assessment: </b></p>
                                        <div class="checkbox">
                                            <label><input type="checkbox" value="" id="removal" name="removal">I removed all inappropriate existing tags.</label>
                                        </div>
                                        <div class="checkbox">
                                            <label><input type="checkbox" value="" id="correcting" name="correcting">I corrected all existing tags with correct categorical information.</label>
                                        </div>
                                        <div class="checkbox">
                                            <label><input type="checkbox" value="" id="coverage1" name="coverage1">I tagged all required types of tags.</label>
                                        </div>
                                        <div class="checkbox">
                                            <label><input type="checkbox" value="" id="coverage2" name="coverage2">I did not tag non-required types of tags.</label>
                                        </div>
                                        <div class="checkbox">
                                            <label><input type="checkbox" value="" id="completeness" name="completeness">I provided categorical information for all tags.</label>
                                        </div>
                                        <div class="checkbox">
                                            <label><input type="checkbox" value="" id="correctnames" name="correctnames">I tagged correct tag names based on instructions.</label>
                                        </div>
                                        <div class="checkbox">
                                            <label><input type="checkbox" value="" id="correctcategory" name="correctcategory">I provided correct categorical information for all tags.</label>
                                        </div>
                                        <p>How effective are your tags?</p>
                                        <select id="eff_tag" class="form-control">
                                            <option value="0"></option>
                                            <option value="9">9 Excellent</option>
                                            <option value="8">8</option>
                                            <option value="7">7 Very Good</option>
                                            <option value="6">6</option>
                                            <option value="5">5 Acceptable</option>
                                            <option value="4">4</option>
                                            <option value="3">3 Borderline</option>
                                            <option value="2">2</option>
                                            <option value="1">1 Poor</option>
                                        </select>
                                        <p>How effective are your categorical information?</p>
                                        <select id="eff_category" class="form-control">
                                            <option value="0"></option>
                                            <option value="9">9 Excellent</option>
                                            <option value="8">8</option>
                                            <option value="7">7 Very Good</option>
                                            <option value="6">6</option>
                                            <option value="5">5 Acceptable</option>
                                            <option value="4">4</option>
                                            <option value="3">3 Borderline</option>
                                            <option value="2">2</option>
                                            <option value="1">1 Poor</option>
                                        </select>
                                        <p>How can you improve your work?</p>
                                        <textarea style="width:100%;" rows="4" id="feedback"></textarea>
                                        <button type="button" class="btn btn-primary pull-right" id="phase2-button">Next</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-group" id="phase3-panel-group" style="display: none;">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                         <a data-toggle="collapse" href="#phase3-panel" id="phase3-link">Phase 3: Revise</a>
                                    </h4>
                                </div>
                                <div id="phase3-panel" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <p class="header-step">Based on what you learned from Phase 2, please revise your responses from Phase 1. Your previous responses have been copied here and you may go back to see your answers in Phase 2.</p>
                                        <p class="header-step">Instruction: Please find all tags for different categories. A tag is an object or event in the world like a place or person. It should uniquely refer to an object or event by its proper name (Hillary Clinton), acronym (IBM), nickname (Opra), abbreviation (Minn.) or description for the event (Oration; see the event category for more event description). If you have questions, you can mouseover <span class="glyphicon glyphicon-info-sign step-instruction-glyphicon"></span> to get more information.</p>
                                        <p class="header-step">Step <?php echo $task_seq; ?>.3a: Verify tags. Add subcategories and details if they are missing. If it's not a tag, delete it.</p>
                                        <a id="view-revision-history-link" style="display: none;">View Revision History...  </a>
                                        <table class="table" id="reventity-table">
                                        </table>
                                        <br>
                                        <p class="header-step">Step <?php echo $task_seq; ?>.3b: Add missing tags by highlighting words in the transcription on the left. You may skip this step if you do not see any missing tags</p>
                                        <table class="table" id="revuser-entity-table">
                                        </table>
                    <form id="tag-form" method="post">
                        <input type="hidden" id="start" name="start" value="">
                        <input type="hidden" id="baseline" name="baseline" value="">
                        <input type="hidden" id="condition" name="condition" value="">
                        <input type="hidden" id="revised" name="revised" value="">
                        <input type="hidden" id="end" name="end" value="">
                        <button type="button" class="btn btn-primary pull-right" id="phase3-button">Submit</button>
                    </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                <hr size=2 class="discussion-seperation-line">
            </div>
        </div>
    <!-- End work container -->

<script type="text/javascript">
    //Global variable to store categories/counters
    var categories = <?php echo json_encode($category_object).";\n"; ?>
    // alert(categories[2]['subcategory'].length);
    var category_id_to_name_table = <?php echo json_encode($category_id_name_table).";\n"; ?>
    var tagid_id_counter = <?php echo (isset($this->tag_id_counter) ? $this->tag_id_counter : "0"); ?>;
    var phase2events = [];
    var baseline = {};
    var condition = {};
    var revised = {};
    function check_input_baseline() {
        if ($('.category-select option:selected[value=0]').length > 0) {
            notif({
              msg: '<b>Error: </b> Tag category cannot be empty!',
              type: "error"
            });
            return false;
        }
        return true;
    }
    function check_input_condition() {
        if ($('#eff_tag').val() == 0) {
            notif({
              msg: '<b>Error: </b> You need to answer Q1.',
              type: "error"
            });
            return false;
            
        }
        if ($('#eff_category').val() == 0) {
            notif({
              msg: '<b>Error: </b> You need to answer Q2.',
              type: "error"
            });
            return false;
            
        }

        if ($('#feedback').val().length < 50) {
            notif({
              msg: '<b>Error: </b> Your feedback is too short!',
              type: "error"
            });
            return false;
            
        }
        return true;
    }
    function check_input_revised() {
        if ($('.category-select option:selected[value=0]').length > 0) {
            notif({
              msg: '<b>Error: </b> Tag category cannot be empty!',
              type: "error"
            });
            return false;
        }
        return true;
    }

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
        var new_entity = $('<tr id="tag_id_'+span_id+'_table" data-tagid="'+span_id+'"><td><span class="entity-name">'+text+'</span></td><td><select class="category-select"></select></td><td><select class="subcategory-select" multiple="multiple"></select></td><td><input class="form-control entity-details" type="text" value=""></td><td><button type="button" class="btn btn-default remove-entity-button" aria-label="Left Align"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button></td></tr>');

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
            var new_entity = $('<tr id="'+this.id+'_table" data-tagid="'+(""+this.id).replace("tag_id_", "")+'"><td><span class="entity-name">'+$(this).text()+'</span></td><td><select class="category-select '+this.className+'"></select></td><td><select class="subcategory-select" multiple="multiple"></select></td><td><input class="form-control entity-details" type="text" value=""></td><td><button type="button" class="btn btn-default remove-entity-button" aria-label="Left Align"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button></td></tr>');
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
    function getTagJSONString() {
        var entities = [];
        var rows = $('#entity-table tr').has("td");
        rows.each(function (idx) {
            //handle each field of an entity: should be 4 fields (name, cat, subcat, details); the 5th field is a button for deletion
            var name = $(this).find('.entity-name');
            var details = $(this).find('.entity-details');
            var category = $(this).find('.category-select option:selected');
            var subcategories = $(this).find('.subcategory-select option:selected');
            var subcategories_array = [];
            subcategories.each( function (idx) {
                subcategories_array.push($(this).val());
            });
            entities.push({entity: $(name).text(), category: $(category).val(), subcategory: subcategories_array, details: $(details).val()});
            $('#'+(""+this.id).replace('_table', '')).attr('data-subs', subcategories_array.toString());
            $('#'+(""+this.id).replace('_table', '')).attr('data-details', $(details).val());
        });
        rows = $('#user-entity-table tr').has("td");
        rows.each(function (idx) {
            //handle each field of an entity: should be 4 fields (name, cat, subcat, details); the 5th field is a button for deletion
            var name = $(this).find('.entity-name');
            var details = $(this).find('.entity-details');
            var category = $(this).find('.category-select option:selected');
            var subcategories = $(this).find('.subcategory-select option:selected');
            var subcategories_array = [];
            subcategories.each( function (idx) {
                subcategories_array.push($(this).val());
            });
            entities.push({entity: $(name).text(), category: $(category).val(), subcategory: subcategories_array, details: $(details).val()});
            $('#'+(""+this.id).replace('_table', '')).attr('data-subs', subcategories_array.toString());
            $('#'+(""+this.id).replace('_table', '')).attr('data-details', $(details).val());
        });
        return JSON.stringify(entities);
    }


    $(document).ready(function () {
        $('#start').val(getNow());
        baseline['start'] = getNow();
        addExistingTags();
        migrateTaggedDocumentsFromV1toV2();
        set_tag_id_counter();
        setInterval(function() {$('#count_down_timer').text("Time left: "+numToTime(allowed_time >= 0 ? allowed_time-- : 0)); timeIsUpCheck();}, 1000);

        <?php if ($this->is_being_edited): ?>
            styleForEditing();
        <?php endif; ?>
        $('#phase2-link').on('click', function(e) {
            if ($('#phase2-link').hasClass('collapsed')) { //event will be the opposite
                phase2events.push(['expand', getNow()]);
            } else {
                phase2events.push(['collapse', getNow()]);
            }
        });

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

        $('#phase1-button').on('click', function(e) {
            if (check_input_baseline()) {
                //window.onbeforeunload = null;
                //$('#interpretation-form').submit();
                baseline['end'] = getNow();
                baseline['response'] = getTagJSONString();
                $('#phase1-panel').collapse('hide');
                $('#phase1-panel').on('show.bs.collapse', function(e) {
                    e.preventDefault();
                });
                $('#phase1-link').addClass('disabled');
                $('#phase2-panel-group').show();
                $('#phase2-panel').collapse('show');
                $('#baseline').val(JSON.stringify(baseline));
                condition['start'] = getNow();
                //Add to taglist
                $('#entity-table tr').each(function (idx) {
                    if (idx != 0) {
                        var row = "<tr><td>";
                        var children = $(this).children('td');
                        row += $(children[0]).text();
                        row += "</td><td>";
                        row += $(children[1]).find('option:selected').text();
                        row += "</td><td>";
                        var subcats = $(children[2]).find('option:selected');
                        subcats.each(function(idx) {
                            row += $(this).text()+", ";
                        });
                        if (subcats.length > 0) {
                            row = row.substring(0, row.length-2);
                        }
                        row += "</td></tr>";
                        $("#taglist").append(row);
                    }
                });
                $('#user-entity-table tr').each(function (idx) {
                    if (idx != 0) {
                        var row = "<tr><td>";
                        var children = $(this).children('td');
                        row += $(children[0]).text();
                        row += "</td><td>";
                        row += $(children[1]).find('option:selected').text();
                        row += "</td><td>";
                        var subcats = $(children[2]).find('option:selected');
                        subcats.each(function(idx) {
                            row += $(this).text()+", ";
                        });
                        if (subcats.length > 0) {
                            row = row.substring(0, row.length-2);
                        }
                        row += "</td></tr>";
                        $("#taglist").append(row);
                    }

                });
                $("html, body").animate({ scrollTop: 0 }, "slow");
            }
        });
        $('#phase2-button').on('click', function(e) {
            if (check_input_condition()) {
                condition['end'] = getNow();
                $('#phase2-panel').collapse('hide');
                $('#phase3-panel-group').show();
                $('#phase3-panel').collapse('show');
                $("html, body").animate({ scrollTop: 0 }, "slow");
                $('#phase2-button').hide();
                $('#reventity-table').replaceWith($('#entity-table'));
                $('#revuser-entity-table').replaceWith($('#user-entity-table'));
                $('input[type=checkbox]').prop('disabled', true)
                $('label:has(input[type=checkbox][disabled])').css('color', '#999')
                $('#phase2-panel select').prop('disabled', true);
                $('#feedback').prop('disabled', true);
                $('#feedback').css('color', '#999');
                condition['response'] = {};
                condition['response']['checklist'] = {};
                $('input[type=checkbox]').each(function (idx) {
                    if (this.checked) {
                        condition['response']['checklist'][this.name] = 1;
                    } else {
                        condition['response']['checklist'][this.name] = 0;
                    }
                });
                condition['response']['eff_tag'] = $('#eff_tag').val();
                condition['response']['eff_category'] = $('#eff_category').val();
                condition['response']['feedback'] = $('#feedback').val();
                $('#condition').val(JSON.stringify(condition));
                revised['start'] = getNow();
            }
        });
        $('#phase3-button').on('click', function(e) {
            if (check_input_revised()) {
                window.onbeforeunload = null;
                $('#end').val(getNow());
                revised['response'] = getTagJSONString();
                revised['phase2events'] = phase2events;
                revised['end'] = getNow();
                $('#revised').val(JSON.stringify(revised));
                $('#tag-form').submit();
            }
        });
        $('#phase1-panel').collapse('show');

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

</style>

</body>

</html>
