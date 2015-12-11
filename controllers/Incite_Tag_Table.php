<?php
/**
 * API for the Incite_Tag_Table
 */
require_once("DB_Connect.php");
require_once("Incite_Document_Table.php");

/**
 * Creates a tag for a specific document.
 * @param int $userID
 * @param int $tag_text
 * @param int $category
 * @param int $subcategory
 * @param string $description
 * @param int $documentID
 */
function createTag($userID, $tag_text, $category, $subcategory, $description, $documentID) {
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
    if ($count > 0) {
        //store tag in conjunction table
        $db->close();
        $db = DB_Connect::connectDB();
        $newStmt = $db->prepare("INSERT INTO omeka_incite_documents_tags_conjunction VALUES (NULL, ?, ?)");
        $newStmt->bind_param("ii", $id, $tagID);
        $newStmt->execute();
        $newStmt->close();
    } else {
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
    for ($i = 0; $i < sizeof($subcategory); $i++) {
        $insertSubCat = $db->prepare("INSERT INTO omeka_incite_tags_subcategory_conjunction VALUES (?, ?)");
        $insertSubCat->bind_param("ii", $tagID, $subcategory[$i]);
        $insertSubCat->execute();
        $insertSubCat->close();
    }
    $db->close();
}
/**
 * Finds a tag based on a description
 * @param string $description
 * @return array of results
 */
function findTagOnDescription($description) {
    $results = Array();
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("SELECT * FROM omeka_incite_tags WHERE description LIKE %?%");
    $stmt->bind_param("s", $description);
    $stmt->bind_result($tagObject);
    while ($stmt->fetch()) {
        $results[] = $tagObject;
    }
    $stmt->close();
    $db->close();
    return $tagObject;
}
/**
 * Finds tags by name
 * @param String $name
 * @return an array of results
 */
function findTagOnName($name) {
    $results = Array();
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("SELECT * FROM omeka_incite_tags WHERE tag_text LIKE %?%");
    $stmt->bind_param("s", $name);
    $stmt->bind_result($tagObject);
    while ($stmt->fetch()) {
        $results[] = $tagObject;
    }
    $stmt->close();
    $db->close();
    return $tagObject;
}
/**
 * Finds tags based on who entered the tag
 * @param int $userID
 * @return an array of results
 */
function findTagOnUserId($userID) {
    $results = Array();
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("SELECT * FROM omeka_incite_tags WHERE user_id = ?");
    $stmt->bind_param("i", $userID);
    $stmt->bind_result($tagObject);
    while ($stmt->fetch()) {
        $results[] = $tagObject;
    }
    $stmt->close();
    $db->close();
    return $tagObject;
}
/**
 * Find tags based on a category id
 * @param int $category
 * @return array of results
 */
function findTagOnCategory($category) {
    $results = Array();
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("SELECT * FROM omeka_incite_tags INNER JOIN omeka_incite_tags_category ON category_id = omeka_incite_tags_category.id WHERE omeka_incite_tags_category.id = ?");
    $stmt->bind_param("i", $category);
    $stmt->bind_result($tagObject);
    while ($stmt->fetch()) {
        $results[] = $tagObject;
    }
    $stmt->close();
    $db->close();
    return $results;
}
/**
 * Returns a list of all categories along with their subcategory information
 * @return an array of results
 */
function getAllCategories() {
    $results = Array();
    $count = 0;
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("SELECT * FROM omeka_incite_tags_category");
    $stmt->bind_result($id, $name);
    $stmt->execute();
    while ($stmt->fetch()) {
        $results[] = Array("id" => $id, "name" => $name, "subcategory" => array());

        $newDB = DB_Connect::connectDB();
        $subcategoryStmt = $newDB->prepare("SELECT id, name, created_by, timestamp FROM omeka_incite_tags_subcategory WHERE category_id = ?");
        $subcategoryStmt->bind_param("i", $id);
        $subcategoryStmt->bind_result($subID, $subName, $subCreatedBy, $subTimestamp);
        $subcategoryStmt->execute();
        while ($subcategoryStmt->fetch()) {
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
/**
 * Returns true is a document is tagged, false otherwise
 * @param int $documentID
 * @return boolean
 */
function isDocumentTagged($documentID) {
    $count = 0;
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("SELECT COUNT(*) FROM omeka_incite_documents_tags_conjunction WHERE document_id = ?");
    $stmt->bind_param("i", $documentID);
    $stmt->bind_result($count);
    $stmt->execute();
    $stmt->fetch();
    $stmt->close();
    if ($count > 0) {
        return true;
    } else {
        return false;
    }
}

/**
 * @param $document_id to pull tags in reference to
 * $document_id is also the item_id
 * @return array of all tags in specified format:
 * [0]["tag_id", "user_id", "document_id", "tag_text", "timestamp", "category_name", "subcategories", "description"]
 */
function getAllTagInformation($item_id) 
{
    $db5 = DB_Connect::connectDB();
    $getDocumentId = $db5->prepare("SELECT id FROM omeka_incite_documents WHERE item_id = ?");
    $getDocumentId->bind_param("i", $item_id);
    $getDocumentId->bind_result($document_id);
    $getDocumentId->execute();
    $getDocumentId->fetch();
    $getDocumentId->close();
    $db5->close();
    
    $dataArray = array();
    $db4 = DB_Connect::connectDB();
    $getTagID = $db4->prepare("SELECT DISTINCT tag_id FROM omeka_incite_documents_tags_conjunction WHERE document_id = ?");
    $getTagID->bind_param("i", $document_id);
    $getTagID->bind_result($id);
    $getTagID->execute();
    while ($getTagID->fetch()) {

        $db = DB_Connect::connectDB();
        $stmt = $db->prepare("SELECT * FROM omeka_incite_tags WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->bind_result($tag_id, $user_id, $tag_text, $timestamp, $category_id, $description);
        $stmt->execute();
        $stmt->fetch();
        $db->close();
        $stmt->close();
        
        $db1 = DB_Connect::connectDB();
        $db2 = DB_Connect::connectDB();
        
        
        $category_name = "";
        $getCategoryName = $db1->prepare("SELECT name FROM omeka_incite_tags_category WHERE id = ?");
        $getCategoryName->bind_param("i", $category_id);
        $getCategoryName->bind_result($category_name);
        $getCategoryName->execute();
        $getCategoryName->fetch();
        $getCategoryName->close();
        $db1->close();
        $subcategory = array();

        $getSubCategories = $db2->prepare("SELECT name FROM `omeka_incite_tags_subcategory` INNER JOIN omeka_incite_tags_subcategory_conjunction ON omeka_incite_tags_subcategory.id = omeka_incite_tags_subcategory_conjunction.subcategory_id WHERE omeka_incite_tags_subcategory_conjunction.tag_id = ?");
        $getSubCategories->bind_param("i", $id);
        $getSubCategories->bind_result($subcategory_name);
        $getSubCategories->execute();
        while ($getSubCategories->fetch()) {
            $subcategory[] = $subcategory_name;
        }
        $getSubCategories->close();
        $db2->close();

        $dataArray[] = array("tag_id" => $id, "user_id" => $user_id, "document_id" => $item_id, "tag_text" => $tag_text, "timestamp" => $timestamp, "category_name" => $category_name, "subcategories" => $subcategory, "description" => $description);
    }
    $getTagID->close();
    $db4->close();
    return $dataArray;
}
/**
 * Gets all document id with out tags
 * @return an array of results
 */
function getDocumentsWithoutTag()
{
    $db = DB_Connect::connectDB();
    $tagged_document_ids = array();
    $stmt = $db->prepare("SELECT DISTINCT `omeka_incite_documents`.`item_id` FROM `omeka_incite_documents` INNER JOIN `omeka_incite_documents_tags_conjunction` ON `omeka_incite_documents`.`id` = `omeka_incite_documents_tags_conjunction`.`document_id`");
    $stmt->bind_result($result);
    $stmt->execute();
    while ($stmt->fetch()) {
        $tagged_document_ids[] = $result;
    }
    $stmt->close();
    $db->close();

    //Select document that are untranscribed but transcribable. Since currently if there is not transcription for the document to be tagged, the user will be redirected to transcribe task.
    //$taggable_documents = getTranscribableDocuments();

    //Select documents that have approved transcriptions
    $taggable_documents = getDocumentsWithApprovedTranscription();

    return array_diff($taggable_documents, $tagged_document_ids);
}
/**
 * Return an array of document ids that have the same matching tags as another document
 * @param array $id_array to search against
 * @param int $minimum_match the number of matches per document you want to be included in the search
 */
function searchClosestMatch($id_array, $minimum_match)
{
    $dictionary = array(array());
    for ($i = 0; $i < sizeof($id_array); $i++)
    {
        $db = DB_Connect::connectDB();
        $stmt = $db->prepare("SELECT document_id FROM omeka_incite_documents_tags_conjunction WHERE tag_id = ?");
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
    //dictionary setup: tagID --> [document_ids]
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
