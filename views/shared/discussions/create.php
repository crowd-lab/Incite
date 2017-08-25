<!DOCTYPE html>
<html lang="en">

<head>
    <script type="text/javascript">
        var selectedReferences = null;
        function searchForDocuments2()
        {
            var currentSearch = document.getElementById('search_query').value;
            if (currentSearch !== "")
            {
                var request = $.ajax({
                    type: "POST",
                    url: "<?php echo getFullInciteUrl(); ?>/ajax/searchkeyword2",
                    data: {keyword: currentSearch},
                    success: function(data)
                    {
                        $("#search_results").empty();
                        var parsedData = JSON.parse(data);
                        $('#result_info').text(" ("+parsedData.length+" document(s) found)");
                        $.each(parsedData, function (idx) {
                            $('#search_results').append('<br><div><input type="checkbox"> <img style="width: 40px; height: 40px;" src="'+this.uri+'"> '+this.title+' <span class="document_text" data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="'+this.description+'" data-id="'+this.id+'" data-uri="'+this.uri+'" data-title="'+this.title+'"> (<u>summary</u>)</span></div><br>');
                        });
                        $('#search_results').append('</select>');
                        $('#search_results span').each(function (idx) {
                            $(this).popover();
                        });
                    }
                });
            }
            else
            {
                $("#document_icons").empty();
                //clear selector
            }
        }
    </script>
    <style>
    </style>

    <?php
queue_css_file(array('bootstrap', 'style', 'bootstrap.min', 'leaflet'));
queue_js_file(array('leaflet', 'jquery'));
$db = get_db();

include(dirname(__FILE__).'/../common/header.php');
?>


</head>

    <div class="modal fade" id="document-selector-dialog" tabindex="-1" role="dialog" aria-labelledby="document-selector-dialog-label">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="select-references-label">Select References</h4>
                </div>
                <div class="modal-body">
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab1">
                            <form>
                                <div class="form-group">
                                    <label for="recipient-name" class="control-label">Search for documents:</label>
                                    <input type="text" class="form-control" id="searchDocuments" name="searchDocuments" onkeydown="searchForDocuments()">
                                </div>
                                <div id="document_icon_belt">
                                    <select id="document_icons" multiple="multiple">
                                        
                                    </select>
                                    
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" id="modal-footer">
                    <div style="float:left">
                        <button type="button" class="btn btn-primary" id="remove-button" onclick="removeSelectedOptions()">Remove Previously Selected</button>
                    </div>
                    <button type="button" class="btn btn-primary" id="submit-button" onclick="getSelectedOptions()">Add References</button>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-5 col-md-offset-1" id="work-zone">
            <h3>Search Related References/Documents:</h3>
            <form class="form-wrapper" method="post">
                <div class="input-group col-md-11">
                    <input type="text" class="form-control" placeholder="Search" name="srch-term" id="search_query">
                    <div class="input-group-btn">
                        <button id="search_button" class="btn btn-default" type="button"><i class="glyphicon glyphicon-search"></i></button>
                    </div>
                </div>
                <p>Results: <span id="result_info"></span> </p>
                <div id="search_results" class="col-md-11" style="background-color: #EBE5E5;">
                </div>
                <br>
                <br>
                <button id="add_reference" type="button" class="btn btn-primary">Add Selected as Reference(s)</button>
            </form>
        </div>
        <div class="col-md-5">
            <div class="row" id="content-1">
                <a href="<?php echo getFullInciteUrl(); ?>/discussions/discuss">Back to Other Discussions</a>
                <h3>Create a New Discussion</h3>
                <form id="discussion_form" class="form-wrapper" method="post">
                    Title: <input id="discussion_title" name="title" type="text" style="margin-bottom: 10px; width: 100%;" id="search1" placeholder="How do Northerners vs Southerners write....">
                    <p>Content: </p>
                        <textarea id="discussion_content" name="content" style="width: 100%;" rows="10" placeholder="Details of the discussion..."></textarea>
                    <h4>References: </h4>
                    <div id="references" style="white-space: nowrap;">
                    </div>
                    <br>
                    <input type="hidden" id="discussion_references" name="references">
                    <button id="delete_reference" type="button" class="btn btn-primary">Delete Selected Reference(s)</button>
                    <button id="submit_discussion" type="button" class="btn btn-primary pull-right">Submit</button>
                </form>
            </div>
        </div>
    </div>
