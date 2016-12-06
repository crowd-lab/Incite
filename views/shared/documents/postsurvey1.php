<?php

$questions = array();
$questions[] = array(
                'num'     => 1,
                'type'    => 'r',
                'q'       => 'What type of histofical document is the source?',
                'options' => array(
                    array('val' => 1, 'label' => 'A letter'),
                    array('val' => 2, 'label' => 'A clip from newspaper'),
                    array('val' => 3, 'label' => 'A personal diary'),
                    array('val' => 4, 'label' => 'A clip from a book')));

$questions[] = array(
                'num'     => 2,
                'type'    => 'r',
                'q'       => 'Who is the author of the document?',
                'options' => array(
                    array('val' => 1, 'label' => 'Bobby Murray'),
                    array('val' => 2, 'label' => 'Della Murray'),
                    array('val' => 3, 'label' => 'A news reporter'),
                    array('val' => 4, 'label' => 'A government officer')));

$questions[] = array(
                'num'     => 3,
                'type'    => 'r',
                'q'       => 'What is the purpose of the source?',
                'options' => array(
                    array('val' => 1, 'label' => 'Seeking assistance to be able to continue school'),
                    array('val' => 2, 'label' => 'Complaining about bad economy'),
                    array('val' => 3, 'label' => 'Reporting losses of family members'),
                    array('val' => 4, 'label' => 'Reporting misconduct of school officials')));

$questions[] = array(
                'num'     => 4,
                'type'    => 'r',
                'q'       => 'Who is the audience of the document?',
                'options' => array(
                    array('val' => 1, 'label' => 'Children\'s Bureau'),
                    array('val' => 2, 'label' => 'White House'),
                    array('val' => 3, 'label' => 'Readers of a magazine'),
                    array('val' => 4, 'label' => 'Readers of newspaper')));

$questions[] = array(
                'num'     => 5,
                'type'    => 'r',
                'q'       => 'When was the source produced?',
                'options' => array(
                    array('val' => 1, 'label' => '1939'),
                    array('val' => 2, 'label' => '1942'),
                    array('val' => 3, 'label' => '1935'),
                    array('val' => 4, 'label' => '1937')));

$questions[] = array(
                'num'     => 6,
                'type'    => 'r',
                'q'       => 'Where was the source produced?',
                'options' => array(
                    array('val' => 1, 'label' => 'Malvern, Arkansas'),
                    array('val' => 2, 'label' => 'Marion, Alabama'),
                    array('val' => 3, 'label' => 'Anguilla, Mississippi'),
                    array('val' => 4, 'label' => 'Anderson, Missouri')));

$questions[] = array(
                'num'     => 7,
                'type'    => 'tf',
                'q'       => 'Please select "Yes" for follwing statements that are appropriate about the immediate and broader context at the time the source was produced and otherwise "No".',
                'options' => array(
                    array('val' => 1, 'label' => 'The Depression happened in 1929 still had impact during the time the document was produced.'),
                    array('val' => 2, 'label' => 'The document reveals that unemployment was still a problem for boys under 18 years of age.'),
                    array('val' => 3, 'label' => 'The World War II happened in 1939 started to impact the life of Americans.'),
                    array('val' => 4, 'label' => 'Within the immediate context, the document indicates that life was still desperate, not recovering from the Depression.')));

$questions[] = array(
                'num'     => 8,
                'type'    => 'tf',
                'q'       => 'Please select "Yes" for all statements that are appropriately inferred from the document and otherwise select "No".',
                'options' => array(
                    array('val' => 1, 'label' => 'The author appears to be mature for 15 years of age, as he seems to understand the family.s dynamics.'),
                    array('val' => 2, 'label' => 'Since the author is referencing the Superintendent, it is likely the author already asked the Superintendent for assistance.'),
                    array('val' => 3, 'label' => 'The author is concerned with furthering his education, but not so much about the family finances.'),
                    array('val' => 4, 'label' => 'Since the author does not indicate much about his family - who was his father, how did his sister die, who was the father of his sister.s child - it can be concluded that the author was uncomfortable with his family.')));


?>
        <div style="margin-top: 20px;" class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Section 1 <?php if ($_SESSION['study2']['workflow'] == 1) {echo '(Please answer the following questions with respect to the <b>3rd</b> document, that is, <b>the last</b> document you worked on.)';} ?></h3>
                </div>
                    <div style="padding: 15px;">
                        <?php 
                            $tmp_q = $questions;
                            shuffle($tmp_q);
                            foreach($tmp_q as $question) {
                                question_generator($question);
                            }
                        ?>
                    </div>
                <div style="clear:both"></div>
            </div> 
            <?php include('postsurveytlx.php'); ?>
            <div class="row" style="margin: 10px;"><button type="button" id="submit-demo" class="btn btn-primary pull-right">Submit</button></div>
        </div>
