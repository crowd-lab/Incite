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
        echo "hehe";
        $db = DB_Connect::connectDB();
        $stmt = $db->prepare("SELECT * FROM `omeka_incite_documents_tags_conjunction`");
        $stmt->bind_result($id, $item, $tag);
        $stmt->execute();
        while ($stmt->fetch()) {
            print_r("INSERT INTO omeka_incite_documents_tags_conjunction VALUES (".$id .",".$item.",".$tag.", 2)");
            echo "\n";
        }
        $stmt->close();
        $db->close();
    }


}
