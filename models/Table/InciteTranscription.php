
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
    public function findKNewestWithUserInfoByItemId($id, $k = 20)
    {
        $db = get_db();
        $select = $this->getSelect();
        $select->joinInner(array('users' => $db->InciteUser), $this->_name.'.user_id = users.id');
        $select->where("item_id = ?", $id);
        $select->order("timestamp_creation DESC");
        $select->limit($k);
        return $this->fetchObjects($select);
    }


    public function findFirstKItemIdsWithoutTranscriptions($k = 0)
    {
        $db = get_db();
        $trans_select = new Omeka_Db_Select;
        $trans_select->from(array('trans' => $db->InciteTranscription), array('item_id'));

        $item_select = new Omeka_Db_Select;
        $item_select->from(array('items' => $db->Item), array('id'));
        $item_select->where('id NOT IN ('. $trans_select->__toString() .')');
        $item_select->order("id ASC");
        if ($k > 0) {
            $item_select->limit($k);
        }
        $objects = $this->fetchObjects($item_select);
        $ids = array();
        foreach ($objects as $object) {
            $ids[] = $object->id;
        }
        return $ids;
    }
}

?>
