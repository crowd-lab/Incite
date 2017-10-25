
<?php

class Table_InciteTaggedTranscription extends Omeka_Db_Table
{
    public function findByItemId($id)
    {
        $select = $this->getSelect();
        $select->where("item_id = ?", $id);
        $select->order("timestamp_creation DESC");
        return $this->fetchObject($select);
    }
    public function findNewestByTranscriptionId($id)
    {
        $select = $this->getSelect();
        $select->where("transcription_id = ?", $id);
        $select->order("timestamp_creation DESC");
        $select->limit(1);
        return $this->fetchObject($select);
    }
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
    public function findFirstKItemIdsToBeTagged($k = 0)
    {
        //Get id's of all items that don't need to be tagged (they have been tagged after the newest transcriptions)
        $db = get_db();
        $tagged_trans_select = new Omeka_Db_Select;
        $tagged_trans_select->from(array('tagged_trans' => $db->InciteTaggedTranscription), array('item_id'));
        $tagged_trans_select->joinInner(array('trans' => $db->InciteTranscription), 'tagged_trans.item_id = trans.item_id',array(''));
        $tagged_trans_select->where('tagged_trans.timestamp_creation > trans.timestamp_creation');
        $tagged_trans_select->group('tagged_trans.item_id');

        //Get id's of all items whose newest transcriptions are not tagged yet.
        $trans_select = new Omeka_Db_Select;
        $trans_select->from(array('trans' => $db->InciteTranscription), array('item_id'));
        $trans_select->where('item_id NOT IN ('. $tagged_trans_select->__toString() .')');
        $trans_select->group('item_id');
        $trans_select->order("item_id ASC");
        if ($k > 0) {
            $trans_select->limit($k);
        }
        $objects = $this->fetchObjects($trans_select);
        $ids = array();
        foreach ($objects as $object) {
            $ids[] = $object->item_id;
        }
        return $ids;
    }
}

?>
