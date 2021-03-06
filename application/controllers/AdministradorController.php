<?php

class AdministradorController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
        $sessions = new Zend_Session_Namespace('expireAll');

        if ($sessions->usuari != "admin") {
            $this->redirect("Login/index");
        }
    }

    public function indexAction() {

        $alumnes = new Application_Model_DbTable_Alumnes();

        if ($this->getRequest()->isPost()) {

            //$dades = $this->getRequest()->getPost();

            $adapter = new Zend_File_Transfer_Adapter_Http();

            $adapter->setDestination(APPLICATION_PATH . "/public");

            if (!$adapter->receive()) {
                $messages = $adapter->getMessages();
            }

            $csv = array_map('str_getcsv', file(APPLICATION_PATH . '\public\alumnes.csv'));

            foreach ($csv as $alumne) {

                $data = array(
                    "dni" => $alumne[0],
                    "password" => $alumne[1],
                    "nom" => $alumne[2],
                    "correu" => $alumne[3],
                    "cumpleanys" => $alumne[4],
                    "codi_seguretat" => md5(rand()),
                );


                try {
                    $alumnes->insert($data);
                } catch (Exception $ex) {
                    $where = $alumnes->getAdapter()->quoteInto('dni = ?', $alumne[0]);
                    $alumnes->update($data, $where);
                }
            }
        }

        $this->_helper->layout()->usuari = "Administrador";
        $this->view->alumnes = $alumnes->fetchAll();
    }

}
