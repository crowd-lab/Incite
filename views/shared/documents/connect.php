
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
include(dirname(__FILE__).'/../common/header.php');
?>


<script type="text/javascript">
    function x() {
        $('[data-toggle="popover"]').popover({ trigger: "hover" });
        var map = L.map('map-div').setView([37.8, -96], 4);
          L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
              attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
          }).addTo(map);
<?php
    foreach((array)$this->Connections as $connection) {
        $lat_long = loc_to_lat_long(metadata($connection, array('Item Type Metadata', 'Location')));
        if (count($lat_long) > 0) {
            echo 'L.marker(['.$lat_long['lat'].','.$lat_long['long']."]).addTo(map).bindPopup('".metadata($connection, array('Dublin Core', 'Title'))."');\n";
        }
    }
?>
    }

</script>

    <!-- Page Content -->
    <div class="container">

        <div class="row">
             
        </div>
        <!-- /.row -->

        <div class="row">
            <p style="margin-left:0.5em;  display:inline-block;">Sort by: <a href="">completion</a>-<a href="">types</a>-<a href="">time</a>-<a href="">last updated</a> 
                <form style=" display:inline-block; margin-left:27em;" action="">
                        <input type="checkbox" name="vehicle" value="Bike"> - Map+Timeline
                </form>
            </p>


    <div id="map-div" class="col-md-8"></div>
    <div class="col-md-4">
<?php $cf = 1; ?>
<?php foreach ((array)$this->Connections as $connection): ?>
        <div class="col-md-12">
            <div class="col-md-3">
                    <a href="<?php echo 'connect/'.$connection->id; ?>">
                    <img src="<?php echo $connection->getFile()->getProperty('uri'); ?>" class="thumbnail img-responsive" style="width: 40px; height: 40px;">
                    </a>
            </div>
            <div class="col-md-9">
                <p style=""><?php echo metadata($connection, array('Dublin Core', 'Title')); ?></p>
            </div>
        </div>
    <?php if ($cf > 0 && $cf % 2 == 0): ?>
        <div class="clearfix"></div>
    <?php endif; ?>
    <?php $cf++; ?>
<?php endforeach; ?>
    </div>
    <div id="timeline" class="col-md-8"></div>
    <div class="col-md-4 text-center">
        <nav>
          <ul class="pagination">
            <li class="disabled"><a href="#" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
<?php for ($i = 0; $i < $this->total_pages; $i++): ?>
            <li class="<?php if ($this->current_page == ($i+1)) echo 'active'; ?>"><a href="?page=<?php echo ($i+1); ?>"><?php echo ($i+1); ?><span class="sr-only">(current)</span></a></li>
<?php endfor; ?>
            <li><a href="#" aria-label="Next"><span aria-hidden="true">»</span></a></li>
          </ul>
        </nav>
    </div>
    </div>
    <div id="timeline-spacing" class="col-md-8" style="height:100px;"></div>

                     
</div>
    <script type="text/javascript">

    $('#map-div').ready( function (e) {
        $('#map-div').height($('#map-div').width()/2);
    });

$(document).ready( function (e) {
    var ev = [
<?php for ($i = 0; $i < count($this->Connections); $i++): ?>
            {
                id : <?php echo $i; ?>,
                name : "<?php echo metadata($this->Connections[$i], array('Dublin Core', 'Title')); ?>",
                desc : "<?php echo metadata($this->Connections[$i], array('Dublin Core', 'Description')); ?>",
                on : new Date("<?php echo metadata($this->Connections[$i], array('Dublin Core', 'Date')); ?>")
            },
<?php endfor; ?>
    ]

var tl = $('#timeline').jqtimeline({
                            events : ev,
							numYears: (1880-1845),
							startYear: 1845,
                            endYear: 1880,
                            totalWidth: $('#timeline').width(),
                            click:function(e,event){
                                alert(event.desc);
                            }
                        });
            x();

});
</script>


    </div>
    <!-- /.container -->

</body>


</html>
