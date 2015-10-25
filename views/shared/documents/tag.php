<!DOCTYPE html>
<html lang="en">
<?php
queue_css_file(array('bootstrap', 'style', 'bootstrap.min'));
$db = get_db();

?>

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Mapping the 4th</title>
	<?php echo js_tag('jquery'); ?>
	<?php echo js_tag('bootstrap.min'); ?>
    <?php echo head_css(); ?>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
    body {
        padding-top: 70px;
        /* Required padding for .navbar-fixed-top. Remove if using .navbar-static-top. Change if height of navigation changes. */
    }
    </style>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.html">Discover</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li>
                        <a href="transcribe.html">Transcribe</a>
                    </li>
                    <li>
                        <a href="tag.html">Tag</a>
                    </li>
                    <li>
                        <a href="connect.html">Connect</a>
                    </li>
                    <li> 
                        <a href="discuss.html">Discuss</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

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
<?php foreach ($this->Tags as $tag): ?>
    <div class="col-lg-2 col-sm-3 col-xs-4">
        <a href="<?php echo 'tag/'.$tag->id; ?>" data-toggle="popover" title="Popover Header" data-content="Some content inside the popover">
             <img src="<?php echo $tag->getFile()->getProperty('uri'); ?>" class="thumbnail img-responsive">
        </a>
        <h4 style="text-align: center;"><?php echo metadata($tag, array('Dublin Core', 'Title')); ?></h4>
        <p style="text-align: center;"> <?php echo metadata($tag, array('Dublin Core', 'Description')); ?> </p>
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

    <!-- jQuery Version 1.11.1 -->
    <script src="js/jquery.js"></script>

        <script>
$(document).ready(function(){
    $('[data-toggle="popover"]').popover({ trigger: "hover" });
});
</script>
    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

</body>

</html>
