<?php
//Redirection
define('REDIRECTOR_URL', 'incite/documents/redirect');


//URL Path
define('INCITE_PATH', '/m4j/incite/');


//Tag Task
$categories = array('ORGANIZATION', 'PERSON', 'LOCATION', 'EVENT');
$category_colors = array('ORGANIZATION' => 'red', 'PERSON' => 'orange', 'LOCATION' => 'yellow', 'EVENT' => 'gray');

//Search
define('MAXIMUM_SEARCH_RESULTS', 40);
define('SEARCH_RESULTS_PER_PAGE', 8);

//Connect
define("MINIMUM_COMMON_TAGS_FOR_RELATED_DOCUMENTS", 2);

?>