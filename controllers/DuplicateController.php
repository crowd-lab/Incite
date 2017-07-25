<?php
/**
 * Incite
 *
 */
    require_once('Incite_Helpers.php');
    require_once("DB_Connect.php");

/**
 * Plugin "Incite"
 *
 * @package Incite
 */
class Incite_DuplicateController extends Omeka_Controller_AbstractActionController
{
    public function init()
    {
    }
    public function heheAction(){
        
        $this->copy_tag_answer_explain_list();
        $this->copy_incite_tag_question_index();
        $this->copy_incite_trans_reason();
        $this->copy_incite_subject_explain();
        $this->copy_incite_tags();
        $this->copy_incite_documents_subject_conjunction_answer();
        $this->copy_incite_transcriptions_answer();
        
        /*
        $this->copy_incite_documents();
        $this->copy_incite_documents_questions_conjunction();
        $this->copy_incite_documents_replies_conjunction();
        $this->copy_incite_documents_tags_conjunction();
        $this->copy_incite_groups();
        $this->copy_incite_group_instructions_seen_by();
        $this->copy_incite_group_members();
        $this->copy_incite_questions();
        $this->copy_incite_replies();
        $this->copy_incite_users();
        $this->copy_incite_users_map();
        $this->copy_incite_documents_subject_conjunction();
        $this->copy_incite_tagged_transcriptions();
        $this->copy_incite_transcriptions();
        */
        
        /*
        $this->copy_incite_tagged_transcriptions_to_generate_tags();
        $this->find_all_item_taggable();
        */
        
        }

    public function copy_tag_answer_explain_list() {
        $File = getcwd().'/plugins/Incite/controllers/database.sql';
        $Handle = fopen($File, 'a');
        $db = DB_Connect::connectDB();
        $stmt = $db->prepare("SELECT * FROM `omeka_incite_tag_answer_explain_list`");
        $stmt->bind_result($id, $item_id, $question_id, $answer, $correct, $explanation);
        $stmt->execute();
        $id = "NULL";
        fwrite($Handle, "--\n-- Dumping data for table `omeka_incite_tag_answer_explain_list`\n--\n\n");
        $current = "INSERT INTO `omeka_incite_tag_answer_explain_list` (`id`, `item_id`, `question_id`, `answer`, `correct`, `explanation`) VALUES \n";
        fwrite($Handle, $current); 
        while ($stmt->fetch()) {
            $current = '('.$id.', '.$item_id.', '.$question_id.', "'.$answer.'", "'.$correct.'", "'.$explanation.'"),'."\n"; 
            fwrite($Handle, $current); 
        }
        $stmt->close();
        $db->close();
        $str=file_get_contents(getcwd().'/plugins/Incite/controllers/database.sql');
        ftruncate ($Handle , strlen($str)-2);
        fwrite($Handle, ";\n\n-- --------------------------------------------------------\n\n"); 
        fclose($Handle); 
    }

    public function copy_incite_tag_question_index() {
        $File = getcwd().'/plugins/Incite/controllers/database.sql';
        $Handle = fopen($File, 'a');
        $db = DB_Connect::connectDB();
        $stmt = $db->prepare("SELECT * FROM `omeka_incite_tag_question_index`");
        $stmt->bind_result($id, $question);
        $stmt->execute();
        $id = "NULL";
        fwrite($Handle, "--\n-- Dumping data for table `omeka_incite_tag_question_index`\n--\n\n");
        $current = "INSERT INTO `omeka_incite_tag_question_index` (`id`, `question`) VALUES \n";
        fwrite($Handle, $current); 
        while ($stmt->fetch()) {
            $current = '('.$id.', "'.$question.'"),'."\n"; 
            fwrite($Handle, $current); 
        }
        $stmt->close();
        $db->close();
        $str=file_get_contents(getcwd().'/plugins/Incite/controllers/database.sql');
        ftruncate ($Handle , strlen($str)-2);
        fwrite($Handle, ";\n\n-- --------------------------------------------------------\n\n"); 
        fclose($Handle); 
    }

    public function copy_incite_trans_reason() {
        $File = getcwd().'/plugins/Incite/controllers/database.sql';
        $Handle = fopen($File, 'a');
        $db = DB_Connect::connectDB();
        $stmt = $db->prepare("SELECT * FROM `omeka_incite_trans_reason`");
        $stmt->bind_result($id, $item_id, $reason);
        $stmt->execute();
        $id = "NULL";
        fwrite($Handle, "--\n-- Dumping data for table `omeka_incite_trans_reason`\n--\n\n");
        $current = "INSERT INTO `omeka_incite_trans_reason` (`id`, `item_id`,`reason`) VALUES \n";
        fwrite($Handle, $current); 
        while ($stmt->fetch()) {
            $current = '('.$id . ', ' . $item_id . ', "' . $reason .'");'."\n";
            fwrite($Handle, $current); 
        }
        $stmt->close();
        $db->close();
        fwrite($Handle, "\n\n-- --------------------------------------------------------\n\n"); 
        fclose($Handle); 
    }


    public function copy_incite_subject_concepts() {
        $File = getcwd().'/plugins/Incite/controllers/database.sql';
        $Handle = fopen($File, 'a');
        $db = DB_Connect::connectDB();
        $stmt = $db->prepare("SELECT * FROM `omeka_incite_subject_concepts`");
        $stmt->bind_result($id, $name, $defination);
        $stmt->execute();
        fwrite($Handle, "--\n-- Dumping data for table `omeka_incite_subject_concepts`\n--\n\n");
        $current = "INSERT INTO `omeka_incite_subject_concepts` (`id`, `name`, `defination`) VALUES \n";
        fwrite($Handle, $current); 
        while ($stmt->fetch()) {
            $current = '('.$id.', "'.$name.'", "'.$defination.'"),'."\n"; 
            fwrite($Handle, $current); 
        }
        $stmt->close();
        $db->close();
        $str=file_get_contents(getcwd().'/plugins/Incite/controllers/database.sql');
        ftruncate ($Handle , strlen($str)-2);
        fwrite($Handle, ";\n\n-- --------------------------------------------------------\n\n"); 
        fclose($Handle); 
    }

