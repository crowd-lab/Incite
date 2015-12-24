<?php


require_once("DB_Connect.php");

function getTranscribableDocuments()
{

    $db = DB_Connect::connectDB();
    $documents_with_jpeg = array();  //document id's and assume documents with jpeg all need transcriptions and thus tags
    $stmt = $db->prepare("SELECT `item_id` FROM `omeka_files` WHERE `mime_type` = 'image/jpeg'");
    $stmt->bind_result($result);
    $stmt->execute();
    while ($stmt->fetch()) {
        $documents_with_jpeg[] = $result;
    }
    $stmt->close();
    $db->close();
    
    return $documents_with_jpeg;
}

?>
