<?php


require_once("DB_Connect.php");
require_once("Incite_Tag_Table.php");
require_once("Incite_Subject_Concept_Table.php");

function getTranscribableDocuments()
{

    $db = DB_Connect::connectDB();
    $documents_with_jpeg = array();  //document id's and assume documents with jpeg all need transcriptions and thus tags
    $stmt = $db->prepare("SELECT DISTINCT `item_id` FROM `omeka_files` WHERE `mime_type` = 'image/jpeg'");
    $stmt->bind_result($result);
    $stmt->execute();
    while ($stmt->fetch()) {
        $documents_with_jpeg[] = $result;
    }
    $stmt->close();
    $db->close();

    return $documents_with_jpeg;
}
/**
 * Gets all document id with tags
 * @return an array of results
 */
function getDocumentsWithTags()
{
    $db = DB_Connect::connectDB();
    $tagged_document_ids = array();
    $stmt = $db->prepare("SELECT DISTINCT `omeka_incite_documents`.`item_id` FROM `omeka_incite_documents` INNER JOIN `omeka_incite_documents_tags_conjunction` ON `omeka_incite_documents`.`id` = `omeka_incite_documents_tags_conjunction`.`document_id`");
    $stmt->bind_result($result);
    $stmt->execute();
    while ($stmt->fetch()) {
        $tagged_document_ids[] = $result;
    }
    $stmt->close();
    $db->close();

    //Select document that are untranscribed but transcribable. Since currently if there is not transcription for the document to be tagged, the user will be redirected to transcribe task.
    //$taggable_documents = getTranscribableDocuments();

    //Select documents that have approved transcriptions

    return $tagged_document_ids;
}

/**
 * Gets all document id with out tags
 * @return an array of results
 */
function getDocumentsWithoutTag()
{
    $db = DB_Connect::connectDB();
    $tagged_document_ids = array();
    $stmt = $db->prepare("SELECT DISTINCT `omeka_incite_documents`.`item_id` FROM `omeka_incite_documents` INNER JOIN `omeka_incite_documents_tags_conjunction` ON `omeka_incite_documents`.`id` = `omeka_incite_documents_tags_conjunction`.`document_id`");
    $stmt->bind_result($result);
    $stmt->execute();
    while ($stmt->fetch()) {
        $tagged_document_ids[] = $result;
    }
    $stmt->close();
    $db->close();

    //Select document that are untranscribed but transcribable. Since currently if there is not transcription for the document to be tagged, the user will be redirected to transcribe task.
    //$taggable_documents = getTranscribableDocuments();

    //Select documents that have approved transcriptions
    $taggable_documents = getDocumentsWithApprovedTranscription();

    return array_diff($taggable_documents, $tagged_document_ids);
}
function getConnectableDocuments() {
    $all_tagged_documents = getAllTaggedDocuments();
    $connectable_documents = array();
    for ($i = 0; $i < count($all_tagged_documents); $i++) {
        $related_documents = findRelatedDocumentsViaAtLeastNCommonTags($all_tagged_documents[$i]);
        if (count($related_documents) == 0)
            continue;

        $subject_candidates = getBestSubjectCandidateList($related_documents);
        if (count($subject_candidates) == 0)
            continue;

        $self_subjects = getAllSubjectsOnId($all_tagged_documents[$i]);
        for ($j = 0; $j < count($subject_candidates); $j++) {
            if (!in_array($subject_candidates[$j]['subject'], $self_subjects)) {
                if (count($subject_candidates[$j]['ids']) > 0) {
                    $connectable_documents[] = $all_tagged_documents[$i];
                    continue 2;
                }
            }
        }
    }
    return $connectable_documents;

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
    'Wyoming'=>'WY');

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



?>
