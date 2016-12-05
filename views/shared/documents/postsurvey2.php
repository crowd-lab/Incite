<?php

//A letter from George Washington to Benjamin Tallmadge

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
                    array('val' => 1, 'label' => 'George Washington'),
                    array('val' => 2, 'label' => 'Maylans Regiment'),
                    array('val' => 3, 'label' => 'A news reporter'),
                    array('val' => 4, 'label' => 'A government officer')));

$questions[] = array(
                'num'     => 3,
                'type'    => 'r',
                'q'       => 'What is the purpose of the source?',
                'options' => array(
                    array('val' => 1, 'label' => 'Seeking assistance from Col. Maylans Regiment'),
                    array('val' => 2, 'label' => 'Complaining about the war to an immediate family member'),
                    array('val' => 3, 'label' => 'Reporting current situations to the general public'),
                    array('val' => 4, 'label' => 'Addressing issues of Tallmadge\'s position and the fatigue of the horses')));

$questions[] = array(
                'num'     => 4,
                'type'    => 'r',
                'q'       => 'Who is the audience of the document?',
                'options' => array(
                    array('val' => 1, 'label' => 'Col. Maylans Regiment'),
                    array('val' => 2, 'label' => 'The general public'),
                    array('val' => 3, 'label' => 'Benjamin Tallmadge'),
                    array('val' => 4, 'label' => 'An author\'s immediate family member')));

$questions[] = array(
                'num'     => 5,
                'type'    => 'r',
                'q'       => 'When was the source produced?',
                'options' => array(
                    array('val' => 1, 'label' => '1774'),
                    array('val' => 2, 'label' => '1779'),
                    array('val' => 3, 'label' => '1783'),
                    array('val' => 4, 'label' => '1786')));

$questions[] = array(
                'num'     => 6,
                'type'    => 'r',
                'q'       => 'Where was the source produced?',
                'options' => array(
                    array('val' => 1, 'label' => 'New Jersey'),
                    array('val' => 2, 'label' => 'Bedford'),
                    array('val' => 3, 'label' => 'District of Columbia'),
                    array('val' => 4, 'label' => 'New Windsor')));

$questions[] = array(
                'num'     => 7,
                'type'    => 'tf',
                'q'       => 'Please select "Yes" for follwing statements that are appropriate about the immediate and broader context at the time the source was produced and otherwise "No".',
                'options' => array(
                    array('val' => 1, 'label' => 'General Washington was initiating a communication concerning Tallmadge\'s position at Bedford and the condition of his horse.'),
                    array('val' => 2, 'label' => 'They were still in War.'),
                    array('val' => 3, 'label' => 'There is nothing confidential in the document.'),
                    array('val' => 4, 'label' => 'The time of the document reveals that it might be part of the American Revolutionary War.')));

$questions[] = array(
                'num'     => 8,
                'type'    => 'tf',
                'q'       => 'Please select "Yes" for all statements that are appropriately inferred from the document and otherwise select "No".',
                'options' => array(
                    array('val' => 1, 'label' => 'There is clearly a sense that protecting these inhabitants is important.'),
                    array('val' => 2, 'label' => 'The specificity mentioned in the letter, such as Bedford and a central place between the two rivers, indicates that if the enemy intercepts the letter, it will give much information.'),
                    array('val' => 3, 'label' => 'The document clearly indicates Tallmadge\'s specific assignment including the horse fatigue and the Beford location.'),
                    array('val' => 4, 'label' => 'The fact that Washington is writing this type of letter and concerned about Tallmadge\'s location the the fatigue of his horse, suggests how intimately involved Washington was in the military process.')));


?>
        <div style="margin-top: 20px;" class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Section 1</h3>
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