<script type="text/javascript">

    $(document).ready(function () {
        $('#discussion_title').on('keyup keypress', function(e) {
            var code = e.keyCode || e.which;
            if (code == 13) { 
                e.preventDefault();
                return false;
            }
        });
        $('#submit_discussion').on('click', function(e) {
            if ($('#discussion_title').val() === "") {
                notifyOfErrorInForm('The title of the discussion cannot be empty');
                return;
            }
            if ($('#discussion_content').val() === "") {
                notifyOfErrorInForm('The content of the discussion cannot be empty');
                return;
            }

            var refs = '';
            $('#references .reference').each(function (idx) {
                refs += this.dataset.id + ',';
            });
            if (refs.length > 0)
                refs = refs.substring(0, refs.length-1);
            $('#discussion_references').val(refs);
            
            $('#discussion_form').submit();
        });
        $('#search_button').on('click', function() {
            if ($('#search_query').val() === "" ) {
                notifyOfErrorInForm('Please enter some keywords in order to start the search.');
                return;
            }
            searchForDocuments2();
        });
        $('#search_query').on('keypress', function (e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                searchForDocuments2();
            }
        });
        $('#add_reference').on('click', function() {
            //check if already referenced. If not, add it!
            //var existing_refs = 
            var selected_refs = $('#search_results input:checked').parent().find('span.document_text');
            $('#references div.clearfix').remove()
            selected_refs.each(function (idx) {
                var doc_id = $(this).attr('data-id');
                //if not referenced
                if ($('#references div[data-id='+doc_id+']').length == 0) {
                    var cur_doc = $(this);
                    var new_ref = $('<div class="col-md-2 reference" data-id="'+cur_doc.attr('data-id')+'" data-placement="top" data-toggle="popover" data-trigger="hover" data-content="'+cur_doc.attr('data-content')+'" data-title="'+cur_doc.attr('data-title')+'"><input type="checkbox"> <img style="width: 40px; height: 40px;" src="'+cur_doc.attr('data-uri')+'"></div>');
                    $('#references').append(new_ref);
                    new_ref.popover();
                }
            });
            $('#references').append('<div class="clearfix"></div>');
        });
        $('#delete_reference').on('click', function() {
            var selected_refs = $('#references input:checked').parent().remove();
        });
    });
    $('.btn-group .btn').on('click',function(){
        if($('input[name=options]:checked').val() == 'exist') {
            $("#content-2").hide(); 
            $("#content-1").show(); 
        }
        else {
            $("#content-1").hide(); 
            $("#content-2").show(); 
        }
    });
    $("#content-2").hide(); 
    
        $(function()
        {
            $("#document_icons").hide();
            $("#remove-button").hide();
        });
    function getSelectedOptions()
    {      
        
        var delimValue = $("option:selected").map(function(){return $(this).val();}).get().join(",");
        selectedReferences = delimValue.split(',');
        for (var i = 1; i < selectedReferences.length; i++)
        {
            var didFind = false;
            //do not add repeated references to images belt
            if ($('#images').find('img').length > 0)
            {
                var currentReferenceString = "";
                $('#images').find('img').each(function()
                {
                    currentReferenceString += $(this).attr('document-id') + ",";
                });
                var currentReferenceArray = currentReferenceString.split(',');
                for (var j = 0; j < currentReferenceArray.length; j++)
                {
                    
                    if (currentReferenceArray[j] == selectedReferences[i])
                    {
                        didFind = true;
                        break;
                    }
                }
            }
            if (!didFind)
            {
                var imgTag = document.createElement('img');
                imgTag.src = $('img[document-id="' + selectedReferences[i] + '"').attr('src');
                imgTag.setAttribute('document-id', selectedReferences[i]);
                imgTag.setAttribute('data-toggle', 'popover');
                imgTag.setAttribute('data-trigger', 'hover');
                imgTag.setAttribute('data-content', $('img[document-id="' + selectedReferences[i] + '"').attr('data-content'));
                $('#images').append(imgTag);
                $("img").each(function()
                {
                    $(this).css('width', '40px');
                    $(this).css('height', '40px');
                    $(this).popover();
                });
            }
        }
        $('#document-selector-dialog').modal('hide');
    }
</script>

</body>
</html>
