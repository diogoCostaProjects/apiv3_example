<?php

require_once MODELS . '/Moip/order.php';

class MoipController extends MoipPayment {

    public function __construct() {
        $this->model = New MoipPayment();
    }

    public function order() {
      $this->model->Order($this->model);
    }

    public function newpreferencia() {
      $this->model->PreferenciaNew($this->model);
    }
    public function pesquisapreferencia() {
      $this->model->PreferenciaPesquisa($this->model);
    }
    public function removepreferencia() {
      $this->model->PreferenciaRemove($this->model);
    }

    public function notificacao() {
      $this->model->Notificacao($this->model);
    }
    public function pesquisapagamento() {
      $this->model->PaymentPesquisa($this->model);
    }

    public function update() {
      $this->model->updateStatusMoip($this->model);
    }

}
