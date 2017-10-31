<!DOCTYPE html>
<html lang="en">
<?php
queue_css_file(array('bootstrap', 'style', 'bootstrap.min'));
$db = get_db();
include(dirname(__FILE__).'/../common/header.php');
?>
<head>
<style>
p{
  font-size: 120%;
}
</style>

</head>

<body>

  <div class="col-md-8 col-md-offset-2">

    <h1>Page about your project</h1>
    <br>
    <p>Introduction goes here...
     <br><br>
        
        <br>
      <p>The project team includes:</p>
        <ul>
            <li>Member 1</li>
            <li>Member 2</li>
            <li>Member 3</li>
            <li>Member 4</li>
            <li>Member 5</li>
            <li>Member 6</li>
            <li>Member 7</li>
            <li>Member 8</li>
            <li>Member 9</li>
            <li>Member 10</li>
            <li>Other students and contributors from
                <ul>
                    <li>Member 1</li>
                    <li>Member 2</li>
                    <li>Member 3</li>
                    <li>Member 4</li>
                    <li>Member 5</li>
                </ul>
            </li>
        </ul>
      <br>
      <br>
          <br>
          <br>
        </p>

      </div>
    <div class="col-md-12">
<?php   
include(dirname(__FILE__).'/../common/footer.php');
?>
    </div>
    </body>

    </html>
