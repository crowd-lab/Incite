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
function getConnectableDocuments() {
    $all_tagged_documents = getAllTaggedDocuments();
    $connectable_documents = array();
    for ($i = 0; $i < count($all_tagged_documents); $i++) {
        $related_documents = findRelatedDocumentsViaAtLeastNCommonTags($all_tagged_documents[$i]);
        if (count($related_documents) == 0)
            continue;

        $subject_candidates = getBestSubjectCandidateList($related_documents);
        if (count($subject_candidates) == 0)
            continue;

        $self_subjects = getAllSubjectsOnId($all_tagged_documents[$i]);
        for ($j = 0; $j < count($subject_candidates); $j++) {
            if (!in_array($subject_candidates[$j]['subject'], $self_subjects)) {
                if (count($subject_candidates[$j]['ids']) > 0) {
                    $connectable_documents[] = $all_tagged_documents[$i];
                    continue 2;
                }
            }
        }
    }
    return $connectable_documents;

}
?>
