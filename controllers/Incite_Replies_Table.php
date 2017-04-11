<?php

require_once("DB_Connect.php");

function replyToQuestion($reply, $user_id, $question_id, $item_id)
{
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("INSERT INTO omeka_incite_replies VALUES (DEFAULT, ?, ?, ?, 1, CURRENT_TIMESTAMP)");
    $stmt->bind_param("isi", $user_id, $reply, $question_id);
    $stmt->execute();
    $insertedID = $stmt->insert_id;
    $stmt->close();
    $db->close();
    
    $db = DB_Connect::connectDB();
    for ($i = 0; $i < sizeof($item_id); $i++)
    {
        $documentID = intval($item_id[$i]);
        $stmt = $db->prepare("INSERT INTO omeka_incite_documents_replies_conjunction VALUES (DEFAULT, ?, ?)");
        $stmt->bind_param("ii", $documentID, $insertedID);
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
function getAllReferencedDocumentIDsForReply($reply_id)
{
    $idArray = array();
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("SELECT item_id FROM omeka_incite_document_replies_conjunction WHERE reply_id = ?");
    $stmt->bind_param("i", $reply_id);
    $stmt->bind_result($item_id);
    $stmt->execute();
    while ($stmt->fetch())
    {
        $idArray[] = $item_id;
    }
    $stmt->close();
    $db->close();
    return $idArray;
}
function getReplyText($reply_id)
{
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("SELECT reply_text FROM omeka_incite_replies WHERE id = ?");
    $stmt->bind_param("i", $reply_id);
    $stmt->bind_result($text);
    $stmt->execute();
    $stmt->fetch();
    $stmt->close();
    $db->close();
    return $text;
}
function getReplyTimestamp($reply_id)
{
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("SELECT timestamp FROM omeka_incite_replies WHERE id = ?");
    $stmt->bind_param("i", $reply_id);
    $stmt->bind_result($timestamp);
    $stmt->execute();
    $stmt->fetch();
    $stmt->close();
    $db->close();
    return $timestamp;
}
function getUserIdForReply($reply_id)
{
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("SELECT user_id FROM omeka_incite_replies WHERE id = ?");
    $stmt->bind_param("i", $reply_id);
    $stmt->bind_result($userID);
    $stmt->execute();
    $stmt->fetch();
    $stmt->close();
    $db->close();
    return $userID;
}
?>
