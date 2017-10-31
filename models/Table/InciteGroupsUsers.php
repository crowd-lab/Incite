
<?php

class Table_InciteGroupsUsers extends Omeka_Db_Table
{
    public function findGroupsByUserId($id) {
        $db = get_db();
        $select = $this->getSelect();
        $select->joinInner(array('groups' => $db->InciteGroup), "incite_groups_users.group_id = groups.id");
        $select->where("user_id = ?", $id);
        $results = $this->fetchObjects($select);
        return $results;
    }
    public function findGroupUserByUserAndGroupIds($userId, $groupId) {
        $db = get_db();
        $select = $this->getSelect();
        $select->where("user_id = ?", $userId);
        $select->where("group_id = ?", $groupId);
        $result = $this->fetchObject($select);
        return $result;
    }
    public function findUsersByGroupId($id) {
        $select = $this->getSelect();
        $select->where("group_id = ?", $id);
        $results = $this->fetchObjects($select);
        return $results;
    }
    public function findAcceptedUsersByGroupId($id) {
        $select = $this->getSelect();
        $select->where("group_id = ?", $id);
        $select->where("group_privilege = 0");
        $results = $this->fetchObjects($select);
        return $results;
    }
    public function findGroupsUsersByGroupId($id) {
        $select = $this->getSelect();
        $select->where("group_id = ?", $id);
        $results = $this->fetchObjects($select);
        return $results;
    }
    public function findSeenGroupInstructionsByUserId($id) {
        $select = $this->getSelect();
        $select->where("user_id = ?", $id);
        $select->where("group_privilege = 0");
        $select->where("seen_instruction = 1");
        $results = $this->fetchObjects($select);
        return $results;
    }
}
