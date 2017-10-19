
<?php

class Table_InciteTranscription extends Omeka_Db_Table
{
    public function findNewestByItemId($id)
    {
        $select = $this->getSelect();
        $select->where("item_id = ?", $id);
        $select->order("timestamp_creation DESC");
        $select->limit(1);
        return $this->fetchObject($select);
    }
    public function findKNewest($k)
    {
        $select = $this->getSelect();
        $select->order("timestamp_creation DESC");
        $select->limit($k);
        return $this->fetchObjects($select);
    }
    public function findKNewestWithUserEmailByItemId($id, $k = 20)
    {
        $db = get_db();
        $select = $this->getSelect();
        $select->joinInner(array('users' => $db->InciteUser), $this->_name.'.user_id = users.id');
        $select->where("item_id = ?", $id);
        $select->order("timestamp_creation DESC");
        $select->limit($k);
        return $this->fetchObjects($select);
    }
}

?>
