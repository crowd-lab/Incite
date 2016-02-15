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
                    url: "http://localhost/m4j/incite/ajax/searchkeyword2",
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
        .tabs-below > .nav-tabs,
        .tabs-right > .nav-tabs,
        .tabs-left > .nav-tabs {
          border-bottom: 0;
        }
        
        .tab-content > .tab-pane,
        .pill-content > .pill-pane {
          display: none;
        }

        .tab-content > .active,
        .pill-content > .active {
          display: block;
        }

        .tabs-below > .nav-tabs {
          border-top: 1px solid #ddd;
        }

        .tabs-below > .nav-tabs > li {
          margin-top: -1px;
          margin-bottom: 0;
        }

        .tabs-below > .nav-tabs > li > a {
          -webkit-border-radius: 0 0 4px 4px;
             -moz-border-radius: 0 0 4px 4px;
                  border-radius: 0 0 4px 4px;
        }

        .tabs-below > .nav-tabs > li > a:hover,
        .tabs-below > .nav-tabs > li > a:focus {
          border-top-color: #ddd;
          border-bottom-color: transparent;
        }

        .tabs-below > .nav-tabs > .active > a,
        .tabs-below > .nav-tabs > .active > a:hover,
        .tabs-below > .nav-tabs > .active > a:focus {
          border-color: transparent #ddd #ddd #ddd;
        }

        .reference-view {
            width:100%;
            overflow: scroll;
        }

        .nav-tabs > li .close {
            margin: -3px 0 0 10px;
            font-size: 18px;
            padding: 5px 0;
            float: right;
        }
        .viewer
        {
            width: 100%;
            border: 1px solid black;
            position: relative;
        }
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
            <div style="position: fixed;" id="work-view">
                <h3 id="viewer-title">Related Documents:</h3>
                <div class="tabbable tabs-below" id="document-view">
                    <div class="tab-content" id="document-contents">
                    </div>
                    <ul class="nav nav-tabs" id="document-tabs">
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="row" id="content-1">
                <a href="/m4j/incite/discussions/discuss"><button class="btn btn-primary">Back to Other Discussions</button></a>
                <h3><?php echo $this->title; ?></h3>
                <br>
                <h4>Related documents (click the thumbnail to open): </h4>
                <div id="references" style="white-space: nowrap;">
<?php foreach ((array) $this->references as $reference): ?>
                    <div class="col-md-2 reference" data-toggle="popover" data-trigger="hover" data-content="<?php echo $reference['description']; ?>" data-transcription="<?php echo $reference['transcription']; ?>" data-title="<?php echo $reference['title']; ?>" data-placement="top" data-id="<?php echo $reference['id']; ?>" data-uri="<?php echo $reference['uri']; ?>">
                        <img style="width: 40px; height: 40px;" src="<?php echo $reference['uri']; ?>">
                    </div>
<? endforeach; ?>
                    <div class="clearfix"></div>
                </div>
<?php foreach ((array) $this->discussions as $discussion): ?>
                <div style="margin: 10px; background-color: #FFFFFF; padding: 10px;">
                    <b><?php echo $discussion['first_name']; ?> commented on <?php echo $discussion['time']; ?>:</b><br>
                    <p><?php echo $discussion['content']; ?></p>
                </div>

<? endforeach; ?>
                <div id="discussion_reply_form_container">
                    <form id="discussion_form" class="form-wrapper" method="post">
<?php if (isset($_SESSION['Incite']['IS_LOGIN_VALID']) && $_SESSION['Incite']['IS_LOGIN_VALID'] == true /** && is_permitted * */): ?>
                        <p id="reply_title">Reply: </p>
                        <textarea id="discussion_content" name="content" style="width: 100%;" rows="5" placeholder="Your thoughts here..."></textarea>
