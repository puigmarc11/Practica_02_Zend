<?php

class AdministradorController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {

        if ($this->getRequest()->isPost()) {

            $dades = $this->getRequest()->getPost();

            $adapter = new Zend_File_Transfer_Adapter_Http();

            $adapter->setDestination('C:\temp');

            if (!$adapter->receive()) {
                $messages = $adapter->getMessages();
                echo implode("\n", $messages);
            }

            $csv = array_map('str_getcsv', file('C:\temp\alumnes.csv'));
            var_dump($csv);
            
            $this->view->alumnes = $csv;

            /*
              $target_dir = "uploads/";
              $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
              $uploadOk = 1;
              $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
              // Check if image file is a actual image or fake image
              if (isset($_POST["submit"])) {
              $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
              if ($check !== false) {
              echo "File is an image - " . $check["mime"] . ".";
              $uploadOk = 1;
              } else {
              echo "File is not an image.";
              $uploadOk = 0;
              }
              }
             * */
        } else {
            echo "this is not the post request";
        }
    }

}
