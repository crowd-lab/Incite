<?php

require_once("DB_Connect.php");

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

function createTranscription($documentID, $userID, $transcribedText, $summarizedText) {
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("INSERT INTO omeka_incite_transcriptions VALUES (NULL, ?, ?, ?, ?, 1, NULL, CURRENT_TIMESTAMP)");
    $stmt->bind_param("iiss", $documentID, $userID, $transcribedText, $summarizedText);
    $stmt->execute();
    $stmt->close();
    $db->close();
}

function changeTranscribedText($transcriptionID, $text) {
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("UPDATE omeka_incite_transcriptions SET transcribed_text = ? WHERE id = ?");
    $stmt->bind_param("si", $text, $transcriptionID);
    $stmt->execute();
    $stmt->close();
    $db->close();
}

function changeSummarizedText($transcriptionID, $text) {
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("UPDATE omeka_incite_transcriptions SET summarized_text = ? WHERE id = ?");
    $stmt->bind_param("si", $text, $transcriptionID);
    $stmt->execute();
    $stmt->close();
    $db->close();
}

function approve($transcriptionID) {
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("UPDATE omeka_incite_transcriptions SET is_approved = 1 WHERE id = ?");
    $stmt->bind_param("i", $transcriptionID);
    $stmt->execute();
    $stmt->close();
    $db->close();
}

function removeApproval($transcriptionID) {
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("UPDATE omeka_incite_transcriptions SET is_approved = 0 WHERE id = ?");
    $stmt->bind_param("i", $transcriptionID);
    $stmt->execute();
    $stmt->close();
    $db->close();
}

function deleteTranscription($transcriptionID) {
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("DELETE FROM omeka_incite_transcriptions WHERE id = ?");
    $stmt->bind_param("i", $transcriptionID);
    $stmt->execute();
    $stmt->close();
    $db->close();
}
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

function getDocumentsWithoutTranscription()
{
    $db = DB_Connect::connectDB();
    $transcription_ids = array();
    $stmt = $db->prepare("SELECT `document_id` FROM `omeka_incite_transcriptions`");
    $stmt->bind_result($result);
    $stmt->execute();
    while ($stmt->fetch()) {
        $transcription_ids[] = $result;
    }
    $stmt->close();
    $db->close();


    $db = DB_Connect::connectDB();
    $documents_with_jpeg = array();  //document id's and assume documents with jpeg all need transcriptions
    $stmt = $db->prepare("SELECT `item_id` FROM `omeka_files` WHERE `mime_type` = 'image/jpeg'");
    $stmt->bind_result($result);
    $stmt->execute();
    while ($stmt->fetch()) {
        $documents_with_jpeg[] = $result;
    }
    $stmt->close();
    $db->close();

    return array_diff($documents_with_jpeg, $transcription_ids);
}

?>
