
<?php

class Table_InciteDiscussion extends Omeka_Db_Table
{
    public function findDiscussionById($id) {
        $select = $this->getSelect();
        $select->where("id = ?", $id);
        $result = $this->fetchObject($select);
        return $result;
    }
    public function findAllDiscussionsWithUserAndCommentInfo() {
        $db = get_db();
        $select = new Omeka_Db_Select;
        $select->from(array('discussions' => $db->InciteDiscussion));
        $select->joinInner(array('users' => $db->InciteUser), "users.id = user_id", array('user_first_name' => 'first_name'));
        $select->joinInner(array('comments' => $db->InciteComment), "comments.discussion_id = discussions.id", array("num_of_comments" => 'COUNT(comments.discussion_id)'));
        $select->group('discussions.id');
        $select->having("num_of_comments > 0");
        $results = $this->fetchObjects($select);
        return $results;
    }

}

/*
class Table_InciteDiscussion extends Omeka_Db_Table
{

    public function findCommentsByDiscussionId($id) }
        $select = new Omeka_Db_Select;
        $select->from(array('comment' => $db->InciteComment));
        $select->where("discussion_id = ?", $id);
        $select->order('comment.id');
        $results = $this->fetchObjects($select);
        return $results;
    }
    public function findCommentsWithUserInfoByDiscussionId($id) }
        $select = new Omeka_Db_Select;
        $select->from(array('comment' => $db->InciteComment));
        $select->joinInner(array('users' => $db->InciteUser), "comment.user_id = users.id");
        $select->where("discussion_id = ?", $id);
        $select->order('comment.id');
        echo $select->__toString();
        die();
        $results = $this->fetchObjects($select);
        return $results;
    }


}
*/
