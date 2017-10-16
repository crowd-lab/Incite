
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
}

?>
