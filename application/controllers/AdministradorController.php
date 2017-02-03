<?php

class AdministradorController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {

        $alumnes = new Application_Model_DbTable_Alumnes();

        if ($this->getRequest()->isPost()) {

            //$dades = $this->getRequest()->getPost();

            $adapter = new Zend_File_Transfer_Adapter_Http();

            $adapter->setDestination('C:\temp');

            if (!$adapter->receive()) {
                $messages = $adapter->getMessages();
                //echo implode("\n", $messages);
            }

            $csv = array_map('str_getcsv', file('C:\temp\alumnes.csv'));



            foreach ($csv as $alumne) {

                $data = array(
                    "dni" => $alumne[0],
                    "password" => $alumne[1],
                    "nom" => $alumne[2],
                    "correu" => $alumne[3],
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
