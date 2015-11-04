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

    <?php echo js_tag('jquery'); ?>
    <?php echo head_css(); ?>
    <title>Mapping the 4th Static Page</title>

    <!-- Bootstrap Core CSS -->

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
                <a class="navbar-brand" href="./documents/discover">Discover</a>
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

        <!-- /.row -->

        <div class="row"> 
            <div class="col-lg-12 text-center" style="margin-bottom: 30px;">
                <h1>Welcome to Mapping The Fourth!</h1>
                <p class="lead"> Help us better understand the history of our country.</p>

                <p style="margin-bottom: 20px;"> The long crisis of the Civil War, stretching from the 1840s to the 1870s, forced Americans to confront difficult questions about the meaning and the boundaries of their nation. What did it mean to be an American? Who was included and excluded? Where did the nation’s borders lie? But it was on one particular day each year—July 4—that they left the most explicit evidence of their views. In newspapers and speeches, in personal diaries and letters to their friends and family, Americans gave voice to typically unspoken beliefs about national identity.</p>
                <!-- <a class="btn" href="" style="" id="large-btn">Get Started</a> -->
            </div> 
        </div>

        <div class="row">
            <div class="col-lg-12 text-center" style="margin-bottom: 30px;">
                <p>
                    We found a few documents close to <a> Blacksburg</a>. Or You can tell us anything you have in mind about civil war below and see what we can find for you! 
                </p>
                <form class="form-wrapper" >
                    <input type="text" id="search" placeholder="Search for Interesting Places!" required>
                    <input type="submit" value="Search" id="submit">
                </form>  
            </div>
        </div>

        <div class="row">
            <div>
                <div class="col-lg-12 text-center" style="margin-bottom: 30px;">
                    <h2> Here are a list of projects that others are working on!</h2>
                </div>
                <p style="margin-left:1em">Sort by: <a href="">completion</a>-<a href="">types</a>-<a href="">time</a>-<a href="">last updated</a> </p>
            </div>
        </div>

<div class="row">
    <div class="col-lg-2 col-sm-3 col-xs-4">
        <a href="#" data-toggle="popover" title="Popover Header" data-content="Some content inside the popover">
             <img src="http://www.placecage.com/200/200" class="thumbnail img-responsive">
        </a>
        <h4 style="text-align: center;"><?php echo metadata($this->Item, array('Dublin Core', 'Title')); ?> </h4>
        <p style="text-align: center;"> <?php echo metadata($this->Item, array('Dublin Core', 'Description')); ?> </p>
        <div class="progress">
            <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 45%">
                <span class="sr-only">45% Complete</span>
            </div>
        </div>
    </div>
    


<?php 

//print_r(metadata($this->Item, array('Dublin Core', 'Title'), array('all' => true))); 
//echo $this->Item;

?>

  
</div>

    </div>
    <!-- /.container -->

    <!-- jQuery Version 1.11.1 -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
    <script>
$(document).ready(function(){
    $('[data-toggle="popover"]').popover({ trigger: "hover" });
});
</script>

</body>

</html>
