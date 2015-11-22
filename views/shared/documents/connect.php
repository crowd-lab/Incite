<!DOCTYPE html>
<html lang="en">

<?php
include(dirname(__FILE__).'/../common/header.php');
?>

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
<?php foreach ($this->Connections as $connection): ?>
    <div class="col-lg-2 col-sm-3 col-xs-4">
        <a href="<?php echo 'connect/'.$connection->id; ?>" data-toggle="popover" title="Popover Header" data-content="Some content inside the popover">
             <img src="<?php echo $connection->getFile()->getProperty('uri'); ?>" class="thumbnail img-responsive">
        </a>
        <h4 style="text-align: center;"><?php echo metadata($connection, array('Dublin Core', 'Title')); ?></h4>
        <p style="text-align: center;"> <?php echo metadata($connection, array('Dublin Core', 'Description')); ?> </p>
        <div class="progress">
            <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%">
                <span class="sr-only">60% Complete</span>
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

              <div style="margin-top: 5em;">
                <p>Here's what other people are working on: </p>
              </div>
              <ul style="list-style-type:none">
                <li>
                    <p>1) User1 just found a diary from Emma LeConte in 1840! </p> 
                </li>
                <li>
                    <p>2) User2 is decoding a mysterious historical document allegedly from South.  </p> 
                </li>
                <li>
                    <p>3) User3 found a new document talking about Nationalism. </p> 
                </li>
                <li>
                    <p>4) User4 is figuring out what the 3 documents have in common.</p> 
                </li>
              </ul>
        </div>
            
</div>

    </div>
    <!-- /.container -->

    <script>
    $(document).ready(function(){
        $('[data-toggle="popover"]').popover({ trigger: "hover" });
    });
    </script>
</body>

</html>
