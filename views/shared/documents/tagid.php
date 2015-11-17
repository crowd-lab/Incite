<!DOCTYPE html>
<html lang="en">
    <?php
    include(dirname(__FILE__) . '/../common/header.php');
    
    $category_object = getAllCategories();
    ?>
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
            <div style="position: fixed; width: 35%;" id="work-view">
                <textarea name="transcribe_text" rows="20" id="transcribe_copy" style="width: 100%;"><?php print_r($this->transcription); ?></textarea>
                <div class="wrapper">
                    <div id="document_img" class="viewer"></div>
                </div>
                <button type="button" class="btn btn-default" id="show">Document</button>
                <button type="button" class="btn btn-default" id="hide">Transcription</button>
            </div>
        </div>
        <div class="col-md-6">
            <table class="table" id="entity-table">
                <tr><th>Entity</th><th>Category</th><th>Subcategory</th><th>Details</th><th>Not an entity?</th></tr>
                <?php foreach ($this->entities as $entity): ?>
                    <tr><td><input type="text" class="form-control" value="<?php echo $entity['entity']; ?>"></td><td><select class="category-select"></select></td><td><select class="subcategory-select" multiple="multiple"></select></td><td><input class="form-control" type="text" value="<?php echo $entity['details']; ?>"></td><td><button type="button" class="btn btn-default remove-entity-button" aria-label="Left Align"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button></td></tr>
                <?php endforeach; ?>
            </table>
            <button type="button" class="btn btn-primary" id="add-more-button">Add more</button>
            <button type="button" class="btn btn-primary pull-right" id="confirm-button">I confirm the above information is correct!</button>
            <div id="container">
                <h3> Discussion </h3>
                <ul id="comments">
                    <li class="cmmnt">
<!--                            <div class="avatar"><a href="javascript:void(0);"><img src="images/dark-cubes.png" width="55" height="55" alt="DarkCubes photo avatar"></a></div>
                        -->
                        <div class="cmmnt-content">
                            <header><a href="javascript:void(0);" class="userlink">DarkCubes</a> - <span class="pubdate">posted 1 week ago</span></header>
                            <p>Ut nec interdum libero. Sed felis lorem, venenatis sed malesuada vitae, tempor vel turpis. Mauris in dui velit, vitae mollis risus. Cras lacinia lorem sit amet augue mattis vel cursus enim laoreet. Vestibulum faucibus scelerisque nisi vel sodales. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis pellentesque massa ac justo tempor eu pretium massa accumsan. In pharetra mattis mi et ultricies. Nunc vel eleifend augue. Donec venenatis egestas iaculis.</p>
                        </div>
                    </li>
                </ul>
                <?php if (isset($_SESSION['Incite']['IS_LOGIN_VALID']) && $_SESSION['Incite']['IS_LOGIN_VALID'] == true /** && is_permitted * */): ?>
                    <textarea name="transcribe_text" cols="60" rows="10" id="comment">Your comment</textarea>
                    <button type="button" class="btn btn-default" id="">Submit</button>
                <?php else: ?>
                    Please login or signup to join the discussion!
                </div>
            <?php endif; ?>
            <!--
                            <p>Are these the same as the following entities? </p>
                            <form role="form">
                                <label class="radio-inline">
                                  <input type="radio" name="optradio">George Washington
                                </label>
                                <label class="radio-inline">
                                  <input type="radio" name="optradio">Washington State
                                </label>
                                <label class="radio-inline">
                                  <input type="radio" name="optradio">Not an Entity
                                </label>
                                <label class="radio-inline">
                                  <input type="radio" name="optradio">No
                                </label>
                            </form>
            
                            <form role="form" method="post">
                                                    <p>Please type the tag name in the following text box:</p>
                                                              <input type="text" name="tag_text">
                                                    <br />
                                                    <p>Please choose what type the tag is:</p>
                                <label class="radio-inline">
                                  <input type="radio" name="tag_category" value="People">People
                                </label>
                                <label class="radio-inline">
                                  <input type="radio" name="tag_category" value="Places">Places
                                </label>
                                <label class="radio-inline">
                                  <input type="radio" name="tag_category" value="Organizations">Organizations
                                </label>
                                <label class="radio-inline">
                                  <input type="radio" name="tag_category" value="Events">Events
                                </label>
                                <label class="radio-inline">
                                  <input type="radio" name="tag_category" value="Ideas">Ideas
                                </label>
                                <label class="radio-inline">
                                  <input type="radio" name="tag_category" value="Time">Time/Date
                                </label>
                                    <textarea name="tag_description" cols="60" rows="15" id="tag_description">Details</textarea>
                                    <button type="submit" class="btn btn-default">Submit</button>
                            </form>
            -->
        </div> 
    </div>





</div>

</div>
<!-- /.container -->

<script>
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
    });</script>


<script type="text/javascript">
    /*
    $(document).ready(function () {
        var request = $.ajax({
            async: false,
            type: "POST",
            url: "http://localhost/m4j/incite/ajax/getcategories",
            success: function (data) {
                window.categories = JSON.parse(data);
            }});
    });
    */

</script>
<script type="text/javascript">
    
        var ttt;
    
    var $ = jQuery;
    $(document).ready(function () {

        var iv2 = $("#document_img").iviewer({
            src: "<?php echo $this->tag->getFile()->getProperty('uri'); ?>"
        });
        $('#add-more-button').on('click', function (e) {
            var new_entity = $('<tr><td><input type="text" class="form-control" value=""></td><td><select class="category-select"></select></td><td><select class="subcategory-select" multiple="multiple"></select></td><td><input class="form-control" type="text" value=""></td><td><button type="button" class="btn btn-default remove-entity-button" aria-label="Left Align"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button></td></tr>');
            new_entity.find('.subcategory-select').multiselect({
                enableFiltering: true,
                filterBehavior: 'value',
                disableIfEmpty: true
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
            $.each($('.category-select'), function (idx) {
                var subcategory_menu = $(this).closest('tr').find('.subcategory-select');
                $.each(categories[0]['subcategory'], function (idx) {
                    subcategory_menu.append('<option value="'+this['subcategory_id']+'">'+this['subcategory']+'</option>').multiselect('rebuild');
                });
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

            //Initial so category must be 0
            $.each($('.category-select'), function (idx) {
                var subcategory_menu = $(this).closest('tr').find('.subcategory-select');
                $.each(categories[0]['subcategory'], function (idx) {
                    subcategory_menu.append('<option value="'+this['subcategory_id']+'">'+this['subcategory']+'</option>').multiselect('rebuild');
                });
            });
            $('.category-select').multiselect('rebuild');
        });
        $('#confirm-button').on('click', function (e) {
            var entities = [];
            var rows = $('#entity-table tr').has("td");
            rows.each(function (idx) {
                //handle each field of an entity: should be 4 fields (name, cat, subcat, details); the 5th field is a button for deletion
                var fields = $(this).find('input');
                entities.push({entity: $(fields[0]).val(), category: $(fields[1]).val(), subcategory: $(fields[2]).val(), details: $(fields[3]).val()});
            });
            //alert is for testing
            alert(JSON.stringify(entities));
            //data, that is, JSON.stringify(entities) are ready to be submitted for processing
        });
        $('.subcategory-select').each(function (idx) {
            $(this).multiselect({
                enableFiltering: true,
                filterBehavior: 'value',
                disableIfEmpty: true
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
    });
    $('.viewer').height($(window).height() * 68 / 100);
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
<pre>

<?php print_r($category_object); ?>

<?php echo json_encode($category_object); ?>
</pre>

</body>

</html>
