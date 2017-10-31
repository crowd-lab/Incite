

<?php

class Table_InciteGroup extends Omeka_Db_Table
{
    public function findGroupById($id) {
        $select = $this->getSelect();
        $select->where("id = ?", $id);
        $select->limit(1);
        $result = $this->fetchObject($select);
        return $result;
    }
    public function findGroupByPartialName($name) {
        $name = '%'.$name.'%';
        $select = $this->getSelect();
        $select->where("name like ?", $name);
        $results = $this->fetchObjects($select);
        return $results;
    }



}
