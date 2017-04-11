<?php


require_once("DB_Connect.php");
require_once("Incite_Tag_Table.php");
require_once("Incite_Subject_Concept_Table.php");

function getTranscribableDocuments()
{

    $db = DB_Connect::connectDB();
    $documents_with_jpeg = array();  //item id's and assume documents with jpeg all need transcriptions and thus tags
    $stmt = $db->prepare("SELECT DISTINCT `item_id` FROM `omeka_files` WHERE `mime_type` = 'image/jpeg' OR `mime_type` = 'image/png'");
    $stmt->bind_result($result);
    $stmt->execute();
    while ($stmt->fetch()) {
        $documents_with_jpeg[] = $result;
    }
    $stmt->close();
    $db->close();

    return $documents_with_jpeg;
}

function getDocumentsWithTranscriptions()
{

    $db = DB_Connect::connectDB();
    $documents_with_trans = array();  //item id's and assume documents with jpeg all need transcriptions and thus tags
    $stmt = $db->prepare("SELECT DISTINCT `omeka_items`.`id` FROM `omeka_items` INNER JOIN `omeka_incite_transcriptions` ON `omeka_items`.`id` = `item_id`");
    $stmt->bind_result($result);
    $stmt->execute();
    while ($stmt->fetch()) {
        $documents_with_trans[] = $result;
    }
    $stmt->close();
    $db->close();

    return $documents_with_trans;
}

function getDocumentsWithoutTranscriptions()
{

    $db = DB_Connect::connectDB();
    $documents = array();  //item id's and assume documents with jpeg all need transcriptions and thus tags
    $stmt = $db->prepare("SELECT `id` FROM `omeka_items`");
    $stmt->bind_result($result);
    $stmt->execute();
    while ($stmt->fetch()) {
        $documents[] = $result;
    }
    $stmt->close();
    $db->close();

    $documents_with_trans = getDocumentsWithTranscriptions();

    return array_diff($documents, $documents_with_trans);
}
/**
 * Gets all document id with tags
 * @return an array of results
 */
function getDocumentsWithTags()
{
    $db = DB_Connect::connectDB();
    $tagged_item_ids = array();
    $stmt = $db->prepare("SELECT DISTINCT `omeka_incite_documents`.`item_id` FROM `omeka_incite_documents` INNER JOIN `omeka_incite_documents_tags_conjunction` ON `omeka_incite_documents`.`id` = `omeka_incite_documents_tags_conjunction`.`item_id`");
    $stmt->bind_result($result);
    $stmt->execute();
    while ($stmt->fetch()) {
        $tagged_item_ids[] = $result;
    }
    $stmt->close();
    $db->close();

    //Select document that are untranscribed but transcribable. Since currently if there is not transcription for the document to be tagged, the user will be redirected to transcribe task.
    //$taggable_documents = getTranscribableDocuments();

    //Select documents that have approved transcriptions

    return $tagged_item_ids;
}

/**
 * Gets all document id with out tags
 * @return an array of results
 */
function getDocumentsWithoutTagsForLatestTranscription()
{
    $documents_without_tag = array();
    $taggable_documents = getDocumentsWithApprovedTranscription();

    foreach($taggable_documents as $item_id) {
        if (!hasTaggedTranscriptionForNewestTranscription($item_id)) {
            $documents_without_tag[] = $item_id;
        }
    }

    return $documents_without_tag;
}
function getDocumentsWithoutConnectionsForLatestTaggedTranscription() {
    $documents_without_connections = array();
    $tagged_documents = getDocumentsWithTags();

    foreach($tagged_documents as $item_id) {
        $newestSubjects = getNewestSubjectsForNewestTaggedTranscription($item_id);

        if (empty($newestSubjects)) {
            $documents_without_connections[] = $item_id;
        }
    }

    return $documents_without_connections;
}
/**
 * Takes a list of document ids and returns a new array with info about their task completion
 */
function getTaskCompletionInfoFor($itemID) {
    $newestTranscription = getNewestTranscription($itemID);
    $newestSubjects = getNewestSubjectsForNewestTaggedTranscription($itemID);

    $isTranscribed = !empty($newestTranscription);
    $isTagged = hasTaggedTranscriptionForNewestTranscription($itemID);
    $isConnected = !empty($newestSubjects);

    $documents = array('isTranscribed' => ($isTranscribed ? true : false), 'isTagged' => ($isTagged ? true : false), 'isConnected' => ($isConnected ? true : false));

    return $documents;
}

