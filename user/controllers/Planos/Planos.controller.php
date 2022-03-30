<?php

require_once MODELS . '/Planos/Planos.class.php';
require_once MODELS . '/Secure/Secure.class.php';


class PlanosController {

    public function __construct() {

        $request = file_get_contents('php://input');
        $this->input = json_decode($request);
        $this->secure = new Secure();
        
        $this->req = $_REQUEST;
        $this->data_atual = date('Y-m-d H:i:s');
        $this->dia_atual = date('Y-m-d');
    }

   
    public function listall() {
               
        $planos =  new Planos();

        $lista_planos = $planos->listAll();

        jsonReturn($lista_planos);
    }

    public function planovigente($id_user) {
        
        $this->secure->tokens_secure($this->input->token);

        $planos =  new Planos();

        $plano_vigente = $planos->planoVigente($id_user);

        jsonReturn(array($plano_vigente));
    }

    public function inativa_vencidos(){ // USADO EM WEBHOOK PARA DESATIVAR PLANOS VENCIDOS
        
        $planos =  new Planos();

        $plano_vigente = $planos->inativa($this->dia_atual);

        jsonReturn(array($plano_vigente));
    }
   
}

