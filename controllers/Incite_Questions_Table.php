<?php

require_once("DB_Connect.php");


function createQuestion($question, $user_id, $document_id, $type)
{
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("INSERT INTO omeka_incite_questions VALUES (DEFAULT, ?, ?, 1, CURRENT_TIMESTAMP, ?)");
    $stmt->bind_param("is", $user_id, $question, $type);
    $stmt->execute();
    $insertedID = $stmt->insert_id;
    $stmt->close();
    $db->close();
    
    $db = DB_Connect::connectDB();
    for ($i = 0; $i < sizeof($document_id); $i++)
    {
        $stmt = $db->prepare("INSERT INTO omeka_incite_questions_conjunction VALUES (DEFAULT, ?, ?)");
        $stmt->bind_param("ii", $document_id[$i], $insertedID);
        $stmt->execute();
        $stmt->close();
    }
    
}

function getAllQuestionsForUserID($user_id)
{
    $idArray = array();
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("SELECT id FROM omeka_incite_questions WHERE user_id = ?");
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

function disableQuestion($id)
{
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("UPDATE omeka_incite_questions SET is_active = 0 WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    $db->close();
}

function changeQuestionText($id, $text)
{
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("UPDATE omeka_incite_questions SET question_text = ? WHERE id = ?");
    $stmt->bind_param("si", $text, $id);
    $stmt->execute();
    $stmt->close();
    $db->close();
}

function deleteQuestion($id)
{

    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("DELETE FROM omeka_incite_questions WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    $db->close();
    
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("DELETE FROM omeka_incite_questions_conjunction WHERE question_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    $db->close();
}
function getAllReplyIDsForQuestion($question_id)
{
    $idArray = array();
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("SELECT id FROM omeka_incite_replies WHERE question_id = ?");
    $stmt->bind_param("i", $question_id);
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
function getAllReferencedDocumentIdsForQuestion($question_id)
{
    $idArray = array();
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("SELECT document_id FROM omeka_incite_document_questions_conjunction WHERE question_id = ?");
    $stmt->bind_param("i", $question_id);
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