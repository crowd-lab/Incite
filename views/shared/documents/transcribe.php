<?php

/**
    Input: Location from Item Type Metadata (Format: State - County - City or "State - City Indep. City")
    Output: array including "lat" and "long".
 */
function loc_to_lat_long($loc_str)
{
    $states = array(
        'Alabama'=>'AL',
        'Alaska'=>'AK',
        'Arizona'=>'AZ',
        'Arkansas'=>'AR',
        'California'=>'CA',
        'Colorado'=>'CO',
        'Connecticut'=>'CT',
        'Delaware'=>'DE',
        'Florida'=>'FL',
        'Georgia'=>'GA',
        'Hawaii'=>'HI',
        'Idaho'=>'ID',
        'Illinois'=>'IL',
        'Indiana'=>'IN',
        'Iowa'=>'IA',
        'Kansas'=>'KS',
        'Kentucky'=>'KY',
        'Louisiana'=>'LA',
        'Maine'=>'ME',
        'Maryland'=>'MD',
        'Massachusetts'=>'MA',
        'Michigan'=>'MI',
        'Minnesota'=>'MN',
        'Mississippi'=>'MS',
        'Missouri'=>'MO',
        'Montana'=>'MT',
        'Nebraska'=>'NE',
        'Nevada'=>'NV',
        'New Hampshire'=>'NH',
        'New Jersey'=>'NJ',
        'New Mexico'=>'NM',
        'New York'=>'NY',
        'North Carolina'=>'NC',
        'North Dakota'=>'ND',
        'Ohio'=>'OH',
        'Oklahoma'=>'OK',
        'Oregon'=>'OR',
        'Pennsylvania'=>'PA',
        'Rhode Island'=>'RI',
        'South Carolina'=>'SC',
        'South Dakota'=>'SD',
        'Tennessee'=>'TN',
        'Texas'=>'TX',
        'Utah'=>'UT',
        'Vermont'=>'VT',
        'Virginia'=>'VA',
        'Washington'=>'WA',
        'West Virginia'=>'WV',
        'Wisconsin'=>'WI',
        'Wyoming'=>'WY');

    //mostly only use state and city but in case of no such city, we use county instead
    $elem  = explode("-", $loc_str);
    $state = "";
    $city  = "";
    $county = "";

    //Parse state and city names
    if (count($elem) >= 3) { //currently ignore extra info about location. Item 11 is an exception here!
        $state_index = trim(str_replace('State', '', str_replace('state', '', $elem[0])));
        if (!isset($states[$state_index]))
            return array('lat' => '37.23', 'long' => '-80.4178');
        $state  = $states[$state_index];
        $city   = trim($elem[2]);
        $county = trim(str_replace('County', '', $elem[1]));
    } else if (count($elem) == 2) {
        $state = $states[trim(str_replace('State', '', str_replace('state', '', $elem[0])))];
        $city  = strstr(trim($elem[1]), ' Indep.', true);
        if ($city == "")
            $city = trim($elem[1]);
    } else {
        //Should send to log and to alert new format of location!
    }

    //Convert state and city to lat and long
    $result = array();
    $latlong_file = fopen('./plugins/Incite/zip_codes_states.csv', 'r') or die('no zip file!');

    while (($row = fgetcsv($latlong_file)) != FALSE) {
        //Just use the last result as our county guess
        if ($county == $row[5] && $state == $row[4]) {
            $result['lat']  = $row[1];
            $result['long'] = $row[2];
        }
        //Just use the first result as our final result!
        if ($city == $row[3] && $state == $row[4]) {
            $result['lat']  = $row[1];
            $result['long'] = $row[2];
            break;
        }
    }
    fclose($latlong_file);

    return $result;
}


?>
<!DOCTYPE html>
<html lang="en">
<?php
$task = "transcribe";
include(dirname(__FILE__).'/../common/header.php');
?>

<style>
    .no-map-marker {
        background-color: gray;
    }
</style>

<script type="text/javascript">
    var map;
    var msgbox;
    var markers_array = [];
    var nomarkers_array = [];
    var marker_to_id = {};
    var id_to_marker = {};
    function x() {
        $('[data-toggle="popover"]').popover({ trigger: "hover" });
        map = L.map('map-div').setView([37.8, -65], 4);
          L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
              attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
          }).addTo(map);
        var marker;
<?php
    foreach((array)$this->Transcriptions as $transcription) {
        $lat_long = loc_to_lat_long(metadata($transcription, array('Item Type Metadata', 'Location')));
        if (count($lat_long) > 0) {
            echo 'marker = L.marker(['.$lat_long['lat'].','.$lat_long['long']."]).addTo(map).bindPopup('".trim(strip_tags(metadata($transcription, array('Dublin Core', 'Title'))))." in ".metadata($transcription, array('Item Type Metadata', 'Location'))."');\n";
            echo 'markers_array.push({id:'.$transcription->id.', marker: marker});';
            echo 'marker_to_id[marker._leaflet_id]='.$transcription->id.';';
            echo 'id_to_marker['.$transcription->id.']= marker;';
        } else {
            echo 'nomarkers_array.push({id:'.$transcription->id.', marker: marker});';
        }
    } ?> }