<?php else: ?>
                        <p id="reply_title">Please login or signup to join the discussion!</p>
                        <textarea id="discussion_content" name="content" style="width: 100%; display:none;" rows="5" placeholder="Your thoughts here..."></textarea>

<?php endif; ?>
                        <br>
                        <input type="hidden" name="discussion_id" value="<?php echo $this->id; ?>">
<?php if (isset($_SESSION['Incite']['IS_LOGIN_VALID']) && $_SESSION['Incite']['IS_LOGIN_VALID'] == true /** && is_permitted * */): ?>
                        <button id="submit_discussion" type="button" class="btn btn-primary pull-right">Submit</button>
<?php else: ?>
                        <button id="submit_discussion" type="button" class="btn btn-primary pull-right" style="display:none;">Submit</button>
<?php endif; ?>

                    </form>
                </div>
            </div>
        </div>
    </div>
<script type="text/javascript">

    $('#work-zone').ready(function() {
        $('#work-view').width($('#work-zone').width());
    });
    $(document).ready(function () {
        $('.reference-view').height($(window).height()-$('#work-zone').offset().top-$('#viewer-title').height()-$('#document-view ul.nav-tabs').height()-35); //-35 for buffer
        $('#discussion_title').on('keyup keypress', function(e) {
            var code = e.keyCode || e.which;
            if (code == 13) { 
                e.preventDefault();
                return false;
            }
        });
        $(document).on('click', '#submit_discussion', function(e) {
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
        $('.reference').each(function (idx) {
            $(this).popover();
        });

        $('.reference').on('click', function (e) {
            //case1: document is already in the viewer -> switch to the tab
            //case2: document is not yet in the viewer -> add the document to the viewer
            if ($('#doc-'+this.dataset.id).length <= 0) {
                //Add tab
                $('#document-tabs').append('<li><a href="#doc-'+this.dataset.id+'" data-toggle="tab">'+this.dataset.title+'<button class="close" type="button" title="Remove this page">×</button></a></li>');
                //Add content
                $('#document-contents').append('<div class="tab-pane reference-view" id="doc-'+this.dataset.id+'"><ul class="nav nav-tabs" role="tablist"><li class="active"><a class="doc-tab" href="#document-'+this.dataset.id+'" role="tab" data-toggle="tab">Document</a></li><li><a class="transcription-tab" href="#transcription-'+this.dataset.id+'" role="tab" data-toggle="tab">Transcription</a></li></ul><div class="tab-content"><div class="tab-pane fade active in viewer" id="document-'+this.dataset.id+'"></div><div class="tab-pane fade" id="transcription-'+this.dataset.id+'">'+this.dataset.transcription+'</div></div>');
                $('.reference-view').height($(window).height()-$('#work-zone').offset().top-$('#viewer-title').height()-$('#document-tabs').height()-35); //-35 for buffer
                //if there is transcription, show transcription first.
                if (this.dataset.transcription !== "no transcription available")
                    $('#doc-'+this.dataset.id+' a.transcription-tab').click();

                //$('.reference-view ul.nav-tabs').height() sometimes doesn't render in time
                var tab_height = $('.reference-view ul.nav-tabs').height() > 40 ? $('.reference-view ul.nav-tabs').height() : 41;
                $('.viewer').height($('.reference-view').height()-tab_height-5);//-5 for buffer
                $('.viewer').width($('.reference-view').width()-2); //-2 for border
                $("#document-"+this.dataset.id).iviewer({
                    src: this.dataset.uri,
                    zoom_min: 1,
                    zoom: "fit"
                });
            }
            $('ul a[href=#doc-'+this.dataset.id+']').click();
        });
        $('#document-tabs').on('click', 'button.close', function (e) {
            var content_id = $(this).parents('li').children('a').attr('href');
            $(this).parents('li').remove();
            $(content_id).remove();
            $('#document-tabs a:last-child').click();
        });
    });
    $('#work-zone').ready(function() {
        $('#work-view').width($('#work-zone').width());
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
