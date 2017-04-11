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

function getTagsAnswers() {
    $results = Array();
    $db = DB_Connect::connectDB();
    $sql = "SELECT location, date, inferred_location, period, race, gender, occupation, tagged_trans FROM omeka_incite_gold_standard WHERE assessID = '1' ";
    $stmt = $db->prepare($sql);
    $stmt->bind_result($location, $date, $inferred_location, $period, $race, $gender, $occupation, $tagged_trans);
    $stmt->execute();
    while ($stmt->fetch()) {
        $results = Array("location" => $location, "date" => $date, "inferred_location" => $inferred_location, "period" => $period, "race" => $race, "gender" => $gender, "occupation" => $occupation, "tagged" => $tagged_trans);
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