    public function copy_incite_subject_explain() {
        $File = getcwd().'/plugins/Incite/controllers/database.sql';
        $Handle = fopen($File, 'a');
        $db = DB_Connect::connectDB();
        $stmt = $db->prepare("SELECT * FROM `omeka_incite_subject_explain`");
        $stmt->bind_result($id, $item_id, $concept_id, $explanation);
        $stmt->execute();
        $id = "NULL";
        fwrite($Handle, "--\n-- Dumping data for table `omeka_incite_subject_explain`\n--\n\n");
        $current = "INSERT INTO `omeka_incite_subject_explain` (`id`, `item_id`, `concept_id`, `explanation`) VALUES \n";
        fwrite($Handle, $current); 
        while ($stmt->fetch()) {
            $current = "(".$id.", ".$item_id.", ".$concept_id.", '".$explanation."'),"."\n"; 
            fwrite($Handle, $current); 
        }
        $stmt->close();
        $db->close();
        $str=file_get_contents(getcwd().'/plugins/Incite/controllers/database.sql');
        ftruncate ($Handle , strlen($str)-2);
        fwrite($Handle, ";\n\n-- --------------------------------------------------------\n\n"); 
        fclose($Handle); 
    }

    public function copy_incite_documents() {
        $File = getcwd().'/plugins/Incite/controllers/server.sql';
        $Handle = fopen($File, 'a');
        $db = DB_Connect::connectDB();
        $stmt = $db->prepare("SELECT * FROM `omeka_incite_documents`");
        $stmt->bind_result($id, $item_id, $user_id, $tag_ignored, $is_locked, $document_difficulty,$question_id, $Timestamp);
        $stmt->execute();
        fwrite($Handle, "--\n-- Dumping data for table `omeka_incite_documents`\n--\n\n");
        $current = "INSERT INTO `omeka_incite_documents` (`id`, `item_id`, `user_id`, `tags_ignored`, `is_locked`, `document_difficulty`, `question_id`, `Timestamp`) VALUES \n";
        fwrite($Handle, $current); 
        while ($stmt->fetch()) {
            $current = '('.$id.', '.$item_id.', '.$user_id.', '.$tag_ignored.', '. $is_locked.', '. $document_difficulty.', '.$question_id.', "'.$Timestamp.'"),'."\n"; 
            fwrite($Handle, $current); 
        }
        $stmt->close();
        $db->close();
        $str=file_get_contents(getcwd().'/plugins/Incite/controllers/server.sql');
        ftruncate ($Handle , strlen($str)-2);
        fwrite($Handle, ";\n\n-- --------------------------------------------------------\n\n"); 
        fclose($Handle); 
    }

    public function copy_incite_documents_questions_conjunction() {
        $File = getcwd().'/plugins/Incite/controllers/server.sql';
        $Handle = fopen($File, 'a');
        $db = DB_Connect::connectDB();
        $stmt = $db->prepare("SELECT * FROM `omeka_incite_documents_questions_conjunction`");
        $stmt->bind_result($id, $item_id, $question_id);
        $stmt->execute();
        $id = "NULL";
        fwrite($Handle, "--\n-- Dumping data for table `omeka_incite_documents_questions_conjunction`\n--\n\n");
        $current = "INSERT INTO `omeka_incite_documents_questions_conjunction` (`id`, `item_id`, `question_id`) VALUES \n";
        fwrite($Handle, $current); 
        while ($stmt->fetch()) {
            $current = '('.$id.', '.$item_id.', '.$question_id.'),'."\n"; 
            fwrite($Handle, $current); 
        }
        $stmt->close();
        $db->close();
        $str=file_get_contents(getcwd().'/plugins/Incite/controllers/server.sql');
        ftruncate ($Handle , strlen($str)-2);
        fwrite($Handle, ";\n\n-- --------------------------------------------------------\n\n"); 
        fclose($Handle); 
    }

    public function copy_incite_documents_replies_conjunction() {
        $File = getcwd().'/plugins/Incite/controllers/server.sql';
        $Handle = fopen($File, 'a');
        $db = DB_Connect::connectDB();
        $stmt = $db->prepare("SELECT * FROM `omeka_incite_documents_replies_conjunction`");
        $stmt->bind_result($id, $item_id, $reply_id);
        $stmt->execute();
        fwrite($Handle, "--\n-- Dumping data for table `omeka_incite_documents_replies_conjunction`\n--\n\n");
        $current = "INSERT INTO `omeka_incite_documents_replies_conjunction` (`id`, `item_id`, `reply_id`) VALUES \n";
        fwrite($Handle, $current); 
        while ($stmt->fetch()) {
            $current = '('.$id.', '.$item_id.', '.$reply_id.'),'."\n"; 
            fwrite($Handle, $current); 
        }
        $stmt->close();
        $db->close();
        $str=file_get_contents(getcwd().'/plugins/Incite/controllers/server.sql');
        ftruncate ($Handle , strlen($str)-2);
        fwrite($Handle, ";\n\n-- --------------------------------------------------------\n\n"); 
        fclose($Handle); 
    }

