
<?php

class Table_InciteItemsSubjects extends Omeka_Db_Table
{
    public function findKNewestWithUserInfoByItemId($id, $k = 20)
    {
        $db = get_db();
        $select = new Omeka_Db_Select;
        $select->from(array('items_subs' => $db->InciteItemsSubjects), array('timestamp_creation'));
        $select->joinInner(array('users' => $db->InciteUser), 'items_subs.user_id = users.id', array('id', 'first_name', 'last_name', 'email', 'privilege_level', 'experience_level', 'working_group_id'));
        $select->where("item_id = ?", $id);
        $select->group("items_subs.timestamp_creation");
        $select->order("timestamp_creation DESC");
        $select->limit($k);
        return $this->fetchObjects($select);
    }


}
