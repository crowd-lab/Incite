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
    $stmt = $db->prepare("SELECT user_id, privilege from omeka_incite_group_members WHERE group_id = ?");
    $stmt->bind_param("i", $groupid);
    $stmt->bind_result($user, $privilege);
    $stmt->execute();
    while ($stmt->fetch()) {
        $user_data = getUserDataByUserId($user);
        $users[] = array('id' => $user_data['id'], 'email' => $user_data['email'], 'transcribed_doc_count' => getTranscribedDocumentCountByUserId($user), 'tagged_doc_count' => getTaggedDocumentCountByUserId($user), 'connected_doc_count' => getConnectedDocumentCountByUserId($user), 'discussion_count' => getDiscussionCountByUserId($user), 'privilege' => $privilege);
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

/**
 * Create a new group
 *
 * @param string $groupName
 * @param int $userIdOfCreator
 * @param int $groupType
 * @return string "Success" or "Failure"
 */
function createGroup($groupName, $userIdOfCreator, $groupType)
{
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("INSERT INTO omeka_incite_groups VALUES (NULL, ?, ?, ?, CURRENT_TIMESTAMP)");
    $stmt->bind_param("sii", $groupName, $userIdOfCreator, $groupType);
    $stmt->execute();
    $groupId = $stmt->insert_id;
    $stmt->close();

    addGroupMember($userIdOfCreator, $groupId, 0);
    return $groupId;
}

/**
 * Adds a member to a group, both specified by ID
 * Privilege denotes membership status:
 *      0 = member of the group
 *     -1 = has requested to join the group and is not yet approved
 *     -2 = user is banned from the group
 *
 * @param int $memberId
 * @param int $groupId
 * @param int $privilege
 * @return string "Success" or "Failure"
 */
function addGroupMember($memberId, $groupId, $privilege)
{
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("INSERT INTO omeka_incite_group_members VALUES (NULL, ?, ?, ?)");
    $stmt->bind_param("iii", $memberId, $groupId, $privilege);
    $stmt->execute();
    $stmt->close();

    return "true";
}

/**
 * Gets all group ids who's groups contain the search string
 *
 * @param string $groupName
 * @return list of group ids
 */
function searchGroupsByName($groupName)
{
    $matchingTerm = "%".$groupName."%";
    $db = DB_Connect::connectDB();
    $stmt = $db->prepare("SELECT id, name FROM omeka_incite_groups WHERE name LIKE ?");
    $stmt->bind_param("s", $matchingTerm);
    $stmt->bind_result($groupId, $groupNameResult);
    $stmt->execute();
    while ($stmt->fetch()) {
        $groups[] = array('id' => $groupId, 'name' => $groupNameResult);
    }
    $stmt->close();

    return $groups;
}

?>
