<?php

// require_once MODELS . '/Conexao/Conexao.class.php';
require_once MODELS . '/Secure/Secure.class.php';
require_once MODELS . '/ResizeFiles/ResizeFiles.class.php';
require_once MODELS . '/Emails/Emails.class.php';
require_once MODELS . '/Estados/Estados.class.php';
require_once MODELS . '/Conexao/Conexao.class.php';

class Planos extends Conexao {

   
    public function __construct() {
        $this->Conecta();
        $this->data_atual = date('Y-m-d H:i:s');
        $this->tabela = "app_users_planos";
    }
             
    public function save($id_usuario, $id_plano, $validade, $ativo) {
                      
        $sql_cadastro = $this->mysqli->prepare("
        INSERT INTO `$this->tabela`
        (app_users_id, app_planos_id, data_validade, ativo) VALUES ('$id_usuario', '$id_plano', '$validade', '$ativo')");
 
        $sql_cadastro->execute();
    }

    public function update($id_usuario, $id_plano, $validade, $ativo) { // atualizar na notificação do pagamento caso seja aprovado
                      
        $sql_cadastro = $this->mysqli->prepare("UPDATE app_users_planos SET app_planos_id='$id_plano', data_validade='$validade', ativo='$ativo' WHERE app_users_id='$id_usuario'");
        $sql_cadastro->execute();
    }

    public function listID($id){
               
        $sql = $this->mysqli->prepare("SELECT id, nome, free_dias, valor, descricao, status FROM app_planos WHERE id='$id'");
        $sql->execute();
        $sql->bind_result($this->id, $this->nome, $this->free_dias, $this->valor,  $this->descricao, $this->status);
        $sql->fetch();
              
        $Param['id'] = $this->id;
        $Param['nome'] = ucwords($this->nome);
        $Param['free_dias'] = $this->free_dias;
        $Param['valor'] = moneyView($this->valor);
        $Param['descricao'] = $this->descricao;

        return $Param;
    }


    public function inativa($data){
        
        $sql_cadastro = $this->mysqli->prepare("UPDATE app_users_planos SET ativo='2' WHERE data_validade <'$data'");
        $sql_cadastro->execute();
    }


    public function planoVigente($id_user){
               
        $sql = $this->mysqli->prepare("
        SELECT p.id, p.nome, pu.data_validade, IF(pu.ativo=1,'ATIVO','INATIVO')  
        FROM app_users_planos as pu 
        INNER JOIN app_planos as p on p.id = pu.app_planos_id
        WHERE pu.app_users_id='$id_user'"
        );
        $sql->execute();
        $sql->bind_result($this->id_plano, $this->nome_plano, $this->validade, $this->status);
        $sql->fetch();
              
        $Param['id_plano'] = $this->id_plano;
        $Param['nome_plano'] = ucwords($this->nome_plano);
        $Param['data_validade'] = dataBR($this->validade);
        $Param['status'] = $this->status;

        return $Param;
    }

    public function listAll(){
               
        $sql = $this->mysqli->prepare("SELECT id, nome, free_dias, valor, descricao FROM app_planos WHERE status='1' and id <> 1 ORDER BY nome");
        $sql->execute();
        $sql->bind_result($this->id, $this->nome, $this->free_dias, $this->valor,  $this->descricao);
        $sql->store_result();

        while($row =  $sql->fetch()) {
            $Param['id'] = $this->id;
            $Param['nome'] = ucwords($this->nome);
            $Param['free_dias'] = $this->free_dias;
            $Param['valor'] = moneyView($this->valor);
            $Param['descricao'] = $this->descricao;

            $lista[] = $Param;
        }
        
        return $lista;
    }
}
