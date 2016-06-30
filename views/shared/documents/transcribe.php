
<!DOCTYPE html>
<html lang="en">
<?php
$task = "transcribe";
include(dirname(__FILE__).'/../common/header.php');
?>

<style>

.no-map-marker {
    background-color: #EEEEEE;
}

.icon-container {
    position: relative;
    top: -20px;
    margin-left: 45px;
}

.task-icon {
    margin-right: 7px;
    cursor: pointer;
}

.light-grey-color {
    color: lightgrey;
}
</style>

<script type="text/javascript">


var map;
var msgbox;
var markers_array = [];
var nomarkers_array = [];
var marker_to_id = {};
var id_to_marker = {};
var screenX;
var screenY;
var docs;


function addTaskCompletionIconsToResultsRow(documentId) {
    var row = $('#list_id' + documentId);
    var iconContainer = $('<div class="icon-container"></div>');

    var transcribedIcon = $('<a href="<?php echo getFullInciteUrl(); ?>/documents/transcribe/' + documentId + '">' +
    '<span title="Document has not yet been transcribed - Click to transcribe it" class="glyphicon glyphicon-pencil task-icon light-grey-color"></span></a>');

    iconContainer.append(transcribedIcon);
    row.append(iconContainer);
}
</script>

<!-- Page Content -->
<div id="task_description" style="text-align: center;">
    <h3 style="text-align: center;">Search Results of Transcribable Documents</h3>
    <span style="text-align: center;">You can mouse over the pins on the map or document thumbnails to see more details and click them to try transcribing the document!
    </span>
</div>
<div id="map-view" style="margin: 5px; width: 69%;"><div id="map-div" style=""></div></div>

<div id="list-view" style="position: absolute; top: 80px; right: 0; left: 100px; width: 30%; height: 500px; background-color: white; border: solid 1.5px; border-color: #B2B1B1;">



</div>

<div id="timeline"></div>
<div id="timeline-spacing" class="col-md-8" style="height:100px;"></div>

</div>

<script type="text/javascript">

var ev, tl;
ev = [];
var width, height;
var current_page;
var total_pages = 0;
var items_per_page;

$('#map-div').ready( function (e) {
    $('#map-div').height($(window).height()-200);
});

$(document).ready( function (e) {

    // getting the current_page that was saved (using setItem) before it was refreshed.
    var newCurrPage = localStorage.getItem("currPage");
    current_page = (newCurrPage != null ? newCurrPage : 1);

    //setting up for the list
    setUpForDocumentsList();

    $('#timeline').width($(window).width()-30);
    document.getElementById('list-view').style.top = ($('#map-div').offset().top)+'px';
    document.getElementById('list-view').style.left = $('#map-div').width()+10+'px';
    document.getElementById('list-view').style.height = ($('#map-div').height())+'px';


    $(window).on('resize', function(e) {
        $('#map-div').width($(window).width()*0.69);
        $('#timeline').width($(window).width()-30);
        $('#map-div').height($(window).height()-200);
        document.getElementById('list-view').style.top = ($('#map-div').offset().top)+'px';
        document.getElementById('list-view').style.left = $('#map-div').width()+10+'px';
        document.getElementById('list-view').style.height = ($('#map-div').height())+'px';
        //$('#list-view').width($(window).width()*0.15);
    });
    $('#list-view-switch').one('click', showListView);


});

/**
* Saving the current_page after a page number(link) was clicked.
* This will be triggered before redirect.
*/
function setCurrentPageNum(num){
    localStorage.setItem("currPage", num);
}

/**
* Gets the current browser's width and height.
* redefine the items_per_page according to the size.
* Then, it will call getTranscribableDocumentsRequest to get documents.
*/
function setUpForDocumentsList(){
    width = $(window).width()
    height = $(window).height();
    //default
    items_per_page = 8;

    if(height <= 540){
        items_per_page = 4;
    }
    if (height > 540 && height <= 630){
        items_per_page = 5;
    }
    if(height > 630 && height <= 680){
        items_per_page = 6;
    }
    if(height > 680 && height <= 730){
        items_per_page = 7;
    }
    if(height >730 && height <= 780){
        items_per_page = 8;
    }
    if(height > 780 && height <= 840){
        items_per_page = 9;
    }
    if(height >840 && height <= 880){
        items_per_page = 10;
    }
    if(height >880 && height <= 950){
        items_per_page = 11;
    }
    if(height > 950){
        items_per_page= 12;
    }

    getTranscribableDocumentsRequest();

}

