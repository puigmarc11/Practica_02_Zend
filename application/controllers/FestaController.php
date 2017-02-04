<?php

class FestaController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
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

        $organitzador = "77123374G";
        $dbAdapter = Zend_Db_Table::getDefaultAdapter();

        $sql = $dbAdapter->select()
                ->from("festa as f", array('lloc', 'data','id'))
                ->join('organitzadors as o', 'f.id = o.id_festa', array("id_organitzador", "id_festa"))
                ->where('o.id_organitzador = ?', $organitzador);

        $result = $dbAdapter->fetchAll($sql);

        $org = new Application_Model_DbTable_Organitzadors();

        $festes = array();

        foreach ($result as $r) {

            $festes[] = array(
                $r,
                $org->fetchAll(array("id_festa = ?" => $r["id_festa"])),
            );
        }


        $this->view->festesOrganitzador = $festes;
    }

    public function crearfestaAction() {

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
                    foreach ($dades["organitzadors"] as $o) {
                        $org = array(
                            "id_festa" => $id,
                            "id_organitzador" => $o,
                        );

                        $organitzadors->insert($org);
                    }

                    //asignar participants
                    foreach ($alumnes->fetchAll() as $alumne) {
                        $part = array(
                            "id_festa" => $id,
                            "id_participant" => $alumne->dni,
                            "acceptat" => 0,
                        );

                        $participant->insert($part);
                    }
                } else {
                    $resultat = 0;
                }
            } else {
                $resultat = 0;
            }
        }

        $this->view->alumnes = $alumnes->fetchAll();
        $this->view->resultat = $resultat;
    }

    public function llistarparticipantsAction() {

        $id = $this->getRequest()->getParam('idFesta');
        $this->view->festa = $id;

        $dbAdapter = Zend_Db_Table::getDefaultAdapter();

        $sql = $dbAdapter->select()
                ->from("participant as p", array('acceptat'))
                ->join('alumnes as a', 'p.id_participant = a.dni', array("dni","nom","correu"))
                ->where('p.id_festa = ?', $id);

        $result = $dbAdapter->fetchAll($sql);
        $this->view->participant = $result;
                
    }

}
