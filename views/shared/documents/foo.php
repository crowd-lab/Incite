<!DOCTYPE html>
<html lang="en">

<head>



    <?php
queue_css_file(array('bootstrap', 'style', 'bootstrap.min', 'leaflet'));
queue_js_file(array('leaflet', 'jquery'));
$db = get_db();

include(dirname(__FILE__).'/../common/header.php');
?>


</head>

<body>

<div class="row">
    <div class="col-md-4" style="padding-left:40px; ">
        <div class="row">
           <div id="custom-search-input">
                            <div class="input-group col-md-12">
                                <input type="text" class="  search-query form-control" placeholder="Search" />
                                <span class="input-group-btn">
                                    <button class="btn btn-danger" type="button">
                                        <span class=" glyphicon glyphicon-search"></span>
                                    </button>
                                </span>
                            </div>
                        </div>
        </div>
        
        <div class="row" id="content">
            <h3>Documents with mentioned entities</h3>
            <ul>
                <li><p>(Nor...,Sou...,G..) Affairs in New Kent - <a>add to reference</a></p></li>
                <li><p>(Nor...,Sou...,G..) Independence Day - <a>add to reference</a></p></li>
                <li><p>(Nor...,Sou...,G..) Affairs in New Kent - <a>add to reference</a></p></li>
            </ul>
            <h3>Other Documents</h3>
            <ul>
                <li>Doc 1 - <a>add to reference</a></li>
                <li>Doc 2 - <a>add to reference</a></li>
            </ul> 
        </div>
    </div>
    <div class="col-md-8">
        <div class="row" id="content">
            <h3>Create a New Discussion</h3>
            <form class="form-wrapper" >
                Title: <input type="text" style="margin-bottom: 10px;" id="search1" placeholder="How do Northerners vs Southerners write...." required>
            </form>
            <p>Content: </p>
            <form class="form-wrapper">
                <textarea rows="15" cols="100"> </textarea>
            </form>


            <h4>References: </h4>
            <div id="images" style="white-space: nowrap;">
                <img src="https://www.gravatar.com/avatar/9f0fbed7dce3692d69b981b3b7bcbf40?s=32&d=identicon&r=PG&f=1" alt=""/>
                <img src="https://www.gravatar.com/avatar/9f0fbed7dce3692d69b981b3b7bcbf40?s=32&d=identicon&r=PG&f=1" alt="" />
                <img src="https://www.gravatar.com/avatar/9f0fbed7dce3692d69b981b3b7bcbf40?s=32&d=identicon&r=PG&f=1" alt="" />
            </div>
            <br>
            <button type="button" class="btn btn-primary">Submit</button>
        </div>
    </div>
</div>


</body>