<?php

//A view from the Civil War Front lines

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
                    array('val' => 1, 'label' => 'Sandy Christie'),
                    array('val' => 2, 'label' => 'Thomas Christie'),
                    array('val' => 3, 'label' => 'A news reporter'),
                    array('val' => 4, 'label' => 'A government officer')));

$questions[] = array(
                'num'     => 3,
                'type'    => 'r',
                'q'       => 'What is the purpose of the source?',
                'options' => array(
                    array('val' => 1, 'label' => 'Complaining about being treated badly in the military'),
                    array('val' => 2, 'label' => 'Describing what the author is doing to a family member'),
                    array('val' => 3, 'label' => 'Reporting current situations to the general public'),
                    array('val' => 4, 'label' => 'Addressing issues such as insufficient ammunition')));

$questions[] = array(
                'num'     => 4,
                'type'    => 'r',
                'q'       => 'Who is the audience of the document?',
                'options' => array(
                    array('val' => 1, 'label' => 'Thomas Christie'),
                    array('val' => 2, 'label' => 'Sandy Christie'),
                    array('val' => 3, 'label' => 'A military officer'),
                    array('val' => 4, 'label' => 'Readers of some newspaper')));

$questions[] = array(
                'num'     => 5,
                'type'    => 'r',
                'q'       => 'When was the source produced?',
                'options' => array(
                    array('val' => 1, 'label' => '1865'),
                    array('val' => 2, 'label' => '1877'),
                    array('val' => 3, 'label' => '1850'),
                    array('val' => 4, 'label' => '1861')));

$questions[] = array(
                'num'     => 6,
                'type'    => 'r',
                'q'       => 'Where was the source produced?',
                'options' => array(
                    array('val' => 1, 'label' => 'Chattanooga, North Carolina'),
                    array('val' => 2, 'label' => 'Charleston, South Carolina'),
                    array('val' => 3, 'label' => 'Savannah, Georgia'),
                    array('val' => 4, 'label' => 'Birmingham, Alabama')));

$questions[] = array(
                'num'     => 7,
                'type'    => 'tf',
                'q'       => 'Please select "Yes" for follwing statements that are appropriate about the immediate and broader context at the time the source was produced and otherwise "No".',
                'options' => array(
                    array('val' => 1, 'label' => 'The author is very pround of both the army and his contribution.'),
                    array('val' => 2, 'label' => 'It is not clear if the author\'s army is winning its battles.'),
                    array('val' => 3, 'label' => 'The author addresses the issue of enlisting and wants the recipient(s) to enlist.'),
                    array('val' => 4, 'label' => 'The document was produced during the American Civil War.')));

$questions[] = array(
                'num'     => 8,
                'type'    => 'tf',
                'q'       => 'Please select "Yes" for all statements that are appropriately inferred from the document and otherwise select "No".',
                'options' => array(
                    array('val' => 1, 'label' => 'The author is aware of the battles and duels, and provides that these battles and duels are par of a larger picture.'),
                    array('val' => 2, 'label' => 'The author focuses on battery accuracy but gives no indication that they are shotting guns to kill people, nor a sense that the enemy is trying to kill them.'),
                    array('val' => 3, 'label' => 'There does not seem to be a sense that the author is aware of the overall strategy of the battle or the war itself.'),
                    array('val' => 4, 'label' => 'From the document, it seems the author\'s army has been making progress, showing that the war is likely to come to an end.')));

?>
        <div style="margin-top: 20px;" class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Section 1 <?php if ($_SESSION['study2']['workflow'] == 1) {echo '(Please answer the following questions with respect to the <u>3rd</u> document, that is, <u>the last</u> document you worked on.)';} ?></h3>
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
