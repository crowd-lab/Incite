<?php
/**
 * API for the subject/concept table
 */
require_once("DB_Connect.php");
/**
 * Creates a new subject/concept
 * @param String $name
 * @param String $definition
 * @return boolean --> true if created, false if subject/concept already exists
 */
function createNewSubjectConcept($name, $definition)
{
    $db = DB_Connect::connectDB();
    $stmt1 = $db->prepare("SELECT id FROM omeka_incite_subject_concepts WHERE UPPER(name) = UPPER(?)");
    $stmt1->bind_param("s", $name);
    $stmt1->bind_result($id);
    $stmt1->execute();
    $stmt1->fetch();
    if ($id != null)
    {
        $stmt1->close();
        $db->close();
        return false;
    }
    else
    {
        $stmt1->close();
        $db->close();
        $db = DB_Connect::connectDB();
        $stmt = $db->prepare("INSERT INTO omeka_incite_subject_concepts VALUES (NULL, ?, ?)");
        $stmt->bind_param("ss", $name, $definition);
        $stmt->execute();
        $stmt->close();
        $db->close();
    }
}
/**
 * Get the definition of a certain subject/concept
 * @param String $name
 * @return mixed --> return false if no definition exists for a name or returns a string if definition exists
 */
function getDefinition($name)
{
    $def = "";
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("SELECT definition FROM omeka_incite_subject_concepts WHERE UPPER(name) = UPPER(?)");
    $stmt->bind_param("s", $name);
    $stmt->bind_result($def);
    $stmt->execute();
    $stmt->fetch();
    if ($def != null)
    {
        return $def;
    }
    return false;
}
/**
 * Change the definition of a subject/concept
 * @param String $name
 * @param String $def
 * @return boolean true if complete, false if no subject/concept exists with the name provided
 */
function changeDefinition($name, $def)
{
    $db = DB_Connect::connectDB();
    $stmt1 = $db->prepare("SELECT id FROM omeka_incite_subject_concepts WHERE UPPER(name) = UPPER(?)");
    $stmt1->bind_param("s", $name);
    $stmt1->bind_result($id);
    $stmt1->execute();
    $stmt1->fetch();
    if ($id == null)
    {
        $stmt1->close();
        $db->close();
        return false;
    }
    else
    {
        $stmt1->close();
        $stmt = $db->prepare("UPDATE omeka_incite_subject_concepts SET definition = ? WHERE UPPER(name) = UPPER(?)");
        $stmt->bind_param("ss", $def, $name);
        $stmt->execute();
        $stmt->close();
        $db->close();
    }
}
/**
 * Return all subject/concepts from the database
 * @return array of results
 */
function getAllSubjectConcepts()
{
    $db = DB_Connect::connectDB();
    $results = Array();
    $stmt = $db->prepare("SELECT id, name, definition FROM omeka_incite_subject_concepts");
    $stmt->bind_result($id, $name, $def);
    $stmt->execute();
    while ($stmt->fetch())
    {
        $results[] = array('id' => $id, 'name' => $name, 'definition' => $def);
    }
    $stmt->close();
    $db->close();
    return $results;
}
/**
 * Return all subject/concept ids from the database
 * @return array of results
 */
function getAllSubjectConceptIds()
{
    $db = DB_Connect::connectDB();
    $results = Array();
    $stmt = $db->prepare("SELECT id FROM omeka_incite_subject_concepts");
    $stmt->bind_result($id);
    $stmt->execute();
    while ($stmt->fetch())
    {
        $results[] = $id;
    }
    $stmt->close();
    $db->close();
    return $results;
}
/**
 * Get the subject/concept based on id. 
 * @param int $id
 * @return array of [name, definition]
 */