    public function copy_incite_documents_tags_conjunction() {
        $File = getcwd().'/plugins/Incite/controllers/server.sql';
        $Handle = fopen($File, 'a');
        $db = DB_Connect::connectDB();
        $stmt = $db->prepare("SELECT * FROM `omeka_incite_documents_tags_conjunction`");
        $stmt->bind_result($id, $item_id, $tag_id);
        $stmt->execute();
        fwrite($Handle, "--\n-- Dumping data for table `omeka_incite_documents_tags_conjunction`\n--\n\n");
        $current = "INSERT INTO `omeka_incite_documents_tags_conjunction` (`id`, `item_id`, `tag_id`) VALUES \n";
        fwrite($Handle, $current); 
        while ($stmt->fetch()) {
            $current = '('.$id.', '.$item_id.', '.$tag_id.'),'."\n"; 
            fwrite($Handle, $current); 
        }
        $stmt->close();
        $db->close();
        $str=file_get_contents(getcwd().'/plugins/Incite/controllers/server.sql');
        ftruncate ($Handle , strlen($str)-2);
        fwrite($Handle, ";\n\n-- --------------------------------------------------------\n\n"); 
        fclose($Handle); 
    }

    public function copy_incite_groups() {
        $File = getcwd().'/plugins/Incite/controllers/server.sql';
        $Handle = fopen($File, 'a');
        $db = DB_Connect::connectDB();
        $stmt = $db->prepare("SELECT * FROM `omeka_incite_groups`");
        $stmt->bind_result($id, $name, $creator, $group_type, $instructions, $timestamp);
        $stmt->execute();
        fwrite($Handle, "--\n-- Dumping data for table `omeka_incite_groups`\n--\n\n");
        $current = "INSERT INTO `omeka_incite_groups` (`id`, `name`, `creator`, `group_type`, `instructions`, `timestamp`) VALUES \n";
        fwrite($Handle, $current); 
        while ($stmt->fetch()) {
            $current = '('.$id.', "'.$name.'", '.$creator.', '.$group_type.', "'.$instructions.'", "'.$timestamp.'"),'."\n"; 
            fwrite($Handle, $current); 
        }
        $stmt->close();
        $db->close();
        $str=file_get_contents(getcwd().'/plugins/Incite/controllers/server.sql');
        ftruncate ($Handle , strlen($str)-2);
        fwrite($Handle, ";\n\n-- --------------------------------------------------------\n\n"); 
        fclose($Handle); 
    }

    public function copy_incite_group_instructions_seen_by() {
        $File = getcwd().'/plugins/Incite/controllers/server.sql';
        $Handle = fopen($File, 'a');
        $db = DB_Connect::connectDB();
        $stmt = $db->prepare("SELECT * FROM `omeka_incite_group_instructions_seen_by`");
        $stmt->bind_result($user_id, $group_id);
        $stmt->execute();
        fwrite($Handle, "--\n-- Dumping data for table `omeka_incite_group_instructions_seen_by`\n--\n\n");
        $current = "INSERT INTO `omeka_incite_group_instructions_seen_by` (`user_id`, `group_id`) VALUES \n";
        fwrite($Handle, $current); 
        while ($stmt->fetch()) {
            $current = '('.$user_id.', '.$group_id.'),'."\n"; 
            fwrite($Handle, $current); 
        }
        $stmt->close();
        $db->close();
        $str=file_get_contents(getcwd().'/plugins/Incite/controllers/server.sql');
        ftruncate ($Handle , strlen($str)-2);
        fwrite($Handle, ";\n\n-- --------------------------------------------------------\n\n"); 
        fclose($Handle); 
    }

    public function copy_incite_group_members() {
        $File = getcwd().'/plugins/Incite/controllers/server.sql';
        $Handle = fopen($File, 'a');
        $db = DB_Connect::connectDB();
        $stmt = $db->prepare("SELECT * FROM `omeka_incite_group_members`");
        $stmt->bind_result($id, $user_id, $group_id, $privilege);
        $stmt->execute();
        fwrite($Handle, "--\n-- Dumping data for table `omeka_incite_group_members`\n--\n\n");
        $current = "INSERT INTO `omeka_incite_group_members` (`id`, `user_id`, `group_id`, `privilege`) VALUES \n";
        fwrite($Handle, $current); 
        while ($stmt->fetch()) {
            $current = '('.$id.', '.$user_id.', '.$group_id.', '.$privilege.'),'."\n"; 
            fwrite($Handle, $current); 
        }
        $stmt->close();
        $db->close();
        $str=file_get_contents(getcwd().'/plugins/Incite/controllers/server.sql');
        ftruncate ($Handle , strlen($str)-2);
        fwrite($Handle, ";\n\n-- --------------------------------------------------------\n\n"); 
        fclose($Handle); 
    }

    public function copy_incite_questions() {
        $File = getcwd().'/plugins/Incite/controllers/server.sql';
        $Handle = fopen($File, 'a');
        $db = DB_Connect::connectDB();
        $stmt = $db->prepare("SELECT * FROM `omeka_incite_questions`");
        $stmt->bind_result($id, $user_id, $working_group_id, $question_text, $is_active, $timestamp, $question_type);
        $stmt->execute();
        fwrite($Handle, "--\n-- Dumping data for table `omeka_incite_questions`\n--\n\n");
        $current = "INSERT INTO `omeka_incite_questions` ( `id`, `user_id`, `working_group_id`, `question_text`, `is_active`, `timestamp`, `question_type`) VALUES \n";
        fwrite($Handle, $current); 
        while ($stmt->fetch()) {
            $question_text = $this->escapePhpString($question_text);
            $current = "(".$id.", ".$user_id.", ".$working_group_id.", '".$question_text."', ".$is_active.", '".$timestamp."', ".$question_type."),"."\n"; 
            fwrite($Handle, $current); 
        }
        $stmt->close();
        $db->close();
        $str=file_get_contents(getcwd().'/plugins/Incite/controllers/server.sql');
        ftruncate ($Handle , strlen($str)-2);
        fwrite($Handle, ";\n\n-- --------------------------------------------------------\n\n"); 
        fclose($Handle); 
    }

