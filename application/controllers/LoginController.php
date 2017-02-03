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
            
            if($nom == "admin" && $pass == "admin"){
                $this->redirect("Administrador/index");
            }
            
          
        } else {
            echo "this is not the post request";
        }
        
    }

}
