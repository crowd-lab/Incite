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
                CREATE TABLE IF NOT EXISTS {$db->InciteGroup} (
                    `id`                     int(11) NOT NULL AUTO_INCREMENT,
                    `name`                   varchar(200) NOT NULL,
                    `creator_id`             int(11) NOT NULL,
                    `group_type`             int(11) NOT NULL,
                    `instructions`           varchar(3000) NOT NULL,
                    `timestamp_creation`     timestamp NOT NULL,

                    PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci AUTO_INCREMENT=1;
SQL
                );
        $db->query(<<<SQL
                CREATE TABLE IF NOT EXISTS {$db->InciteItemsSubjects} (
                    `id`                    int(11) NOT NULL AUTO_INCREMENT,
                    `item_id`               int(11) NOT NULL,
                    `tagged_trans_id`       int(11) NOT NULL,             
                    `subject_id`    int(11) NOT NULL,
                    `rating`                  int(5) NOT NULL,
                    `user_id`               int(11) NOT NULL,
                    `working_group_id`      int(11) NOT NULL,
                    `type`                  int(8) NOT NULL,
                    `timestamp_creation`          timestamp NOT NULL,
                    PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci AUTO_INCREMENT=1 ;
SQL
                );
        $db->query(<<<SQL
                CREATE TABLE IF NOT EXISTS {$db->InciteItemsTags} (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `item_id` int(11) NOT NULL,
                    `tag_id` int(11) NOT NULL,

                    PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci AUTO_INCREMENT=1 ;       
SQL
                );

        $db->query(<<<SQL
                CREATE TABLE IF NOT EXISTS {$db->prefix}incite_tag_answer_explain_list (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `item_id` int(11) NOT NULL,
                    `question_id` int(11) NOT NULL,
                    `answer`      varchar(500) NOT NULL,
                    `correct`      varchar(11) NOT NULL,
                    `explanation`      varchar(10000) NOT NULL,

                    PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci AUTO_INCREMENT=1 ;       
SQL
                );

        $db->query(<<<SQL
                CREATE TABLE IF NOT EXISTS {$db->InciteGroupsUsers} (
                    `id`                    int(11) NOT NULL AUTO_INCREMENT,
                    `group_id`              int(11) NOT NULL,
                    `user_id`               int(11) NOT NULL,
                    `group_privilege`       int(11) NOT NULL,
                    `seen_instruction`      int(3) NOT NULL,
                    `timestamp_creation`    timestamp NOT NULL,

                    PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci AUTO_INCREMENT=1;
SQL
                );

        $db->query(<<<SQL
                CREATE TABLE IF NOT EXISTS {$db->InciteDiscussion} (
                    `id`                    int(11) NOT NULL AUTO_INCREMENT,
                    `user_id`               int(11) NOT NULL,
                    `working_group_id`      int(11) NOT NULL,
                    `discussion_text`       varchar(1000) NOT NULL,
                    `is_active`             int(11) NOT NULL,
                    `discussion_type`       int(11) NOT NULL,
                    `timestamp_creation`    timestamp NOT NULL,

                    PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci AUTO_INCREMENT=1;
SQL
                );

        $db->query(<<<SQL
                CREATE TABLE IF NOT EXISTS {$db->InciteComment} (
                    `id`                    int(11) NOT NULL AUTO_INCREMENT,
                    `user_id`               int(11) NOT NULL,
                    `comment_text`          varchar(500) NOT NULL,
                    `discussion_id`         int(11) NOT NULL,
                    `is_active`             int(11) NOT NULL,
                    `timestamp_creation`    timestamp NOT NULL,

                    PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci AUTO_INCREMENT=1;
SQL
                );

        $db->query(<<<SQL
                CREATE TABLE IF NOT EXISTS {$db->InciteItemsDiscussions} (
                    `id`                 int(11) NOT NULL AUTO_INCREMENT,
                    `item_id`            int(11) NOT NULL,
                    `discussion_id`      int(11) NOT NULL,

                    PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci AUTO_INCREMENT=1;
SQL
                );
        $db->query(<<<SQL
                CREATE TABLE IF NOT EXISTS {$db->prefix}incite_documents_replies_conjunction (
                    `id`                int(11) NOT NULL AUTO_INCREMENT,
                    `item_id`           int(11) NOT NULL,
                    `comment_id`          int(11) NOT NULL,

                    PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci AUTO_INCREMENT=1;
SQL
                );


        $db->query(<<<SQL
                CREATE TABLE IF NOT EXISTS {$db->InciteSubject} (
                    `id`                    int(11) NOT NULL AUTO_INCREMENT,
                    `name`                  varchar(60) NOT NULL,
                    `definition`            varchar(500) NOT NULL,
                    `timestamp_creation`    timestamp NOT NULL,

                    PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci AUTO_INCREMENT=1;  
SQL
                );
        /*
           $db->query(<<<SQL

           INSERT INTO {$db->prefix}incite_subject_concepts (`id`, `name`, `definition`) VALUES (NULL, 'Religion', 'This document refers to religious ideas, prayers, ministers, etc.'), (NULL, 'White Supremacy', 'This document discusses the belief that white people are racially superior.'), (NULL, 'Racial Equality', 'This document discusses the belief that people of all racial backgrounds are equal.'), (NULL, 'Gender Equality/Inequality', 'This document discusses the status of men and/or women.'), (NULL, 'Human Equality', 'This document refers to the idea that all people are equal.'), (NULL, 'Self Goverment', 'This document refers to democracy, the idea that people should have a say in their own governance.'), (NULL, 'America as a Global Beacon', 'This document celebrates America''s status as an example for the rest of the world to follow.'), (NULL, 'Celebration of Revolutionary Generation', 'This document glorifies the Americans who fought the revolution.'), (NULL, 'White Southerners', 'This document discusses whether or not white southerners should celebrate July 4.');

SQL
           );
         */
        $db->query(<<<SQL
                CREATE TABLE IF NOT EXISTS {$db->InciteTag} (
                    `id`                    int(11) NOT NULL AUTO_INCREMENT,
                    `item_id`               int(11) NOT NULL,
                    `tagged_trans_id`       int(11) NOT NULL,
                    `user_id`               int(11) NOT NULL,
                    `working_group_id`      int(11) NOT NULL,
                    `tag_text`              varchar(30) NOT NULL,
                    `timestamp_creation`    timestamp NOT NULL,
                    `category_id`           int(11) NOT NULL,
                    `description`           varchar(300) NOT NULL,
                    `type`                  int(8) NOT NULL,

                    PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci AUTO_INCREMENT=1;
SQL
                );

        $db->query(<<<SQL
                CREATE TABLE IF NOT EXISTS {$db->InciteTranscription} (
                    `id`                    int(11) NOT NULL AUTO_INCREMENT,
                    `item_id`               int(11) NOT NULL,
                    `user_id`               int(11) NOT NULL,
                    `working_group_id`      int(11) NOT NULL,
                    `transcribed_text`      varchar(200000) NOT NULL,
                    `summarized_text`       varchar(1000) NOT NULL,
                    `tone`                  varchar(50) NOT NULL,
                    `type`                  int(11) NOT NULL,
                    `timestamp_approval`    timestamp NULL DEFAULT NULL,
                    `timestamp_creation`    timestamp NOT NULL,

                    PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci AUTO_INCREMENT=1;
SQL
                );

        $db->query(<<<SQL
                CREATE TABLE IF NOT EXISTS {$db->prefix}incite_available_list (
                    `id`                    int(11) NOT NULL AUTO_INCREMENT,
                    `item_id`               int(11) NOT NULL,
                    `ready_tag`             int(8) NOT NULL,
                    `ready_connect`         int(8) NOT NULL,

                    PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci AUTO_INCREMENT=1;
SQL
                );



        $db->query(<<<SQL
                CREATE TABLE IF NOT EXISTS {$db->InciteTaggedTranscription} (
                    `id`                    int(11) NOT NULL AUTO_INCREMENT,
                    `item_id`               int(11) NOT NULL,
                    `transcription_id`      int(11) NOT NULL,
                    `user_id`               int(11) NOT NULL,
                    `working_group_id`      int(11) NOT NULL,
                    `tagged_transcription`  varchar(200000) NOT NULL,
                    `type`                  int(11) NOT NULL,
                    `timestamp_approval`    timestamp NULL DEFAULT NULL,
                    `timestamp_creation`    timestamp NOT NULL,

                    PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci AUTO_INCREMENT=1;
SQL
                );


        $db->query(<<<SQL
                CREATE TABLE IF NOT EXISTS {$db->prefix}incite_tag_question_conjunction (
                    `id`                    int(11) NOT NULL AUTO_INCREMENT,
                    `tagged_trans_id`       int(11) NOT NULL,
                    `question_id`           int(11) NOT NULL,
                    `answer`                varchar(500) NOT NULL,
                    `type`                  int(11) NOT NULL,

                    PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci AUTO_INCREMENT=1;
SQL
                );


        $db->query(<<<SQL
                CREATE TABLE IF NOT EXISTS {$db->prefix}incite_subject_explain (
                    `id`                   int(11) NOT NULL AUTO_INCREMENT,
                    `item_id`              int(11) NOT NULL,
                    `concept_id`           int(11) NOT NULL,   
                    `explanation`         varchar(1000) NOT NULL,

                    PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci AUTO_INCREMENT=1;
SQL
                );
        $db->query(<<<SQL
                CREATE TABLE IF NOT EXISTS {$db->prefix}incite_tag_question_index (
                    `id`                   int(11) NOT NULL,
                    `question`             varchar(1000) NOT NULL,

                    PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci AUTO_INCREMENT=1;
SQL
                );
        $db->query(<<<SQL
                CREATE TABLE IF NOT EXISTS {$db->InciteUser} (
                    `id`                      int(11) NOT NULL AUTO_INCREMENT,
                    `first_name`              varchar(15) NOT NULL,
                    `last_name`               varchar(15) NOT NULL,
                    `email`                   varchar(50) NOT NULL,
                    `password`                varchar(32) NOT NULL,
                    `privilege_level`         int(11) NOT NULL,
                    `experience_level`        int(11) NOT NULL,
                    `is_active`               int(11) NOT NULL,
                    `working_group_id`        int(11) NOT NULL,
                    `timestamp_creation`      timestamp NOT NULL,

                    PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci AUTO_INCREMENT=1;
SQL
                );
        $db->query(<<<SQL
                CREATE TABLE IF NOT EXISTS {$db->InciteTagsubcategory} (
                    `id`                int(10) unsigned NOT NULL AUTO_INCREMENT,
                    `name`              varchar(30) NOT NULL,
                    `category_id`       int(10) unsigned NOT NULL,
                    `user_id`        int(10) unsigned NOT NULL,
                    `timestamp_creation`         timestamp NOT NULL,

                    PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci AUTO_INCREMENT=1;
SQL
                );
        $db->query(<<<SQL
                INSERT INTO {$db->InciteTagsubcategory} (`id`, `name`, `category_id`, `timestamp_creation`) VALUES (NULL, 'Country', 1, NULL), (NULL, 'State', 1, NULL), (NULL, 'City', 1, NULL), (NULL, 'Town', 1, NULL), (NULL, 'Building', 1, NULL), (NULL, 'Oration', 2, NULL), (NULL, 'Banquet', 2, NULL), (NULL, 'Fireworks', 2, NULL), (NULL, 'Parade', 2, NULL), (NULL, 'Reading of Declaration', 2, NULL), (NULL, 'Prayer', 2, NULL), (NULL, 'Excursion', 2, NULL), (NULL, 'Music', 2, NULL), (NULL, 'White Southerners', 3, NULL), (NULL, 'White Northerners', 3, NULL), (NULL, 'African Americans', 3, NULL), (NULL, 'Women', 3, NULL), (NULL, 'Immigrants',3, NULL), (NULL, 'Whigs', 3, NULL), (NULL, 'Democrats', 3, NULL), (NULL, 'Republicans', 3, NULL), (NULL, 'Military', 4, NULL), (NULL, 'Social', 4, NULL), (NULL, 'Charity', 4, NULL), (NULL, 'Business', 4, NULL), (NULL, 'Religious', 4, NULL), (NULL, 'Political', 4, NULL);

SQL
                );

        $db->query(<<<SQL
                CREATE TABLE IF NOT EXISTS {$db->InciteTagcategory} (
                    `id`                    int(11) NOT NULL AUTO_INCREMENT,
                    `name`                  varchar(30) NOT NULL,
                    `user_id`               int(10) unsigned NOT NULL,
                    `timestamp_creation`    timestamp NOT NULL,

                    PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci AUTO_INCREMENT=1; 
SQL
                );
        $db->query(<<<SQL
                INSERT INTO {$db->InciteTagcategory} (`id`, `name`) VALUES (NULL, 'Location'), (NULL, 'Event'), (NULL, 'Person'), (NULL, 'Organization'), (NULL, 'Other');

SQL
                );
        $db->query(<<<SQL
                CREATE TABLE IF NOT EXISTS {$db->prefix}incite_trans_reason (
                    `id`            int(11) NOT NULL AUTO_INCREMENT,
                    `item_id`       int(11) NOT NULL,
                    `reason`        varchar(100000) ,

                    PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci AUTO_INCREMENT=1; 
SQL
                );
        $db->query(<<<SQL
                CREATE TABLE IF NOT EXISTS {$db->prefix}incite_users_map (
                    `non_guest_id`  int(10) unsigned NOT NULL,
                    `guest_id`      int(10) unsigned NOT NULL

                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci;  
SQL
                );
        $db->query(<<<SQL
                CREATE TABLE IF NOT EXISTS {$db->InciteTagsTagsubcategory} (
                    `id`                int(11) NOT NULL AUTO_INCREMENT,
                    `tag_id`            int(11) NOT NULL,
                    `subcategory_id`    int(11) NOT NULL,

                    PRIMARY KEY (`id`) 
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci;    

SQL
                );  

    }

    /**
     * remove the database tables
     */
    public function hookUninstall() {
        //clear all the options created during last installation
        delete_option('delete_sponsor1');
        delete_option('delete_sponsor2');
        delete_option('delete_sponsor3');
        delete_option('delete_sponsor4');
        delete_option('sponsorlink1');
        delete_option('sponsorlink2');
        delete_option('sponsorlink3');
        delete_option('sponsorlink4');
        delete_option('title');
        delete_option('intro');
        delete_option('logo_set');
        delete_option('active');
        delete_option('encoded_concept');
        delete_option('encoded_def');
        delete_option('twitter_timeline');
        delete_option('twitter_button');
        delete_option('fb');


        $db = get_db();
        $db->query(<<<SQL
                DROP TABLE IF EXISTS {$this->_db->prefix}incite_category_table
SQL
                );
        $db->query(<<<SQL
                DROP TABLE IF EXISTS {$this->_db->prefix}incite_documents
SQL
                );
        $db->query(<<<SQL
                DROP TABLE IF EXISTS {$db->InciteGroup}
SQL
                );
        $db->query(<<<SQL
                DROP TABLE IF EXISTS {$this->_db->prefix}incite_tag_answer_explain_list
SQL
                );
        $db->query(<<<SQL
                DROP TABLE IF EXISTS {$this->_db->prefix}incite_available_list
SQL
                );
        $db->query(<<<SQL
                DROP TABLE IF EXISTS {$this->_db->prefix}incite_subject_explain
SQL
                );
        $db->query(<<<SQL
                DROP TABLE IF EXISTS {$this->_db->prefix}incite_tag_question_conjunction
SQL
                );
        $db->query(<<<SQL
                DROP TABLE IF EXISTS {$this->_db->prefix}incite_tag_question_index
SQL
                );
        $db->query(<<<SQL
                DROP TABLE IF EXISTS {$this->_db->prefix}incite_trans_reason
SQL
                );
        $db->query(<<<SQL
                DROP TABLE IF EXISTS {$db->InciteGroupsUsers}
SQL
                );
        $db->query(<<<SQL
                DROP TABLE IF EXISTS {$db->InciteDiscussion}
SQL
                );
        $db->query(<<<SQL
                DROP TABLE IF EXISTS {$db->InciteItemsSubjects}
SQL
                );
        $db->query(<<<SQL
                DROP TABLE IF EXISTS {$db->InciteComment}
SQL
                );
        $db->query(<<<SQL
                DROP TABLE IF EXISTS {$db->InciteSubject}
SQL
                );
        $db->query(<<<SQL
                DROP TABLE IF EXISTS {$db->InciteTag}
SQL
                );
        $db->query(<<<SQL
                DROP TABLE IF EXISTS {$db->InciteTranscription}
SQL
                );
        $db->query(<<<SQL
                DROP TABLE IF EXISTS {$db->InciteTaggedTranscription}
SQL
                );
        $db->query(<<<SQL
                DROP TABLE IF EXISTS {$db->InciteUser}
SQL
                );

        $db->query(<<<SQL
                DROP TABLE IF EXISTS {$db->InciteItemsTags}
SQL
                );
        $db->query(<<<SQL
                DROP TABLE IF EXISTS {$db->InciteTagcategory}
SQL
                );
        $db->query(<<<SQL
                DROP TABLE IF EXISTS {$db->InciteTagsubcategory}
SQL
                );
        $db->query(<<<SQL
                DROP TABLE IF EXISTS {$db->InciteTagsTagsubcategory}
SQL
                );
        $db->query(<<<SQL
                DROP TABLE IF EXISTS {$db->InciteSubject}
SQL
                );
        $db->query(<<<SQL
                DROP TABLE IF EXISTS {$db->prefix}incite_users_map
SQL
                );
        $db->query(<<<SQL
                DROP TABLE IF EXISTS {$db->InciteItemsDiscussions}
SQL
                );
        $db->query(<<<SQL
                DROP TABLE IF EXISTS {$this->_db->prefix}incite_documents_replies_conjunction
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
        
        if (!isset($_POST['active']))
            set_option('active', "no");
        else
            set_option('active', $_POST['active']);
        //process the theme of connect tasks
        set_option('encoded_concept', $_POST['encoded_concept']);
        set_option('encoded_def', $_POST['encoded_def']);
        //add new themes
        if (isset($_POST['concept'])) {
            $concept = $_POST['concept'];
            $def = $_POST['def'];
            for ($i = 0; $i < count($concept); $i++) {
                $this->addConcept($concept[$i], $def[$i]);
            }
        }
        //delete the chosen themes
        if (isset($_POST['del_concept'])) {
            $removed_subject = $_POST['del_concept'];
            for ($i = 0; $i < count($removed_subject); $i++) {
                $this->removeSubject($removed_subject[$i]);
            }
        }
        set_option('title', trim($_POST['title']));
        set_option('intro', trim($_POST['intro']));
        set_option('twitter_timeline', trim($_POST['twitter_timeline']));
        set_option('twitter_button', trim($_POST['twitter_button']));
        set_option('fb', trim($_POST['fb']));
        //Change the logo
        if (!empty($_FILES["logo"]["name"])) {
            $this->changeImage("customized_logo.png", "logo");
            set_option('logo_set', "true");
        }
        //Change the sponsor
        set_option('delete_sponsor1', $_POST['delete_sponsor1']);
        if ($_POST['delete_sponsor1'] == "yes")
            delete_option('sponsorlink1');
        set_option('delete_sponsor2', $_POST['delete_sponsor2']);
        if ($_POST['delete_sponsor2'] == "yes")
            delete_option('sponsorlink2');
        set_option('delete_sponsor3', $_POST['delete_sponsor3']);
        if ($_POST['delete_sponsor3'] == "yes")
            delete_option('sponsorlink3');
        set_option('delete_sponsor4', $_POST['delete_sponsor4']);
        if ($_POST['delete_sponsor4'] == "yes")
            delete_option('sponsorlink4');
        if (!empty($_FILES["sponsor1"]["name"])) {
            $this->changeImage("customized_sponsors1.png", "sponsor1");
            if (isset($_POST['sponsorlink1']))
                set_option('sponsorlink1', $_POST['sponsorlink1']);
        }
        if (!empty($_FILES["sponsor2"]["name"])) {
            $this->changeImage("customized_sponsors2.png", "sponsor2");
            if (isset($_POST['sponsorlink2']))
                set_option('sponsorlink2', $_POST['sponsorlink2']);
        }
        if (!empty($_FILES["sponsor3"]["name"])) {
            $this->changeImage("customized_sponsors3.png", "sponsor3");
            if (isset($_POST['sponsorlink3']))
                set_option('sponsorlink3', $_POST['sponsorlink3']);
        }
        if (!empty($_FILES["sponsor4"]["name"])) {
            $this->changeImage("customized_sponsors4.png", "sponsor4");
            if (isset($_POST['sponsorlink4']))
                set_option('sponsorlink4', $_POST['sponsorlink4']);
        }
        //end of changing sponsors
    }

    public function changeImage($name, $tagName) {
        $uploadOK = 0;
        $target = dirname(__FILE__). "/views/shared/images/".$name;
        if(!empty($_FILES[$tagName]["name"])) {
            $uploadOK = 1;
        }

        if ($uploadOK == 1) {
            if (move_uploaded_file($_FILES[$tagName]["tmp_name"], $target)) {
                echo "The file has been uploaded.";
            } 
            else {
                echo "fail";

            }
        }
    }

    public function addConcept($concept, $def) {
        $subject = new InciteSubject;
        $subject->name = $concept;
        $subject->definition = $def;
        $subject->save();
    }

    public function removeSubject($concept) {
        $db = get_db();
        $table = $db->getTable('InciteSubject');
        $subject = $table->findSubjectByName($concept);
        $subject->delete();
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
