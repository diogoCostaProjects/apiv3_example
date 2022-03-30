<?php

require_once MODELS . '/Gcm/Gcm.class.php';

class GcmController extends Gcm {

    public function __construct() {
        $this->model = New Gcm();
    }

    public function lembreteconsulta() {
        $this->view = $this->model->lembrete_consulta_android();
    }
    public function lembreteconsultaios() {
        $this->view = $this->model->lembrete_consulta_ios();
    }
    
    
}
