<?php

require_once("DB_Connect.php");
require_once("Incite_Users_Table.php");

function getMembersByGroupId($groupid) 
{
    $users = array();
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("SELECT user_id from omeka_incite_group_members WHERE group_id = ?");
    $stmt->bind_param("i", $groupid);
    $stmt->bind_result($user_id);
    $stmt->execute();
    while ($stmt->fetch()) {
        $users[] = $user_id;
    }
    $db->close();
    return $users;
}
function getMembersWithActivityOverviewByGroupId($groupid) 
{
    $users = array();
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("SELECT user_id from omeka_incite_group_members WHERE group_id = ?");
    $stmt->bind_param("i", $groupid);
    $stmt->bind_result($user);
    $stmt->execute();
    while ($stmt->fetch()) {
        $user_data = getUserDataByUserId($user);
        $users[] = array('id' => $user_data['id'], 'email' => $user_data['email'], 'transcribed_doc_count' => getTranscribedDocumentCountByUserId($user), 'tagged_doc_count' => getTaggedDocumentCountByUserId($user), 'connected_doc_count' => getConnectedDocumentCountByUserId($user), 'discussion_count' => getDiscussionCountByUserId($user));
    }
    $db->close();
    return $users;
}

function getGroupInfoByGroupId($groupid)
{
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("SELECT name, creator, group_type, timestamp from omeka_incite_groups WHERE id = ?");
    $stmt->bind_param("i", $groupid);
    $stmt->bind_result($name, $creator, $group_type, $time);
    $stmt->execute();
    $stmt->fetch();
    $user = getUserDataByUserId($creator);
    $group_info = array('id' => $groupid, 'name' => $name, 'creator' => $user, 'type' => $group_type, 'created_time' => $time);
    $db->close();
    return $group_info;
    
}


?>
