<!DOCTYPE html>
<html lang="en">
    <?php
    include(dirname(__FILE__) . '/../common/header.php');
    
    $category_object = getAllCategories();
    $subcategory_id_name_table = getSubcategoryIdAndNames();
    ?>
    <script type="text/javascript">
        $(function ()
        {
            getNewComments(<?php echo $this->tag->id; ?>);
        });
    </script>
    <!-- Page Content -->
    <div id="task_description">
        <h1 class="task-header">Tag</h1>
    </div>
    <div class="container-fluid">
        <div class="col-md-5" id="work-zone">
            <div style="position: fixed;" id="work-view">
                <div class="document-header">
                    <span class="document-title"><b>Title:</b> <?php echo metadata($this->tag, array('Dublin Core', 'Title')); ?></span>
                    <span class="document-additional-info" 
                        data-toggle="popover" data-html="true" data-trigger="hover" 
                        data-title="Additional Information" 
                        data-content="<?php echo "<strong>Date:</strong> " 
                                . metadata($tag, array('Dublin Core', 'Date')) 
                                . "<br><br> <strong>Location:</strong> " 
                                . metadata($this->tag, array('Item Type Metadata', 'Location')) 
                                . "<br><br> <strong>Description:</strong> " 
                                . metadata($this->tag, array('Dublin Core', 'Description')); ?>" 
                        data-placement="bottom" data-id="<?php echo $tag->id; ?>">
                        More about this document..
                    </span>
                </div> 
                <div>
                    Legends:
<?php foreach ((array)$this->category_colors as $category => $color): ?>
                    <span class="<?php echo strtolower($category); ?>"><?php echo ucfirst(strtolower($category)); ?></span>
<?php endforeach; ?>
                    <span class="unknown">Unknown</span>
                </div>
                <div style="border-style: solid; overflow: scroll;" name="transcribe_text" rows="10" id="transcribe_copy" style="width: 100%;"><?php print_r($this->transcription); ?></div>
                <div class="wrapper">
                    <div id="document_img" class="viewer"></div>
                </div>
                <button type="button" class="btn btn-default" id="show">Document</button>
                <button type="button" class="btn btn-default" id="hide">Transcription</button>
            </div>
        </div>
        <div class="col-md-7">
            <div class="col-md-12">
                <p class="header-step"><i>Step 1 of 2: Verify and expand existing tags</i></p>
                <table class="table" id="entity-table">
                    <tr>
                        <th>Tag</th>
                        <th>Category</th>
                        <th>Subcategory</th>
                        <th>Details</th>
                        <th>Not a tag?</th></tr>
                </table>
                <br>
                <p class="step"><i>Step 2 of 2: Add missing tags (by selecting texts from the transcription on the left). (You may skip this step if you do not see any missing tags.)</i></p>
                <table class="table" id="user-entity-table">
                    <tr>
                        <th>Tag</th>
                        <th>Category</th>
                        <th>Subcategory</th>
                        <th>Details</th>
                        <th>Not a tag?</th></tr>
                    <tr>
                </table>
    <!--            <button type="button" class="btn btn-primary" id="add-more-button">Add more</button>  -->
                <button type="submit" class="btn btn-primary pull-right" id="confirm-button">Submit</button>
                <form id="entity-form" method="post">
                    <input id="entity-info" type="hidden" name="entities" />
                    <input id="tagged-doc" type="hidden" name="tagged_doc" />
                    <input id="trans-id" type="hidden" name="transcription_id" value="<?php echo $this->transcription_id; ?>" />
                    <input type="hidden" name="query_str" value="<?php echo (isset($this->query_str) ? $this->query_str : ""); ?>">  
                </form>
            </div>
            <br>
            <hr size=2 class="discussion-seperation-line">
            <div id="container">
                <h3> Discussion </h3>
                <div id="onLogin">
                <?php if (isset($_SESSION['Incite']['IS_LOGIN_VALID']) && $_SESSION['Incite']['IS_LOGIN_VALID'] == true /** && is_permitted * */): ?>
                            <textarea name="transcribe_text" cols="60" rows="10" id="comment" class="comment-textarea" placeholder="Your comment"></textarea>
                            <button type="button" class="btn btn-default submit-comment-btn" onclick="submitComment(<?php echo $this->tag->id; ?>)">Post Comment</button>
                <?php else: ?>
                    Please login or signup to join the discussion!
                <?php endif; ?>
                </div>
                <ul id="comments">
                </ul>
            </div>
        </div> 
    </div>





</div>

</div>
<!-- /.container -->

