
<?php

class Table_InciteItemTaskStatus extends Omeka_Db_Table
{
    public function findItemTaskStatusByItemId($id) {
        $select = $this->getSelect();
        $select->where('item_id = ?', $id);
        $select->limit(1);
        $result = $this->fetchObject($select);
        return $result;
    }
    public function findFirstKItemIdsToBeTagged($k = 0) {
        $select = $this->getSelect();
        $select->where('ready_to_tag = 1');
        $results = $this->fetchObjects($select);

        $ids = array();
        foreach ((array) $results as $result) {
            $ids[] = $result->item_id;
        }
        return $ids;
    }
    public function findFirstKItemIdsToBeConnected($k = 0) {
        $select = $this->getSelect();
        $select->where('ready_to_connect = 1');
        $results = $this->fetchObjects($select);

        $ids = array();
        foreach ((array) $results as $result) {
            $ids[] = $result->item_id;
        }
        return $ids;
    }

}