    public function copy_incite_replies() {
        $File = getcwd().'/plugins/Incite/controllers/server.sql';
        $Handle = fopen($File, 'a');
        $db = DB_Connect::connectDB();
        $stmt = $db->prepare("SELECT * FROM `omeka_incite_replies`");
        $stmt->bind_result($id, $user_id, $reply_text, $question_id, $is_active, $timestamp);
        $stmt->execute();
        fwrite($Handle, "--\n-- Dumping data for table `omeka_incite_replies`\n--\n\n");
        $current = "INSERT INTO `omeka_incite_replies` (`id`, `user_id`, `reply_text`,`question_id`, `is_active`, `timestamp`) VALUES \n";
        fwrite($Handle, $current); 
        while ($stmt->fetch()) {
            $current = '('.$id.', '.$user_id.', "'.$reply_text.'", '.$question_id.', '. $is_active.', "'.$timestamp.'"),'."\n"; 
            fwrite($Handle, $current); 
        }
        $stmt->close();
        $db->close();
        $str=file_get_contents(getcwd().'/plugins/Incite/controllers/server.sql');
        ftruncate ($Handle , strlen($str)-2);
        fwrite($Handle, ";\n\n-- --------------------------------------------------------\n\n"); 
        fclose($Handle); 
    }

    public function copy_incite_tags_category() {
        $File = getcwd().'/plugins/Incite/controllers/server.sql';
        $Handle = fopen($File, 'a');
        $db = DB_Connect::connectDB();
        $stmt = $db->prepare("SELECT * FROM `omeka_incite_tags_category`");
        $stmt->bind_result($id, $name);
        $stmt->execute();
        fwrite($Handle, "--\n-- Dumping data for table `omeka_incite_tags_category`\n--\n\n");
        $current = "INSERT INTO `omeka_incite_tags_category` (`id`, `name`) VALUES \n";
        fwrite($Handle, $current); 
        while ($stmt->fetch()) {
            $current = '('.$id.', "'.$name.'"),'."\n"; 
            fwrite($Handle, $current); 
        }
        $stmt->close();
        $db->close();
        $str=file_get_contents(getcwd().'/plugins/Incite/controllers/server.sql');
        ftruncate ($Handle , strlen($str)-2);
        fwrite($Handle, ";\n\n-- --------------------------------------------------------\n\n"); 
        fclose($Handle); 
    }

    public function copy_incite_tags_subcategory() {
        $File = getcwd().'/plugins/Incite/controllers/server.sql';
        $Handle = fopen($File, 'a');
        $db = DB_Connect::connectDB();
        $stmt = $db->prepare("SELECT * FROM `omeka_incite_tags_subcategory`");
        $stmt->bind_result($id, $name, $category_id, $created_by, $timestamp);
        $stmt->execute();
        fwrite($Handle, "--\n-- Dumping data for table `omeka_incite_tags_subcategory`\n--\n\n");
        $current = "INSERT INTO `omeka_incite_tags_subcategory` (`id`, `name`, `category_id`, `created_by`, `timestamp`) VALUES \n";
        fwrite($Handle, $current); 
        while ($stmt->fetch()) {
            $current = '('.$id.', "'.$name.'", '.$category_id.', '.$created_by.', "'.$timestamp.'"),'."\n"; 
            fwrite($Handle, $current); 
        }
        $stmt->close();
        $db->close();
        $str=file_get_contents(getcwd().'/plugins/Incite/controllers/server.sql');
        ftruncate ($Handle , strlen($str)-2);
        fwrite($Handle, ";\n\n-- --------------------------------------------------------\n\n"); 
        fclose($Handle); 
    }

    public function copy_incite_users() {
        $File = getcwd().'/plugins/Incite/controllers/server.sql';
        $Handle = fopen($File, 'a');
        $db = DB_Connect::connectDB();
        $stmt = $db->prepare("SELECT * FROM `omeka_incite_users`");
        $stmt->bind_result($id, $first_name, $last_name, $email, $password, $privilege_level, $experience_level, $is_active, $working_group_id, $timestamp);
        $stmt->execute();
        fwrite($Handle, "--\n-- Dumping data for table `omeka_incite_users`\n--\n\n");
        $current = "INSERT INTO `omeka_incite_users` (`id`, `first_name`, `last_name`, `email`, `password`, `privilege_level`, `experience_level`, `is_active`, `working_group_id`, `timestamp`) VALUES \n";
        fwrite($Handle, $current); 
        $index = 0;
        while ($stmt->fetch()) {
            $current = '('.$id.', "'.$first_name.'", "'.$last_name.'", "'.$email.'", "'.$password.'", '.$privilege_level.', '.$experience_level.', '.$is_active.', '.$working_group_id.', "'.$timestamp.'"),'."\n"; 
            fwrite($Handle, $current); 
            $index++;
            if ($index == 50) {
                $str=file_get_contents(getcwd().'/plugins/Incite/controllers/server.sql');
                ftruncate ($Handle , strlen($str)-2);
                fwrite($Handle, ";\n\n-- --------------------------------------------------------\n\n"); 
                $current = "INSERT INTO `omeka_incite_users` (`id`, `first_name`, `last_name`, `email`, `password`, `privilege_level`, `experience_level`, `is_active`, `working_group_id`, `timestamp`) VALUES \n";
                fwrite($Handle, $current); 
                $index = 0;
            }
        }
        $stmt->close();
        $db->close();
        $str=file_get_contents(getcwd().'/plugins/Incite/controllers/server.sql');
        ftruncate ($Handle , strlen($str)-2);
        fwrite($Handle, ";\n\n-- --------------------------------------------------------\n\n"); 
        fclose($Handle); 
    }

