<?php
/**
 * Incite 
 *
 */

/**
 * Plugin "Incite"
 *
 * @package Incite 
 */
class Incite_DocumentsController extends Omeka_Controller_AbstractActionController
{
    public function init()
    {
                        //echo '<div style="color:red">Documents Controller Initialized! This is probably a good place to put the header such as <a href="./discover">discover</a> - <a href="transcribe">transcribe</a> - <a href="tag">tag</a> - <a href="connect">connect</a> - <a href="discuss">discuss</a></div>';
        require_once("Incite_Transcription_Table.php");
        require_once("Incite_Tag_Table.php");
        require_once("Incite_Subject_Concept_Table.php");
        if (!DB_Connect::isLoggedIn())
        {
            $GLOBALS['USERID'] = -1; //user is anonymous
        }
        else
        {
            $userArray = $_SESSION['USER_DATA'];
            $GLOBALS['USERID'] = $userArray[0]; 
        }
        
    }

    public function indexAction()
    {
        echo '<div style="color:black">Welcome to Homepage (discover)!</div>';
		$this->_helper->db->setDefaultModelName('Item');
		$record = $this->_helper->db->findById(225);
		$this->view->assign(array('Item' => $record));
		//$this->discoverAction();
    }

    public function discoverAction()
    {

        echo '<div style="color:red">Welcome to Discover!</div>';
		//print_r($this->_getAllParams());
		if ($this->_hasParam('id'))
			echo 'discovering document with id: '.$this->_getParam('id');
		else
			echo 'general discovering';
    }

	public function showAction()
    {

		$this->_helper->db->setDefaultModelName('Item');
		if ($this->_hasParam('id')) {
			$record = $this->_helper->db->find($this->_getParam('id'));
			if ($record != null) {
				$this->view->assign(array('Item' => $record));
			} else {
				//no such item
				$this->_forward('index');
			}
		} else {
			//default view without id
			//$this->_forward('discover');
		}
    }
	
    public function transcribeAction()
    {

		if ($this->getRequest()->isPost()) {
			//save transcription and summary to database
                    if ($this->_hasParam('id'))
                    {
                        createTranscription($this->_getParam('id'),  $GLOBALS['USERID'], $_POST['transcription'], $_POST['summary']);
                    }
                    
		} 

		$this->_helper->db->setDefaultModelName('Item');
		if ($this->_hasParam('id')) {
			$record = $this->_helper->db->find($this->_getParam('id'));
			if ($record != null) {
				if ($record->getFile() == null) {
					//no image to transcribe
					echo 'no image';
				}
				$this->_helper->viewRenderer('transcribeid');
				$this->view->transcription = $record;
			} else {
				//no such document
				echo 'no such document';
			}
		} else {
			//default view without id
			//$this->_forward('discover');
			//$records = get_records('Item', array('type' => 21), 20);  //21: Image
			$records[] = $this->_helper->db->find(15);
			$records[] = $this->_helper->db->find(18);
			$records[] = $this->_helper->db->find(20);
			$records[] = $this->_helper->db->find(77);

			//check if there is really exacit one image file for each item
			if ($records != null) {
				$this->view->assign(array('Transcriptions' => $records));
			} else {
				//no need to transcribe
			}
			
		}
    }

    public function tagAction()
    {
        // echo '<div style="color:blue">Welcome to Tag!</div>';
		if ($this->getRequest()->isPost()) {
			//save a tag to database
                    if ($this->_hasParam('id'))
                    {
                        //createTranscription($this->_getParam('id'), -1, $_POST['transcription'], $_POST['summary']);
						//data from post: $_POST['tag_text'], $_POST['tag_category'], $_POST['tag_description']
						//ready to insert tag into database
                        //createTag($userID, $tag_text, $category_name, $description, $documentID)
                        createTag($GLOBALS['USERID'], $_POST['tag_text'], $_POST['tag_category'], $_POST['tag_description'], $this->_getParam('id'));
                    }
                    
		} 

		$this->_helper->db->setDefaultModelName('Item');
		if ($this->_hasParam('id')) {
			$record = $this->_helper->db->find($this->_getParam('id'));
			if ($record != null) {
				if ($record->getFile() == null) {
					//no image to transcribe
					echo 'no image';
				}
				$transcription = getIsAnyTranscriptionApproved($this->_getParam('id'));
				$this->view->transcription = "No transcription";
				if ($transcription != null) {
					$this->view->transcription = getTranscriptionText($transcription[0]);
				} else {
				}
				$this->_helper->viewRenderer('tagid');
				$this->view->tag = $record;
			} else {
				//no such document
				echo 'no such document';
			}
		} else {
			//default view without id
			//$this->_forward('discover');
			//$records = get_records('Item', array('type' => 21), 20);  //21: Image
			$records[] = $this->_helper->db->find(15);
			$records[] = $this->_helper->db->find(18);
			$records[] = $this->_helper->db->find(22);
			$records[] = $this->_helper->db->find(24);

			//check if there is really exacit one image file for each item
			if ($records != null) {
				$this->view->assign(array('Tags' => $records));
			} else {
				//no need to transcribe
			}
			
		}
    }

    public function connectAction()
    {
        //echo '<div style="color:green">Welcome to Connect!</div>';
		$this->_helper->db->setDefaultModelName('Item');
                $subjectConceptArray = getAllSubjectConcepts();
                $randomSubjectInt = rand(0, sizeof($subjectConceptArray) - 1);
                $subjectName = getSubjectConceptOnId($randomSubjectInt);
                $subjectDef = getDefinition($subjectName[0]);
		//Choosing a subject to test with some fake data to test view
		$this->view->subject = $subjectName[0];
		$this->view->subject_definition = $subjectDef;
		$this->view->entities = array('liberty', 'independence');
		$this->view->related_documents = array($this->_helper->db->find(15), $this->_helper->db->find(77));

		if ($this->getRequest()->isPost()) {
			//save a connection to database
                    if ($this->_hasParam('id'))
                    {
                        //createTranscription($this->_getParam('id'), -1, $_POST['transcription'], $_POST['summary']);
						//data from post: $_POST['connection'] //either true or false
						//ready to connect subject to a document
                        $userID = -1;
                        addConceptToDocument($randomSubjectInt, $this->_getParam('id'), $userID);
                    }
                    
		} 

		if ($this->_hasParam('id')) {
			$record = $this->_helper->db->find($this->_getParam('id'));
			if ($record != null) {
				if ($record->getFile() == null) {
					//no image to transcribe
					echo 'no image';
				}
				$transcription = getIsAnyTranscriptionApproved($this->_getParam('id'));
				$this->view->transcription = "No transcription";
				if ($transcription != null) {
					$this->view->transcription = getTranscriptionText($transcription[0]);
				} else {
					
				}
				$this->_helper->viewRenderer('connectid');
				$this->view->connection = $record;
			} else {
				//no such document
				echo 'no such document';
			}
		} else {
			//default view without id
			//$this->_forward('discover');
			//$records = get_records('Item', array('type' => 21), 20);  //21: Image
			$records[] = $this->_helper->db->find(15);
			$records[] = $this->_helper->db->find(18);
			$records[] = $this->_helper->db->find(77);

			//check if there is really exacit one image file for each item
			if ($records != null) {
				$this->view->assign(array('Connections' => $records));
			} else {
				//no need to transcribe
			}
			
		}
    }

    public function discussAction()
    {
                        echo '<div style="color:black">Welcome to Discuss!</div>';
    }
}
