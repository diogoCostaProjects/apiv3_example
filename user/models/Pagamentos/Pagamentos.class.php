<?php

// require_once MODELS . '/Conexao/Conexao.class.php';
require_once MODELS . '/Secure/Secure.class.php';
require_once MODELS . '/ResizeFiles/ResizeFiles.class.php';
require_once MODELS . '/Emails/Emails.class.php';
require_once MODELS . '/Estados/Estados.class.php';
require_once MODELS . '/Conexao/Conexao.class.php';

class Pagamentos extends Conexao {


    public function __construct() {
        $this->Conecta();
        $this->data_atual = date('Y-m-d H:i:s');
        $this->tabela = "app_planos_pagamentos";
    }

    public function save($id_plano, $id_user, $type, $data, $token, $valor, $parcelas, $link_boleto, $status) {

        $sql_cadastro = $this->mysqli->prepare("
        INSERT INTO `app_planos_pagamentos`(`app_planos_id`, `app_users_id`, `type`, `data`, `token`, `valor`, `parcelas`, `url`, `status`)
         VALUES ('$id_plano', '$id_user', '$type', '$data', '$token', '$valor', '$parcelas', '$link_boleto', '$status')");
        $sql_cadastro->execute();

        $this->id_cadastro = $sql_cadastro->insert_id;

        $Param = [
                  "status"=>"01",
                  "msg"=>"Pagamento de plano realizado, aguarde enquanto validamos seus dados.",
                  "id"=>$this->id_cadastro,
                 ];

        return $Param;
    }


    public function listAll($id_user){
              
        $sql = $this->mysqli->prepare("
        SELECT pg.id, pg.data, pg.valor, pg.parcelas, pg.url, p.nome, pg.status, IF(pg.type=1,'CARTÃO', 'BOLETO')
        FROM app_planos_pagamentos as pg 
        INNER JOIN app_planos as p on p.id = pg.app_planos_id
        WHERE pg.app_users_id='$id_user'
        ORDER BY pg.id DESC
        ");
        $sql->execute();
        $sql->bind_result($this->id_pgto, $this->data_pgto, $this->valor_pgto, $this->parcelas, $this->url_boleto, $this->plano, $this->status, $this->tipo);
        $sql->store_result();
        $rows = $sql->num_rows();

        if($rows == 0) {
            $param['rows'] = $rows;
            $lista[] = $param;
        }
        else {
            while($row = $sql->fetch()){
                $param['id_pgto'] = $this->id_pgto;
                $param['data_pgto'] = dataBR($this->data_pgto);
                $param['valor_pgto'] = moneyView($this->valor_pgto);
                $param['parcelas'] = $this->parcelas;
                $param['url_boleto'] = $this->url_boleto;
                $param['plano'] = $this->plano;
                $param['tipo'] = $this->tipo;
                $param['status'] = MoipBR($this->status);
                $lista[] = $param;
            }
        }
        return $lista;
        
    }


}