function getSubjectConceptOnId($id)
{
    $db = DB_Connect::connectDB();
    $arr = Array();
    $stmt = $db->prepare("SELECT name, definition FROM omeka_incite_subject_concepts WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->bind_result($name, $def);
    $stmt->execute();
    $stmt->fetch();
    $arr[0] = $name;
    $arr[1] = $def;
    $stmt->close();
    $db->close();
    return $arr;
}
/**
 * Get all the subjects/concepts (both positive and negative) for a document based on item_id. 
 * @param int $item_id
 * @return array of subjects
 */
function getAllSubjectsOnId($item_id)
{
    $db = DB_Connect::connectDB();
    $subjects = array();
    $stmt = $db->prepare("SELECT omeka_incite_subject_concepts.name, is_positive, omeka_incite_documents_subject_conjunction.user_id FROM `omeka_incite_subject_concepts` JOIN omeka_incite_documents_subject_conjunction ON omeka_incite_subject_concepts.id=omeka_incite_documents_subject_conjunction.subject_concept_id JOIN omeka_incite_documents ON omeka_incite_documents.id=omeka_incite_documents_subject_conjunction.document_id WHERE omeka_incite_documents.item_id = ?");
    $stmt->bind_param("i", $item_id);
    $stmt->bind_result($subject, $is_positive, $userID);
    $stmt->execute();
    while ($stmt->fetch()) {
        if ($is_positive == 1) 
            $subjects[] = array('subject_name' => $subject, 'is_positive' => true, 'user_id' => $userID);
        else
            $subjects[] = array('subject_name' => $subject, 'is_positive' => false, 'user_id' => $userID);

    }
    $stmt->close();
    $db->close();
    return $subjects;
}
/**
 * Return the latest n subjects for the document where n is the number of subjects available in the database
 */
function getNewestSubjectsForDocument($documentID) {
    if (hasTaggedTranscription($documentID)) {
        $taggedTranscriptionID = getLatestTaggedTranscriptionID($documentID);
    } else {
        return array();
    }

    $db = DB_Connect::connectDB();
    $subjects = array();
    $stmt = $db->prepare("SELECT omeka_incite_subject_concepts.name, is_positive, omeka_incite_documents_subject_conjunction.user_id, omeka_incite_subject_concepts.id FROM `omeka_incite_subject_concepts` JOIN omeka_incite_documents_subject_conjunction ON omeka_incite_subject_concepts.id=omeka_incite_documents_subject_conjunction.subject_concept_id JOIN omeka_incite_documents ON omeka_incite_documents.id=omeka_incite_documents_subject_conjunction.document_id WHERE omeka_incite_documents.item_id = ? AND omeka_incite_documents_subject_conjunction.tagged_trans_id = ? ORDER BY created_time DESC LIMIT ?");
    $stmt->bind_param("iii", $documentID, $taggedTranscriptionID, countSubjects());
    $stmt->bind_result($subject, $is_positive, $userID, $subjectID);
    $stmt->execute();
    while ($stmt->fetch()) {
        if ($is_positive == 1) 
            $subjects[] = array('subject_name' => $subject, 'is_positive' => true, 'user_id' => $userID, 'subject_id' => $subjectID);
        else
            $subjects[] = array('subject_name' => $subject, 'is_positive' => false, 'user_id' => $userID, 'subject_id' => $subjectID);

    }
    $stmt->close();
    $db->close();
    return $subjects;
}
/**
 * Get the 20 latest subject edits (approved or not)
 *
 * @param int $documentID
 * @return array with the info request, or empty if no transcriptions for document
 */
function getConnectionRevisionHistory($documentID) {
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("SELECT created_time, omeka_incite_users.email, omeka_incite_users.id FROM omeka_incite_documents_subject_conjunction, omeka_incite_users WHERE document_id = ? AND omeka_incite_documents_subject_conjunction.user_id = omeka_incite_users.id GROUP BY created_time ORDER BY created_time DESC LIMIT 20");
    $stmt->bind_param("i", $documentID);
    $stmt->bind_result($timestamp, $userEmail, $userID);
    $stmt->execute();
    $connection_history = array();
    while ($stmt->fetch())
    {
        $connection_history[] = array('userEmail' => $userEmail, 'userID' => $userID, 'timestamp' => $timestamp);
    }
    $stmt->close();
    $db->close();
    return $connection_history;
}
/**
 * Returns the count of subjects that currently exist 
 */
function countSubjects() {
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("SELECT count(*) FROM omeka_incite_subject_concepts");
    $stmt->bind_result($count);
    $stmt->execute();
    $stmt->fetch();
    $stmt->close();
    $db->close();
    return $count;
}
/**
 * Tags the document with a subject/concept
 * @param int $conceptID
 * @param int $itemID
 * @param int $userID
 * @param int $groupID
 * @param int $taggedTranscriptionID
 * @param bool $positive
 */
function addConceptToDocument($conceptID, $itemID, $userID, $groupID, $taggedTranscriptionID, $positive)
{
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("SELECT id FROM omeka_incite_documents WHERE item_id = ?");
    $stmt->bind_param("i", $itemID);
    $stmt->bind_result($documentID);
    $stmt->execute();
    $stmt->store_result();
    $stmt->fetch();
    if($stmt->num_rows > 0) 
    {
        
        //store concept in conjunction table
        $db->close();
        $db = DB_Connect::connectDB();
        $newStmt = $db->prepare("INSERT INTO omeka_incite_documents_subject_conjunction VALUES (NULL, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP)");
        $newStmt->bind_param("iiiiii", $documentID, $taggedTranscriptionID, $conceptID, $positive, $userID, $groupID);
        $newStmt->execute();
        $newStmt->close();
        
    }
    else
    {
        //create document then tag
        $db->close();
        $db = DB_Connect::connectDB();
        $newStmt = $db->prepare("INSERT INTO omeka_incite_documents VALUES (NULL, ?, ?, -1, 0, 1, -1, CURRENT_TIMESTAMP)");
        $newStmt->bind_param("ii", $documentID, $userID);
        $newStmt->execute();
        $id = $newStmt->insert_id;
        $newStmt->close();
        $db->close();
        $db = DB_Connect::connectDB();
        $newStmt1 = $db->prepare("INSERT INTO omeka_incite_documents_subject_conjunction VALUES (NULL, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP)");
        $newStmt1->bind_param("iiiiii", $documentID, $taggedTranscriptionID, $conceptID, $positive, $userID, $groupID);
        $newStmt1->execute();
        $newStmt1->close();
    }
}

/**
 * Return an array of document ids that have the same matching tags as another document
 * @param array $id_array to search against
 * @param int $minimum_match the number of matches per document you want to be included in the search
 */
function searchClosestMatchConcept($id_array, $minimum_match)
{
    $dictionary = array(array());
    for ($i = 0; $i < sizeof($id_array); $i++)
    {
        $db = DB_Connect::connectDB();
        $stmt = $db->prepare("SELECT document_id FROM omeka_incite_documents_suject_conjunction WHERE subject_concept_id = ?");
        $stmt->bind_param("i", $id_array[$i]);
        $stmt->bind_result($document_id);
        $stmt->execute();
        while ($stmt->fetch())
        {
            $dictionary[$id_array[$i]][] = $document_id;
        }
        $stmt->close();
        $db->close();
    }
    //dictionary setup: conceptID --> [document_ids]
    $allDocumentIDs = array();
    for ($i = 0; $i < sizeof($dictionary); $i++)
    {
        for ($j = 0; $j < sizeof($dictionary[$i]); $j++)
        {
            $allDocumentIDs[] = $dictionary[$i][$j];
        }
    }
    asort($allDocumentIDs);
    
    $frequencyChart = array_count_values($allDocumentIDs);
    $idAboveMinimum = array();
    foreach($frequencyChart as $key => $value)
    {
        if ($value >= $minimum_match)
        {
            $idAboveMinimum[] = $key;
        }
    }
    return $idAboveMinimum;
}
?>
