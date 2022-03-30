<?php

// require_once MODELS . '/Conexao/Conexao.class.php';
require_once MODELS . '/Secure/Secure.class.php';
require_once MODELS . '/Estados/Estados.class.php';
require_once MODELS . '/Conexao/Conexao.class.php';


class Ofertas extends Conexao {

    
    private $id;
    private $nome;
    private $valor;
    private $taxa_servico;
    private $desconto;
    private $descricao;
    private $regras;
    private $destaque;
    private $status;

    function setId($id) { $this->id = $id; }
    function getId() { return $this->id; }
    function setNome($nome) { $this->nome = $nome; }
    function getNome() { return $this->nome; }
    function setValor($valor) { $this->valor = $valor; }
    function getValor() { return $this->valor; }
    function setTaxa_servico($taxa_servico) { $this->taxa_servico = $taxa_servico; }
    function getTaxa_servico() { return $this->taxa_servico; }
    function setDesconto($desconto) { $this->desconto = $desconto; }
    function getDesconto() { return $this->desconto; }
    function setDescricao($descricao) { $this->descricao = $descricao; }
    function getDescricao() { return $this->descricao; }
    function setRegras($regras) { $this->regras = $regras; }
    function getRegras() { return $this->regras; }
    function setDestaque($destaque) { $this->destaque = $destaque; }
    function getDestaque() { return $this->destaque; }
    function setStatus($status) { $this->status = $status; }
    function getStatus() { return $this->status; }


    public function __construct() {
        $this->Conecta();
        $this->data_atual = date('Y-m-d H:i:s');
        $this->tabela = "app_ofertas";
    }
              

