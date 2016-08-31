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

    <div style="margin-top: 20px;" class="col-md-6 col-md-offset-3">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">For Teachers</h3>
            </div>
            <div style="padding: 15px;">
                <p>Mapping the Fourth is an innovative, primary source-based teaching tool that brings the experiences of Civil War Americans alive for your students.</p>
                <p>Whether you teach at the college or high school level, your students will jump at the chance to learn about how a previous generation of Americans celebrated the Fourth. (Yes, there were fireworks!) These are engaging first-hand documents that open up big themes: North-South differences; the causes and consequences of the Civil War; African American experiences of emancipation.</p>
                <p>Our easy-to-use Groups feature allows teachers and professors to provide customized instructions and keep track of the work their students have done.</p>
                <p>If you are planning to use the site with your students, please contact us at <a href="mailto:july4.civilwar-g@vt.edu">july4.civilwar-g@vt.edu</a>. We can provide personalized guidance on how to make the most of Mapping the Fourth, and may even be able to upload new primary sources to suit your students. specific needs.</p>
                <p>We've prepared a selection of lesson plans and assignment guidelines that make it easy to integrate the site into your courses in the following two sections.</p>
                <p>Questions? Suggestions? Please contact us: <a href="mailto:july4.civilwar-g@vt.edu">july4.civilwar-g@vt.edu</a></p>
            </div>
        </div> 
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
    <div class="col-md-12">
<?php   
include(dirname(__FILE__).'/../common/footer.php');
?>
    </div>
</body>

</html>
