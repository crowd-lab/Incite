
<?php

class Table_InciteSubject extends Omeka_Db_Table
{
    public function findSubjectByName($name) {
        $select = $this->getSelect();
        $select->where("name = ?", $name);
        $select->limit(1);
        print_r($select->__toString());
        $result = $this->fetchObject($select);
        return $result;
    }

}
