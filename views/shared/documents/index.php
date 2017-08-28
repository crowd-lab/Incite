<!DOCTYPE html>
<html lang="en">
<?php
//queue_css_file(array('bootstrap', 'style', 'bootstrap.min'));
$db = get_db();
include(dirname(__FILE__).'/../common/header.php');
?>

    <!-- Page Content -->
    <div class="container-fluid" style="margin-left: 100px; margin-right: 100px;">
            <h2 style="text-align: center;">Investigation of Crowdsourced Connections between Historical Documents and High-Level Concepts</h2>
            <h4>Purpose of this Research Project</h4>
            <p>You are being asked to participate in a research study. We are studying the effects of different work conditions on historical learning/thinking. We will use these results to inform and improve the design of an online text analysis system. In addition, the results may have implications for designing future digital archival systems as well as online educational systems. The results of this study may be published in academic conferences or journals or included in student theses and dissertations.</p>
            <h4>Procedures</h4>
            <p>If you agree to participate, you will be asked to 1) take a pre-task survey about demographic information (&lt; 1 minute) , 2) do three tasks each of which may take maximum 10 minutes and 3) complete a survey about your experience of the tasks (&lt; 10 minutes).</p>
            <h4>Risks</h4>
            <p>The risks of the study are minimal and are similar to what you would encounter doing everyday computer tasks like browsing the Internet, writing a document, or checking email.</p>
            <h4>Benefits</h4>
            <p>You will not receive any benefits by participating in this study. However, the study will help us develop software and techniques for analyzing textual documents. This could benefit society by avoiding inefficient utilization of workforce and by informing better design of future digital archival systems as well as online educational systems. No promise or guarantee of benefits has been made to encourage you to participate.</p>
            <h4>Extent of Anonymity and Confidentiality</h4>
            <p>We will collect your ID information (e.g. your name for extra credit in class), but it will be stored separately from the rest of your study data, and only the Principal Investigator and Co-Principal Investigator will have the ability to link them. We will use a numeric code instead of your ID information to refer to your study data.</p>
            <p>We may share your anonymized study data in publications or in data sets on our project website. If we use your data for these purposes, we will use the numeric code instead of your ID information to refer to you. We may also aggregate your data with other participants when reporting our results to minimize the possibility of identifying you. Your information will never be shared outside the research team.</p>
            <p>The Virginia Tech (VT) Institutional Review Board (IRB) may view the study's data for auditing purposes. The IRB is responsible for the oversight of the protection of human subjects involved in research.</p>
            <h4>Compensation</h4>
            <p>You will not receive monetary compensation but you will get extra credit for the class participating in this study.</p>
            <h4>Freedom to Withdraw</h4>
            <p>It is important for you to know that you are free to withdraw from this study at any time without penalty. You are free not to answer any questions that you choose or respond to what is being asked of you without penalty.</p>
            <p>Please note that there may be circumstances under which the investigator may determine that a subject should not continue as a subject.</p>
            <h4>Questions or Concerns</h4>
            <p>Should you have any questions about this study, you may contact Principal Investigator Dr. Kurt Luther at kluther@vt.edu or (540) 231-4857 or Co-Principal Investigator Nai-Ching Wang at naiching@vt.edu.</p>
            <p>Should you have any questions or concerns about the study.s conduct or your rights as a research subject, or need to report a research-related injury or event, you may contact the VT IRB Chair, Dr. David M. Moore at moored@vt.edu or (540) 231-4991.</p>
            <h4>Subject's Consent</h4>
            <p style="color: red;">If you are 18 years of age or older, understand the statements above, and freely consent to participate in the study, please click on the "Accept" button below to begin your participation.</p>
            <br>
<?php 
        $is_hit_accepted = !(!isset($_GET['assignmentId']) || (isset($_GET['assignmentId']) && $_GET['assignmentId'] == "ASSIGNMENT_ID_NOT_AVAILABLE"));
?>
        <?php if ($is_hit_accepted): ?>
            <div style="text-align: center; margin-bottom: 30px;"><button id="accept_btn" style="width: 150px; font-size:200%;">Accept</button></div>
        <?php else: ?>
            <div style="text-align: center; margin-bottom: 30px;">You need to accept HIT to prodeed.</div>
        <?php endif; ?>

        </div>
    <!-- /.container -->

    <!-- jQuery Version 1.11.1 -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
    <script>
$(document).ready(function(){
    $('[data-toggle="popover"]').popover({ trigger: "hover" });

    $('#accept_btn').on('click', function(e) {
        window.onbeforeunload = "";
        window.location = '<?php echo getFullInciteUrl(); ?>/documents/show?<?php echo $_SERVER['QUERY_STRING']; ?>';
    });

    window.onbeforeunload = "";
});
</script>

</body>

</html>
