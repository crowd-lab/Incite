<!DOCTYPE html>
<html lang="en">
<?php
queue_css_file(array('bootstrap', 'style', 'bootstrap.min'));
$db = get_db();

include(dirname(__FILE__).'/../common/header.php');
?>


    <!-- Page Content -->
    <div class="container">
        <div class="second header">
            <!-- Primary Navbar -->
            <div class="navbar navbar-inverse" style="margin-right:-40px;">
              <div class="container">
                <div class="navbar-header">
                  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                  </button>
                  <a class="navbar-brand" href="#"><?php echo metadata($this->connection, array('Dublin Core', 'Title')); ?></a>
                </div>
                <div class="nav navbar-nav navbar-right"> 
                    <ul class="nav navbar-nav">
                        <li ><a href="transcription_document.html">Transcribe</a></li>
                        <li class="active"><a href="#about">Tag</a></li>
                        <li><a href="#contact">Menu Item 2</a></li>
                        <li><a href="#about">Menu Item 3</a></li>
                    </ul>
                </div> 
              </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="progress">
                    <div class="progress-bar" role="progressbar" aria-valuenow="60"
                    aria-valuemin="0" aria-valuemax="100" style="width:60%">
                    60%
                </div>
            </div>
            </div>
            <div class="col-md-4">
                <button type="button" class="btn btn-default">Subscribe</button>
                <button type="button" class="btn btn-default">Guide</button>
            </div>
        </div>
        <div class="container">
            <div class="col-md-8">
                <textarea name="transcribe_text" cols="97" rows="40" id="transcribe_copy"><?php echo $this->transcription; ?></textarea>
                <img id="document_img" src="<?php echo $this->connection->getFile()->getProperty('uri'); ?>" alt="Mountain View" style="max-width:604px;max-height:800px;">

                <br>
                <button type="button" class="btn btn-default" id="show">Document</button>
                <button type="button" class="btn btn-default" id="hide">Transcription</button>
            </div>
            <div class="col-md-4">
      
            <h2>Does this document talk about <a href="" data-toggle="popover" title="Definition" data-content="<?php echo $this->subject_definition; ?>"><?php echo $this->subject; ?></a></h2>
			<form action="post">
				<button type="submit" class="btn btn-default" name="connection" value="true">Yes</button>
				<button type="submit" class="btn btn-default" name="connection" value="false">No</button>
			</form>
            <p>It mentions (<?php echo implode(', ', $this->entities);  ?>) and so do the following three documents.</p>
<?php foreach($this->related_documents as $document): ?>
        <div class="">
            <a href="#" data-toggle="popover" title="Popover Header" data-content="Some content inside the popover">
                 <img src="<?php echo $document->getFile()->getProperty('uri'); ?>" class="thumbnail img-responsive">
            </a>
            <h4 style=""><?php echo metadata($document, array('Dublin Core', 'Title')); ?></h4>
        </div>
<?php endforeach; ?>
    </div>
            </div> 
        </div>




  
</div>

    </div>
    <!-- /.container -->

    <!-- jQuery Version 1.11.1 -->

    <!-- Bootstrap Core JavaScript -->
    <script>
$(document).ready(function(){
    $('[data-toggle="popover"]').popover({ trigger: "hover" });
        $("#document_img").hide();
    $("#hide").click(function(){
        $("#document_img").hide();
        $("#transcribe_copy").show();
    });
    $("#show").click(function(){
        $("#document_img").show();
        $("#transcribe_copy").hide();
    });
});
</script>

</body>

</html>
