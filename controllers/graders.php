

<?php




function get_graders() {
    $graders = array();

    $graders['interpretation'] = array(
        'Robert' => 'fj892q3fiwo@#fask',
        'Michael' => '3829fla@fa932#$al',
        'Nai-Ching' => 'fjaw#!afj9823rADF'
    );

    $graders['summary'] = array(
        'Andy' => 'andyhisinterpretation',
        'Ashleigh' => 'ashleighhisinterpretation'
    );
    return $graders;
}


function is_valid_interpretation_grader($username, $password) {
    $graders = get_graders();
    return isset($graders['interpretation'][$username]) && $graders['interpretation'][$username] == $password;
}

function is_valid_summary_grader($username, $password) {
    $graders = get_graders();
    return isset($graders['summary'][$username]) && $graders['summary'][$username] == $password;
}
