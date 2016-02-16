<!DOCTYPE html>
<html lang="en">
    <head>
        <?php
            include(dirname(__FILE__) . '/../common/header.php');
            include(dirname(__FILE__) . '/../common/progress_indicator.php');
        
            $category_object = getAllCategories();
            $subcategory_id_name_table = getSubcategoryIdAndNames();
        ?>

        <script type="text/javascript">
            var msgbox;
            var comment_type = 1;
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
                <div class="col-md-12" id="tagging-container">
                    <p class="header-step"><i>Step 1 of 2: Verify and expand existing tags</i></p>
                    <table class="table" id="entity-table">
                        <tr>
                            <th>
                                Tag
                                <span class="glyphicon glyphicon-info-sign step-instruction-glyphicon"
                                    aria-hidden="true" data-trigger="hover"
                                    data-toggle="popover" data-html="true"
                                    data-viewport="#tagging-container";
                                    data-title="<strong>Creating a tag</strong>" 
                                    data-content="<?php echo "Tags in this upper table are computer generated. If no tags are present, then the computer did not find anything it could tag accurately." ?>" 
                                    data-placement="bottom" data-id="<?php echo $transcription->id; ?>">
                                </span>
                            </th>
                            <th>
                                Category
                                <span class="glyphicon glyphicon-info-sign step-instruction-glyphicon"
                                    aria-hidden="true" data-trigger="hover"
                                    data-toggle="popover" data-html="true"
                                    data-viewport="#tagging-container";
                                    data-title="<strong>Selecting a category</strong>" 
                                    data-content="<?php echo "The computer has tried to identify the category of this tag, please ensure it is accurate and change it if needed." ?>" 
                                    data-placement="bottom" data-id="<?php echo $transcription->id; ?>">
                                </span>
                            </th>
                            <th>
                                Subcategory
                                <span class="glyphicon glyphicon-info-sign step-instruction-glyphicon"
                                    aria-hidden="true" data-trigger="hover"
                                    data-toggle="popover" data-html="true"
                                    data-viewport="#tagging-container";
                                    data-title="<strong>Selecting a subcategory</strong>" 
                                    data-content="<?php echo "The computer has tried to identify the subcategories for this tag, please ensure they are accurate and change them if needed." ?>" 
                                    data-placement="bottom" data-id="<?php echo $transcription->id; ?>">
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
                                    data-placement="bottom" data-id="<?php echo $transcription->id; ?>">
                                </span>
                            </th>
                            <th>Not a tag?</th></tr>
                    </table>
                    <br>
                    <p class="step"><i>Step 2 of 2: Add missing tags by highlighting words in the transcription on the left. You may skip this step if you do not see any missing tags</i></p>
                    <table class="table" id="user-entity-table">
                        <tr>
                            <th>
                                Tag
                                <span class="glyphicon glyphicon-info-sign step-instruction-glyphicon"
                                    aria-hidden="true" data-trigger="hover"
                                    data-toggle="popover" data-html="true"
                                    data-title="<strong>Creating a tag</strong>"
                                    data-viewport="#tagging-container"; 
                                    data-content="<?php echo "Computers can't always recognize tags, so we need your help! Highlighting a word in the transcription box to the left will generate a new tag." ?>" 
                                    data-placement="bottom" data-id="<?php echo $transcription->id; ?>">
                                </span>
                            </th>
                            <th>
                                Category
                                <span class="glyphicon glyphicon-info-sign step-instruction-glyphicon"
                                    aria-hidden="true" data-trigger="hover"
                                    data-toggle="popover" data-html="true"
                                    data-title="<strong>Selecting a category</strong>" 
                                    data-content="<?php echo "For the given tag, select the category which it falls into most easily. A tag must have a category other than 'unknown' to be accepted." ?>" 
                                    data-placement="right" data-id="<?php echo $transcription->id; ?>">
                                </span>
                            </th>
                            <th>
                                Subcategory
                                <span class="glyphicon glyphicon-info-sign step-instruction-glyphicon"
                                    aria-hidden="true" data-trigger="hover"
                                    data-toggle="popover" data-html="true"
                                    data-title="<strong>Selecting a subcategory</strong>" 
                                    data-content="<?php echo "For the given tag and category, select the appropriate subcategories, if any. If it doesn't fall into any subcategories simply leave none selected." ?>" 
                                    data-placement="right" data-id="<?php echo $transcription->id; ?>">
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
                                    data-placement="bottom" data-id="<?php echo $transcription->id; ?>">
                                </span>
                            </th>
                            <th>Not a tag?</th></tr>
                        <tr>
                    </table>
                    <button type="submit" class="btn btn-primary pull-right" id="confirm-button">Submit</button>
                    <form id="entity-form" method="post">
                        <input id="entity-info" type="hidden" name="entities" />
                        <input id="tagged-doc" type="hidden" name="tagged_doc" />
                        <input id="trans-id" type="hidden" name="transcription_id" value="<?php echo $this->transcription_id; ?>" />
                        <input type="hidden" name="query_str" value="<?php echo (isset($this->query_str) ? $this->query_str : ""); ?>">  
                    </form>

                    <hr size=2 class="discussion-seperation-line">
                </div>

                <?php
                    include(dirname(__FILE__) . '/../common/task_comments_section.php');
                ?>
            </div> 
        </div>
    <!-- End work container -->

