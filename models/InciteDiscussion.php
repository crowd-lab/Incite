

<?php

class InciteDiscussion extends Omeka_Record_AbstractRecord
{
    public $id;
    public $user_id;
    public $working_group_id;
    public $discussion_text;
    public $is_active;
    public $discussion_type;
    public $timestamp_creation;
}
