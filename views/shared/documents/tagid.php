<!DOCTYPE html>
<html lang="en">
    <?php
    include(dirname(__FILE__) . '/../common/header.php');
    
    $category_object = getAllCategories();
    ?>
    <script type="text/javascript">
        $(function ()
        {
            getNewComments(<?php echo $this->tag->id; ?>);
        });
    </script>
    <!-- Page Content -->
    <div class="container">
        <div class="row">
            <div class="col-md-8">
            </div>
        </div>
        <div class="col-md-4">
            <button type="button" class="btn btn-default">Subscribe</button>
            <button type="button" class="btn btn-default">Guide</button>
        </div>
    </div>
    <div class="container">
        <div class="col-md-6" id="work-zone">
            <div style="position: fixed;" id="work-view">
                <h4>Information:</h4>
                    <div>Title: <?php echo metadata($this->tag, array('Dublin Core', 'Title')); ?></div>
                    <div>Date: <?php echo metadata($this->tag, array('Dublin Core', 'Date')); ?></div>
                    <div>Location: <?php echo metadata($this->tag, array('Item Type Metadata', 'Location')); ?></div>
                    <div>Description: <?php echo metadata($this->tag, array('Dublin Core', 'Description')); ?></div>
                <h4>Transcription:</h4>
<?php foreach ((array)$this->category_colors as $category => $color): ?>
                <div><span style="background-color:<?php echo $color; ?>;"><?php echo ucfirst(strtolower($category)); ?></span></div>
<?php endforeach; ?>
                <div style="border-style: solid; overflow: scroll;" name="transcribe_text" rows="10" id="transcribe_copy" style="width: 100%;"><?php print_r($this->transcription); ?></div>
                <div class="wrapper">
                    <div id="document_img" class="viewer"></div>
                </div>
                <button type="button" class="btn btn-default" id="show">Document</button>
                <button type="button" class="btn btn-default" id="hide">Transcription</button>
            </div>
        </div>
        <div class="col-md-6">
            <table class="table" id="entity-table">
                <tr>
                    <th>Tag</th>
                    <th>Category</th>
                    <th>Subcategory</th>
                    <th>Details</th>
                    <th>Not a tag?</th></tr>
<?php foreach ((array)$this->entities as $entity): ?>
                <tr>
                    <td>
                        <input type="text" class="form-control entity-name" value="<?php echo $entity['entity']; ?>">
                    </td>
                    <td>
                        <select class="category-select <?php echo ucwords(strtolower($entity['category'])); ?>"></select>
                    </td>
                    <td>
                        <select class="subcategory-select <?php //echo implode(' ', $entity['subcategory']); ?>" multiple="multiple"></select>
                    </td>
                    <td>
                        <input class="form-control entity-details" type="text" value="<?php echo $entity['details']; ?>">
                    </td>
                    <td>
                        <button type="button" class="btn btn-default remove-entity-button" aria-label="Left Align">
                            <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                        </button>
                    </td>
                </tr>