/**
* Ajax Request.
* Send current_page of the list and number of items per page.
* When succeed, response contains two items. 'total_pages' is the total number of pages
* in the list. 'records' contains documents that we need to display.
* This function will call other functions to build the map, timeline, pagination bar and the list.
*/
function getTranscribableDocumentsRequest() {


    var request = $.ajax({
        type: "GET",
        // dataType:"json",
        url: "<?php echo getFullInciteUrl().'/ajax/getdocuments'; ?>",
        data: {"current_page": current_page, "items_per_page": items_per_page<?php if (isset($_GET['location'])) echo ', "location":"'.$_GET['location'].'"';?><?php if (isset($_GET['time'])) echo ', "time":"'.$_GET['time'].'"';?>
        <?php if (isset($_GET['keywords'])) echo ', "keywords":"'.$_GET['keywords'].'"';?>},
        success: function (response)
        {
            if(response != "false"){
                var data =  $.parseJSON(response);
                total_pages = data['total_pages'];
                docs = jQuery.extend(true, [], data['records']);
                //display documents in the list
                displayDocumentsList(data['records']);
            }
            //build map and pagination
            generatePaginationBar();
            buildMap();

        },
        error: function(xhr,textStatus,err)
        {
            console.log("readyState: " + xhr.readyState);
            console.log("responseText: "+ xhr.responseText);
            console.log("status: " + xhr.status);
            console.log("text status: " + textStatus);
            console.log("error: " + err);
        }
    });
};

function generatePaginationBar(){
    var buffer ="";
    var disableFirst = (current_page == 1 || total_pages == 0? "disabled" : "");
    var disableLast = (current_page == total_pages || total_pages == 0? "disabled" : "");
    var query = "<?php echo ($this->query_str == "" ? "" : "&".$this->query_str); ?>";
    var startNum;
    var endNum;

$('#list-view').append("<div id=\"pagination-bar\" class=\"text-center\"><nav><ul class=\"pagination\"></ul></nav></div>");
    if (total_pages != 0){
        // first page
        $(".pagination").append("<li class=\"page-item "+ disableFirst +"\" value=\"1\"> <a class=\"page-link\" href=\"?page=1"+ query +"\" "+ "onclick=\"return " +(disableFirst != "" ? "false" : "setCurrentPageNum("+ (1) +")") + "\" > 1<span class=\"sr-only\">First</span></a></li>");

    }

    // previous page
    $(".pagination").append("<li class=\"page-item "+ disableFirst +"\" value=\""+(current_page-1)+"\"> <a class=\"page-link\" href=\"?page="+ (current_page-1) +query +"\" onclick=\"return " +(disableFirst != "" ? "false" : "setCurrentPageNum("+ (current_page-1) +")") + "\" aria-label=\"Previous\">&laquo<span class=\"sr-only\"></span></a></li>");


    if(total_pages > 5){
        if(current_page < 3){
            startNum = 0;
        }
        else if(total_pages- current_page < 2){
            startNum = total_pages - 5;
        }
        else{
            startNum = current_page -3;
        }
    }
    else{
        startNum = 0;
    }
    if(total_pages < 5){
        endNum = total_pages;
    }
    else{
        endNum = startNum + 5;
    }


    for (i = startNum; i < endNum; i++){
        $(".pagination").append("<li class=\"page-item "+ (current_page == (i+1) ? "active" : "") +"\" value=\""+(i+1)+"\"> <a class=\"page-link\" href=\"?page="+ (i+1) + query +"\" onClick=\"return setCurrentPageNum("+ (i+1) +")\">"+ (i+1) + "<span class=\"sr-only\">(current)</span></a></li>" );
    }

    var nextPage = parseInt(current_page)+1;
    $(".pagination").append("<li class=\"page-item "+ disableLast +"\" value=\""+nextPage+"\"> <a class=\"page-link\" href=\"?page="+ nextPage +query +"\" onclick=\"return " +((disableLast != "") ? "false" : "setCurrentPageNum("+ nextPage +")") + "\" aria-label=\"Next\">&raquo<span class=\"sr-only\"></span></a></li>");

    if(total_pages != 0){
        $(".pagination").append("<li class=\"page-item "+ disableLast +"\" value=\""+(total_pages)+"\"> <a class=\"page-link\" href=\"?page="+total_pages+ query +"\" "+ "onclick=\"return " +(disableLast != "" ? "false" : "setCurrentPageNum("+ total_pages +")") + "\" > "+total_pages+"<span class=\"sr-only\">Last</span></a></li>");
    }


}

function displayDocumentsList(response) {
    var query = "<?php (isset($this->query_str) && $this->query_str !== "") ? $this->query_str : ""?>";
    var address = "<?php echo getFullInciteUrl().'/documents/transcribe/'; ?>";


    $.each(response, function(){

            $('#list-view').append("<div id=\"list_id"+this.id+"\" style=\"margin: 10px; height: 45px;\"  data-toggle=\"popover\" data-trigger=\"hover\" data-html=\"true\"  data-content=\"<strong>Date:</strong> "+this.date+ "<br><br> <strong>Description:</strong> "+this.desc+"\" data-title=\"<strong>" + this.name + "</strong>\" data-placement=\"left\" data-id=\"" +this.id+ "\"> <a href =\"" + address + this.id + (query != "" ? "?" + query : "\">") + "<div style=\"height: 40px; width:40px; float: left;\"><img src=\""+this.url+"\" class=\"thumbnail img-responsive\" style=\"width: 40px; height: 40px;\"></div><div style=\"height: 40px; margin-left: 45px;\"><p style=\"height: 20px; width: 100%; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;\">"+ this.name+"</p></div></div>");

    addTaskCompletionIconsToResultsRow(this.id);
    });
    $('#list-view').prepend("<span style=\"width: 20px; background: #EEEEEE; margin-right: 5px;\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span>: Location on map unknown.</span><br>");


};