    public function copy_incite_users_map() {
        $File = getcwd().'/plugins/Incite/controllers/server.sql';
        $Handle = fopen($File, 'a');
        $db = DB_Connect::connectDB();
        $stmt = $db->prepare("SELECT * FROM `omeka_incite_users_map`");
        $stmt->bind_result($non_guest_id, $guest_id);
        $stmt->execute();
        fwrite($Handle, "--\n-- Dumping data for table `omeka_incite_users_map`\n--\n\n");
        $current = "INSERT INTO `omeka_incite_users_map` (`non_guest_id`, `guest_id`) VALUES \n";
        fwrite($Handle, $current); 
        while ($stmt->fetch()) {
            $current = '('.$non_guest_id.', '.$guest_id.'),'."\n"; 
            fwrite($Handle, $current); 
        }
        $stmt->close();
        $db->close();
        $str=file_get_contents(getcwd().'/plugins/Incite/controllers/server.sql');
        ftruncate ($Handle , strlen($str)-2);
        fwrite($Handle, ";\n\n-- --------------------------------------------------------\n\n"); 
        fclose($Handle); 
    }

    public function copy_incite_documents_subject_conjunction() {
        $File = getcwd().'/plugins/Incite/controllers/server.sql';
        $Handle = fopen($File, 'a');
        $db = DB_Connect::connectDB();
        $stmt = $db->prepare("SELECT * FROM `omeka_incite_documents_subject_conjunction`");
        $stmt->bind_result($id, $item_id, $tagged_trans_id, $subject_concept_id, $rank, $user_id, $working_group_id, $created_time);
        $stmt->execute();
        fwrite($Handle, "--\n-- Dumping data for table `omeka_incite_documents_subject_conjunction`\n--\n\n");
        $current = "INSERT INTO `omeka_incite_documents_subject_conjunction` (`id`, `item_id`, `tagged_trans_id`, `subject_concept_id`, `rank`, `user_id`, `working_group_id`, `type`, `created_time`) VALUES \n";
        fwrite($Handle, $current); 
        $r = 1;
        while ($stmt->fetch()) {
            $current = '('.$id.', '.$item_id.', '.$tagged_trans_id.', '. $subject_concept_id.', '. $rank.', '. $user_id.', '. $working_group_id.', '.$r.', "'.$created_time.'"),'."\n"; 
            fwrite($Handle, $current); 
        }
        $stmt->close();
        $db->close();
        $str=file_get_contents(getcwd().'/plugins/Incite/controllers/server.sql');
        ftruncate ($Handle , strlen($str)-2);
        fwrite($Handle, ";\n\n-- --------------------------------------------------------\n\n"); 
        fclose($Handle); 
    }


    public function copy_incite_tagged_transcriptions() {
        $File = getcwd().'/plugins/Incite/controllers/server.sql';
        $Handle = fopen($File, 'a');
        $db = DB_Connect::connectDB();
        $stmt = $db->prepare("SELECT * FROM `omeka_incite_tagged_transcriptions`");
        $stmt->bind_result($id, $item_id, $transcription_id, $user_id, $working_group_id, $tagged_transcription, $type, $timestamp_approval, $timestamp_creation);
        $stmt->execute();
        fwrite($Handle, "--\n-- Dumping data for table `omeka_incite_tagged_transcriptions`\n--\n\n");
        $current = "INSERT INTO `omeka_incite_tagged_transcriptions` (`id`, `item_id`, `transcription_id`, `user_id`, `working_group_id`, `tagged_transcription`, `type`, `timestamp_approval`, `timestamp_creation`) VALUES \n";
        fwrite($Handle, $current);
        $r = 1;
        $index = 0;
        while ($stmt->fetch()) {
            $type = 1;
            if ($index == 50) {
                $str=file_get_contents(getcwd().'/plugins/Incite/controllers/server.sql');
                ftruncate ($Handle , strlen($str)-2);
                fwrite($Handle, ";\n"); 
                $current = "INSERT INTO `omeka_incite_tagged_transcriptions` (`id`, `item_id`, `transcription_id`, `user_id`, `working_group_id`, `tagged_transcription`, `type`, `timestamp_approval`, `timestamp_creation`) VALUES \n";
                fwrite($Handle, $current);
                $index = 0;
            }
            
            $tagged_transcription = $this->escapePhpString($tagged_transcription);
            $current = "(".$id.", ".$item_id.", ".$transcription_id.", ". $user_id.", ". $working_group_id.", '". $tagged_transcription."', ". $type.", '". $timestamp_approval."', '". $timestamp_creation."'),"."\n"; 
            fwrite($Handle, $current);
            $index = $index + 1;
        }
        $stmt->close();
        $db->close();
        $str=file_get_contents(getcwd().'/plugins/Incite/controllers/server.sql');
        ftruncate ($Handle , strlen($str)-2);
        fwrite($Handle, ";\n\n-- --------------------------------------------------------\n\n"); 
        fclose($Handle); 
    }
    function escapePhpString($target) {
        $replacements = array(
                "'" => "''",
                "\r\n" => "\\r\\n",
                "\n" => "\\n"
        );
        return strtr($target, $replacements);
    }

