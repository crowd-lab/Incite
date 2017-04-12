<?php
/**
 * API for the transcription table
 */
require_once("DB_Connect.php");
require_once("Incite_Document_Table.php");
/**
 * Get a transcription's author's id
 * @param int $transcriptionID
 * @return int --> user id
 */
function getTranscriptionAuthorID($transcriptionID) {
    $userID = -1;
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("SELECT user_id FROM omeka_incite_transcriptions WHERE id = ?");
    $stmt->bind_param("i", $transcriptionID);
    $stmt->bind_result($userID);
    $stmt->execute();
    $stmt->fetch();
    $stmt->close();
    $db->close();
    return $userID;
}
/**
 * Get the summarized text of a specific transcription
 * @param int $documentID
 * @param int $transcriptionID
 * @return string of the summarized text
 */
function getSummarizedText($documentID, $transcriptionID) {
    $text = "";
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("SELECT summarized_text FROM omeka_incite_transcriptions WHERE item_id = ? AND id = ?");
    $stmt->bind_param("ii", $documentID, $transcriptionID);
    $stmt->bind_result($text);
    $stmt->execute();
    $stmt->fetch();
    $stmt->close();
    $db->close();
    return $text;
}
/**
 * Get the transcription text of a specific transcription id
 * @param int $transcriptionID
 * @return string of the transcription
 */
function getTranscriptionText($transcriptionID) {
    $text = "";
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("SELECT transcribed_text FROM omeka_incite_transcriptions WHERE id = ?");
    $stmt->bind_param("i", $transcriptionID);
    $stmt->bind_result($text);
    $stmt->execute();
    $stmt->fetch();
    $stmt->close();
    $db->close();
    return $text;
}

function getSummaryText($transcriptionID) {
    $text = "";
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("SELECT summarized_text FROM omeka_incite_transcriptions WHERE id = ?");
    $stmt->bind_param("i", $transcriptionID);
    $stmt->bind_result($text);
    $stmt->execute();
    $stmt->fetch();
    $stmt->close();
    $db->close();
    return $text;
}
/**
 * Get a list of ids for approved transcriptions for a document
 * @param int documentID
 * @return array of results
 */
function getApprovedTranscriptionIDs($documentID) {
    $db = DB_Connect::connectDB();
    $result = Array();
    $stmt = $db->prepare("SELECT id FROM omeka_incite_transcriptions WHERE item_id = ? AND is_approved = 1");
    $stmt->bind_param("i", $documentID);
    $stmt->bind_result($id);
    $stmt->execute();
    while ($stmt->fetch())
    {
        $result[] = $id;
    }
    return $result;
}
/**
 * Get when the transcription was approved
 * @param int $transcriptionID
 * @return string of timestamp
 */
function getApprovalTranscriptionTimestamp($transcriptionID) {
    $timestamp = "";
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("SELECT timestamp_approval FROM omeka_incite_transcriptions WHERE id = ?");
    $stmt->bind_param("i", $transcriptionID);
    $stmt->bind_result($timestamp);
    $stmt->execute();
    $stmt->fetch();
    $stmt->close();
    $db->close();
    return $timestamp;
}
/**
 * Get when the transcription was created
 * @param int $transcriptionID
 * @return string of the transcription
 */
function getTranscriptionCreationTimestamp($transcriptionID) {
    $timestamp = "";
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("SELECT timestamp_creation FROM omeka_incite_transcriptions WHERE id = ?");
    $stmt->bind_param("i", $transcriptionID);
    $stmt->bind_result($timestamp);
    $stmt->execute();
    $stmt->fetch();
    $stmt->close();
    $db->close();
    return $timestamp;
}
/**
 * Create a transcription and summary of the document
 * @param type $documentID
 * @param type $userID
 * @param int $workingGroupID
 * @param type $transcribedText
 * @param type $summarizedText
 */
function createTranscription($documentID, $userID, $workingGroupID, $transcribedText, $summarizedText, $tone) {
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("INSERT INTO omeka_incite_transcriptions VALUES (NULL, ?, ?, ?, ?, ?, ?, 1, NULL, CURRENT_TIMESTAMP)");
    $stmt->bind_param("iiisss", $documentID, $userID, $workingGroupID, $transcribedText, $summarizedText, $tone);
    $stmt->execute();
    $stmt->close();
    $db->close();
}
/**
 * Change the trasncription string a specific transcription id
 * @param int $transcriptionID
 * @param string $text
 */
function changeTranscribedText($transcriptionID, $text) {
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("UPDATE omeka_incite_transcriptions SET transcribed_text = ? WHERE id = ?");
    $stmt->bind_param("si", $text, $transcriptionID);
    $stmt->execute();
    $stmt->close();
    $db->close();
}
/**
 * Change the summarized text of a specific transcription id
 * @param int $transcriptionID
 * @param string $text
 */
function changeSummarizedText($transcriptionID, $text) {
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("UPDATE omeka_incite_transcriptions SET summarized_text = ? WHERE id = ?");
    $stmt->bind_param("si", $text, $transcriptionID);
    $stmt->execute();
    $stmt->close();
    $db->close();
}
/**
 * Approve a transcription
 * @param int $transcriptionID
 */
