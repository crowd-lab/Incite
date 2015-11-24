<?php
/**
 * API for the transcription table
 */
require_once("DB_Connect.php");
<<<<<<< HEAD
/**
 * Get a transcription's author's id
 * @param int $transcriptionID
 * @return int --> user id
 */
=======
require_once("Incite_Document_Table.php");

>>>>>>> 2c91187de42103f6f0b5cb0ee7bff9f9e0dab04c
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
    $stmt = $db->prepare("SELECT summarized_text FROM omeka_incite_transcriptions WHERE document_id = ? AND id = ?");
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
/**
 * Get a list of ids for approved transcriptions for a document
 * @param int documentID
 * @return array of results
 */
function getIsAnyTranscriptionApproved($documentID) {
    $db = DB_Connect::connectDB();
    $result = Array();
    $stmt = $db->prepare("SELECT id FROM omeka_incite_transcriptions WHERE document_id = ? AND is_approved = 1");
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
 * Gets the transcription status of a document based on the document id
 * @param int $documentID
 * @return int of status
 */
function getTranscriptionStatus($documentID) {
    $status = -1;
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("SELECT transcription_status FROM omeka_incite_transcriptions WHERE document_id = ?");
    $stmt->bind_param("i", $documentID);
    $stmt->bind_result($status);
    $stmt->execute();
    $stmt->fetch();
    $stmt->close();
    $db->close();
    return $status;
}
/**
 * Create a transcription and summary of the document
 * @param type $documentID
 * @param type $userID
 * @param type $transcribedText
 * @param type $summarizedText
 */
function createTranscription($documentID, $userID, $transcribedText, $summarizedText) {
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("INSERT INTO omeka_incite_transcriptions VALUES (NULL, ?, ?, ?, ?, 1, NULL, CURRENT_TIMESTAMP)");
    $stmt->bind_param("iiss", $documentID, $userID, $transcribedText, $summarizedText);
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
    $stmt = $db->prepare("SELECT * FROM omeka_incite_transcriptions WHERE document_id = ?");
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
 * Get all documents that do not have a transcription
 * @return array of document ids
 */
function getDocumentsWithoutTranscription()
{
    $db = DB_Connect::connectDB();
    $transcription_ids = array();
    $stmt = $db->prepare("SELECT document_id FROM omeka_incite_transcriptions");
    $stmt->bind_result($result);
    $stmt->execute();
    while ($stmt->fetch()) {
        $transcription_ids[] = $result;
    }
    $stmt->close();
    $db->close();

    $transcribable_documents = getTranscribableDocuments();

<<<<<<< HEAD
    $db = DB_Connect::connectDB();
    $documents_with_jpeg = array();  //document id's and assume documents with jpeg all need transcriptions
    $stmt = $db->prepare("SELECT item_id FROM omeka_files WHERE mime_type = image/jpeg");
    $stmt->bind_result($result);
    $stmt->execute();
    while ($stmt->fetch()) {
        $documents_with_jpeg[] = $result;
    }
    $stmt->close();
    $db->close();

    return array_diff($documents_with_jpeg, $transcription_ids);
=======
    return array_diff($transcribable_documents, $transcription_ids);
>>>>>>> 2c91187de42103f6f0b5cb0ee7bff9f9e0dab04c
}

?>
