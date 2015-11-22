<?php

require_once("DB_Connect.php");

function createTag($userID, $tag_text, $category, $subcategory, $description, $documentID)
{
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("INSERT INTO omeka_incite_tags VALUES (NULL, ?, ?, CURRENT_TIMESTAMP, ?, ?)");
    $stmt->bind_param('isis', $userID, $tag_text, $category, $description);
    $stmt->execute();
    $tagID = $stmt->insert_id;
    $stmt->close();
    
    //see if document is existing in database and tag document if exists
    //if it doesn't exist, create it with the document id and then tag it with the tag id
    $db->close();
    $count = 0;
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("SELECT COUNT(*), id FROM omeka_incite_documents WHERE item_id = ?");
    $stmt->bind_param("i", $documentID);
    $stmt->bind_result($count, $id);
    $stmt->execute();
    $stmt->fetch();
    if($count > 0) 
    {
        //store tag in conjunction table
        $db->close();
        $db = DB_Connect::connectDB();
        $newStmt = $db->prepare("INSERT INTO omeka_incite_documents_tags_conjunction VALUES (NULL, ?, ?)");
        $newStmt->bind_param("ii", $id, $tagID);
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
        $newStmt1 = $db->prepare("INSERT INTO omeka_incite_documents_tags_conjunction VALUES (NULL, ?, ?)");
        $newStmt1->bind_param("ii", $id, $tagID);
        $newStmt1->execute();
        $newStmt1->close();
    }
    //store the tag with the respective subcategories
    //since there could be multiple subcategories, do this in a loop
    $db = DB_Connect::connectDB();
    for ($i = 0; $i < sizeof($subcategory); $i++)
    {
        $insertSubCat = $db->prepare("INSERT INTO omeka_incite_tags_subcategory_conjunction VALUES (?, ?)");
        $insertSubCat->bind_param("ii", $tagID, $subcategory[$i]);
        $insertSubCat->execute();
        $insertSubCat->close();
    }
    $db->close();
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
    return $results;
}
function getAllCategories()
{
    $results = Array();
    $count = 0;
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("SELECT * FROM omeka_incite_tags_category");
    $stmt->bind_result($id, $name);
    $stmt->execute();
    while ($stmt->fetch())
    {
        $results[] = Array("id" => $id, "name" => $name, "subcategory" => array());
        
        $newDB = DB_Connect::connectDB();
        $subcategoryStmt = $newDB->prepare("SELECT id, name, created_by, timestamp FROM omeka_incite_tags_subcategory WHERE category_id = ?");
        $subcategoryStmt->bind_param("i", $id);
        $subcategoryStmt->bind_result($subID, $subName, $subCreatedBy, $subTimestamp);
        $subcategoryStmt->execute();
        while ($subcategoryStmt->fetch())
        {
            $results[$count]["subcategory"][] = Array("subcategory_id" => $subID, "subcategory" => $subName, "subcategory_created_by" => $subCreatedBy, "subcategory_timestamp" => $subTimestamp);
        }
        $subcategoryStmt->close();
        $newDB->close();
        $count++;
    }
    $stmt->close();
    $db->close();
    return $results;
}
function isDocumentTagged($documentID)
{
    $count = 0;
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("SELECT COUNT(*) FROM omeka_incite_documents_tags_conjunction WHERE document_id = ?");
    $stmt->bind_param("i", $documentID);
    $stmt->bind_result($count);
    $stmt->execute();
    $stmt->fetch();
    $stmt->close();
    if ($count >= 10)
    {
        return true;
    }
    else
    {
        return false;
    }
}
?>
