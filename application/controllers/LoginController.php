<?php

class LoginController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {

        if ($this->getRequest()->isPost()) {

            $dadesLogin = $this->getRequest()->getPost();

            $nom = $dadesLogin["nom"];
            $pass = $dadesLogin["password"];

            if ($nom == "admin" && $pass == "admin") {
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
                $this->redirect("Festa/index");
                exit();
            }
        }
    }

}
