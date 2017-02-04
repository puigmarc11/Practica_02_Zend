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

    public function enviarCorreu() {

        $config = array(
            'ssl' => 'tls',
            'port' => 587,
            'auth' => 'login',
            'username' => 'w2.mpuig@gmail.com',
            'password' => 'infomila.info',
        );
        $transport = new Zend_Mail_Transport_Smtp('smtp.gmail.com', $config);
        Zend_Mail::setDefaultTransport($transport);


        $mail = new Zend_Mail();
        $mail->addTo('marcpuig123@gmail.com', 'Marc')
                ->setFrom('w2.mpuig@gmail.com', 'Myself')
                ->setSubject('My Subject')
                ->setBodyHtml('Email Body')
                ->send();
    }

}
