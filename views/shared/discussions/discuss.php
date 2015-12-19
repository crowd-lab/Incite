<!DOCTYPE html>
<html lang="en">
<?php
include(dirname(__FILE__).'/../common/header.php');
?>


<body>
    <!-- Page Content -->
    <div class="container">


        <div class="row">
            <p style="margin-left:0.5em;  display:inline-block;">Sort by: <a href="">completion</a>-<a href="">types</a>-<a href="">time</a>-<a href="">last updated</a> 
                <form style=" display:inline-block; margin-left:27em;" action="">
                        <input type="checkbox" name="vehicle" value="Bike"> - Map+Timeline
                </form>
            </p>


    <div class="col-md-4 col-md-offset-4">
<?php $cf = 1; ?>
<?php foreach ((array)$this->Discussions as $discussion): ?>
        <div class="col-md-12">
            <div class="col-md-3">
                    <a href="<?php echo 'transcribe/'.$transcription->id; ?>">
                    <img src="<?php echo $transcription->getFile()->getProperty('uri'); ?>" class="thumbnail img-responsive" style="width: 40px; height: 40px;">
                    </a>
            </div>
            <div class="col-md-9">
                <p style=""><?php echo metadata($transcription, array('Dublin Core', 'Title')); ?></p>
            </div>
        </div>
    <?php if ($cf > 0 && $cf % 2 == 0): ?>
        <div class="clearfix"></div>
    <?php endif; ?>
    <?php $cf++; ?>
<?php endforeach; ?>
    </div>
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

<!--
<?php foreach ((array)$this->Transcriptions as $transcription): ?>
    <div class="col-md-4">
        <div class="thumbnail">
             <a href="<?php echo 'transcribe/'.$transcription->id; ?>">
                <img src="<?php echo $transcription->getFile()->getProperty('uri'); ?>" class="thumbnail img-responsive" style="width: 300px; height: 300px;">
            </a>
            <h3 style="text-align: center;"><?php echo metadata($transcription, array('Dublin Core', 'Title')); ?></h3>
            <p style="text-align: center;"> <?php echo metadata($transcription, array('Dublin Core', 'Description')); ?> </p>
            <p style="text-align: center;"> <?php echo metadata($transcription, array('Item Type Metadata', 'Location')); ?> </p>
        </div>
    </div>
<?php endforeach; ?>
-->
                     
</div>
    <script type="text/javascript">

    $('#map-div').ready( function (e) {
        $('#map-div').height($('#map-div').width()/2);
    });

$(document).ready( function (e) {
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

});
</script>


    </div>
    <!-- /.container -->

</body>


<
