<!DOCTYPE html>
<html lang="en">

<head>
    <script type="text/javascript">
        var selectedReferences = null;
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
    #document-info-glphicon {
        color: #337AB7; 
        font-size: 20px;
        top: 8px;
        left: 15px;
    }

    .popover {
    	max-width: 100%;
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
                <a href="<?php echo getFullInciteUrl(); ?>/discussions/discuss">Back to Other Discussions</a>
                <h3><?php echo $this->title; ?></h3>
<?php if (count($this->discussions) > 0 ): // else it's an error!?>
                <div style="margin: 10px; background-color: #FFFFFF; padding: 10px;">
                    <b><a href="<?php echo getFullInciteUrl().'/users/view/'.$this->discussions[0]->user_id; ?>"><?php echo $this->discussions[0]->first_name; ?></a> commented on <span class="raw-date"><?php echo $this->discussions[0]->timestamp_creation; ?></span>:</b><br>
                    <p><?php echo $this->discussions[0]->comment_text; ?></p>
                </div>

<?php endif; ?>
                <h4>Related documents: </h4>
                <div id="references" style="white-space: nowrap;">
<?php $ref_row_counter = 0; $col_per_row = 6;?>
<?php foreach ((array) $this->references as $reference): ?>
                    <div class="col-md-2 reference" data-toggle="popover" data-trigger="hover" data-content="<?php echo $reference['description']; ?>" data-description="<?php echo $reference['description']; ?>" data-transcription="<?php echo $reference['transcription']; ?>" data-title="<?php echo $reference['title']; ?>" data-placement="top" data-id="<?php echo $reference['id']; ?>" data-uri="<?php echo $reference['uri']; ?>" data-data="<?php echo $reference['date']; ?>" data-location="<?php echo $reference['location']; ?>">
                        <img style="width: 40px; height: 40px; margin-bottom: 13px;" src="<?php echo $reference['uri']; ?>">
                    </div>
<?php if ($ref_row_counter++%$col_per_row == ($col_per_row-1)): ?>
                    <div class="clearfix"></div>
<?php endif; ?>

<?php endforeach; ?>
                    <div class="clearfix"></div>
                </div>
<?php if (count($this->discussions) > 1): ?>
                <h4>Follow-up comments: </h4>
<?php endif; ?>
<?php for ($i = 1; $i < count($this->discussions); $i++): ?>
                <div style="margin: 10px; background-color: #FFFFFF; padding: 10px;">
                    <b><a href="<?php echo getFullInciteUrl().'/users/view/'.$this->discussions[$i]->user_id; ?>"><?php echo $this->discussions[$i]->first_name; ?></a> commented on <span class="raw-date"><?php echo $this->discussions[$i]->timestamp_creation; ?></span>:</b><br>
                    <p><?php echo $this->discussions[$i]->comment_text; ?></p>
                </div>

<?php endfor; ?>
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
        $('.raw-date').each(function (idx) {
            this.innerHTML = compareDates(new Date(this.innerHTML));
        });
        $('.reference-view').height($(window).height()-$('#work-zone').offset().top-$('#viewer-title').height()-$('#document-view ul.nav-tabs').height()-35); //-35 for buffer
        $('#discussion_title').on('keyup keypress', function(e) {
            var code = e.keyCode || e.which;
            if (code == 13) { 
                e.preventDefault();
                return false;
            }
        });
        $(document).on('mouseenter', '#document-tabs a', function(e) {
            var pos = this.href.indexOf('#doc-');
            if (pos != -1) {
                pos += 5;
                $('div[data-id='+this.href.substring(pos)+']').popover('show');
            }
        });
        $(document).on('mouseleave', '#document-tabs a', function(e) {
            var pos = this.href.indexOf('#doc-');
            if (pos != -1) {
                pos += 5;
                $('div[data-id='+this.href.substring(pos)+']').popover('hide');
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
        $('.reference').each(function (idx) {
            $(this).popover();
        });

        $('.reference').on('click', function (e) {
            //case 1 of 2: document is not yet in the viewer -> add the document to the viewer
            if ($('#doc-'+this.dataset.id).length <= 0) {
                while ($('#document-tabs').children().length >= 3) {
                    $($('#document-tabs').children()[0]).remove();
                    $($('#document-contents').children()[0]).remove();
                }
                //Add tab
                $('#document-tabs').append('<li style="width: 33%;"><a href="#doc-'+this.dataset.id+'" data-toggle="tab"><div style="overflow:hidden; height: 20px; text-overflow: ellipsis;">'+this.dataset.title+'<button class="close" type="button" title="Remove this page">×</button></div></a></li>');
                //Add content
                var content = '<div class="tab-pane reference-view" id="doc-'+this.dataset.id+'">';
                content += '<ul class="nav nav-tabs" role="tablist">';
                content += '<li class="active"><a class="doc-tab" href="#document-'+this.dataset.id+'" role="tab" data-toggle="tab">Document</a></li>';
                content += '<li><a class="transcription-tab" href="#transcription-'+this.dataset.id+'" role="tab" data-toggle="tab">Transcription</a></li>';
                content += '<span class="glyphicon glyphicon-info-sign" id="document-info-glphicon" aria-hidden="true" data-trigger="hover" data-toggle="popover" data-html="true" data-viewport=".tabbable" '
                content += 'data-title="Document Information" data-content="'
                content += '<strong>Title:</strong>'
                content += this.dataset.title;
                content += '<br><br><strong>Date:</strong><br>'
                content += this.dataset.data;
                content += '<br><br><strong>Location:</strong><br>'
                content += this.dataset.location;
                content += '<br><br><strong>Description:</strong><br>'
                content += this.dataset.description;
                content += '"></span></ul>';
                content += '<div class="tab-content"><div class="tab-pane fade active in viewer" id="document-'+this.dataset.id+'"></div>';
                content += '<div class="tab-pane fade" id="transcription-'+this.dataset.id+'">'+this.dataset.transcription+'</div></div>';
                $('#document-contents').append(content);
                $('.glyphicon').popover();
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
            //case 2 of 2: document is already in the viewer -> switch to the tab
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
