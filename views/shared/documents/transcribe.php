<!DOCTYPE html>
<html lang="en">
<?php
include(dirname(__FILE__).'/../common/header.php');
?>

<style type='text/css'>
    #map-div {
    width:600px;
    height:300px;
    }
</style>

<script type="text/javascript">
    function x() {
        $('[data-toggle="popover"]').popover({ trigger: "hover" });
        var map = L.map('map-div').setView([37.8, -96], 4);
          L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
              attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
          }).addTo(map);
          L.marker([38.907192, -77.036871]).addTo(map);
    }

</script>

<body onload="x();  ">
    <!-- Page Content -->
    <div class="container">

        <div class="row">
             
        </div>
        <!-- /.row -->

        <div class="row">
            <h1 style="text-align:center;">Your Contributions</h1>
            <p style="margin-left:0.5em;  display:inline-block;">Sort by: <a href="">completion</a>-<a href="">types</a>-<a href="">time</a>-<a href="">last updated</a> 
                <form style=" display:inline-block; margin-left:27em;" action="">
                        <input type="checkbox" name="vehicle" value="Bike"> - Map+Timeline
                </form>
            </p>


    <div id="map-div" style="width:600px;
    height:300px;"></div>
    
    <div id="timeline" style="width:1000px;"></div>
    <div id="" style="width:1000px; height:100px;"></div>


<?php foreach ($this->Transcriptions as $transcription): ?>
    <div class="col-lg-2 col-sm-3 col-xs-4">
        <a href="<?php echo 'transcribe/'.$transcription->id; ?>" data-toggle="popover" title="Popover Header" data-content="Some content inside the popover">
             <img src="<?php echo $transcription->getFile()->getProperty('uri'); ?>" class="thumbnail img-responsive">
        </a>
        <h4 style="text-align: center;"><?php echo metadata($transcription, array('Dublin Core', 'Title')); ?></h4>
        <p style="text-align: center;"> <?php echo metadata($transcription, array('Dublin Core', 'Description')); ?> </p>
        <div class="progress">
            <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 45%">
                <span class="sr-only">45% Complete</span>
            </div>
        </div>
    </div>
<?php endforeach; ?>

        <div class="col-lg-4">
             <form class="form-wrapper" >
                    <input type="text" style="margin-bottom: 10px;" id="search1" placeholder="Keywords" required>
            </form>
            <form class="form-wrapper" >
                    <input type="text" style="margin-bottom: 10px;" id="search1" placeholder="Locations" required>
            </form>
            <div class="two-col">
                <div class="col1">
                   <form class="form-wrapper" >
                        <input type="text" id="company" placeholder="Date 1"/> 
                   </form>
                </div>
              <div class="col2">
                   <form class="form-wrapper" >
                        <input type="text" id="company" placeholder="Date 2"/>
                   </form>
              </div>

        </div>
                     
</div>
    <script type="text/javascript">
    var ev = [
<?php for ($i = 0; $i < count($this->Transcriptions); $i++): ?>
            {
                id : <?php echo $i; ?>,
                name : "<?php echo metadata($this->Transcriptions[$i], array('Dublin Core', 'Title')); ?>",
                desc : "<?php echo metadata($this->Transcriptions[$i], array('Dublin Core', 'Description')); ?>",
                on : new Date("<?php echo metadata($this->Transcriptions[$i], array('Dublin Core', 'Date')); ?>")
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
</script>


    </div>
    <!-- /.container -->

</body>

    <!-- jQuery Version 1.11.1 -->



    <!-- Bootstrap Core JavaScript -->


</body>

</html>
