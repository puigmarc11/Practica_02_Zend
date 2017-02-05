<?php

class LoginController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {

        
        //$this->enviarCorreu();
        
        $sessions = new Zend_Session_Namespace('expireAll');
        unset($sessions->usuari);

        if ($this->getRequest()->isPost()) {

            $dadesLogin = $this->getRequest()->getPost();

            $nom = $dadesLogin["nom"];
            $pass = $dadesLogin["password"];

            if ($nom == "admin" && $pass == "admin") {
                $sessions->usuari = 'Admin';
                $sessions->setExpirationSeconds(3600, 'usuari');
                $this->redirect("Administrador/index");
                exit();
            }

            $alumnes = new Application_Model_DbTable_Alumnes();
            $d = $alumnes->find($nom);


            $row = $alumnes->fetchRow(
                    $alumnes->select()
                            ->where('dni = ?', $nom)
                            ->where('password = ?', $pass)
            );

            if ($row) {
                $sessions->usuari = $nom;
                $sessions->setExpirationSeconds(3600, 'usuari');
                $this->redirect("Festa/index");
                exit();
            }
        }
    }

}
