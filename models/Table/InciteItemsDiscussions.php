

<?php

class Table_InciteItemsDiscussions extends Omeka_Db_Table
{
    public function findItemsByDiscussionId($id) {
        $select = $this->getSelect();
        $select->where("discussion_id = ?", $id);
        $results = $this->fetchObjects($select);
        return $results;
    }

}   
