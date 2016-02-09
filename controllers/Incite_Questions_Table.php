<?php

require_once("DB_Connect.php");

/**
 * 
 * **USE THIS GUIDELINE THROUGHOUT PLUGIN**
 * 
 * TYPES OF QUESTIONS
 * 0 --> belongs to a specific document's transcribe question
 * 1 --> belongs to a specific document's tag question
 * 2 --> belongs to a specific document's connect question
 * 4 --> General forum question, can tag multiple documents
 * @param type $question
 * @param type $user_id
 * @param type $document_ids: references used in the question
 * @param type $type
 */
function createQuestion($question, $user_id, $document_ids, $type)
{
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("INSERT INTO omeka_incite_questions VALUES (DEFAULT, ?, ?, 1, CURRENT_TIMESTAMP, ?)");
    $stmt->bind_param("isi", $user_id, $question, $type);
    $stmt->execute();
    $insertedID = $stmt->insert_id;
    $stmt->close();
    $db->close();
    $newdb = DB_Connect::connectDB();
    for ($i = 0; $i < sizeof($document_ids); $i++)
    {
        $documentID = intval($document_ids[$i]);
        $newstmt = $newdb->prepare("INSERT INTO omeka_incite_documents_questions_conjunction VALUES (DEFAULT, ?, ?)");
        $newstmt->bind_param("ii", $documentID, $insertedID);
        $newstmt->execute();
        $newstmt->close();
    }
    return $insertedID;
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
function getAllDiscussions()
{
    $discussions = array();
    $db = DB_Connect::connectDB();
    //question_type 4 is between-documents discussion
    $stmt = $db->prepare("SELECT id, user_id, question_text, timestamp FROM omeka_incite_questions WHERE question_type = 4 ORDER BY timestamp DESC");
    $stmt->bind_result($id, $user_id, $content, $time);
    $stmt->execute();
    while ($stmt->fetch())
    {
        $discussions[] = array('id' => $id, 'user_id' => $user_id, 'title' => $content, 'time' => $time);
    }
    $stmt->close();
    $db->close();
    return $discussions;
}

function getAllDiscussionsWithNumOfReplies()
{
    $discussions = array();
    $db = DB_Connect::connectDB();
    //question_type 4 is between-documents discussion
    $stmt = $db->prepare("SELECT id, user_id, question_text, timestamp FROM omeka_incite_questions WHERE question_type = 4 ORDER BY timestamp DESC");
    $stmt->bind_result($id, $user_id, $content, $time);
    $stmt->execute();
    $newdb = DB_Connect::connectDB();
    while ($stmt->fetch())
    {
        $user_data = getUserDataID($user_id);
        $newstmt = $newdb->prepare("SELECT COUNT(*) FROM omeka_incite_replies WHERE is_active = 1 AND question_id = ?");
        $newstmt->bind_param("i", $id);
        $newstmt->bind_result($num_of_replies);
        $newstmt->execute();
        $newstmt->fetch();
        $newstmt->close();
        $discussions[$id] = array('id' => $id, 'user_id' => $user_id, 'user_first_name' => $user_data[0], 'title' => $content, 'time' => $time, 'num_of_replies' => $num_of_replies);
    }
    $stmt->close();
    $db->close();
    return $discussions;
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
    $stmt = $db->prepare("SELECT document_id FROM omeka_incite_documents_questions_conjunction WHERE question_id = ?");
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

function pullQuestionsForDocumentOnly($document_id)
{
    $idArray = array();
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("SELECT question_id FROM omeka_incite_documents_questions_conjunction INNER JOIN omeka_incite_questions ON omeka_incite_questions.id = omeka_incite_documents_questions_conjunction.question_id WHERE (question_type = 0 OR question_type = 1 OR question_type = 2) AND document_id = ? ORDER BY timestamp");
    $stmt->bind_param("i", $document_id);
    $stmt->bind_result($question_id);
    $stmt->execute();
    while ($stmt->fetch())
    {
        $idArray[] = $question_id;
    }
    $stmt->close();
    $db->close();
    return $idArray;
}
function getQuestionText($question_id)
{
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("SELECT question_text FROM omeka_incite_questions WHERE id = ?");
    $stmt->bind_param("i", $question_id);
    $stmt->bind_result($text);
    $stmt->execute();
    $stmt->fetch();
    $stmt->close();
    $db->close();
    return $text;
}
function getQuestionType($question_id)
{
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("SELECT question_type FROM omeka_incite_questions WHERE id = ?");
    $stmt->bind_param("i", $question_id);
    $stmt->bind_result($type);
    $stmt->execute();
    $stmt->fetch();
    $stmt->close();
    $db->close();
    return $type;
}
function getQuestionUser($question_id)
{
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("SELECT user_id FROM omeka_incite_questions WHERE id = ?");
    $stmt->bind_param("i", $question_id);
    $stmt->bind_result($userid);
    $stmt->execute();
    $stmt->fetch();
    $stmt->close();
    $db->close();
    return $userid;
}
function getQuestionTimestamp($question_id)
{
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("SELECT timestamp FROM omeka_incite_questions WHERE id = ?");
    $stmt->bind_param("i", $question_id);
    $stmt->bind_result($timestamp);
    $stmt->execute();
    $stmt->fetch();
    $stmt->close();
    $db->close();
    return $timestamp;
}
function getAllRepliesForQuestion($question_id)
{
    $idArray = array();
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("SELECT id FROM omeka_incite_replies WHERE question_id = ? ORDER BY id");
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
?>