    public function copy_incite_tags_subcategory_conjunction() {
        $File = getcwd().'/plugins/Incite/controllers/server.sql';
        $Handle = fopen($File, 'a');
        $db = DB_Connect::connectDB();
        $stmt = $db->prepare("SELECT * FROM `omeka_incite_tags_subcategory_conjunction`");
        $stmt->bind_result($user_id, $group_id);
        $stmt->execute();
        fwrite($Handle, "--\n-- Dumping data for table `omeka_incite_tags_subcategory_conjunction`\n--\n\n");
        $current = "INSERT INTO `omeka_incite_tags_subcategory_conjunction` (`user_id`, `group_id`) VALUES \n";
        fwrite($Handle, $current); 
        while ($stmt->fetch()) {
            $current = '('.$tag_id.', '.$subcategory_id.'),'."\n"; 
            fwrite($Handle, $current); 
        }
        $stmt->close();
        $db->close();
        $str=file_get_contents(getcwd().'/plugins/Incite/controllers/server.sql');
        ftruncate ($Handle , strlen($str)-2);
        fwrite($Handle, ";\n\n-- --------------------------------------------------------\n\n"); 
        fclose($Handle); 
    }

    public function copy_incite_transcriptions() {
        $File = getcwd().'/plugins/Incite/controllers/server.sql';
        $Handle = fopen($File, 'a');
        $db = DB_Connect::connectDB();
        $stmt = $db->prepare("SELECT * FROM `omeka_incite_transcriptions`");
        $stmt->bind_result($id, $item_id, $user_id, $working_group_id, $transcribed_text, $summarized_text, $tone, $type, $timestamp_approval, $timestamp_creation);
        $stmt->execute();
        fwrite($Handle, "--\n-- Dumping data for table `omeka_incite_transcriptions`\n--\n\n");
        $current = "INSERT INTO `omeka_incite_transcriptions` (`id`, `item_id`, `user_id`, `working_group_id`, `transcribed_text`, `summarized_text`, `tone`, `type`, `timestamp_approval`, `timestamp_creation`) VALUES \n";
        fwrite($Handle, $current);
        $r = 1;
        $index = 0;
        while ($stmt->fetch()) {
            $type = 1;
            if ($index == 50) {
                $str=file_get_contents(getcwd().'/plugins/Incite/controllers/server.sql');
                ftruncate ($Handle , strlen($str)-2);
                fwrite($Handle, ";\n"); 
                $current = "INSERT INTO `omeka_incite_transcriptions` (`id`, `item_id`, `user_id`, `working_group_id`, `transcribed_text`, `summarized_text`, `tone`, `type`, `timestamp_approval`, `timestamp_creation`) VALUES \n";
                fwrite($Handle, $current);
                $index = 0;
            }
            
            $transcribed_text = $this->escapePhpString($transcribed_text);
            $summarized_text = $this->escapePhpString($summarized_text);
            $current = "(".$id.", ".$item_id.", ". $user_id.", ". $working_group_id.", '". $transcribed_text."', '". $summarized_text."', '" .$tone."', ". $type.", '". $timestamp_approval."', '". $timestamp_creation."'),"."\n"; 
            fwrite($Handle, $current);
            $index = $index + 1;
        }
        $stmt->close();
        $db->close();
        $str=file_get_contents(getcwd().'/plugins/Incite/controllers/server.sql');
        ftruncate ($Handle , strlen($str)-2);
        fwrite($Handle, ";\n\n-- --------------------------------------------------------\n\n"); 
        fclose($Handle); 
    }

    public function copy_incite_tags() {
        $File = getcwd().'/plugins/Incite/controllers/database.sql';
        $Handle = fopen($File, 'a');
        $db = DB_Connect::connectDB();
        $stmt = $db->prepare("SELECT * FROM `omeka_incite_tags` WHERE `type` = 2");
        $stmt->bind_result($id, $item_id, $tagged_trans_id, $user_id, $working_group_id, $tag_text, $created_timestamp, $category_id, $description, $type);
        $stmt->execute();
        
        fwrite($Handle, "--\n-- Dumping data for table `omeka_incite_tags`\n--\n\n");
        $current = "INSERT INTO `omeka_incite_tags` (`id`, `item_id`, `tagged_trans_id`, `user_id`, `working_group_id`, `tag_text`, `created_timestamp`, `category_id`, `description`, `type`) VALUES \n";
        fwrite($Handle, $current); 
        while ($stmt->fetch()) {
            $id = "NULL";
            $current = '('.$id.', '.$item_id.', '.$tagged_trans_id.', '.$user_id.', '.$working_group_id.', "'.$tag_text.'", "'. $created_timestamp.'", '. $category_id.', "'. $description.'", '.$type.'),'."\n"; 
            fwrite($Handle, $current); 
        }
        $stmt->close();
        $db->close();
        $str=file_get_contents(getcwd().'/plugins/Incite/controllers/database.sql');
        ftruncate ($Handle , strlen($str)-2);
        fwrite($Handle, ";\n\n-- --------------------------------------------------------\n\n"); 
        fclose($Handle); 
    }

