<!DOCTYPE html>
<html lang="en">
<?php
queue_css_file(array('bootstrap', 'style', 'bootstrap.min'));
$db = get_db();

include(dirname(__FILE__).'/../common/header.php');
?>
<head>
<style>

.panel-heading .accordion-toggle:after {
    /* symbol for "opening" panels */
    font-family: 'Glyphicons Halflings';  /* essential for enabling glyphicon */
    content: "\e114";    /* adjust as needed, taken from bootstrap.css */
    float: right;        /* adjust as needed */
    color: grey;         /* adjust as needed */
}
.panel-heading .accordion-toggle.collapsed:after {
    /* symbol for "collapsed" panels */
    content: "\e080";
  }

</style>

</head>

<body>

    <div style="margin-top: 40px;" class="col-md-6 col-md-offset-3">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">High School Assignment Guidelines</h3>
            </div>
            <div style="padding: 15px;">
                These guildelines include the following sample assignments:
                <ul>
                    <li>In-Class Tagging Activity</li>
                    <li>In-Class Primary Source Transcription and Tagging Activity</li>
                    <li>Pre-packaged Documents</li>
                    <li>Homework Assignment</li>
                    <li>Semester Long Project</li>
                </ul>
              Please click <a target="_blank" href="https://docs.google.com/document/d/1pWJuNW4t3rVqLDc9qGngukz6fTxf5PYVecxVNUn2MwI/edit?usp=sharing">here</a> for more details. 
            </div>
        </div> 
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">College Assignment Guidelines</h3>
            </div>
            <div style="padding: 15px;">
                These guildelines include the following sample assignments:
                <ul>
                    <li>Homework assignment and in-class activity for a course in U.S. history</li>
                    <li>Multiple homework assignments and in-class activities for a course on the American Civil War Era</li>
                    <li>Archival research project with document upload</li>
                    <li>Semester-long research project for an advanced course in U.S. history</li>
                </ul>
              Please click <a target="_blank" href="https://drive.google.com/open?id=0B4qOgF-3g8gfTEJYdDRrN054RTA">here</a> for more details.
            </div>
        </div> 
    </div>
</body>

</html>
