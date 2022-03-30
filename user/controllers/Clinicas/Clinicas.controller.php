<?php

require_once MODELS . '/Clinicas/Clinicas.class.php';
require_once MODELS . '/Secure/Secure.class.php';


class ClinicasController {

    public function __construct() {

        $request = file_get_contents('php://input');
        $this->input = json_decode($request);
        $this->secure = new Secure();

        $this->req = $_REQUEST;
        $this->data_atual = date('Y-m-d H:i:s');
        $this->dia_atual = date('Y-m-d');
    }


    public function clinicas_cidade() {

        $this->secure->tokens_secure($this->input->token);

        $clinicas =  new Clinicas();

        $lista_clin = $clinicas->clinicasByCidade($this->req['categoria'], $this->req['subcategoria'], $this->req['cidade']);

        jsonReturn($lista_clin);
    }

    public function clinicas_latitude() {

        $this->secure->tokens_secure($this->input->token);

        $clinicas =  new Clinicas();
        
        $lista_clin = $clinicas->clinicasDistancia($this->req['categoria'], $this->req['subcategoria'], $this->req['lat'], $this->req['long']);

        jsonReturn($lista_clin);
    }
}
