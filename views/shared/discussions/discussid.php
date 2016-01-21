<!DOCTYPE html>
<html lang="en">

<head>
    <script type="text/javascript">
        var selectedReferences = null;
        function searchForDocuments()
        {
            var currentSearch = document.getElementById('searchDocuments').value;
            if (currentSearch !== "")
            {
                var request = $.ajax({
                    type: "POST",
                    url: "http://localhost/m4j/incite/ajax/searchkeyword",
                    data: {keyword: currentSearch},
                    success: function(data)
                    {
                        $("#document_icons").empty();
                        var parsedData = JSON.parse(data);
                        for (var i = 0; i < parsedData.length; i += 3)
                        {
                            var optionTag = document.createElement('option');
                            optionTag.setAttribute("data-img-src", parsedData[i]);
                            optionTag.className = "thumbnail";
                            optionTag.value = parsedData[i + 1];
                            $("#document_icons").append(optionTag);
                        }                        
                        $("#document_icons").imagepicker();
                        var count = 0;
                        $("img").each(function()
                        {
                            $(this).css('width', '40px');
                            $(this).css('height', '40px');
                            $(this).attr("data-toggle","popover");
                            $(this).attr("data-trigger", "hover");
                            $(this).attr("data-content", "" + parsedData[count + 2]);
                            $(this).attr("document-id", "" + parsedData[count + 1])
                            $(this).popover();
                            count += 3;
                            
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
            <h3>Viewer for Related References/Documents:</h3>
            <div class="tabbable tabs-below" id="document-view">
                <div class="tab-content" id="document-contents">
                </div>
                <ul class="nav nav-tabs" id="document-tabs">
                </ul>
            </div>
        </div>
        <div class="col-md-5">
            <div class="row btn-group" data-toggle="buttons">
              <label class="btn btn-primary">
                <input type="radio" name="options" id="option1" autocomplete="off" value="exist"> View existing discussions
              </label>
              <label class="btn btn-primary active">
                <input type="radio" name="options" id="option2" value="new" autocomplete="off" checked> Create a new discussion
              </label>
            </div>

            <div class="row" id="content-1">
                <h3><?php echo $this->title; ?></h3>
<?php foreach ((array) $this->discussions as $discussion): ?>
                <div style="margin: 10px; background-color: #AAAAAA; padding: 10px;">
                    <b><?php echo $discussion['first_name']; ?>:</b><br>
                    <p><?php echo $discussion['content']; ?></p>
                </div>

<? endforeach; ?>
                <form id="discussion_form" class="form-wrapper" method="post">
                    <p>Reply: </p>
                        <textarea id="discussion_content" name="content" style="width: 100%;" rows="5" placeholder="Your thoughts here..."></textarea>
                    <h4>References: </h4>
                    <div id="references" style="white-space: nowrap;">
<?php foreach ((array) $this->references as $reference): ?>
                        <div class="col-md-2 reference" data-toggle="popover" data-trigger="hover" data-content="<?php echo $reference['description']; ?>" data-transcription="<?php echo $reference['transcription']; ?>" data-title="<?php echo $reference['title']; ?>" data-placement="top" data-id="<?php echo $reference['id']; ?>">
                            <img style="width: 40px; height: 40px;" src="<?php echo $reference['uri']; ?>">
                        </div>
<? endforeach; ?>
                        <div class="clearfix"></div>
                    </div>
                    <br>
                    <button id="submit_discussion" type="button" class="btn btn-primary pull-right">Submit</button>
                </form>
            </div>
            <div class="row" id="content-2">
                <h3> Subjects of this document: <a href="">Nationalism</a>, 
                    <a href="">Freedom</a>, 
                    <a href="">Revolution</a> </h3>
                <h3>Related Discussions: </h3>
                <p>Number: <a href="">URL</a></p>

            </div>
        </div>
    </div>
<script type="text/javascript">

    $(document).ready(function () {
        $('.reference-view').height($(window).height()-$('#document-view').offset().top-$('#document-view ul.nav-tabs').height()-5); //-5 for buffer
        $('#discussion_title').on('keyup keypress', function(e) {
            var code = e.keyCode || e.which;
            if (code == 13) { 
                e.preventDefault();
                return false;
            }
        });
        $('#submit_discussion').on('click', function(e) {
            if ($('#discussion_title').val() === "") {
                alert('The title of the discussion cannot be empty');
                return;
            }
            if ($('#discussion_content').val() === "") {
                alert('The content of the discussion cannot be empty');
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
                $('#document-tabs').append('<li><a href="#doc-'+this.dataset.id+'" data-toggle="tab">'+this.dataset.id+'<button class="close" type="button" title="Remove this page">×</button></a></li>');
                //Add content
                $('#document-contents').append('<div class="tab-pane reference-view" id="doc-'+this.dataset.id+'">'+this.dataset.transcription+'</div>');
                $('.reference-view').height($(window).height()-$('#document-view').offset().top-$('#document-view ul.nav-tabs').height()-5); //-5 for buffer
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
