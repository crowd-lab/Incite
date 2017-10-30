
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
    public function findUserByEmailAndPassword($email, $password)
    {
        $select = $this->getSelect();
        $select->where("email = ?", $email);
        $select->where("password = ?", $password);
        $select->limit(1);
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
    public function findTranscribedItemsByUserId($id) {
        $db = get_db();
        $elementIdForTitle = 50;
        $select = new Omeka_Db_Select;
        $select->from(array('trans' => $db->InciteTranscription), array('item_id', 'trans_timestamp_creation' => 'MAX(trans.timestamp_creation)','working_group_id'));
        $select->joinInner(array('elem_text' => $db->ElementText), "trans.item_id = elem_text.record_id", array('title'=>'text'));
        $select->where("elem_text.element_id = ?", $elementIdForTitle); 
        $select->where("trans.user_id = ?",  $id);
        $select->group('item_id');
        $result = $this->fetchObjects($select);
        return $result;
    }
    public function findTaggedItemsByUserId($id) {
        $db = get_db();
        $elementIdForTitle = 50;
        $select = new Omeka_Db_Select;
        $select->from(array('trans' => $db->InciteTaggedTranscription), array('item_id', 'tag_timestamp_creation' => 'trans.timestamp_creation','working_group_id'));
        $select->joinInner(array('elem_text' => $db->ElementText), "trans.item_id = elem_text.record_id", array('title'=>'text'));
        $select->where("elem_text.element_id = ?", $elementIdForTitle); 
        $select->where("trans.user_id = ?",  $id);
        $select->group('item_id');
        $result = $this->fetchObjects($select);
        return $result;
    }
    public function findConnectedItemsByUserId($id) {
        $db = get_db();
        $elementIdForTitle = 50;
        $select = new Omeka_Db_Select;
        $select->from(array('subs' => $db->InciteItemsSubjects), array('item_id', 'connect_timestamp_creation' => 'MAX(subs.timestamp_creation)','working_group_id'));
        $select->joinInner(array('elem_text' => $db->ElementText), "subs.item_id = elem_text.record_id", array('title'=>'text'));
        $select->where("elem_text.element_id = ?", $elementIdForTitle); 
        $select->where("subs.user_id = ?",  $id);
        $select->group('item_id');
        $result = $this->fetchObjects($select);
        return $result;
    }
    public function findDiscussionsByUserId($id) {
        $db = get_db();
        $select = new Omeka_Db_Select;
        $select->from(array('dis' => $db->InciteDiscussion));
        $select->where("dis.user_id = ?",  $id);
        $results = $this->fetchObjects($select);
        return $results;
    }
    public function findActivitiesByUserId($id, $asc_time = false) {
        $db = get_db();
        $elementIdForTitle = 50;
        //Discuss
        $discuss_select = new Omeka_Db_Select;
        $discuss_select->from(array('dis' => $db->InciteDiscussion), array('item_id' => 'id', new Zend_Db_Expr ('"Discuss" AS activity_type'), 'timestamp_creation' => 'MAX(dis.timestamp_creation)', 'working_group_id', 'item_title' => 'dis.discussion_text'));
        $discuss_select->where("dis.user_id = ?",  $id);
        $discuss_select->where("dis.discussion_type = 4");
        $discuss_select->group('item_id');
        //Connect
        $connect_select = new Omeka_Db_Select;
        $connect_select->from(array('subs' => $db->InciteItemsSubjects), array('item_id', new Zend_Db_Expr ('"Connect" AS activity_type'), 'timestamp_creation' => 'MAX(subs.timestamp_creation)', 'working_group_id'));
        $connect_select->joinInner(array('elem_text' => $db->ElementText), "subs.item_id = elem_text.record_id", array('item_title'=>'text'));
        $connect_select->where("elem_text.element_id = ?", $elementIdForTitle); 
        $connect_select->where("subs.user_id = ?",  $id);
        $connect_select->group('item_id');
        //Tag
        $tag_select = new Omeka_Db_Select;
        $tag_select->from(array('tags' => $db->InciteTaggedTranscription), array('item_id', new Zend_Db_Expr ('"Tag" AS activity_type'), 'timestamp_creation' => 'MAX(tags.timestamp_creation)', 'working_group_id'));
        $tag_select->joinInner(array('elem_text' => $db->ElementText), "tags.item_id = elem_text.record_id", array('item_title'=>'text'));
        $tag_select->where("elem_text.element_id = ?", $elementIdForTitle); 
        $tag_select->where("tags.user_id = ?",  $id);
        $tag_select->group('item_id');
        //Transcribe
        $trans_select = new Omeka_Db_Select;
        $trans_select->from(array('trans' => $db->InciteTranscription), array('item_id', new Zend_Db_Expr ('"Transcribe" AS activity_type'), 'timestamp_creation' => 'MAX(trans.timestamp_creation)', 'working_group_id'));
        $trans_select->joinInner(array('elem_text' => $db->ElementText), "trans.item_id = elem_text.record_id", array('item_title'=>'text'));
        $trans_select->where("elem_text.element_id = ?", $elementIdForTitle); 
        $trans_select->where("trans.user_id = ?",  $id);
        $trans_select->group('item_id');
        $select = new Omeka_Db_Select;
        $select->union(array($discuss_select, $connect_select, $tag_select, $trans_select));
        if ($asc_time) {
            $select->order('timestamp_creation ASC');
        } else {
            $select->order('timestamp_creation DESC');
        }
        $result = $this->fetchObjects($select);
        return $result;
    }
}

?>
