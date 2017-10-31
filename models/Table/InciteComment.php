

<?php

class Table_InciteComment extends Omeka_Db_Table
{
    public function findCommentsByDiscussionId($id) {
        $select = $this->getSelect();
        $select->where("discussion_id = ?", $id);
        $select->order('id');
        $results = $this->fetchObjects($select);
        return $results;
    }
    public function findCommentsWithUserInfoByDiscussionId($id) {
        $db = get_db();
        //$select = new Omeka_Db_Select;
        //$select->from(array('comments' => $db->InciteComment));
        $select = $this->getSelect();
        $select->joinInner(array('users' => $db->InciteUser), "incite_comments.user_id = users.id", array('first_name'));
        $select->where("discussion_id = ?", $id);
        $select->order('incite_comments.id');
        $results = $this->fetchObjects($select);
        return $results;
    }

}
