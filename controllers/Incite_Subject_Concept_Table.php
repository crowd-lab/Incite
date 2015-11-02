<?php
require_once("DB_Connect.php");
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
        $stmt = $db->prepare("INSERT INTO omeka_incite_subject_concepts VALUES (NULL, ?, ?)");
        $stmt->bind_param("ss", $name, $definition);
        $stmt->execute();
        $stmt->close();
        $db->close();
    }
}
function getDefinition($name)
{
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("SELECT definition FROM omeka_incite_subject_concepts WHERE UPPER(name) = UPPER(?)");
    $stmt->bind_param("s", $name);
    $stmt->bind_reuslt($def);
    $stmt->execute();
    $stmt->fetch();
    if ($def != null)
    {
        return $def;
    }
    return false;
}
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
function getAllSubjectConcepts()
{
    $db = DB_Connect::connectDB();
    $results = Array();
    $stmt = $db->prepare("SELECT * FROM omeka_incite_subject_concepts");
    $stmt->bind_result($result);
    $stmt->execute();
    while ($stmt->fetch())
    {
        $results[] = $result;
    }
    $stmt->close();
    $db->close();
    return $results;
}
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
function addConceptToDocument($conceptID, $documentID, $userID)
{
    $stmt = $db->prepare("SELECT id FROM omeka_incite_documents WHERE item_id = ?");
    $stmt->bind_param("i", $documentID);
    $stmt->bind_result($id);
    $stmt->execute();
    $stmt->fetch();
    if($stmt->num_rows > 0) 
    {
        
        //store concept in conjunction table
        $newStmt = $db->prepare("INSERT INTO omeka_incite_documents_subject_conjunction VALUES (NULL, ?, ?)");
        $newStmt->bind_param("ii", $conceptID, $documentID);
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
        $newStmt1 = $db->prepare("INSERT INTO omeka_incite_documents_subject_conjunction VALUES (NULL, ?, ?)");
        $newStmt1->bind_param("ii", $conceptID, $id);
        $newStmt1->execute();
        $newStmt1->close();
    }
}
?>
