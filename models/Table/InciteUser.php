
<?php

class Table_InciteUser extends Omeka_Db_Table
{
    public function findUserById($id)
    {
        $select = $this->getSelect();
        $select->where("id = ?", $id);
        return $this->fetchObject($select);
    }
    public function findUserByEmail($email)
    {
        $select = $this->getSelect();
        $select->where("email = ?", $email);
        return $this->fetchObject($select);
    }
}

?>
