<?php

// require_once MODELS . '/Conexao/Conexao.class.php';
require_once MODELS . '/Secure/Secure.class.php';
require_once MODELS . '/ResizeFiles/ResizeFiles.class.php';
require_once MODELS . '/Emails/Emails.class.php';
require_once MODELS . '/Estados/Estados.class.php';
require_once MODELS . '/Conexao/Conexao.class.php';


class Carrinho extends Conexao {
   
    public function __construct() {
        $this->Conecta();
        $this->data_atual = date('Y-m-d H:i:s');
        $this->helper = new CarrinhoHelper();
        $this->tabela = "app_users";
    }
     
    public function addItem($id_carrinho, $id_oferta, $qtd, $valor_uni, $valor_desc, $valor_total, $id_derivado) {

        $sql = $this->mysqli->prepare("INSERT INTO app_carrinho_conteudo (app_carrinho_id, app_ofertas_id, qtd, valor_uni, valor_desc, valor_total, id_derivado) 
        VALUES ('$id_carrinho', '$id_oferta', '$qtd', '$valor_uni', '$valor_desc', '$valor_total', '$id_derivado')");
        
        $sql->execute();
        $id_novo = $sql->insert_id;  

        $Param['id_item'] = $id_novo;
        $Param['status'] = '01';
        $Param['msg'] = 'Ítem adicionado ao carrinho.';

        return $Param;
    } 

    public function save($id_de) {

        $status=1;

        // echo "INSERT INTO app_carrinho (app_users_id, data, status) VALUES ('$id_de', '$this->data_atual', '$status')"; exit;

        $sql = $this->mysqli->prepare("INSERT INTO app_carrinho (app_users_id, data, status) VALUES ('$id_de', '$this->data_atual', '$status')");
        $sql->execute();
        $id_novo = $sql->insert_id;            

        return $id_novo;
    } 

    public function itenscarrinho($id_carrinho){
        
        // echo " SELECT i.id, o.id, o.nome, i.valor_uni, i.qtd, i.valor_total, i.valor_desc, (i.valor_total-i.valor_desc) as valor_descontado, i.id_derivado, d.nome
        // FROM app_carrinho_conteudo as i
        // INNER JOIN app_ofertas as o on o.id = i.app_ofertas_id
        // LEFT JOIN app_ofertas_derivados as d on d.id = i.id_derivado
        // WHERE i.id_carrinho='$id_carrinho'"; exit;

        $sql = $this->mysqli->prepare("
        SELECT i.id, o.id, o.nome, i.valor_uni, i.qtd, i.valor_total, i.valor_desc, (i.valor_total-i.valor_desc) as valor_descontado, i.id_derivado, d.nome, d.valor
        FROM app_carrinho_conteudo as i
        INNER JOIN app_ofertas as o on o.id = i.app_ofertas_id
        LEFT JOIN app_ofertas_derivados as d on d.id = i.id_derivado
        WHERE i.app_carrinho_id='$id_carrinho'
        ");
        $sql->execute();
        $sql->bind_result($this->id_item, $this->id_oferta, $this->nome_oferta, $this->valor_uni, $this->qtd, $this->valor_total, $this->valor_desconto, $this->valor_descontado, $this->id_derivado, $this->nome_derivado, $this->valor_derivado);
        $sql->store_result();
        $rows = $sql->num_rows();

        if($rows == 0) {
            $param['rows'] = $rows;
            $lista[] = $param;
        }
        else {
            while($row = $sql->fetch()){
                $param['id_item'] = $this->id_item;
                $param['id_oferta'] = $this->id_oferta;
                $param['nome_oferta'] = $this->nome_oferta;
                $param['valor_uni'] = moneyView($this->valor_uni);
                $param['qtd'] = $this->qtd;
                $param['valor_itens'] = moneyView($this->valor_total);
                $param['valor_desconto'] = moneyView($this->valor_desconto);
                $param['valor_final'] = moneyView($this->valor_descontado + $this->valor_derivado);
                $param['valor_descontado_float'] = $this->valor_descontado + $this->valor_derivado;
                $param['id_derivado'] = $this->id_derivado;
                $param['nome_derivado'] = $this->nome_derivado;
                $param['valor_derivado'] = moneyView($this->valor_derivado);
                $lista[] = $param;
            }
        }
        return $lista;
        
    }
   
}
