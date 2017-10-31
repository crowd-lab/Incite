
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
    public function findFirstKItemIdsToBeConnected($k = 0)
    {
        //Get id's of all items that don't need to be connected (they have been connected after the newest tagged_transcriptions)
        $db = get_db();
        $connected_select = new Omeka_Db_Select;
        $connected_select->from(array('conn' => $db->InciteItemsSubjects), array('item_id'));
        $connected_select->joinInner(array('tagged_trans' => $db->InciteTaggedTranscription), 'conn.tagged_trans_id = tagged_trans.id', array(''));
        $connected_select->where('conn.timestamp_creation > tagged_trans.timestamp_creation');
        $connected_select->group('conn.item_id');


        //Get id's of all items whose newest tagged_transcriptions are not connected yet.
        $tagged_trans_select = new Omeka_Db_Select;
        $tagged_trans_select->from(array('tagged_trans' => $db->InciteTaggedTranscription), array('item_id'));
        $tagged_trans_select->joinInner(array('trans' => $db->InciteTranscription), 'tagged_trans.item_id = trans.item_id',array(''));
        $tagged_trans_select->where('tagged_trans.timestamp_creation > trans.timestamp_creation');
        $tagged_trans_select->where('tagged_trans.item_id NOT IN ('. $connected_select->__toString() .')');
        $tagged_trans_select->group('tagged_trans.item_id');
        $tagged_trans_select->order('item_id ASC');

        if ($k > 0) {
            $tagged_trans_select->limit($k);
        }
        $objects = $this->fetchObjects($tagged_trans_select);
        $ids = array();
        foreach ($objects as $object) {
            $ids[] = $object->item_id;
        }
        return $ids;
    }

    
    public function findSubjectsStatsByItemId($id) {
        $db = get_db();
        $select = new Omeka_Db_Select;
        $select->from(array('items_subs' => $db->InciteItemsSubjects), array('*', 'rating_num'=>'COUNT(subject_id)', 'rating_sum'=>'SUM(rating)'));
        $select->where('item_id = ?', $id);
        $select->group('subject_id');
        $results = $this->fetchObject($select);
        return $results;
        
    }
    public function findNewestSubjectRatingsByItemId($id) {
        $select = $this->getSelect();
        $select->where('item_id = ?', $id);
        $select->order('timestamp_creation DESC');
        $select->group('tagged_trans_id');
        $select->limit(1);
        $result = $this->fetchObject($select);
        return $result;
        
    }


}
