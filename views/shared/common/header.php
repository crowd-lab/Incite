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
                <a class="navbar-brand" href="/m4j/incite">Incite from Mapping the Fourth</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<form class="navbar-form navbar-left" role="search">
					<div class="form-group">
						<input type="text" class="form-control" placeholder="Location" name="location">
						<input type="text" class="form-control" placeholder="Time" name="time">
						<input type="text" class="form-control" placeholder="Keywords" name="keywords">
						<select class="form-control" name="task">
							<option value="">Choose a task</option>
							<option value="transcribe">Transcribe</option>
							<option value="tag">Tag</option>
							<option value="connect">Connect</option>
						</select>
					</div>
					<button type="submit" class="btn btn-default">Discover</button>
			    </form>
				<!-- discussion
                <ul class="nav navbar-nav">
                    <li>
                        <a href="/m4j/incite/documents/transcribe">Transcribe</a>
                    </li>
                    <li>
                        <a href="/m4j/incite/documents/tag">Tag</a>
                    </li>
                    <li>
                        <a href="/m4j/incite/documents/connect">Connect</a>
                    </li>
                    <li>
                        <a href="/m4j/incite/discussions">Discuss</a>
                    </li>
                    <li> 
                        <a href="./discussions">Discuss</a>
                    </li>
					-->
					<!-- discover
                    <li> 
                        <a href="./discover">Discover</a>
                    </li>
					-->
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>
