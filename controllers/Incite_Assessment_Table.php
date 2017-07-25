<?php
/**
 * API for the Incite_Tag_Table
 */
require_once("DB_Connect.php");
require_once("Incite_Document_Table.php");
require_once("Incite_Env_Setting.php");


function getTransAnswers() {
    $results = Array();
    $db = DB_Connect::connectDB();
    $sql = "SELECT transcribed_text, tone FROM omeka_incite_transcriptions WHERE item_id = '731' AND type = ".GOLD_STANDARD."";
    $stmt = $db->prepare($sql);
    $stmt->bind_result($transcribed_text, $tone);
    $stmt->execute();
    while ($stmt->fetch()) {
        $results = Array("trans" => $transcribed_text, "tone" => $tone);
    }
    $stmt->close();
    $db->close();
    return $results;
}


function createTrans($itemID, $userID, $workingGroupID, $transcribedText, $summarizedText, $tone) {
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("INSERT INTO omeka_incite_transcriptions VALUES (NULL, ?, ?, ?, ?, ?, ?, ".ASSESSMENT.", NULL, CURRENT_TIMESTAMP)");
    $stmt->bind_param("iiisss", $itemID, $userID, $workingGroupID, $transcribedText, $summarizedText, $tone);
    $stmt->execute();
    $stmt->close();
    $db->close();
}


?>
