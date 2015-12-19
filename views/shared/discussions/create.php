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
        <div class="col-md-6" id="work-zone">
            <div style="position: fixed; width: 35%;" id="work-view">
            </div>
        </div>
        <div class="col-md-5">
            <div class="row btn-group" data-toggle="buttons">
              <label class="btn btn-primary">
                <input type="radio" name="options" id="option1" autocomplete="off" value="exist"> View existing discussions
              </label>
              <label class="btn btn-primary active">
                <input type="radio" name="options" id="option2" value="new" autocomplete="off" checked> Create a new discussion
              </label>
            </div>

            <div class="row" id="content-1">
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
            <div class="row" id="content-2">
                <h3> Subjects of this document: <a href="">Nationalism</a>, 
                    <a href="">Freedom</a>, 
                    <a href="">Revolution</a> </h3>
                <h3>Related Discussions: </h3>
                <p>Number: <a href="">URL</a></p>

            </div>
        </div>
    </div>
<script type="text/javascript">
    $('.btn-group .btn').on('click',function(){
        if($('input[name=options]:checked').val() == 'exist') {
            $("#content-2").hide(); 
            $("#content-1").show(); 
        }
        else {
            $("#content-1").hide(); 
            $("#content-2").show(); 
        }
    });
    $("#content-2").hide(); 

</script>

</body>
</html>
