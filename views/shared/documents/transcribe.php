
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


</script>

<!-- Page Content -->
<div id="task_description" style="text-align: center;">
  <h3 style="text-align: center;">Search Results of Transcribable Documents</h3>
  <span style="text-align: center;">You can mouse over the pins on the map or document thumbnails to see more details and click them to try transcribing the document!
  </span>
</div>
<div id="map-div" style="width:500px;"></div>
<div id="list-view" style="position: absolute; top: 80px; right: 0; left: 100px; width: 30%; height: 500px; background-color: white;">
  <div id="list-view-switch" style="cursor: pointer; border:1px solid; float: left; margin-right: 10px;">Show</div>
  <span style="width: 20px; background: #EEEEEE; margin-right: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span>: Location unknown.</span>
  <br>
  <div id = "document-list"></div>


  <!--  (first_page) (previous_page) (current_page-2) (current_page-1) (current_page) (current_page+1) (current_page+2) (next_page) (last_page). -->
  <div id="pagination-bar" class="text-center">
    <nav>
      <ul class="pagination">

        <!--  First page-->
        <li class="page-item <?php echo ($this->current_page == 1 ? "disabled" : ""); ?>"> <a class="page-link" href="?page=1<?php echo ($this->query_str == "" ? "" : "&".$this->query_str); ?>"
          <?php if ($this->current_page == 1) echo 'onclick="return false" ' ?>
          > 1<span class="sr-only">First</span></a></li>

          <!--  Previous Page-->
          <li class="page-item <?php echo ($this->current_page == 1 ? "disabled" : ""); ?>">
            <a class="page-link"  href="?page=<?php echo ($this->current_page-1). ($this->query_str == "" ? "" : "&".$this->query_str); ?>"
              <?php if ($this->current_page == 1) echo 'onclick="return false" ' ?> aria-label="Previous">
              <span aria-hidden="true">&laquo;</span></a></li>

              <?php

              if ($this->total_pages > 5  ){
                if($this->current_page < 3){
                  $start = 0;
                }else if($total_pages - $this->current_page < 2){
                  $start = $total_pages - 5;
                }
                else{
                  $start = $this->current_page - 3;
                }
              }
              else{
                $start = 0;
              }


              for ($i = $start; $i < $start+5; $i++): ?>

              <li class=" page-item <?php if ($this->current_page == ($i+1)) echo 'active'; ?>">
                <a class="page-link" href="?page=<?php echo ($i+1); ?><?php echo ($this->query_str == "" ? "" : "&".$this->query_str); ?>">
                  <?php echo ($i+1); ?>
                  <span class="sr-only">(current)</span></a></li>
                <?php endfor; ?>

                <!-- Next Page -->
                <li class=" page-item <?php echo ($total_pages == $this->current_page ? "disabled" : ""); ?>">
                  <a class="page-link" href="?page=<?php echo ($this->current_page+1).($this->query_str == "" ? "" : "&".$this->query_str); ?>" <?php if ($this->total_pages == $this->current_page) echo 'onclick="return false" ' ?>
                    aria-label="Next">
                    <span aria-hidden="true">&raquo;</span></a></li>

                    <!--  Last page -->
                    <li class="page-item <?php echo ($this->total_pages == $this->current_page ? "disabled" : ""); ?>">
                      <a class="page-link" href="?page=<?php echo $this->total_pages.($this->query_str == "" ? "" : "&".$this->query_str); ?>" <?php if ($this->total_pages == $this->current_page) echo 'onclick="return false" ' ?>> <?php echo $this->total_pages ?>
                        <span class="sr-only">Last</span></a></li>
                      </ul>
                    </nav>
                  </div>
                </div>

                <div id="timeline"></div>
                <div id="timeline-spacing" class="col-md-8" style="height:100px;"></div>

              </div>

              <script type="text/javascript">



              var ev, tl;
              ev = [];


              $('#map-div').ready( function (e) {
                $('#map-div').height($(window).height()-200);
              });

              $(document).ready( function (e) {

                getCurrentPageNumberRequest();



                $('#map-div').width($(window).width());
                $('#timeline').width($(window).width()-30);
                document.getElementById('list-view').style.top = ($('#map-div').offset().top+20)+'px';
                document.getElementById('list-view').style.left = ($(window).width()-$('#list-view-switch').width()-5)+'px';
                document.getElementById('list-view').style.height = ($('#map-div').height()-40)+'px';
                showListView();


                $(window).on('resize', function(e) {
                  $('#map-div').width($(window).width());
                  $('#timeline').width($(window).width()-30);
                  $('#map-div').height($(window).height()-200);
                  document.getElementById('list-view').style.left = ($(window).width()-$('#list-view-switch').width()-5)+'px';

                  document.getElementById('list-view').style.height = ($('#map-div').height()-40)+'px';
                  showListView();
                  buildTimeLine(ev);
                  //$('#list-view').width($(window).width()*0.15);
                });
                $('#list-view-switch').one('click', showListView);


              });

              function generateHashForTimeLine(){
                var i = 0;
                $.each(docs, function() {
                  ev[i] = {'id': this.id, 'name': this.name, 'desc': this.desc, 'on':new Date(this.date)};
                  i++;
                });
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

                function getCurrentPageNumberRequest() {
                  $.ajax({
                    url: "<?php echo getFullInciteUrl().'/ajax/getcurrpage'; ?>",
                    type: "GET",
                    dataType : "text",
                  })
                  .done(function(data) {
                    alert(data);
                    getTranscribableDocumentsRequest();
                  })
                  .fail(function( xhr, status, errorThrown ) {
                    alert( "Sorry, there was a problem!" );
                    console.log( "Error: " + errorThrown );
                    console.log( "Status: " + status );
                    console.dir( xhr );
                  })
                };


                function getTranscribableDocumentsRequest() {
                  var request = $.ajax({
                    type: "POST",
                    dataType:"json",
                    url: "<?php echo getFullInciteUrl().'/ajax/getdocuments'; ?>",
                    data: {"width": $(window).width(), "height": $(window).height()},
                    success: function (response)
                    {
                      if(response.length == 0){
                        // alert("empty");
                        return;
                      } else {
                        docs = jQuery.extend(true, [], response);
                        displayDocumentsList(response);
                        buildMap();
                        generateHashForTimeLine();
                        buildTimeLine(ev);

                      }
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



                function displayDocumentsList(response) {

                  var buffer="";
                  $.each(response, function() {
                    buffer += "<div id=\"list_id"+this.id+"\" style=\"margin: 10px;\"  data-toggle=\"popover\" data-trigger=\"hover\" data-html=\"true\"  data-content=\"<strong>Date:</strong> "+this.date+ "<br><br> <strong>Description:</strong> "+this.desc+"\" data-title=\"<strong>" + this.name + "</strong>\" data-placement=\"left\" data-id=\"" +this.id+ "\">";

                    <?php if (isset($this->query_str) && $this->query_str !== ""): ?>
                    var query = "<?php echo $this->query_str; ?>";
                    var address = "<?php echo getFullInciteUrl().'/documents/transcribe/'; ?>";

                    buffer += "<a href=\""+ address +this.id+"?" + query +"\">";

                    <?php else: ?>

                    buffer += "<a href=\""+ address +this.id+ "\">";

                    <?php endif ?>

                    buffer += "<div style=\"height: 40px; width:40px; float: left;\"><img src=\""+this.uri+"\" class=\"thumbnail img-responsive\" style=\"width: 40px; height: 40px;\"></div><div style=\"height: 40px; margin-left: 45px;\"><p style=\"\">"+this.name+"</p></div></a></div>";
                  });

                  $(buffer).insertAfter('#document-list');
                };


                function x() {
                  $('[data-toggle="popover"]').popover({ trigger: "hover" });
                  map = L.map('map-div').setView([37.8, -65], 4);
                  L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
                  }).addTo(map);
                  var marker;


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

                function buildMap(){

                  x();

                  $.each(nomarkers_array, function (idx) {
                    $('#list_id'+this['id']).addClass('no-map-marker');
                  });

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
                </script>


              </div>
              <!-- /.container -->

            </body>

            </html>
