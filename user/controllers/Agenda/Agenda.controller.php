<?php

require_once MODELS . '/Agenda/Agenda.class.php';
require_once MODELS . '/Planos/Planos.class.php';
require_once MODELS . '/Usuarios/Enderecos.class.php';
require_once MODELS . '/Secure/Secure.class.php';
require_once HELPERS . '/UsuariosHelper.class.php';
require_once HELPERS . '/AgendaHelper.class.php';

class AgendaController {

    public function __construct() {

        $request = file_get_contents('php://input');
        $this->input = json_decode($request);
        $this->secure = new Secure();

        $this->req = $_REQUEST;
        $this->data_atual = date('Y-m-d H:i:s');
        $this->dia_atual = date('Y-m-d');
    }


    public function save() {

        $this->secure->tokens_secure($this->input->token);
        $agenda =  new Agenda();

        $result = $agenda->save($this->input->id_user, $this->input->day, $this->input->horario_in, $this->input->horario_out, moneySQL($this->input->valor), $status=1);

        jsonReturn(array($result));
    }


    public function listall() {

        $this->secure->tokens_secure($this->input->token);

        $agenda = new Agenda();

        $agenda_lista = $agenda->listAll($this->req['id_user'], $this->req['day']);

        jsonReturn($agenda_lista);
    }

    public function listall_vendedor() {

        $this->secure->tokens_secure($this->input->token);

        $agenda = new Agenda();

        $diasemana_numero = date('w', strtotime(dataUS($this->req['data'])));
        $diasemana_numero ++;

        $agenda_lista = $agenda->listAllVendedor($this->req['id_user'], $diasemana_numero, dataUS($this->req['data']));

        jsonReturn($agenda_lista);
    }


    public function dias_semana(){

        $helper = new AgendaHelper();
        $lista_dias = $helper->dias($this->input->id_clinica);

        jsonReturn($lista_dias);
    }

    public function update() {

        $this->secure->tokens_secure($this->input->token);

        $agenda =  new Agenda();

        $result = $agenda->update($this->input->id, $this->input->horario_in, $this->input->horario_out, moneySQL($this->input->valor));

        jsonReturn(array($result));
    }

    public function updatestatus() {

        $this->secure->tokens_secure($this->input->token);

        $agenda =  new Agenda();

        $result = $agenda->updateStatus($this->input->day, $this->input->status, $this->input->id_clinica);

        jsonReturn(array($result));
    }

    public function deletehorario() {

        $this->secure->tokens_secure($this->input->token);

        $agenda =  new Agenda();

        $result = $agenda->delete($this->input->id_horario);

        jsonReturn(array($result));
    }
}
