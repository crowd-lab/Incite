<?php

require_once("DB_Connect.php");

function replyToQuestion($reply, $user_id, $question_id, $document_id)
{
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("INSERT INTO omeka_incite_replies VALUES (DEFAULT, ?, ?, ?, 1, CURRENT_TIMESTAMP)");
    $stmt->bind_param("isi", $user_id, $reply, $question_id);
    $stmt->execute();
    $insertedID = $stmt->insert_id;
    $stmt->close();
    $db->close();
    
    $db = DB_Connect::connectDB();
    for ($i = 0; $i < sizeof($document_id); $i++)
    {
        $stmt = $db->prepare("INSERT INTO omeka_incite_replies_conjunction VALUES (DEFAULT, ?, ?)");
        $stmt->bind_param("ii", $document_id[$i], $insertedID);
        $stmt->execute();
        $stmt->close();
    }
}
function deleteReply($id)
{
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("DELETE FROM omeka_incite_documents_replies_conjunction WHERE reply_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    $db->close();
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("DELETE FROM omeka_incite_replies WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    $db->close();
}
function changeReplyText($id, $text)
{
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("UPDATE omeka_incite_replies SET reply_text = ?, timestamp = CURRENT_TIMESTAMP WHERE id = ?");
    $stmt->bind_param("si", $text, $id);
    $stmt->execute();
    $stmt->close();
    $db->close();
}
function deactivateReply($id)
{
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("UPDATE omeka_incite_replies SET is_active = 0 WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    $db->close();
}
function reactiveReply($id)
{
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("UPDATE omeka_incite_replies SET is_active = 1 WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    $db->close();
}
function getAllReplyIDsForUserId($user_id)
{
    $idArray = array();
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("SELECT id FROM omeka_incite_replies WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->bind_result($id);
    $stmt->execute();
    while ($stmt->fetch())
    {
        $idArray[] = $id;
    }
    $stmt->close();
    $db->close();
    return $idArray;
}
function getAllReferencedDocumentIDs($reply_id)
{
    $idArray = array();
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("SELECT document_id FROM omeka_incite_document_replies_conjunction WHERE reply_id = ?");
    $stmt->bind_param("i", $reply_id);
    $stmt->bind_result($document_id);
    $stmt->execute();
    while ($stmt->fetch())
    {
        $idArray[] = $document_id;
    }
    $stmt->close();
    $db->close();
    return $idArray;
}
?>