function approve($transcriptionID) {
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("UPDATE omeka_incite_transcriptions SET is_approved = 1 WHERE id = ?");
    $stmt->bind_param("i", $transcriptionID);
    $stmt->execute();
    $stmt->close();
    $db->close();
}
/**
 * Remove approval for a transcription
 * @param int $transcriptionID
 */
function removeApproval($transcriptionID) {
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("UPDATE omeka_incite_transcriptions SET is_approved = 0 WHERE id = ?");
    $stmt->bind_param("i", $transcriptionID);
    $stmt->execute();
    $stmt->close();
    $db->close();
}
/**
 * Delete a transcription from table
 * @param id $transcriptionID
 */
function deleteTranscription($transcriptionID) {
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("DELETE FROM omeka_incite_transcriptions WHERE id = ?");
    $stmt->bind_param("i", $transcriptionID);
    $stmt->execute();
    $stmt->close();
    $db->close();
}
/**
 * Get all transcriptions (approved or not) for a specific document
 * @param int $documentID
 * @return array of integers
 */
function getTranscriptionIDsForDocument($documentID)
{
    $db = DB_Connect::connectDB();
    $arr = Array();
    $stmt = $db->prepare("SELECT * FROM omeka_incite_transcriptions WHERE item_id = ?");
    $stmt->bind_param("i", $documentID);
    $stmt->bind_result($results);
    $stmt->execute();
    while ($stmt->fetch())
    {
        $arr[] = $results;
    }
    $stmt->close();
    $db->close();
    return $arr;
}
/**
 * Get the latest transcription, summary, tone and id for a specific document (approved or not)
 *
 * @param int $documentID
 * @return array with the info request, or empty if no transcriptions for document
 */
function getNewestTranscription($documentID) {
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("SELECT transcribed_text, summarized_text, tone, id FROM omeka_incite_transcriptions WHERE item_id = ? ORDER BY timestamp_creation DESC LIMIT 1");
    $stmt->bind_param("i", $documentID);
    $stmt->bind_result($transcription, $summary, $tone, $transcriptionID);
    $stmt->execute();
    $newest_transcription = array();
    while ($stmt->fetch())
    {
        $newest_transcription = array('transcription' => $transcription, 'summary' => $summary, 'tone' => $tone, 'id' => $transcriptionID);
    }
    $stmt->close();
    $db->close();
    return $newest_transcription;
}
/**
 * Get the 20 latest transcription edits (approved or not)
 *
 * @param int $documentID
 * @return array with the info request, or empty if no transcriptions for document
 */
function getTranscriptionRevisionHistory($documentID) {
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("SELECT omeka_incite_transcriptions.timestamp_creation, omeka_incite_users.email, omeka_incite_users.id FROM omeka_incite_transcriptions, omeka_incite_users WHERE item_id = ? AND omeka_incite_transcriptions.user_id = omeka_incite_users.id ORDER BY timestamp_creation DESC LIMIT 20");
    $stmt->bind_param("i", $documentID);
    $stmt->bind_result($timestamp, $userEmail, $userID);
    $stmt->execute();
    $transcription_history = array();
    while ($stmt->fetch())
    {
        $transcription_history[] = array('userEmail' => $userEmail, 'userID' => $userID, 'timestamp' => $timestamp);
    }
    $stmt->close();
    $db->close();
    return $transcription_history;
}
/**
 * Get all documents that do not have a transcription
 * @return array of document ids
 */
function getDocumentsWithoutTranscription()
{
    $db = DB_Connect::connectDB();
    $documents_with_transcription = array();
    //item_id is actually item_id in omeka
    $stmt = $db->prepare("SELECT item_id FROM omeka_incite_transcriptions");
    $stmt->bind_result($result);
    $stmt->execute();
    while ($stmt->fetch()) {
        $documents_with_transcription[] = $result;
    }
    $stmt->close();
    $db->close();

    $transcribable_documents = getTranscribableDocuments();

    return array_diff($transcribable_documents, $documents_with_transcription);
}

/**
 * Get all documents that have at least one transcription
 * @return array of document ids
 */
function getDocumentsWithTranscription()
{
    $db = DB_Connect::connectDB();
    $documents_with_transcription = array();
    $stmt = $db->prepare("SELECT DISTINCT item_id FROM omeka_incite_transcriptions");
    $stmt->bind_result($result);
    $stmt->execute();
    while ($stmt->fetch()) {
        $documents_with_transcription[] = $result;
    }
    $stmt->close();
    $db->close();

    return $documents_with_transcription;
}

/**
 * Get all documents that have at least one approved transcription
 * @return array of document ids
 */
function getDocumentsWithApprovedTranscription()
{
    $db = DB_Connect::connectDB();
    $documents_with_transcription = array();
    $stmt = $db->prepare("SELECT DISTINCT item_id FROM omeka_incite_transcriptions WHERE type = 1");
    $stmt->bind_result($result);
    $stmt->execute();
    while ($stmt->fetch()) {
        $documents_with_transcription[] = $result;
    }
    $stmt->close();
    $db->close();

    return $documents_with_transcription;
}

?>
