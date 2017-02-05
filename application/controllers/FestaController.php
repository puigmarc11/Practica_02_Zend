<?php

class FestaController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
        $sessions = new Zend_Session_Namespace('expireAll');

        if (!isset($sessions->usuari)) {
            $this->redirect("Login/index");
        }
    }

    public function indexAction() {
        /*
          $sql =  $db ->select()
          ->from('messages as mes',array('fromid','is_read','id as mesid','message','max(date) as date'))
          ->join('users as user','user.id = mes.fromid',array('username','id as uid'))
          ->where('mes.toid = ? ', $toid)
          ->where('mes.fromid = ?', $fromid)
          ->group('mes.fromid')
          ->order('date DESC');

         */

        $sessions = new Zend_Session_Namespace('expireAll');

        $organitzador = $sessions->usuari;
        $dbAdapter = Zend_Db_Table::getDefaultAdapter();

        $sql = $dbAdapter->select()
                ->from("festa as f", array('lloc', 'data', 'id'))
                ->join('organitzadors as o', 'f.id = o.id_festa', array("id_organitzador", "id_festa"))
                ->where('o.id_organitzador = ?', $organitzador);




        $result = $dbAdapter->fetchAll($sql);

        //$org = new Application_Model_DbTable_Organitzadors();

        $festes = array();

        foreach ($result as $r) {

            $sql2 = $dbAdapter->select()
                    ->from("alumnes as a", array('dni', 'nom'))
                    ->join('organitzadors as o', 'a.dni = o.id_organitzador', array('id_organitzador'))
                    ->where('o.id_festa = ?', $r["id_festa"]);

            $festes[] = array(
                $r,
                $dbAdapter->fetchAll($sql2),
                    //$org->fetchAll(array("id_festa = ?" => $r["id_festa"])),
            );
        }

        $this->view->festesOrganitzador = $festes;

        $dataAvui = date("Y-m-d");
        $dni = $sessions->usuari;

        $sql = $dbAdapter->select()
                ->from("festa as f", array('lloc', 'data', 'id'))
                //->join('organitzadors as o', 'f.id = o.id_festa', array("id_organitzador", "id_festa"))
                ->join('participant as p', 'f.id = p.id_festa', array("acceptat"))
                ->where("? NOT IN (select id_organitzador from organitzadors where id_festa = f.id)", $dni)
                ->where("f.data >= ?", $dataAvui)
                ->where("p.id_participant = ?", $dni);

        $fp = $dbAdapter->fetchAll($sql);

        $this->view->festesParticipant = $fp;
    }

    public function crearfestaAction() {

        $sessions = new Zend_Session_Namespace('expireAll');

        $resultat = -1;
        $alumnes = new Application_Model_DbTable_Alumnes();

        if ($this->getRequest()->isPost()) {

            $dades = $this->getRequest()->getPost();

            $dataFesta = new DateTime($dades["data"]);
            $dataAvui = new DateTime("now");

            if ($dataFesta > $dataAvui && $dades["lloc"] != "") {

                $festa = new Application_Model_DbTable_Festa();
                $organitzadors = new Application_Model_DbTable_Organitzadors();
                $participant = new Application_Model_DbTable_Participant();

                $data = array(
                    "lloc" => $dades["lloc"],
                    "data" => $dades["data"],
                );

                //Crear la festa
                $id = $festa->insert($data);


                if ($id != null) {

                    $resultat = 1;

                    //Assignar organitzadors
                    if (isset($dades["organitzadors"])) {

                        foreach ($dades["organitzadors"] as $o) {
                            $org = array(
                                "id_festa" => $id,
                                "id_organitzador" => $o,
                            );

                            $organitzadors->insert($org);
                        }
                    }

                    //Organitzador LOGAT
                    $org = array(
                        "id_festa" => $id,
                        "id_organitzador" => $sessions->usuari,
                    );

                    $organitzadors->insert($org);

                    //asignar participants
                    foreach ($alumnes->fetchAll() as $alumne) {

                        $part = array(
                            "id_festa" => $id,
                            "id_participant" => $alumne->dni,
                            "acceptat" => 0,
                        );

                        $participant->insert($part);

                        if (isset($dades["notificar"]) && $alumne->correu != "") {
                            $this->enviarCorreu($alumne->correu);
                        }
                    }

                    //acceptar organitzadors
                    if (isset($dades["organitzadors"])) {
                        foreach ($dades["organitzadors"] as $o) {

                            $where['id_festa = ?'] = $id;
                            $where['id_participant = ?'] = $o;

                            $update = array(
                                "acceptat" => "1",
                            );

                            $participant->update($update, $where);
                        }
                    }
                    
                    $where['id_festa = ?'] = $id;
                    $where['id_participant = ?'] = $sessions->usuari;

                    $update = array(
                        "acceptat" => "1",
                    );

                    $participant->update($update, $where);
                    
                } else {
                    $resultat = 0;
                }
            } else {
                $resultat = 0;
            }
        }

        $this->view->alumnes = $alumnes->fetchAll(array("dni <> ?" => $sessions->usuari));
        $this->view->resultat = $resultat;
    }

    public function llistarparticipantsAction() {

        $id = $this->getRequest()->getParam('idFesta');
        $this->view->festa = $id;

        $dbAdapter = Zend_Db_Table::getDefaultAdapter();

        $sql = $dbAdapter->select()
                ->from("participant as p", array('acceptat'))
                ->join('alumnes as a', 'p.id_participant = a.dni', array("dni", "nom", "correu"))
                ->where('p.id_festa = ?', $id);

        $result = $dbAdapter->fetchAll($sql);
        $this->view->participant = $result;
    }

    public function acceptarAction() {

        $sessions = new Zend_Session_Namespace('expireAll');

        $id = $this->getRequest()->getParam('id');
        $estat = $this->getRequest()->getParam('estat');

        var_dump($estat);


        $part = new Application_Model_DbTable_Participant();

        $where['id_festa = ?'] = $id;
        $where['id_participant = ?'] = $sessions->usuari;

        $update = array(
            "acceptat" => $estat,
        );

        $part->update($update, $where);

        $this->redirect("festa/index");
    }

    public function enviarCorreu($to) {

        $subject = "Invitacio a la festa d'aniversari";
        $body = "Has estat invitat a la festa d'aniversari!!!!<br>"
                . "El seguent enllac serveix per confirmar l'esdeveniment.<br>"
                . "<a href=''>Confirmar festa aniversari</a>";

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
        $mail->addTo($to, '')
                ->setFrom('w2.mpuig@gmail.com', 'Organitzador festes')
                ->setSubject($subject)
                ->setBodyHtml($body)
                ->send();
    }

}
