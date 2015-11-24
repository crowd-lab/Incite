<?php
/**
 * API for the subject/concept table
 */
require_once("DB_Connect.php");
/**
 * Creates a new subject/concept
 * @param String $name
 * @param String $definition
 * @return boolean --> true if created, false if subject/concept already exists
 */
function createNewSubjectConcept($name, $definition)
{
    $db = DB_Connect::connectDB();
    $stmt1 = $db->prepare("SELECT id FROM omeka_incite_subject_concepts WHERE UPPER(name) = UPPER(?)");
    $stmt1->bind_param("s", $name);
    $stmt1->bind_result($id);
    $stmt1->execute();
    $stmt1->fetch();
    if ($id != null)
    {
        $stmt1->close();
        $db->close();
        return false;
    }
    else
    {
        $stmt1->close();
        $db->close();
        $db = DB_Connect::connectDB();
        $stmt = $db->prepare("INSERT INTO omeka_incite_subject_concepts VALUES (NULL, ?, ?)");
        $stmt->bind_param("ss", $name, $definition);
        $stmt->execute();
        $stmt->close();
        $db->close();
    }
}
/**
 * Get the definition of a certain subject/concept
 * @param String $name
 * @return mixed --> return false if no definition exists for a name or returns a string if definition exists
 */
function getDefinition($name)
{
    $def = "";
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("SELECT definition FROM omeka_incite_subject_concepts WHERE UPPER(name) = UPPER(?)");
    $stmt->bind_param("s", $name);
    $stmt->bind_result($def);
    $stmt->execute();
    $stmt->fetch();
    if ($def != null)
    {
        return $def;
    }
    return false;
}
/**
 * Change the definition of a subject/concept
 * @param String $name
 * @param String $def
 * @return boolean true if complete, false if no subject/concept exists with the name provided
 */
function changeDefinition($name, $def)
{
    $db = DB_Connect::connectDB();
    $stmt1 = $db->prepare("SELECT id FROM omeka_incite_subject_concepts WHERE UPPER(name) = UPPER(?)");
    $stmt1->bind_param("s", $name);
    $stmt1->bind_result($id);
    $stmt1->execute();
    $stmt1->fetch();
    if ($id == null)
    {
        $stmt1->close();
        $db->close();
        return false;
    }
    else
    {
        $stmt1->close();
        $stmt = $db->prepare("UPDATE omeka_incite_subject_concepts SET definition = ? WHERE UPPER(name) = UPPER(?)");
        $stmt->bind_param("ss", $def, $name);
        $stmt->execute();
        $stmt->close();
        $db->close();
    }
}
/**
 * Return all subject/concepts from the database
 * @return array of results
 */
function getAllSubjectConcepts()
{
    $db = DB_Connect::connectDB();
    $results = Array();
    $stmt = $db->prepare("SELECT id, name, definition FROM omeka_incite_subject_concepts");
    $stmt->bind_result($id, $name, $def);
    $stmt->execute();
    while ($stmt->fetch())
    {
        $results[] = array($id, $name, $def);
    }
    $stmt->close();
    $db->close();
    return $results;
}
/**
 * Get the subject/concept based on id. 
 * @param int $id
 * @return array of [name, definition]
 */
function getSubjectConceptOnId($id)
{
    $db = DB_Connect::connectDB();
    $arr = Array();
    $stmt = $db->prepare("SELECT name, definition FROM omeka_incite_subject_concepts WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->bind_result($name, $def);
    $stmt->execute();
    $stmt->fetch();
    $arr[0] = $name;
    $arr[1] = $def;
    $stmt->close();
    $db->close();
    return $arr;
}
/**
 * Tags the document with a subject/concept
 * @param int $conceptID
 * @param int $documentID
 * @param int $userID
 */
function addConceptToDocument($conceptID, $documentID, $userID)
{
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("SELECT id FROM omeka_incite_documents WHERE item_id = ?");
    $stmt->bind_param("i", $documentID);
    $stmt->bind_result($id);
    $stmt->execute();
    $stmt->fetch();
    if($stmt->num_rows > 0) 
    {
        
        //store concept in conjunction table
        $db->close();
        $db = DB_Connect::connectDB();
        $newStmt = $db->prepare("INSERT INTO omeka_incite_documents_subject_conjunction VALUES (NULL, ?, ?)");
        $newStmt->bind_param("ii", $conceptID, $id);
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
        $newStmt1 = $db->prepare("INSERT INTO omeka_incite_documents_subject_conjunction VALUES (NULL, ?, ?)");
        $newStmt1->bind_param("ii", $conceptID, $id);
        $newStmt1->execute();
        $newStmt1->close();
    }
}
?>
