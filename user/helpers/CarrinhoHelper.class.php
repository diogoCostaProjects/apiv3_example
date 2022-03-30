<?php

require_once MODELS . '/Conexao/Conexao.class.php';
require_once MODELS . '/Configuracoes/Configuracoes.class.php';
require_once MODELS . '/Usuarios/Enderecos.class.php';
/**
 * Helpers são responsáveis por todas validaçoes e regras de negócio que o Modelo pode possuir
 *
 */

class CarrinhoHelper extends Conexao {

    public function __construct() {
        $this->tabela = "app_ofertas";
        $this->Conecta();
    }

    

    public function findCarrinhoAberto($id_user) { 
                      
        $sql = $this->mysqli->prepare("SELECT id FROM app_carrinho WHERE app_users_id='$id_user' and status='1'");
        $sql->execute();
        $sql->bind_result($this->carrinho_aberto); 
        $sql->store_result();
        $sql->fetch();

        return $this->carrinho_aberto;
    }

    public function calc($valor, $taxa_servico, $qtd){
        
        $valor_total = ($qtd * $valor) + $taxa_servico;
        return $valor_total;
    }
}