<script type="text/javascript">
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
        <?php for ($i = 0; $i < sizeof($category_object); $i++){
            echo "new_entity.find('.category-select').append(\"<option value='".$category_object[$i]["id"]."'>".$category_object[$i]["name"]."</option>\");";
        }
        ?>
        new_entity.find('.category-select').multiselect({
            disableIfEmpty: true
        });
        $('.category-select').multiselect('rebuild');
            
        $('#user-entity-table').append(new_entity);
        new_entity.closest('tr').find('.subcategory-select').multiselect('rebuild');
    }
    function addExistingTags() {
        $('#transcribe_copy span').each(function (idx) {
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
            new_entity.find('.category-select').append('<option value="0">Unknown</option>');
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

    //Global variable to store categories
    var categories = <?php echo json_encode($category_object).";\n"; ?>
    var subcategory_id_to_name_table = <?php echo json_encode($subcategory_id_name_table).";\n"; ?>
    var msgbox;
    
    $(document).ready(function () {
        $('[data-toggle="popover"]').popover({trigger: "hover"});
        $("#document_img").hide();
        $("#hide").click(function () {
            $("#document_img").hide();
            $("#transcribe_copy").show();
        });
        $("#show").click(function () {
            $("#document_img").show();
            $("#transcribe_copy").hide();
        });
        $(document).on('click', 'button', function (event)
        {
            if (event.target.name === "reply")
            {
                var NewContent = '<form id="reply-form" method="POST"><textarea name="transcribe_text" cols="60" rows="10" id="replyBox' + event.target.id.substring(5) + '" placeholder="Your Reply"></textarea><button type="button" onclick="submitReply(event<?php echo ', '.$this->tag->id; ?>)" class="btn btn-default" id="submit' + event.target.id.substring(5) + '" value="' + event.target.value + '">Submit</button></form>';
                $("#" + event.target.id).after(NewContent);
                $("#" + event.target.id).remove();
            }
        });
    });

    $('#work-zone').ready(function() {
        $('#work-view').width($('#work-zone').width());
    });

    $(document).ready(function () {
        addExistingTags();
        $('.viewer').height($(window).height()-$('#transcribe_copy')[0].getBoundingClientRect().top-50);
        $('#transcribe_copy').height($(window).height()-$('#transcribe_copy')[0].getBoundingClientRect().top-50);
        $("#document_img").iviewer({
            src: "<?php echo $this->tag->getFile()->getProperty('uri'); ?>",
            zoom_min: 1,
            zoom: "fit"
        });

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
            $('#tag_id_'+$(this).parent().parent().attr('data-tagid')).addClass($(this).find('option[value='+$(this).val()+']').text().toLowerCase());
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
            $('#tag_id_'+$(this).parent().parent().attr('data-tagid')).addClass($(this).find('option[value='+$(this).val()+']').text().toLowerCase());
        });
/*
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
            $('#tag_id_'+$(this).parent().parent().attr('data-tagid')).addClass($(this).find('option[value='+$(this).val()+']').text().toLowerCase());
//*/
        $('#confirm-button').on('click', function (e) {
            if ($('.category-select option:selected[value=0]').length > 0) {
                alert('Category cannot be "Unknown"');
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
        //$('.SelectBox').SumoSelect();
        //$('.SelectBox').SumoSelect({placeholder: 'This is a placeholder', csvDispCount: 3 });
        $('#work-view').width($('#work-zone').width());


        //Add entities by selection
        $('#transcribe_copy').on('mouseup', function (e) {
            var tag_selection = document.getSelection();
            var tag_text = tag_selection.toString();
            if (tag_selection.rangeCount && tag_text !== "") {
                var tag_range = tag_selection.getRangeAt(0);
                var tag_span = document.createElement('span');
                tag_span.id = 'tag_id_'+tagid_id_counter;
                tag_span.className = 'unknown';
                tag_span.appendChild(document.createTextNode(tag_text));
                tag_range.deleteContents();
                tag_range.insertNode(tag_span);
                addUserTag(tag_text, tagid_id_counter++);
            }
        });
        $('#transcribe_copy').on('mouseenter', 'span', function (e) {
            $('#'+this.id+'_table').toggleClass(this.className);
        });
        $('#transcribe_copy').on('mouseleave', 'span', function (e) {
            $('#'+this.id+'_table').toggleClass(this.className);
        });
<?php
    if (isset($_SESSION['incite']['message'])) {
        echo "msgbox = BootstrapDialog.alert({message:$('<div>".$_SESSION['incite']['message']."</div>')});\n";
        //echo "setTimeout(closeMsgBox, 3000);\n";
        unset($_SESSION['incite']['message']);
    }

?>
    });
</script>
<style>
        .document-header {
            margin-top: -30px;
        }

        .document-title {
            font-size: 20px; 
            position: relative; 
            top: -5px;
        }

        .document-additional-info {
            color: #0645AD; 
            float: right;
        }
        .task-header {
            text-align: center; 
            margin-bottom: 40px; 
            margin-top: 0px;
        }
        #task_description {
            text-align: center;
        }
        .step {
            margin-top: 10px;
        }

        .header-step {
            margin-top: -32px;
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
    .viewer
    {
        width: 100%;
        border: 1px solid black;
        position: relative;
    }

    .wrapper
    {
        overflow: hidden;
    }
    .location {
        background-color: yellow;
    }
    .organization {
        background-color: red;
    }
    .person {
        background-color: orange;
    }
    .event {
        background-color: green;
    }
    .unknown {
        background-color: gray;
    }
        .discussion-seperation-line {
            margin-top: 40px;
        }
</style>

</body>

</html>
