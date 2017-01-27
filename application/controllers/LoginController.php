<?php

class LoginController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {
        
        if ($this->getRequest()->isPost()) {
          
            var_dump("Connexio");
            
            $dadesLogin = $this->getRequest()->getPost();
            
            var_dump($dadesLogin["nom"]);
            var_dump($dadesLogin["password"]);
            
          
        } else {
            echo "this is not the post request";
        }
        
    }

}
