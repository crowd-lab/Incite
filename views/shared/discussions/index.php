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
    <div class="col-lg-8">
        <button type="button">Create a New Discussion</button>
        <h3>Discussions that you've subscribed to: </h3>
        <ul>
            <li>
                How do Northerners vs. Southerners write about George Washington?
                <br>
                <p> (1 new user joined, 2 more responses posted since last time) </p>
            </li>
            <li>
                How do Northerners vs. Southerners write about George Washington?
                <br>
                <p> (1 new user joined, 2 more responses posted since last time)</p>
            </li>
            <li>
                How do Northerners vs. Southerners write about George Washington?
                <br>
                <p> (1 new user joined, 2 more responses posted since last time) </p>
            </li>
        </ul>
        <h3>Discussions that you were involved in: </h3>
        <ul>
            <li>
                How do Northerners vs. Southerners write about George Washington?
                <br>
                <p> (1 new user joined, 2 more responses posted since last time)</p>
            </li>
            <li>
                How do Northerners vs. Southerners write about George Washington?
                <br>
                <p> (1 new user joined, 2 more responses posted since last time) </p>
            </li>
        </ul>
        <h3>Other Discussions</h3>
        <ul>
            <li>
                Why is George Washington so important to southerners?
                <br>
                <p>   (2 users are discussing - join)</p>
            </li>
        </ul>
    </div>
 

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