</script>

    <!-- Page Content -->
    <div id="task_description" style="text-align: center;">
        <h3 style="text-align: center;">Search Results of Transcribable Documents</h3>
        <span style="text-align: center;">You can mouse over the pins on the map or document thumbnails to see more details and click them to try transcribing the document!
        </span>
    </div>
    <div id="map-div" style="width:500px;"></div>
    <div id="list-view" style="position: absolute; top: 80px; right: 0; left: 100px; width: 30%; height: 500px; background-color: white;">
        <div id="list-view-switch" style="cursor: pointer; border:1px solid; float: left;">Show</div>
        <br>
<?php foreach ((array)$this->Transcriptions as $transcription): ?>
        <div id="list_id<?php echo $transcription->id; ?>" style="margin: 10px;" 
            data-toggle="popover" data-trigger="hover" data-html="true"
            data-content="<?php echo "<strong>Date:</strong> " 
                        . metadata($transcription, array('Dublin Core', 'Date'))
                        . "<br><br> <strong>Description:</strong> "
                        . metadata($transcription, array('Dublin Core', 'Description')); ?>" 
            data-title="<?php echo "<strong>" . metadata($transcription, array('Dublin Core', 'Title')) . "</strong>";?>"
            data-placement="left" data-id="<?php echo $transcription->id; ?>" 
        >
<?php if (isset($this->query_str) && $this->query !== ""): ?>
            <a href="<?php echo getFullInciteUrl().'/documents/transcribe/'.$transcription->id."?".$this->query_str; ?>">
<?php else: ?>
            <a href="<?php echo getFullInciteUrl().'/documents/transcribe/'.$transcription->id; ?>">
<?php endif; ?>
                <div style="height: 40px; width:40px; float: left;">
                    <img src="<?php echo $transcription->getFile()->getProperty('uri'); ?>" class="thumbnail img-responsive" style="width: 40px; height: 40px;">    
                </div>
                <div style="height: 40px; margin-left: 45px;">
                    <p style=""><?php echo metadata($transcription, array('Dublin Core', 'Title')); ?></p>
                </div>
            </a>
        </div>
<?php endforeach; ?>
        <div id="pagination-bar" class="text-center">
            <nav>
              <ul class="pagination">
                <li class="<?php echo ($this->current_page == 1 ? "disabled" : ""); ?>"><a <?php echo ($this->current_page == 1 ? "" : 'href="?page='.($this->current_page-1)); ?><?php echo ($this->query_str == "" ? "" : "&".$this->query_str); ?>" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
<?php for ($i = 0; $i < $this->total_pages; $i++): ?>
                <li class="<?php if ($this->current_page == ($i+1)) echo 'active'; ?>"><a href="?page=<?php echo ($i+1); ?><?php echo ($this->query_str == "" ? "" : "&".$this->query_str); ?>"><?php echo ($i+1); ?><span class="sr-only">(current)</span></a></li>
<?php endfor; ?>
                <li class="<?php echo ($this->total_pages == $this->current_page ? "disabled" : ""); ?>"><a <?php echo ($this->current_page == $this->total_pages ? "" : 'href="?page='.($this->current_page+1)); ?><?php echo ($this->query_str == "" ? "" : "&".$this->query_str); ?>" aria-label="Next"><span aria-hidden="true">»</span></a></li>
              </ul>
            </nav>
        </div>
    </div>
    <div id="timeline"></div>
    <div id="timeline-spacing" class="col-md-8" style="height:100px;"></div>

                     
    </div>
    <script type="text/javascript">
        var ev, tl;
            ev = [
        <?php for ($i = 0; $i < count($this->Transcriptions); $i++): ?>
                    {
                        id : <?php echo $i; ?>,
                        name : "<?php echo trim(metadata($this->Transcriptions[$i], array('Dublin Core', 'Title'))); ?>",
                        desc : "<?php echo trim(metadata($this->Transcriptions[$i], array('Dublin Core', 'Description'))); ?>",
                        on : new Date("<?php echo trim(metadata($this->Transcriptions[$i], array('Dublin Core', 'Date'))); ?>")
                    },
        <?php endfor; ?>
            ];
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

        $('#map-div').ready( function (e) {
            $('#map-div').height($(window).height()-200);
        });

        $(document).ready( function (e) {
            $('#map-div').width($(window).width());
            $('#timeline').width($(window).width()-30);
            document.getElementById('list-view').style.top = ($('#map-div').offset().top+20)+'px';
            document.getElementById('list-view').style.left = ($(window).width()-$('#list-view-switch').width()-5)+'px';
            document.getElementById('list-view').style.height = ($('#map-div').height()-40)+'px';
            showListView();
            buildTimeLine(ev);
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

        });
</script>


    </div>
    <!-- /.container -->

</body>


</html>
