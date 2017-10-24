
<?php

class Table_InciteSubject extends Omeka_Db_Table
{
    public function findSubjectByName($name) {
        $select = $this->getSelect();
        $select->where("name = ?", $name);
        $select->limit(1);
        $result = $this->fetchObject($select);
        return $result;
    }
    public function findAllSubjects() {
        $select = $this->getSelect();
        $result = $this->fetchObjects($select);
        return $result;
    }

}