    public function listId($id) {
                        
        $sql = $this->mysqli->prepare("
        SELECT id, nome, valor, taxa_servico FROM `$this->tabela` WHERE id='$id'");
        $sql->execute();
        $sql->bind_result($this->id, $this->nome, $this->valor, $this->taxa_servico);
        $sql->fetch();
        $sql->Close();

        $capa = $this->getCapa($id);
        if($capa ==""){ $capa = 'empty.png';}

        $Param['id'] = $this->id;
        $Param['nome'] = $this->nome;
        $Param['valor'] = moneyView($this->valor);
        $Param['taxa_servico'] = moneyView($this->taxa_servico);
        $Param['capa'] = $capa;
               
        
        return $Param;
    }


    public function listDerivados($id_oferta) {
                        
        $sql = $this->mysqli->prepare("SELECT id, nome, descricao, valor FROM app_ofertas_derivados WHERE app_ofertas_id='$id_oferta' and status=1");
        $sql->execute();
        $sql->bind_result($this->id_derivado, $this->nome_derivado, $this->descricao_derivado, $this->valor_derivado);
        $sql->store_result();
        $rows = $sql->num_rows;
        
        $ofertas = [];

        if($rows == 0){
            $Param['rows'] = $rows;
            array_push($ofertas, $Param);
        }
        else {
            while($row = $sql->fetch()) {
            
                $Param['id'] = $this->id_derivado;
                $Param['nome'] = $this->nome_derivado;
                $Param['descricao'] = $this->descricao_derivado;
                $Param['valor'] = moneyView($this->valor_derivado);
                $Param['rows'] = $rows;
    
                array_push($ofertas, $Param);
            }
        }
        $sql->Close();
        return $ofertas;
    }

    /**
     * REGRAS
     * 
     * - cupons em ordem aleatória
     * - cupons apenas ativos (status 1)
     * - cupons dentro do prazo de validade
     */

    public function listCupons($id_oferta) {

        $data_atual = date('Y-m-d');
                        
        $sql = $this->mysqli->prepare("SELECT id, cod, valor_desc, data_validade FROM app_ofertas_cupons WHERE app_ofertas_id='$id_oferta' and status=1 and data_validade >='$data_atual' ORDER BY rand() LIMIT 1");
        $sql->execute();
        $sql->bind_result($this->id, $this->cod, $this->valor_desc, $this->data_validade);
        $sql->store_result();
        $rows = $sql->num_rows;
        
        $ofertas = [];

        if($rows == 0){
            $Param['rows'] = $rows;
            array_push($ofertas, $Param);
        }
        else {
            while($row = $sql->fetch()) {
            
                $Param['id'] = $this->id;
                $Param['cod'] = $this->cod;
                $Param['valor_desc'] = moneyView($this->valor_desc);
                $Param['data_validade'] = dataBR($this->data_validade);
                $Param['rows'] = $rows;
    
                array_push($ofertas, $Param);
            }
        }
        $sql->Close();
        return $ofertas;
    }



    public function listFotos($id_oferta) {
                        
        $sql = $this->mysqli->prepare("SELECT id, url FROM app_ofertas_fotos WHERE app_ofertas_id='$id_oferta' and capa <> 1");
        $sql->execute();
        $sql->bind_result($this->id_foto, $this->url_foto);
        $sql->store_result();
        $rows = $sql->num_rows;
        
        $ofertas = [];

        if($rows == 0){
            $Param['rows'] = $rows;
            array_push($ofertas, $Param);
        }
        else {
            while($row = $sql->fetch()) {
            
                $Param['id'] = $this->id_foto;
                $Param['url'] = $this->url_foto;
                $Param['rows'] = $rows;
    
                array_push($ofertas, $Param);
            }
        }
        $sql->Close();

        return $ofertas;
    }

    public function listCategorias($id_oferta) {
                        
        $sql = $this->mysqli->prepare("
        SELECT c.id, c.nome
        FROM app_ofertas_categorias as c
        INNER JOIN app_ofertas_catg as oc on oc.app_ofertas_categorias_id = c.id
        WHERE oc.app_ofertas_id='$id_oferta'");
        $sql->execute();
        $sql->bind_result($this->id_categoria, $this->nome_categoria);
        $sql->store_result();
        $rows = $sql->num_rows;
        
        $ofertas = [];

        if($rows == 0){
            $Param['rows'] = $rows;
            array_push($ofertas, $Param);
        }
        else {
            while($row = $sql->fetch()) {
            
                $Param['id'] = $this->id_categoria;
                $Param['nome'] = $this->nome_categoria;
                $Param['rows'] = $rows;
    
                array_push($ofertas, $Param);
            }
        }
        $sql->Close();

        return $ofertas;
    }


    public function find($query) {
                        
        $sql = $this->mysqli->prepare($query);
        $sql->execute();
        $sql->bind_result($this->id, $this->nome, $this->valor, $this->taxa_servico);
        $sql->store_result();
        $rows = $sql->num_rows;
        
        $ofertas = [];

        if($rows == 0){
            $Param['rows'] = $rows;
            array_push($ofertas, $Param);
        }
        else {
            while($row = $sql->fetch()) {
            
                $Param['id'] = $this->id;
                $Param['nome'] = $this->nome;
                $Param['valor'] = moneyView($this->valor);
                $Param['taxa_servico'] = moneyView($this->taxa_servico);
                $Param['rows'] = $rows;
    
                array_push($ofertas, $Param);
            }
        }
        return $ofertas;
    }

    public function getCapa($id_oferta) {
                        
        // echo $query; exit;

        // echo "SELECT url FROM app_ofertas_fotos WHERE app_ofertas_id='$id_oferta' and capa=1"; exit;

        $sql = $this->mysqli->prepare("SELECT url FROM app_ofertas_fotos WHERE app_ofertas_id='$id_oferta' and capa=1");
        $sql->execute();
        $sql->bind_result($this->capa);
        $sql->store_result();
        $sql->fetch();
        $sql->Close();
        return $this->capa;
    }


    public function findByDistance($query) {
                        
        // echo $query; exit;

        $sql = $this->mysqli->prepare($query);
        $sql->execute();
        $sql->bind_result($this->id, $this->nome, $this->valor, $this->taxa_servico);
        $sql->store_result();
        $rows = $sql->num_rows;
        
        $ofertas = [];

        if($rows == 0){
            $Param['rows'] = $rows;
            array_push($ofertas, $Param);
        }
        else {
            while($row = $sql->fetch()) {
            
                $capa = $this->getCapa($this->id);
                if($capa ==""){ $capa = 'empty.png';}

                $Param['id'] = $this->id;
                $Param['nome'] = $this->nome;
                $Param['capa'] = $capa;
                $Param['valor'] = moneyView($this->valor);
                $Param['taxa_servico'] = moneyView($this->taxa_servico);
                $Param['rows'] = $rows;
    
                array_push($ofertas, $Param);
            }
        }
        return $ofertas;
    }


    

    
}
