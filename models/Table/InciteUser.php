
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
    public function findTranscribedItemCountByUserId($id) {
        $db = get_db();
        $elementIdForTitle = 50;
        $select = new Omeka_Db_Select;
        $select->from(array('trans' => $db->InciteTranscription), array("COUNT(DISTINCT trans.item_id)"));
        $select->joinInner(array('elem_text' => $db->ElementText), "trans.item_id = elem_text.record_id", array());
        $select->where("element_id = ?", $elementIdForTitle); 
        $select->where("trans.user_id = ?",  $id);
        $result = $this->fetchOne($select);
        return $result;
    }
    public function findTaggedItemCountByUserId($id) {
        $db = get_db();
        $elementIdForTitle = 50;
        $select = new Omeka_Db_Select;
        $select->from(array('trans' => $db->InciteTaggedTranscription), array("COUNT(DISTINCT trans.item_id, trans.timestamp_creation, elem_text.text)"));
        $select->joinInner(array('elem_text' => $db->ElementText), "trans.item_id = elem_text.record_id", array());
        $select->where("elem_text.element_id = ?", $elementIdForTitle); 
        $select->where("trans.user_id = ?",  $id);
        $result = $this->fetchOne($select);
        return $result;
    }

    public function findConnectedItemCountByUserId($id) {
        $db = get_db();
        $elementIdForTitle = 50;
        $select = new Omeka_Db_Select;
        $select->from(array('trans' => $db->InciteTaggedTranscription), array("COUNT(DISTINCT trans.item_id, trans.timestamp_creation, elem_text.text)"));
        $select->joinInner(array('elem_text' => $db->ElementText), "trans.item_id = elem_text.record_id", array());
        $select->where("elem_text.element_id = ?", $elementIdForTitle); 
        $select->where("trans.user_id = ?",  $id);
        $result = $this->fetchOne($select);
        return $result;
    }
}

?>
