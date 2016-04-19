<?php
/**
 * API for the Incite_Tag_Table
 */
require_once("DB_Connect.php");
require_once("Incite_Document_Table.php");
require_once("Incite_Env_Setting.php");

/**
 * Creates a tag for a specific document.
 * @param int $userID
 * @param int $groupID
 * @param int $tag_text
 * @param int $category
 * @param int $subcategory
 * @param string $description
 * @param int $documentID
 */
function createTag($userID, $groupID, $tag_text, $category, $subcategory, $description, $documentID) {
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("INSERT INTO omeka_incite_tags VALUES (NULL, ?, ?, ?, CURRENT_TIMESTAMP, ?, ?)");
    $stmt->bind_param('iisis', $userID, $groupID, $tag_text, $category, $description);
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
function getSubcategoryIdAndNames() {
    $results = Array();
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("SELECT * FROM omeka_incite_tags_category");
    $stmt->bind_result($id, $name);
    $stmt->execute();
    while ($stmt->fetch()) {
        $results[$id] = $name;
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
 * Returns a list of all tagged documents in item id
 * @return an array of results
 */
function getAllTaggedDocuments() {
    $item_ids = array();
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("SELECT DISTINCT omeka_incite_documents.item_id FROM omeka_incite_documents_tags_conjunction JOIN omeka_incite_documents ON omeka_incite_documents_tags_conjunction.document_id=omeka_incite_documents.id");
    $stmt->bind_result($item_id);
    $stmt->execute();
    while ($stmt->fetch()) {
        $item_ids[] = $item_id;
    }
    $stmt->close();
    $db->close();
    return $item_ids;
}
/**
 * Returns true if a document is tagged, false otherwise
 * @param int $itemID
 * @return boolean
 */
function getAllTaggedTranscriptions($itemID) {
    $count = 0;
    $db = DB_Connect::connectDB();
    $transcriptions = array();
    $stmt = $db->prepare("SELECT tagged_transcription FROM omeka_incite_tagged_transcriptions WHERE item_id = ? AND is_approved = 1");
    $stmt->bind_param("i", $itemID);
    $stmt->bind_result($transcription);
    $stmt->execute();
    while( $stmt->fetch() ) {
        $transcriptions[] = $transcription;
    }
    $stmt->close();
    $db->close();
    return $transcriptions;
}
/**
 * Returns true if a document is tagged, false otherwise
 * @param int $itemID
 * @return boolean
 */
function hasTaggedTranscription($itemID) {
    $count = 0;
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("SELECT COUNT(*) FROM omeka_incite_tagged_transcriptions WHERE item_id = ?");
    $stmt->bind_param("i", $itemID);
    $stmt->bind_result($count);
    $stmt->execute();
    $stmt->fetch();
    $stmt->close();
    $db->close();
    if ($count > 0) {
        return true;
    } else {
        return false;
    }
}
/**
 * Returns true if a document is tagged, false otherwise
 * @param int $itemID
 * @return boolean
 */
function isDocumentTagged($itemID) {
    $count = 0;
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("SELECT COUNT(*) FROM omeka_incite_documents INNER JOIN omeka_incite_documents_tags_conjunction ON omeka_incite_documents_tags_conjunction.document_id = omeka_incite_documents.id WHERE item_id = ?");
    $stmt->bind_param("i", $itemID);
    $stmt->bind_result($count);
    $stmt->execute();
    $stmt->fetch();
    $stmt->close();
    $db->close();
    if ($count > 0) {
        return true;
    } else {
        return false;
    }
}

/**
 * Returns true if a tag exists
 * @param string $tag
 * @return boolean
 */
function tagExists($tag) {
    $count = 0;
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("SELECT COUNT(*) FROM `omeka_incite_tags` WHERE `tag_text` = ?");
    $stmt->bind_param("s", $tag);
    $stmt->bind_result($count);
    $stmt->execute();
    $stmt->fetch();
    $stmt->close();
    $db->close();
    if ($count > 0) {
        return true;
    } else {
        return false;
    }
}

/**
 * Returns true if a tag exists in a document
 * @param string $tag, int item_id
 * @return boolean
 */
function tagExistsInDocument($tag, $itemID) {
    $count = 0;
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("SELECT COUNT(*) FROM `omeka_incite_tags` JOIN `omeka_incite_documents_tags_conjunction` ON `omeka_incite_tags`.`id` = `omeka_incite_documents_tags_conjunction`.`tag_id` JOIN `omeka_incite_documents` ON `omeka_incite_documents`.`id` = `omeka_incite_documents_tags_conjunction`.`document_id` WHERE `tag_text` = ? AND `item_id` = ?");
    $stmt->bind_param("si", $tag, $itemID);
    $stmt->bind_result($count);
    $stmt->execute();
    $stmt->fetch();
    $stmt->close();
    $db->close();
    if ($count > 0) {
        return true;
    } else {
        return false;
    }
}

/**
 * Remove all tags in a document
 * @param int item_id
 */
function removeAllTagsFromDocument($itemID) {
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("SELECT `omeka_incite_tags`.`id`, `omeka_incite_documents_tags_conjunction`.`document_id` FROM `omeka_incite_tags` JOIN `omeka_incite_documents_tags_conjunction` ON `omeka_incite_tags`.`id` = `omeka_incite_documents_tags_conjunction`.`tag_id` JOIN `omeka_incite_documents` ON `omeka_incite_documents`.`id` = `omeka_incite_documents_tags_conjunction`.`document_id` WHERE `item_id` = ?");
    $stmt->bind_param("i", $itemID);
    $stmt->bind_result($tag_id, $document_id);
    $stmt->execute();
    while ($stmt->fetch()) {
        $db2 = DB_Connect::connectDB();
        $stmt2 = $db2->prepare("DELETE FROM omeka_incite_tags WHERE id = ?");
        $stmt2->bind_param("i", $tag_id);
        $stmt2->execute();
        $stmt2->close();
        $db2->close();

        $db2 = DB_Connect::connectDB();
        $stmt2 = $db2->prepare("DELETE FROM omeka_incite_documents_tags_conjunction WHERE document_id = ? AND tag_id = ?");
        $stmt2->bind_param("ii", $document_id, $tag_id);
        $stmt2->execute();
        $stmt2->close();
        $db2->close();
    }
    $stmt->close();
    $db->close();
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
 * TODO finish this guy up
 */
function getNewestTagsForDocument($documentID) {
    return array();
}
/**
 * Gets all tag names of a document by item id
 * @return an array of results
 */
function getTagNamesOnId($item_id)
{
    $db = DB_Connect::connectDB();
    $tag_names = array();
    $stmt = $db->prepare("SELECT DISTINCT omeka_incite_tags.tag_text FROM omeka_incite_tags JOIN omeka_incite_documents_tags_conjunction on omeka_incite_documents_tags_conjunction.tag_id=omeka_incite_tags.id JOIN omeka_incite_documents ON omeka_incite_documents.id=omeka_incite_documents_tags_conjunction.document_id WHERE omeka_incite_documents.item_id = ?");
    $stmt->bind_param("i", $item_id);
    $stmt->bind_result($tag_name);
    $stmt->execute();
    while ($stmt->fetch()) {
        $tag_names[] = $tag_name;
    }
    $stmt->close();
    $db->close();
    return $tag_names;
}
/**
 * Return an array of document ids that have at least $minimum of the given tag names
 * @param array of tag id $tag_name_array to search against
 * @param int $minimum_match the minimum number of matches (common tags) per document you want to be included in the search
 */
function findDocumentsWithAtLeastNofGivenTagNames($tag_name_array, $N)
{
    $dictionary = array();
    for ($i = 0; $i < sizeof($tag_name_array); $i++)
    {
        $db = DB_Connect::connectDB();
        $stmt = $db->prepare("SELECT DISTINCT item_id FROM omeka_incite_documents_tags_conjunction JOIN omeka_incite_tags on omeka_incite_documents_tags_conjunction.tag_id=omeka_incite_tags.id JOIN omeka_incite_documents ON omeka_incite_documents.id = omeka_incite_documents_tags_conjunction.document_id WHERE omeka_incite_tags.tag_text = ?");
        $stmt->bind_param("s", $tag_name_array[$i]);
        $stmt->bind_result($document_id);
        $stmt->execute();
        while ($stmt->fetch())
        {
            $dictionary[$tag_name_array[$i]][] = $document_id;
        }
        $stmt->close();
        $db->close();
    }
    //dictionary setup: tagID --> [document_ids]
    $allDocumentIDs = array();
    foreach ((array)$dictionary as $tag_name) {
        for ($i = 0; $i < count($tag_name); $i++) {
            $allDocumentIDs[] = $tag_name[$i];
        }
    }
    asort($allDocumentIDs);
    
    $frequencyChart = array_count_values($allDocumentIDs);
    $idAboveMinimum = array();
    foreach($frequencyChart as $doc_id => $num_of_tags)
    {
        if ($num_of_tags >= $N)
        {
            $idAboveMinimum[] = $doc_id;
        }
    }

    
    return $idAboveMinimum;
}
function findRelatedDocumentsViaAtLeastNCommonTags($self_id, $minimum_common_tags = MINIMUM_COMMON_TAGS_FOR_RELATED_DOCUMENTS) {
    $tag_names = getTagNamesOnId($self_id);
    $related = array();

    if ($minimum_common_tags > count($tag_names))
        return $related;

    $related = findDocumentsWithAtLeastNofGivenTagNames($tag_names, $minimum_common_tags);

    return array_values(array_diff($related, array($self_id)));
}
/**
 * Return an array of tag ids and names that are in common of given documents (by item_ids)
 * @param array of item id $item_ids to search against
 */
function findCommonTagNames($item_ids)
{
    $tags_for_items = array();
    for ($i = 0; $i < sizeof($item_ids); $i++)
    {
        $tags_for_one_item = array();
        $db = DB_Connect::connectDB();
        //$stmt = $db->prepare("SELECT tag_id, tag_text FROM omeka_incite_tags JOIN omeka_incite_documents_tags_conjunction ON omeka_incite_tags.id = omeka_incite_documents_tags_conjunction.tag_id JOIN omeka_incite_documents ON omeka_incite_documents_tags_conjunction.document_id = omeka_incite_documents.id WHERE omeka_incite_documents.item_id = ?");
        $stmt = $db->prepare("SELECT DISTINCT tag_text FROM omeka_incite_tags JOIN omeka_incite_documents_tags_conjunction ON omeka_incite_tags.id = omeka_incite_documents_tags_conjunction.tag_id JOIN omeka_incite_documents ON omeka_incite_documents_tags_conjunction.document_id = omeka_incite_documents.id WHERE omeka_incite_documents.item_id = ?");
        $stmt->bind_param("i", $item_ids[$i]);
        $stmt->bind_result($tag_name);
        $stmt->execute();
        while ($stmt->fetch())
        {
            $tags_for_one_item[] = $tag_name;
        }
        $stmt->close();
        $db->close();
        $tags_for_items[] = $tags_for_one_item;
    }
    if (count($tags_for_items) == 1)
        return $tags_for_items[0];
    else
        return array_values(call_user_func_array('array_intersect', $tags_for_items));
    
}
/**
 * Return an array of candidate subjects and frequencies of subjects
 * @param array of related item id $item_ids to search against
 */
function getBestSubjectCandidateList($item_ids)
{
    $subjects_counts = array();
    $subjects_ids = array();
    $subject_and_id = array();
    $subject_and_def = array();
    for ($i = 0; $i < sizeof($item_ids); $i++)
    {
        $tags_for_one_item = array();
        $db = DB_Connect::connectDB();
        $stmt = $db->prepare("SELECT omeka_incite_subject_concepts.id, omeka_incite_subject_concepts.name, omeka_incite_subject_concepts.definition FROM omeka_incite_subject_concepts JOIN omeka_incite_documents_subject_conjunction on omeka_incite_documents_subject_conjunction.subject_concept_id = omeka_incite_subject_concepts.id JOIN omeka_incite_documents ON omeka_incite_documents_subject_conjunction.document_id = omeka_incite_documents.id WHERE omeka_incite_documents.item_id = ? AND omeka_incite_documents_subject_conjunction.is_positive = 1");
        $stmt->bind_param("i", $item_ids[$i]);
        $stmt->bind_result($subject_id, $subject_name, $subject_def);
        $stmt->execute();
        while ($stmt->fetch())
        {
            if (!isset($subjects_counts[$subject_name]))
                $subjects_counts[$subject_name] = 0;
            $subjects_counts[$subject_name]++;
            if (!isset($subjects_ids[$subject_name]))
                $subjects_ids[$subject_name] = array();
            $subjects_ids[$subject_name][] = $item_ids[$i];

            $subject_and_id[$subject_name] = $subject_id;
            $subject_and_def[$subject_name] = $subject_def;
        }
        $stmt->close();
        $db->close();
    }
    
    if (count($subjects_counts) == 0)
        return array();

    arsort($subjects_counts);
    $results = array();
    foreach ($subjects_counts as $subject => $count)
        $results[] = array('subject' => $subject, 'subject_id' => $subject_and_id[$subject], 'subject_definition' => $subject_and_def[$subject], 'ids' => $subjects_ids[$subject], 'count' => $count);
    return $results;
}

function createTaggedTranscription($item_id, $transcription_id, $userID, $tagged_transcription) {
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("INSERT INTO omeka_incite_tagged_transcriptions VALUES (NULL, ?, ?, ?, ?, 1, NULL, CURRENT_TIMESTAMP)");
    $stmt->bind_param("iiis", $item_id, $transcription_id, $userID, $tagged_transcription);
    $stmt->execute();
    $stmt->close();
    $db->close();
}
?>
