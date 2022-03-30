<?php

// require_once MODELS . '/Conexao/Conexao.class.php';
require_once MODELS . '/Secure/Secure.class.php';
require_once MODELS . '/Estados/Estados.class.php';
require_once MODELS . '/Conexao/Conexao.class.php';


class Configuracoes extends Conexao {
   
    
    public function __construct() {
        $this->Conecta();
        $this->data_atual = date('Y-m-d H:i:s');
        $this->tabela = "app_config";
    }
              

    public function listConfig() {
                        
        $sql = $this->mysqli->prepare("SELECT cep, estado, cidade, endereco, numero, complemento, latitude, longitude FROM `$this->tabela`");
        $sql->execute();
        $sql->bind_result($this->cep, $this->estado, $this->cidade, $this->endereco, $this->numero, $this->complemento, $this->latitude, $this->longitude);
        $sql->fetch();
              
        $Param['endereco_completo'] = $this->endereco.' '.$this->numero.', '.$cidade.' '.$this->estado;
        $Param['latitude'] = $this->latitude;
        $Param['longitude'] = $this->longitude;
               
        return $Param;
    }

    // public function find($query) {
                        
    //     $sql = $this->mysqli->prepare($query);
    //     $sql->execute();
    //     $sql->bind_result($this->id, $this->nome, $this->valor, $this->taxa_servico);
    //     $sql->store_result();
    //     $rows = $sql->num_rows;
        
    //     $ofertas = [];

    //     if($rows == 0){
    //         $Param['rows'] = $rows;
    //         array_push($ofertas, $Param);
    //     }
    //     else {
    //         while($row = $sql->fetch()) {
            
    //             $Param['id'] = $this->id;
    //             $Param['nome'] = $this->nome;
    //             $Param['valor'] = moneyView($this->valor);
    //             $Param['taxa_servico'] = moneyView($this->taxa_servico);
    //             $Param['rows'] = $rows;
    
    //             array_push($ofertas, $Param);
    //         }
    //     }
    //     return $ofertas;
    // }

    

    
}