function loc_to_lat_long($loc_str)
{
    $states = array(
            'Alabama'=>'AL',
            'Alaska'=>'AK',
            'Arizona'=>'AZ',
            'Arkansas'=>'AR',
            'California'=>'CA',
            'Colorado'=>'CO',
            'Connecticut'=>'CT',
            'Delaware'=>'DE',
            'Florida'=>'FL',
            'Georgia'=>'GA',
            'Hawaii'=>'HI',
            'Idaho'=>'ID',
            'Illinois'=>'IL',
            'Indiana'=>'IN',
            'Iowa'=>'IA',
            'Kansas'=>'KS',
            'Kentucky'=>'KY',
            'Louisiana'=>'LA',
            'Maine'=>'ME',
            'Maryland'=>'MD',
            'Massachusetts'=>'MA',
            'Michigan'=>'MI',
            'Minnesota'=>'MN',
            'Mississippi'=>'MS',
            'Missouri'=>'MO',
            'Montana'=>'MT',
            'Nebraska'=>'NE',
            'Nevada'=>'NV',
            'New Hampshire'=>'NH',
            'New Jersey'=>'NJ',
            'New Mexico'=>'NM',
            'New York'=>'NY',
            'North Carolina'=>'NC',
            'North Dakota'=>'ND',
            'Ohio'=>'OH',
            'Oklahoma'=>'OK',
            'Oregon'=>'OR',
            'Pennsylvania'=>'PA',
            'Rhode Island'=>'RI',
            'South Carolina'=>'SC',
            'South Dakota'=>'SD',
            'Tennessee'=>'TN',
            'Texas'=>'TX',
            'Utah'=>'UT',
            'Vermont'=>'VT',
            'Virginia'=>'VA',
            'Washington'=>'WA',
            'West Virginia'=>'WV',
            'Wisconsin'=>'WI',
            'Wyoming'=>'WY',
        'District of Columbia' => 'DC');

    //mostly only use state and city but in case of no such city, we use county instead
    $elem  = explode("-", $loc_str);
    $state = "";
    $city  = "";
    $county = "";

    //Parse state and city names
    if (count($elem) >= 3) { //currently ignore extra info about location. Item 11 is an exception here!
        $state_index = trim(str_replace('State', '', str_replace('state', '', $elem[0])));
        if (!isset($states[$state_index]))
            return array('lat' => '37.23', 'long' => '-80.4178');
        $state  = $states[$state_index];
        $city   = trim($elem[2]);
        $county = trim(str_replace('County', '', $elem[1]));
    } else if (count($elem) == 2) {
        $state = $states[trim(str_replace('State', '', str_replace('state', '', $elem[0])))];
        $city  = strstr(trim($elem[1]), ' Indep.', true);
        if ($city == "")
            $city = trim($elem[1]);
    } else {
        //Should send to log and to alert new format of location!
    }

    //Convert state and city to lat and long
    $result = array();
    $latlong_file = fopen('./plugins/Incite/zip_codes_states.csv', 'r') or die('no zip file!');

    while (($row = fgetcsv($latlong_file)) != FALSE) {
        //Just use the last result as our county guess
        if ($county == $row[5] && $state == $row[4]) {
            $result['lat']  = $row[1];
            $result['long'] = $row[2];
        }
        //Just use the first result as our final result!
        if ($city == $row[3] && $state == $row[4]) {
            $result['lat']  = $row[1];
            $result['long'] = $row[2];
            break;
        }
    }
    fclose($latlong_file);

    return $result;
}

function location_to_city_state_str($location_str)
{
    $elements = explode('-', $location_str);

    if (count($elements) < 2)
        $elements = explode(',', $location_str);

    if (count($elements) < 2)
        return $location_str; //I guess this is better than returning unknown places

    $state = trim(str_replace('State', '', str_replace('state', '', $elements[0])));
    $city = strstr(trim($elements[count($elements)-1]), ' Indep.', true);
    if (strlen($city) == 0)
        $city = trim($elements[count($elements)-1]);

    return $city.', '.$state;
}

function year_of_full_iso_date ($full_iso_date)
{
    $elements = explode('-', $full_iso_date);
    if (count($elements) != 3)
        return '???';

    return $elements[0];
}




?>