    public function copy_incite_documents_subject_conjunction_answer() {
        $File = getcwd().'/plugins/Incite/controllers/database.sql';
        $Handle = fopen($File, 'a');
        $db = DB_Connect::connectDB();
        $stmt = $db->prepare("SELECT * FROM `omeka_incite_documents_subject_conjunction` WHERE `type` = 2");
        $stmt->bind_result($id, $item_id, $tagged_trans_id, $subject_concept_id, $rank, $user_id, $working_group_id, $type, $created_time);
        $stmt->execute();
        
        fwrite($Handle, "--\n-- Dumping data for table `omeka_incite_documents_subject_conjunction`\n--\n\n");
        $current = "INSERT INTO `omeka_incite_documents_subject_conjunction` (`id`, `item_id`, `tagged_trans_id`, `subject_concept_id`, `rank`, `user_id`, `working_group_id`, `type`, `created_time`) VALUES \n";
        fwrite($Handle, $current); 
        while ($stmt->fetch()) {
            $id = "NULL";
            $current = '('.$id.', '.$item_id.', '.$tagged_trans_id.', '. $subject_concept_id.', '. $rank.', '. $user_id.', '. $working_group_id.', '.$type.', "'.$created_time.'"),'."\n"; 
            fwrite($Handle, $current); 
        }
        $stmt->close();
        $db->close();
        $str=file_get_contents(getcwd().'/plugins/Incite/controllers/database.sql');
        ftruncate ($Handle , strlen($str)-2);
        fwrite($Handle, ";\n\n-- --------------------------------------------------------\n\n"); 
        fclose($Handle); 
    }

    public function copy_incite_transcriptions_answer() {
        $File = getcwd().'/plugins/Incite/controllers/database.sql';
        $Handle = fopen($File, 'a');
        $db = DB_Connect::connectDB();
        $stmt = $db->prepare("SELECT * FROM `omeka_incite_transcriptions` WHERE `type` = 2");
        $stmt->bind_result($id, $item_id, $user_id, $working_group_id, $transcribed_text, $summarized_text, $tone, $type, $timestamp_approval, $timestamp_creation);
        $stmt->execute();
        $id = "NULL";
        fwrite($Handle, "--\n-- Dumping data for table `omeka_incite_transcriptions`\n--\n\n");
        $current = "INSERT INTO `omeka_incite_transcriptions` (`id`, `item_id`, `user_id`, `working_group_id`, `transcribed_text`, `summarized_text`, `tone`, `type`, `timestamp_approval`, `timestamp_creation`) VALUES \n";
        fwrite($Handle, $current);
        while ($stmt->fetch()) {
            $transcribed_text = $this->escapePhpString($transcribed_text);
            $summarized_text = $this->escapePhpString($summarized_text);
            $current = "(NULL, ".$item_id.", ". $user_id.", ". $working_group_id.", '". $transcribed_text."', '". $summarized_text."', '" .$tone."', ". $type.", NULL, '". $timestamp_creation."'),"."\n"; 
            fwrite($Handle, $current);
        }
        $stmt->close();
        $db->close();
        $str=file_get_contents(getcwd().'/plugins/Incite/controllers/database.sql');
        ftruncate ($Handle , strlen($str)-2);
        fwrite($Handle, ";\n\n-- --------------------------------------------------------\n\n"); 
        fclose($Handle); 
    }

    public function copy_incite_tagged_transcriptions_to_generate_tags() {
        $File = getcwd().'/plugins/Incite/controllers/sup.sql';
        $Handle = fopen($File, 'a');
        $db = DB_Connect::connectDB();
        $stmt = $db->prepare("SELECT DISTINCT * FROM `omeka_incite_tagged_transcriptions` ORDER BY `timestamp_creation` DESC");
        $stmt->bind_result($id, $item_id, $transcription_id, $user_id, $working_group_id, $tagged_transcription, $type, $timestamp_approval, $timestamp_creation);
        $stmt->execute();
        fwrite($Handle, "--\n-- Dumping data for table `omeka_incite_tags`\n--\n\n");
        
        $r = 1;
        $index = 0;
        while ($stmt->fetch()) {
            print_r($item_id);
            echo "\n";
            $tagged_transcription = $this->escapePhpString($tagged_transcription);
            $this->process_tagged_transcription ($tagged_transcription, $id, $item_id, $user_id, $working_group_id, $timestamp_creation);
        }
        $stmt->close();
        $db->close();
        $str=file_get_contents(getcwd().'/plugins/Incite/controllers/sup.sql');
        ftruncate ($Handle , strlen($str)-2);
        fwrite($Handle, ";\n\n-- --------------------------------------------------------\n\n"); 
        fclose($Handle); 
    }

    function process_tagged_transcription ($tagged_transcription, $tagged_trans_id, $item_id, $user_id, $working_group_id, $timestamp_creation) {
        $File = getcwd().'/plugins/Incite/controllers/sup.sql';
        $Handle = fopen($File, 'a');
        $type = 1;
        $description = "NULL";
        $id = "NULL";
        $str=file_get_contents(getcwd().'/plugins/Incite/controllers/sup.sql');
        ftruncate ($Handle , strlen($str)-2);
        fwrite($Handle, ";\n\n-- --------------------------------------------------------\n\n"); 
        if (strpos($tagged_transcription, "<em") !== false) {
            $current = "INSERT INTO `omeka_incite_tags` (`id`, `item_id`, `tagged_trans_id`, `user_id`, `working_group_id`, `tag_text`, `created_timestamp`, `category_id`, `description`, `type`) VALUES \n";
            fwrite($Handle, $current); 
            while (strpos($tagged_transcription, "<em") !== false) {
                
                $em_start = strpos($tagged_transcription, "<em");
                $em = substr($tagged_transcription, $em_start + 3);
                $tagged_transcription = substr($tagged_transcription,$em_start);
                $end = strpos($em, ">");
                $em = substr($em, 0, $end);
                $tagged_transcription = substr($tagged_transcription, strpos($tagged_transcription, ">") + 1);
                $end_index = strpos($tagged_transcription, "</em>");
                $tag = substr($tagged_transcription, 0, $end_index);
                $tag = $this->escapePhpString($tag);
                $tagged_transcription = substr($tagged_transcription,$end_index + 5);
                //Parse category
                $pattern = '/class="([a-z\-\s]+)"/';
                preg_match($pattern, $em, $matches, PREG_OFFSET_CAPTURE);
                $cat = ucfirst(explode (" " , $matches[1][0])[0]);
                $cat_id = $this->tag_to_id($cat);
                //Parse detail
                $pattern = '/data-details="([A-Za-z\s]+)"/';
                preg_match($pattern, $em, $matches, PREG_OFFSET_CAPTURE);
                if ($matches == null)
                    $description = NULL;
                else
                    $description = $matches[1][0];
                if (strpos($tag, "&nbsp") == false) {
                    $current = "(".$id.", ".$item_id.", ".$tagged_trans_id.", ".$user_id.", ".$working_group_id.", '".$tag."', '". $timestamp_creation."', ".$cat_id .", '". $description."', ".$type."),"."\n"; 
                    fwrite($Handle, $current);
                }
                
            }
            
            $str=file_get_contents(getcwd().'/plugins/Incite/controllers/sup.sql');
            ftruncate ($Handle , strlen($str)-2);
            fwrite($Handle, ";\n\n-- --------------------------------------------------------\n\n"); 
            
            fclose($Handle);
        }
    }