<script type="text/javascript">
    //Global variable to store categories/counters
    var categories = <?php echo json_encode($category_object).";\n"; ?>
    var subcategory_id_to_name_table = <?php echo json_encode($subcategory_id_name_table).";\n"; ?>
    var tagid_id_counter = <?php echo (isset($this->tag_id_counter) ? $this->tag_id_counter : "0"); ?>;

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
        new_entity.find('.category-select').append('<option value="0">Unknown</option>');

        <?php for ($i = 0; $i < sizeof($category_object); $i++) {
            echo "new_entity.find('.category-select').append(\"<option value='".$category_object[$i]["id"]."'>".$category_object[$i]["name"]."</option>\");";
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
            
            <?php for ($i = 0; $i < sizeof($category_object); $i++){
                echo "new_entity.find('.category-select').append(\"<option value='".$category_object[$i]["id"]."'>".$category_object[$i]["name"]."</option>\");";
            }
            ?>
            new_entity.find('.category-select').multiselect({
                disableIfEmpty: true
            });
            $('.category-select').multiselect('rebuild');
                
            $('#entity-table').append(new_entity);
            new_entity.closest('tr').find('.subcategory-select').multiselect('rebuild');
            new_entity.find('.category-select')
                //Location: 1, Event: 2, Person: 3, Organization 4
                var cat = 1;
                if (new_entity.find('.category-select').hasClass('location')) {
                    new_entity.find('.category-select option[value=1]').attr('selected', 'selected');
                } else if (new_entity.find('.category-select').hasClass('person')) {
                    cat = 3;
                    new_entity.find('.category-select option[value=3]').attr('selected', 'selected');
                } else if (new_entity.find('.category-select').hasClass('organization')) {
                    cat = 4;
                    new_entity.find('.category-select option[value=4]').attr('selected', 'selected');
                } else if (new_entity.find('.category-select').hasClass('event')) {
                    cat = 2;
                    new_entity.find('.category-select option[value=2]').attr('selected', 'selected');
                } else {  //Unknown category!
                    cat = 0;
                    new_entity.find('.category-select option[value=0]').attr('selected', 'selected');
                }
                new_entity.find('.category-select').multiselect('rebuild');
                var subcategory_menu = new_entity.find('.subcategory-select');
                $(subcategory_menu).empty();
                if (new_entity.find('.category-select option:selected').text() !== "Unknown") {
                    $.each(categories[new_entity.find('.category-select').val()-1]['subcategory'], function (idx) {
                        subcategory_menu.append('<option value="'+this['subcategory_id']+'">'+this['subcategory']+'</option>').multiselect('rebuild');
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

        $('#user-entity-table').on('click', '.remove-entity-button', function (e) {
            $(this).parent().parent().remove();
        });

        $('#user-entity-table').on('change', '.category-select', function (e) {
            var subcategory_menu = $(this).closest('tr').find('.subcategory-select');
            subcategory_menu.find('option').remove().end();
            if ($(this).find('option:selected').text() !== "Unknown") {
                $.each(categories[$(this).val()-1]['subcategory'], function (idx) {
                    subcategory_menu.append('<option value="'+this['subcategory_id']+'">'+this['subcategory']+'</option>').multiselect('rebuild');
                });
            } else {
                subcategory_menu.multiselect('rebuild');
            }
            $('#tag_id_'+$(this).parent().parent().attr('data-tagid')).removeClass();
            $('#tag_id_'+$(this).parent().parent().attr('data-tagid')).addClass($(this).find('option[value='+$(this).val()+']').text().toLowerCase() + " tagged-text");
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
            if ($(this).find('option:selected').text() !== "Unknown") {
                $.each(categories[$(this).val()-1]['subcategory'], function (idx) {
                    subcategory_menu.append('<option value="'+this['subcategory_id']+'">'+this['subcategory']+'</option>').multiselect('rebuild');
                });
            } else {
                subcategory_menu.multiselect('rebuild');
            }
            $('#tag_id_'+$(this).parent().parent().attr('data-tagid')).removeClass();
            $('#tag_id_'+$(this).parent().parent().attr('data-tagid')).addClass($(this).find('option[value='+$(this).val()+']').text().toLowerCase() + " tagged-text");
        });

        $('#confirm-button').on('click', function (e) {
            if ($('.category-select option:selected[value=0]').length > 0) {
                notifyOfErrorInForm('Tag category cannot be set to "Unknown"');
                return;
            }
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
            //alert is for testing
            $('#entity-info').val(JSON.stringify(entities));
            $('#tagged-doc').val($('#transcribe_copy').html());
            $('#entity-form').submit();
            //data, that is, JSON.stringify(entities) are ready to be submitted for processing
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
</script>

<style>
    .discussion-seperation-line {
        margin-top: 100px;
    }

    #tagging-container {
        padding-right: 0px;
    }

    .comments-section-container {
        padding-left: 15px;
    }
</style>

</body>

</html>
