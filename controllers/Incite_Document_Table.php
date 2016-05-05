<?php


require_once("DB_Connect.php");
require_once("Incite_Tag_Table.php");
require_once("Incite_Subject_Concept_Table.php");

function getTranscribableDocuments()
{

    $db = DB_Connect::connectDB();
    $documents_with_jpeg = array();  //document id's and assume documents with jpeg all need transcriptions and thus tags
    $stmt = $db->prepare("SELECT DISTINCT `item_id` FROM `omeka_files` WHERE `mime_type` = 'image/jpeg'");
    $stmt->bind_result($result);
    $stmt->execute();
    while ($stmt->fetch()) {
        $documents_with_jpeg[] = $result;
    }
    $stmt->close();
    $db->close();
    
    return $documents_with_jpeg;
}
/**
 * Gets all document id with tags
 * @return an array of results
 */
function getDocumentsWithTags()
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

    return $tagged_document_ids;
}

/**
 * Gets all document id with out tags
 * @return an array of results
 */
function getDocumentsWithoutTagsForLatestTranscription()
{
    $documents_without_tag = array();
    $taggable_documents = getDocumentsWithApprovedTranscription();

    foreach($taggable_documents as $document_id) {
        if (!hasTaggedTranscriptionForNewestTranscription($document_id)) {
            $documents_without_tag[] = $document_id;
        }
    }

    return $documents_without_tag;
}
function getDocumentsWithoutConnectionsForLatestTaggedTranscription() {
    $documents_without_connections = array();
    $tagged_documents = getDocumentsWithTags();

    foreach($tagged_documents as $document_id) {
        if (empty(getNewestSubjectsForNewestTaggedTranscription($document_id))) {
            $documents_without_connections[] = $document_id;
        }
    }

    return $documents_without_connections;
}
/**
 * Takes a list of document ids and returns a new array with info about their task completion
 */
function getTaskCompletionInfoFor($documentID) {
    $isTranscribed = !empty(getNewestTranscription($documentID));
    $isTagged = hasTaggedTranscriptionForNewestTranscription($documentID);
    $isConnected = !empty(getNewestSubjectsForNewestTaggedTranscription($documentID));

    $documents = array('isTranscribed' => ($isTranscribed ? true : false), 'isTagged' => ($isTagged ? true : false), 'isConnected' => ($isConnected ? true : false));

    return $documents;
}
?>
