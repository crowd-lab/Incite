<?php

require_once("DB_Connect.php");

function createTag($userID, $tag_text, $category_name, $description, $documentID)
{
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("INSERT INTO omeka_incite_tags VALUES (NULL, ?, ?, CURRENT_TIMESTAMP, ?, ?");
    $stmt->bind_param("isss", $userID, $tag_text, $category_name, $description);
    $stmt->execute();
    $tagID = $stmt->insert_id;
    $stmt->close();
    
    //see if document is existing in database and tag document if exists
    //if it doesn't exist, create it with the document id and then tag it with the tag id
    
    $stmt = $db->prepare("SELECT id FROM omeka_incite_documents WHERE item_id = ?");
    $stmt->bind_param("i", $documentID);
    $stmt->bind_result($id);
    $stmt->execute();
    $stmt->fetch();
    if($stmt->num_rows > 0) 
    {
        
        //store tag in conjunction table
        $newStmt = $db->prepare("INSERT INTO omeka_incite_documents_tags_conjunction VALUES (NULL, ?, ?)");
        $newStmt->bind_param("ii", $id, $tagID);
        $newStmt->execute();
        $newStmt->close();
        
    }
    else
    {
        //create document then tag
        $newStmt = $db->prepare("INSERT INTO omeka_incite_documents VALUES (NULL, ?, ?, -1, 0, 1, -1, NULL)");
        $newStmt->bind_param("ii", $documentID, $userID);
        $newStmt->execute();
        $id = $newStmt->insert_id;
        $newStmt->close();
        $newStmt1 = $db->prepare("INSERT INTO omeka_incite_documents_tags_conjunction VALUES (NULL, ?, ?)");
        $newStmt1->bind_param("ii", $id, $tagID);
        $newStmt1->execute();
        $newStmt1->close();
    }
}
function findTagOnDescription($description)
{
    $results = Array();
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("SELECT * FROM omeka_incite_tags WHERE description LIKE %?%");
    $stmt->bind_param("s", $description);
    $stmt->bind_result($tagObject);
    while ($stmt->fetch())
    {
        $results[] = $tagObject;
    }
    $stmt->close();
    $db->close();
    return $tagObject;
}

function findTagOnName($name)
{
    $results = Array();
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("SELECT * FROM omeka_incite_tags WHERE tag_text LIKE %?%");
    $stmt->bind_param("s", $name);
    $stmt->bind_result($tagObject);
    while ($stmt->fetch())
    {
        $results[] = $tagObject;
    }
    $stmt->close();
    $db->close();
    return $tagObject;
}
function findTagOnUserId($userID)
{
    $results = Array();
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("SELECT * FROM omeka_incite_tags WHERE user_id = ?");
    $stmt->bind_param("i", $userID);
    $stmt->bind_result($tagObject);
    while ($stmt->fetch())
    {
        $results[] = $tagObject;
    }
    $stmt->close();
    $db->close();
    return $tagObject;
}
function findTagOnCategory($category)
{
    $results = Array();
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("SELECT * FROM omeka_incite_tags WHERE category_name = ?");
    $stmt->bind_param("i", $category);
    $stmt->bind_result($tagObject);
    while ($stmt->fetch())
    {
        $results[] = $tagObject;
    }
    $stmt->close();
    $db->close();
    return $tagObject;
}

?>