<?php endforeach; ?>
            </table>
            <button type="button" class="btn btn-primary" id="add-more-button">Add more</button>
            <button type="submit" class="btn btn-primary pull-right" id="confirm-button">Submit</button>
            <form id="entity-form" method="post">
                <input id="entity-info" type="hidden" name="entities" />
            </form>
            <div id="container">
                <h3> Discussion </h3>
                <div id="onLogin">
                <?php if (isset($_SESSION['Incite']['IS_LOGIN_VALID']) && $_SESSION['Incite']['IS_LOGIN_VALID'] == true /** && is_permitted * */): ?>
                    <textarea name="comment_text" cols="60" rows="10" id="comment" placeholder="Your comment"></textarea>
                    <button type="button" class="btn btn-default" onclick="submitComment(<?php echo $this->tag->id; ?>)">Submit</button>
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
    //Global variable to store categories
    var categories = <?php echo json_encode($category_object); ?>
    
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

        var iv2 = $("#document_img").iviewer({
            src: "<?php echo $this->tag->getFile()->getProperty('uri'); ?>"
        });
        $('#add-more-button').on('click', function (e) {
            var new_entity = $('<tr><td><input type="text" class="form-control entity-name" value=""></td><td><select class="category-select"></select></td><td><select class="subcategory-select" multiple="multiple"></select></td><td><input class="form-control entity-details" type="text" value=""></td><td><button type="button" class="btn btn-default remove-entity-button" aria-label="Left Align"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button></td></tr>');
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
            //Initial so category must be 0
            var subcategory_menu = new_entity.closest('tr').find('.subcategory-select');
            $.each(categories[0]['subcategory'], function (idx) {
                subcategory_menu.append('<option value="'+this['subcategory_id']+'">'+this['subcategory']+'</option>').multiselect('rebuild');
            });
        });
        $('#entity-table').on('click', '.remove-entity-button', function (e) {
            $(this).parent().parent().remove();
        });
        $('#entity-table').on('change', '.category-select', function (e) {
            var subcategory_menu = $(this).closest('tr').find('.subcategory-select');
            subcategory_menu.find('option').remove().end();
            $.each(categories[$(this).val()-1]['subcategory'], function (idx) {
                subcategory_menu.append('<option value="'+this['subcategory_id']+'">'+this['subcategory']+'</option>').multiselect('rebuild');
            });
        });
        $('#entity-table').ready( function(e) {

            //if first time, use ajax to fetch subcategories, add to .subcategory-select and rebuild .subcategory-select by $('#example-subcategory-select').multiselect('rebuild'). Also, the categories/subcategories should be stored to avoid frequent ajax calls
            $('.category-select').empty();
            <?php 
                for ($i = 0; $i < sizeof($category_object); $i++)
                {
                    echo '$(\'.category-select\').append("<option value=\''.$category_object[$i]["id"].'\'>'.$category_object[$i]["name"].' </option>");';
                }
            ?>

            $.each($('.category-select'), function (idx) {
                //Location: 1, Event: 2, Person: 3, Organization 4
                var cat = 1;
                if ($(this).hasClass('Location')) {
                    $($(this).find('option[value=1]')).attr('selected', 'selected');
                } else if ($(this).hasClass('Person')) {
                    cat = 3;
                    $($(this).find('option[value=3]')).attr('selected', 'selected');
                } else if ($(this).hasClass('Organization')) {
                    cat = 4;
                    $($(this).find('option[value=4]')).attr('selected', 'selected');
                } else if ($(this).hasClass('Event')) {
                    cat = 2;
                    $($(this).find('option[value=2]')).attr('selected', 'selected');
                } else {  //unexpected category!
                }
                var subcategory_menu = $(this).closest('tr').find('.subcategory-select');
                $.each(categories[cat-1]['subcategory'], function (idx) {
                    var selected = "";
                    if (subcategory_menu.hasClass(this['subcategory'].replace(/ /g, ''))) {
                        selected = "selected=selected";
                    }
                    subcategory_menu.append('<option value="'+this['subcategory_id']+'"'+selected+'>'+this['subcategory']+'</option>').multiselect('rebuild');
                });
            });
            $('.category-select').multiselect('rebuild');
        });
        $('#confirm-button').on('click', function (e) {
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
                entities.push({entity: $(name).val(), category: $(category).val(), subcategory: subcategories_array, details: $(details).val()});
            });
            //alert is for testing
            $('#entity-info').val(JSON.stringify(entities));
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
    $('.viewer').height($(window).height()-$('#transcribe_copy')[0].getBoundingClientRect().top-60);
    $('#transcribe_copy').height($(window).height()-$('#transcribe_copy')[0].getBoundingClientRect().top-60);
    });
</script>
<style>
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
</style>

</body>

</html>