function generateTimeLine(){
    var i = 0;
    $.each(docs, function() {
        ev[i] = {'id': this.id, 'name': this.name, 'desc': this.desc, 'on':new Date(this.date)};
        i++;
    });

    buildTimeLine(ev);
}


function showListView() {
    $('#list-view').animate({ left: $(window).width()-$('#list-view').width() }, 'slow', function() {
        $('#list-view-switch').html('Hide');
    });
    $('#list-view-switch').one("click", hideListView);
}

function hideListView() {
    $('#list-view').animate({ left: $(window).width()-$('#list-view-switch').width()-5 }, 'slow', function() {
        $('#list-view-switch').html('Show');
    });
    $('#list-view-switch').one("click", showListView);
}

function buildTimeLine(evt) {
    $('#timeline').empty();
    tl = $('#timeline').jqtimeline({
        events : evt,
        numYears: (1880-1845),
        startYear: 1845,
        endYear: 1880,
        totalWidth: $('#timeline').width(),
        click:function(e,event){
            alert(event.desc);
        }});
    }





    function x() {
        $('[data-toggle="popover"]').popover({ trigger: "hover" });
        map = L.map('map-div').setView([37.8, -65], 4);
        L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
        var marker;

        if (docs){
            $.each(docs, function() {

                var lat_long_var = this.lat_long;
                if(lat_long_var.long && lat_long_var.lat){
                    marker = L.marker([lat_long_var.lat, lat_long_var.long]).addTo(map).bindPopup("\'"+ this.name +" in "+ this.loc + "\'");
                    markers_array.push({id: this.id, marker: marker});
                    marker_to_id[marker._leaflet_id] = this.id;
                    id_to_marker[this.id] = marker;

                }else{
                    nomarkers_array.push({id:this.id, marker:marker});
                }
            });
        }
    }

    function buildMap(){

        x();

        if(nomarkers_array){
            $.each(nomarkers_array, function (idx) {
                $('#list_id'+this['id']).addClass('no-map-marker');
            });
        }
        if(markers_array){
            $.each(markers_array, function (idx) {
                this['marker'].on('mouseover', function (e) {
                    $('div[data-id='+marker_to_id[this._leaflet_id]+']').popover('show');
                    this.openPopup();
                });
                this['marker'].on('mouseout', function (e) {
                    $('div[data-id='+marker_to_id[this._leaflet_id]+']').popover('hide');
                    this.closePopup();
                });
                this['marker'].on('click', function (e) {
                    this.openPopup();
                    window.location.href="/m4j/incite/documents/transcribe/"+marker_to_id[this._leaflet_id];
                });
            });
        }

        $('[data-toggle="popover"]').each( function (idx) {
            $(this).on('shown.bs.popover', function (e) {
                if (id_to_marker[this.dataset.id])
                id_to_marker[this.dataset.id].openPopup();
            });
            $(this).on('hidden.bs.popover', function (e) {
                if (id_to_marker[this.dataset.id])
                id_to_marker[this.dataset.id].closePopup();
            });
        });


        <?php

        if (isset($_SESSION['incite']['message'])) {

            if (strpos($_SESSION["incite"]["message"], 'Unfortunately') !== false) {
                echo "notifyOfRedirect('" . $_SESSION["incite"]["message"] . "');";
            } else {
                echo "notifyOfSuccessfulActionNoTimeout('" . $_SESSION["incite"]["message"] . "');";
            }

            unset($_SESSION['incite']['message']);
        }
        ?>
        buildPopoverContent();

    }

    function buildPopoverContent() {
        if(docs){
            $.each(docs, function () {
                var content = '';
                var date = this.date;
                var location = this.loc;
                var source = this.src;
                var contributor = this.contr;
                var rights = this.rights;

                if (date) {
                    content += '<strong>Date: </strong>' + date + '<br><br>';
                }

                if (location) {
                    content += '<strong>Location: </strong>' + location + '<br><br>';
                }

                if (source) {
                    content += '<strong>Source: </strong>' + source + '<br><br>';
                }

                if (contributor) {
                    content += '<strong>Contributor: </strong>' + contributor + '<br><br>';
                }

                if (rights) {
                    content += '<strong>Rights: </strong>' + rights + '<br><br>';
                } else {
                    content += '<strong>Rights: </strong>Public Domain<br><br>';
                }


                if (content) {
                    //cut off the last <br><br>
                    content = content.slice(0, -8);

                    $('#list_id'+this.id).attr('data-content', content);
                } else {
                    $('#list_id'+this.id).attr('data-content', "No available document information, sorry!");
                }

            });
        }
    }
    </script>


</div>
<!-- /.container -->

</body>

</html>
