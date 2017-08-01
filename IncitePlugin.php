<?php
//require_once("controllers/Incite_Env_Setting.php");
require_once("controllers/Incite_Helpers.php");
//require_once("controllers/Incite_Subject_Concept_Table.php");
/**
 * The Incite plugin.
 *
 * @package Omeka\Plugins\Incite
 */
class IncitePlugin extends Omeka_Plugin_AbstractPlugin 
{  
    protected $_hooks = array(
        'install',
        'uninstall',
        'config_form',
        'config',
        'define_routes',
        'public_items_show',
        'public_head',
    );

    /**
     * Create the database tables
     */
    public function hookInstall() {
        $db = get_db();
        $db->query(<<<SQL
    CREATE TABLE IF NOT EXISTS {$db->prefix}incite_documents (
        `id`                int(11) NOT NULL AUTO_INCREMENT,
        `item_id`               int(11) NOT NULL,
        `user_id`               int(11) NOT NULL,
        `tags_ignored`          int(11) NOT NULL,
        `is_locked`             int(11) NOT NULL,
        `document_difficulty`   int(11) NOT NULL,
        `question_id`           int(11) NOT NULL,
        `Timestamp`             timestamp NOT NULL,
  
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
SQL
        );

        get_db()->query(<<<SQL
    CREATE TABLE IF NOT EXISTS {$db->prefix}incite_groups (
        `id`                int(11) NOT NULL AUTO_INCREMENT,
        `name`              varchar(200) NOT NULL,
        `creator`           int(11) NOT NULL,
        `group_type`        int(11) NOT NULL,
        `instructions`      varchar(3000) NOT NULL,
        `timestamp`         timestamp NOT NULL,
        
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
SQL
        );
        get_db()->query(<<<SQL
      CREATE TABLE IF NOT EXISTS {$db->prefix}incite_documents_subject_conjunction (
        `id`                    int(11) NOT NULL AUTO_INCREMENT,
        `item_id`               int(11) NOT NULL,
        `tagged_trans_id`       int(11) NOT NULL,             
        `subject_concept_id`    int(11) NOT NULL,
        `rank`                  int(5) NOT NULL,
        `user_id`               int(11) NOT NULL,
        `working_group_id`      int(11) NOT NULL,
        `type`                  int(8) NOT NULL,
        `created_time`          timestamp NOT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
SQL
                );
        get_db()->query(<<<SQL
    CREATE TABLE IF NOT EXISTS {$db->prefix}incite_documents_tags_conjunction (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `item_id` int(11) NOT NULL,
        `tag_id` int(11) NOT NULL,
                
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;       
SQL
        );

        get_db()->query(<<<SQL
    CREATE TABLE IF NOT EXISTS {$db->prefix}incite_tag_answer_explain_list (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `item_id` int(11) NOT NULL,
        `question_id` int(11) NOT NULL,
        `answer`      varchar(500) NOT NULL,
        `correct`      varchar(11) NOT NULL,
        `explanation`      varchar(10000) NOT NULL,
                
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;       
SQL
        );

        get_db()->query(<<<SQL
   CREATE TABLE IF NOT EXISTS {$db->prefix}incite_group_members (
        `id`            int(11) NOT NULL AUTO_INCREMENT,
        `user_id`       int(11) NOT NULL,
        `group_id`      int(11) NOT NULL,
        `privilege`     int(11) NOT NULL,
        
        PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
SQL
        );

        get_db()->query(<<<SQL
   CREATE TABLE IF NOT EXISTS {$db->prefix}incite_group_instructions_seen_by (
        `user_id`       int(11) NOT NULL,
        `group_id`      int(11) NOT NULL,
        
        PRIMARY KEY (`user_id`, `group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
SQL
        );
   
        get_db()->query(<<<SQL
   CREATE TABLE IF NOT EXISTS {$db->prefix}incite_questions (
        `id`                    int(11) NOT NULL AUTO_INCREMENT,
        `user_id`               int(11) NOT NULL,
        `working_group_id`      int(11) NOT NULL,
        `question_text`         varchar(1000) NOT NULL,
        `is_active`             int(11) NOT NULL,
        `timestamp`             timestamp NOT NULL,
        `question_type`         int(11) NOT NULL,
  
        PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
SQL
        );

        get_db()->query(<<<SQL
   CREATE TABLE IF NOT EXISTS {$db->prefix}incite_replies (
        `id`            int(11) NOT NULL AUTO_INCREMENT,
        `user_id`       int(11) NOT NULL,
        `reply_text`    varchar(500) NOT NULL,
        `question_id`   int(11) NOT NULL,
        `is_active`     int(11) NOT NULL,
        `timestamp`     timestamp NOT NULL,
        
        PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
SQL
        );
   
        get_db()->query(<<<SQL
   CREATE TABLE IF NOT EXISTS {$db->prefix}incite_documents_questions_conjunction (
        `id`                int(11) NOT NULL AUTO_INCREMENT,
        `item_id`       int(11) NOT NULL,
        `question_id`       int(11) NOT NULL,
   
        PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
SQL
        );
   get_db()->query(<<<SQL
   CREATE TABLE IF NOT EXISTS {$db->prefix}incite_documents_replies_conjunction (
        `id`                int(11) NOT NULL AUTO_INCREMENT,
        `item_id`       int(11) NOT NULL,
        `reply_id`           int(11) NOT NULL,
        
        PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
SQL
        );
   

        get_db()->query(<<<SQL
   CREATE TABLE IF NOT EXISTS {$db->prefix}incite_subject_concepts (
        `id`            int(11) NOT NULL AUTO_INCREMENT,
        `name`          varchar(60) NOT NULL,
        `definition`    varchar(500) NOT NULL,
        
        PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;  
SQL
        );
        /*
        get_db()->query(<<<SQL
    
    INSERT INTO {$db->prefix}incite_subject_concepts (`id`, `name`, `definition`) VALUES (NULL, 'Religion', 'This document refers to religious ideas, prayers, ministers, etc.'), (NULL, 'White Supremacy', 'This document discusses the belief that white people are racially superior.'), (NULL, 'Racial Equality', 'This document discusses the belief that people of all racial backgrounds are equal.'), (NULL, 'Gender Equality/Inequality', 'This document discusses the status of men and/or women.'), (NULL, 'Human Equality', 'This document refers to the idea that all people are equal.'), (NULL, 'Self Goverment', 'This document refers to democracy, the idea that people should have a say in their own governance.'), (NULL, 'America as a Global Beacon', 'This document celebrates America''s status as an example for the rest of the world to follow.'), (NULL, 'Celebration of Revolutionary Generation', 'This document glorifies the Americans who fought the revolution.'), (NULL, 'White Southerners', 'This document discusses whether or not white southerners should celebrate July 4.');
   
SQL
   );
    */
        get_db()->query(<<<SQL
   CREATE TABLE IF NOT EXISTS {$db->prefix}incite_tags (
        `id`                    int(11) NOT NULL AUTO_INCREMENT,
        `item_id`               int(11) NOT NULL,
        `tagged_trans_id`       int(11) NOT NULL,
        `user_id`               int(11) NOT NULL,
        `working_group_id`      int(11) NOT NULL,
        `tag_text`              varchar(30) NOT NULL,
        `created_timestamp`     timestamp NOT NULL,
        `category_id`           int(11) NOT NULL,
        `description`           varchar(300) NOT NULL,
        `type`                  int(8) NOT NULL,
  
        PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
SQL
        );

        get_db()->query(<<<SQL
   CREATE TABLE IF NOT EXISTS {$db->prefix}incite_transcriptions (
        `id`                    int(11) NOT NULL AUTO_INCREMENT,
        `item_id`           int(11) NOT NULL,
        `user_id`               int(11) NOT NULL,
        `working_group_id`      int(11) NOT NULL,
        `transcribed_text`      varchar(200000) NOT NULL,
        `summarized_text`       varchar(1000) NOT NULL,
        `tone`                  varchar(50) NOT NULL,
        `type`           int(11) NOT NULL,
        `timestamp_approval`    timestamp NULL DEFAULT NULL,
        `timestamp_creation`    timestamp NOT NULL,
        
        PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
SQL
        );

        get_db()->query(<<<SQL
   CREATE TABLE IF NOT EXISTS {$db->prefix}incite_available_list (
        `id`                    int(11) NOT NULL AUTO_INCREMENT,
        `item_id`               int(11) NOT NULL,
        `ready_tag`             int(8) NOT NULL,
        `ready_connect`         int(8) NOT NULL,
        
        PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
SQL
        );



        get_db()->query(<<<SQL
   CREATE TABLE IF NOT EXISTS {$db->prefix}incite_tagged_transcriptions (
        `id`                    int(11) NOT NULL AUTO_INCREMENT,
        `item_id`               int(11) NOT NULL,
        `transcription_id`      int(11) NOT NULL,
        `user_id`               int(11) NOT NULL,
        `working_group_id`      int(11) NOT NULL,
        `tagged_transcription`  varchar(200000) NOT NULL,
        `type`           int(11) NOT NULL,
        `timestamp_approval`    timestamp NULL DEFAULT NULL,
        `timestamp_creation`    timestamp NOT NULL,
        
        PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
SQL
        );


        get_db()->query(<<<SQL
   CREATE TABLE IF NOT EXISTS {$db->prefix}incite_tag_question_conjunction (
        `id`                    int(11) NOT NULL AUTO_INCREMENT,
        `tagged_trans_id`       int(11) NOT NULL,
        `question_id`           int(11) NOT NULL,
        `answer`                varchar(500) NOT NULL,
        `type`                  int(11) NOT NULL,

        PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
SQL
        );


        get_db()->query(<<<SQL
   CREATE TABLE IF NOT EXISTS {$db->prefix}incite_subject_explain (
        `id`                   int(11) NOT NULL AUTO_INCREMENT,
        `item_id`              int(11) NOT NULL,
        `concept_id`           int(11) NOT NULL,   
        `explanation`         varchar(1000) NOT NULL,

        PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
SQL
        );
        get_db()->query(<<<SQL
   CREATE TABLE IF NOT EXISTS {$db->prefix}incite_tag_question_index (
        `id`                   int(11) NOT NULL,
        `question`             varchar(1000) NOT NULL,
        
        PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
SQL
        );
        get_db()->query(<<<SQL
   CREATE TABLE IF NOT EXISTS {$db->prefix}incite_users (
        `id`                int(11) NOT NULL AUTO_INCREMENT,
        `first_name`        varchar(15) NOT NULL,
        `last_name`         varchar(15) NOT NULL,
        `email`             varchar(50) NOT NULL,
        `password`          varchar(32) NOT NULL,
        `privilege_level`   int(11) NOT NULL,
        `experience_level`  int(11) NOT NULL,
        `is_active`         int(11) NOT NULL,
        `working_group_id`  int(11) NOT NULL,
        `timestamp`         timestamp NOT NULL,
        
        PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
SQL
        );
        get_db()->query(<<<SQL
    CREATE TABLE IF NOT EXISTS {$db->prefix}incite_tags_subcategory (
        `id`                int(10) unsigned NOT NULL AUTO_INCREMENT,
        `name`              varchar(30) NOT NULL,
        `category_id`       int(10) unsigned NOT NULL,
        `created_by`        int(10) unsigned NOT NULL,
        `timestamp`         timestamp NOT NULL,
  
        PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
SQL
    );
        get_db()->query(<<<SQL
      INSERT INTO {$db->prefix}incite_tags_subcategory (`id`, `name`, `category_id`, `timestamp`) VALUES (NULL, 'Country', 1, NULL), (NULL, 'State', 1, NULL), (NULL, 'City', 1, NULL), (NULL, 'Town', 1, NULL), (NULL, 'Building', 1, NULL), (NULL, 'Oration', 2, NULL), (NULL, 'Banquet', 2, NULL), (NULL, 'Fireworks', 2, NULL), (NULL, 'Parade', 2, NULL), (NULL, 'Reading of Declaration', 2, NULL), (NULL, 'Prayer', 2, NULL), (NULL, 'Excursion', 2, NULL), (NULL, 'Music', 2, NULL), (NULL, 'White Southerners', 3, NULL), (NULL, 'White Northerners', 3, NULL), (NULL, 'African Americans', 3, NULL), (NULL, 'Women', 3, NULL), (NULL, 'Immigrants',3, NULL), (NULL, 'Whigs', 3, NULL), (NULL, 'Democrats', 3, NULL), (NULL, 'Republicans', 3, NULL), (NULL, 'Military', 4, NULL), (NULL, 'Social', 4, NULL), (NULL, 'Charity', 4, NULL), (NULL, 'Business', 4, NULL), (NULL, 'Religious', 4, NULL), (NULL, 'Political', 4, NULL);
   
SQL
    );
    
        get_db()->query(<<<SQL
    CREATE TABLE IF NOT EXISTS {$db->prefix}incite_tags_category (
        `id`            int(11) NOT NULL AUTO_INCREMENT,
        `name`          varchar(30) NOT NULL,
  
        PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1; 
SQL
   );
   get_db()->query(<<<SQL
    CREATE TABLE IF NOT EXISTS {$db->prefix}incite_trans_reason (
        `id`            int(11) NOT NULL AUTO_INCREMENT,
        `item_id`       int(11) NOT NULL,
        `reason`        varchar(100000) ,
  
        PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1; 
SQL
   );
        get_db()->query(<<<SQL
    INSERT INTO {$db->prefix}incite_tags_category (`id`, `name`) VALUES (NULL, 'Location'), (NULL, 'Event'), (NULL, 'Person'), (NULL, 'Organization'), (NULL, 'Other');
    
SQL
   );
            get_db()->query(<<<SQL
    CREATE TABLE IF NOT EXISTS {$db->prefix}incite_users_map (
        `non_guest_id`  int(10) unsigned NOT NULL,
        `guest_id`      int(10) unsigned NOT NULL
                
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;  
SQL
    );
        get_db()->query(<<<SQL
    CREATE TABLE IF NOT EXISTS {$db->prefix}incite_tags_subcategory_conjunction (
        `id`                int(11) NOT NULL AUTO_INCREMENT,
        `tag_id`            int(11) NOT NULL,
        `subcategory_id`    int(11) NOT NULL,
    
   PRIMARY KEY (`id`) 
) ENGINE=InnoDB DEFAULT CHARSET=utf8;    
   
SQL
    );  
      
}

    /**
     * remove the database tables
     */
    public function hookUninstall() {
                $db = get_db();
        $db->query(<<<SQL
        DROP TABLE IF EXISTS {$this->_db->prefix}incite_category_table
SQL
        );
        get_db()->query(<<<SQL
      DROP TABLE IF EXISTS {$this->_db->prefix}incite_documents
SQL
        );
        get_db()->query(<<<SQL
      DROP TABLE IF EXISTS {$this->_db->prefix}incite_groups
SQL
        );
        get_db()->query(<<<SQL
      DROP TABLE IF EXISTS {$this->_db->prefix}incite_tag_answer_explain_list
SQL
        );
        get_db()->query(<<<SQL
      DROP TABLE IF EXISTS {$this->_db->prefix}incite_available_list
SQL
        );
        get_db()->query(<<<SQL
      DROP TABLE IF EXISTS {$this->_db->prefix}incite_subject_explain
SQL
        );
        get_db()->query(<<<SQL
      DROP TABLE IF EXISTS {$this->_db->prefix}incite_tag_question_conjunction
SQL
        );
        get_db()->query(<<<SQL
      DROP TABLE IF EXISTS {$this->_db->prefix}incite_tag_question_index
SQL
        );
        get_db()->query(<<<SQL
      DROP TABLE IF EXISTS {$this->_db->prefix}incite_trans_reason
SQL
        );
        get_db()->query(<<<SQL
      DROP TABLE IF EXISTS {$this->_db->prefix}incite_group_members
SQL
        );
        get_db()->query(<<<SQL
      DROP TABLE IF EXISTS {$this->_db->prefix}incite_questions
SQL
        );
        get_db()->query(<<<SQL
      DROP TABLE IF EXISTS {$this->_db->prefix}incite_replies
SQL
        );
        get_db()->query(<<<SQL
      DROP TABLE IF EXISTS {$this->_db->prefix}incite_subject_concepts
SQL
        );
        get_db()->query(<<<SQL
      DROP TABLE IF EXISTS {$this->_db->prefix}incite_tags
SQL
        );
        get_db()->query(<<<SQL
      DROP TABLE IF EXISTS {$this->_db->prefix}incite_transcriptions
SQL
        );
        get_db()->query(<<<SQL
      DROP TABLE IF EXISTS {$this->_db->prefix}incite_tagged_transcriptions
SQL
        );
        get_db()->query(<<<SQL
      DROP TABLE IF EXISTS {$this->_db->prefix}incite_users
SQL
        );
      
      get_db()->query(<<<SQL
    DROP TABLE IF EXISTS {$this->_db->prefix}incite_documents_tags_conjunction
SQL
              );
     get_db()->query(<<<SQL
    DROP TABLE IF EXISTS {$this->_db->prefix}incite_tags_category
SQL
   );
    get_db()->query(<<<SQL
    DROP TABLE IF EXISTS {$this->_db->prefix}incite_tags_subcategory
SQL
   );
    get_db()->query(<<<SQL
    DROP TABLE IF EXISTS {$this->_db->prefix}incite_tags_subcategory_conjunction
SQL
   );
    get_db()->query(<<<SQL
    DROP TABLE IF EXISTS {$this->_db->prefix}incite_documents_subject_conjunction
SQL
   );
    get_db()->query(<<<SQL
    DROP TABLE IF EXISTS {$db->prefix}incite_users_map
SQL
   );
    get_db()->query(<<<SQL
    DROP TABLE IF EXISTS {$this->_db->prefix}incite_documents_questions_conjunction
SQL
   );
    get_db()->query(<<<SQL
    DROP TABLE IF EXISTS {$this->_db->prefix}incite_documents_replies_conjunction
SQL
   );
    get_db()->query(<<<SQL
    DROP TABLE IF EXISTS {$this->_db->prefix}incite_group_instructions_seen_by
SQL
   );
    get_db()->query(<<<SQL
    DROP TABLE IF EXISTS {$this->_db->prefix}incite_group
SQL
   );
    }

    /**
     * Display the config form.
     */
    public function hookConfigForm() {
        require 'config-form.php';
    }

    /**
     * Handle the config form.
     */
    public function hookConfig() {
        set_option('encoded_concept', $_POST['encoded_concept']);
        set_option('encoded_def', $_POST['encoded_def']);
        $concept = $_POST['concept'];
        $def = $_POST['def'];
        $removed_concept = $_POST['del_concept'];
        for ($i = 0; $i < count($concept); $i++) {
            $this->addConcept($concept[$i], $def[$i]);
        }
        for ($i = 0; $i < count($removed_concept); $i++) {
            $this->removeConcept($removed_concept[$i]);
        }
        
        $uploadOK = 0;
        set_option('title', trim($_POST['title']));
        set_option('intro', trim($_POST['intro']));
        set_option('twitter_timeline', trim($_POST['twitter_timeline']));
        set_option('twitter_button', trim($_POST['twitter_button']));
        set_option('fb', trim($_POST['fb']));
        $target = dirname(__FILE__). "/views/shared/images/customized_logo.png";
        if(isset($_POST['confirm-logo'])) {
            $uploadOK = 1;
        }
        if ($uploadOK == 1) {

            if (move_uploaded_file($_FILES["logo"]["tmp_name"], $target)) {
                echo "The file has been uploaded.";
            } 
            else {
                echo "fail";
            }
        }
    }

    public function addConcept($concept, $def) {
        $ini_array = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . getOmekaPath(). '/../db.ini');
        $dbhost = $ini_array["host"];
        $dbuser = $ini_array["username"];
        $dbpass = $ini_array["password"];
        $dbname = $ini_array["dbname"];
        $db = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
        if (mysqli_connect_errno()) 
        {
            printf("Connection to database has failed: %s\n", $db_conn->connect_error);
            exit();
        }
        $stmt = $db->prepare("INSERT INTO omeka_incite_subject_concepts VALUES (NULL, ?, ?)");
        $stmt->bind_param("ss", $concept, $def);
        $stmt->execute();
        $stmt->close();
        $db->close();
    }

    public function removeConcept($concept) {
        $ini_array = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . getOmekaPath(). '/../db.ini');
        $dbhost = $ini_array["host"];
        $dbuser = $ini_array["username"];
        $dbpass = $ini_array["password"];
        $dbname = $ini_array["dbname"];
        $db = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
        if (mysqli_connect_errno()) 
        {
            printf("Connection to database has failed: %s\n", $db_conn->connect_error);
            exit();
        }
        $stmt = $db->prepare("DELETE FROM `omeka_incite_subject_concepts` WHERE `name` = ?");
        $stmt->bind_param("s", $concept);
        $stmt->execute();
        $stmt->close();
        $db->close();
    }

    /**
     * Controller triggering on /incite/stage/ACTIONS,
     */
    public function hookPublicHead($args) {
        get_view()->addHelperPath(dirname(__FILE__) . '/views/helpers', 'Incite_View_Helper_');
    }

    /**
     * Print out configured message on item show
     */
    public function hookPublicItemsShow() {
        echo '<p>' . get_option('text_test') . '</p>';
    }

    public function hookDefineRoutes($args) {
        /*
          if (!defined('EXHIBIT_PLUGIN_DIR')) {
          define('EXHIBIT_PLUGIN_DIR', dirname(__FILE__));
          }
         */
        $router = $args['router'];
//    	    $router->addConfig(new Zend_Config_Ini(EXHIBIT_PLUGIN_DIR .
//        DIRECTORY_SEPARATOR . 'routes.ini', 'routes'));
///*	    
        $route = new Zend_Controller_Router_Route(
                'incite/:controller/:action/:id', array(
            'module' => 'incite',
            'controller' => 'documents',
            'action' => 'index')
        );
        $router->addRoute('incite', $route);
//*/
        //print_r($router);
    }


}
