

<?php




function get_graders() {
    $graders = array();

    $graders['interpretation'] = array(
        'Robert' => 'roberthisinterpretation',
        'Michael' => 'michaelhisinterpretation'
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