    function tag_to_id ($cat) {
        if ($cat == "Location")
            return 1;
        else if ($cat == "Event") 
            return 2;
        else if ($cat == "Person") 
            return 3;
        else if ($cat == "Organization") 
            return 4;
        else if ($cat == "Other") 
            return 5;
        else 
            return 0;
    }

    function find_all_item_with_transcription() {
        $db = DB_Connect::connectDB();
        $stmt = $db->prepare("SELECT `document_id` FROM `omeka_incite_transcriptions`");
        $stmt->bind_result($item_id);
        $stmt->execute();
        $items = array();
        while ($stmt->fetch()) {
            $items[] = $item_id;
        }
        $items = array_unique($items);
        $stmt->close();
        $db->close();
        return $items;
    }
    
    function find_all_item_taggable() {
        $items = $this->find_all_item_with_transcription();
        $taggable = array();
        $tagged = array();
        $db = DB_Connect::connectDB();
        foreach ($items as $key => $value) {
            $stmt = $db->prepare("SELECT `tagged_transcription` FROM `omeka_incite_tagged_transcriptions` WHERE `item_id` = ?");
            $stmt->bind_param("i", $value);
            $stmt->bind_result($trans);
            $stmt->execute();
            $stmt->store_result();
            $stmt->fetch();
            if ($stmt->num_rows == 0)
                $taggable[] = $value;
            else
                $tagged[] = $value;
        }
        $stmt->close();
        $db->close();
        $this->fill_taggable($taggable);
        $this->find_all_item_connectable($tagged);
        return $taggable;
    }

    function find_all_item_connectable($tagged) {
        $connectable = array();
        $db = DB_Connect::connectDB();
        foreach ($tagged as $key => $value) {
            $stmt = $db->prepare("SELECT `created_time` FROM `omeka_incite_documents_subject_conjunction` WHERE `document_id` = ?");
            $stmt->bind_param("i", $value);
            $stmt->bind_result($trans);
            $stmt->execute();
            $stmt->store_result();
            $stmt->fetch();
            if ($stmt->num_rows == 0)
                $connectable[] = $value;
            $stmt->close();
        }
        $this->fill_connectable($connectable);
        
        $db->close();
    }

    function fill_taggable($taggable) {
        $File = getcwd().'/plugins/Incite/controllers/available.sql';
        $Handle = fopen($File, 'a');
        $db = DB_Connect::connectDB();
        fwrite($Handle, "--\n-- Dumping data for table `omeka_incite_available_list`\n--\n\n");
        $current = "INSERT INTO `omeka_incite_available_list` (`id`, `item_id`, `ready_tag`, `ready_connect`) VALUES \n";
        fwrite($Handle, $current); 
        $id = "NULL";
        $ready_tag = 1;
        $ready_connect = 0;
        foreach ($taggable as $key => $value){
            $current = '('.$id.', '.$value.', '.$ready_tag.', '. $ready_connect.'),'."\n"; 
            fwrite($Handle, $current); 
        }
        $str=file_get_contents(getcwd().'/plugins/Incite/controllers/available.sql');
        ftruncate ($Handle , strlen($str)-2);
        fwrite($Handle, ";\n\n-- --------------------------------------------------------\n\n"); 
        fclose($Handle); 
    }

    function fill_connectable($connectable) {
        $File = getcwd().'/plugins/Incite/controllers/available.sql';
        $Handle = fopen($File, 'a');
        $db = DB_Connect::connectDB();
        fwrite($Handle, "--\n-- Dumping data for table `omeka_incite_available_list`\n--\n\n");
        $current = "INSERT INTO `omeka_incite_available_list` (`id`, `item_id`, `ready_tag`, `ready_connect`) VALUES \n";
        fwrite($Handle, $current); 
        $id = "NULL";
        $ready_tag = 0;
        $ready_connect = 1;
        foreach ($connectable as $key => $value){
            $current = '('.$id.', '.$value.', '.$ready_tag.', '. $ready_connect.'),'."\n"; 
            fwrite($Handle, $current); 
        }
        $str=file_get_contents(getcwd().'/plugins/Incite/controllers/available.sql');
        ftruncate ($Handle , strlen($str)-2);
        fwrite($Handle, ";\n\n-- --------------------------------------------------------\n\n"); 
        fclose($Handle); 
    }
    

}

