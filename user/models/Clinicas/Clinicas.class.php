<?php

// require_once MODELS . '/Conexao/Conexao.class.php';
require_once MODELS . '/Secure/Secure.class.php';
require_once MODELS . '/ResizeFiles/ResizeFiles.class.php';
require_once MODELS . '/Emails/Emails.class.php';
require_once MODELS . '/Estados/Estados.class.php';
require_once MODELS . '/Conexao/Conexao.class.php';
require_once MODELS . '/phpMailer/Enviar.class.php';


class Clinicas extends Conexao {


    public function __construct() {
        $this->Conecta();
        $this->data_atual = date('Y-m-d H:i:s');
        // $this->helper = new UsuariosHelper();
        $this->tabela = "app_users";
    }


    public function categoriasSubcategoriasClinica($clinica) {
        
        $sql = $this->mysqli->prepare("
        SELECT s.nome as subcategoria, c.nome as categoria 
        FROM app_users_subcategorias as s 
        INNER JOIN app_categorias as c on c.id = s.app_subcategorias_id  
        WHERE s.app_users_id='$clinica'
        "
        );
        $sql->execute();
        $sql->bind_result($this->categoria, $this->subcategoria);
        $sql->store_result();
        $rows = $sql->num_rows;

        if($rows == 0){
            $Param['rows'] = $rows;
            array_push($usuarios, $Param);
        }
        else {
            while($row = $sql->fetch()) {

                $Param['categoria'] = $this->categoria;
                $Param['subcategoria'] = $this->subcategoria;
                $Param['rows'] = $rows;

                array_push($usuarios, $Param);
            }
        }

        return $usuarios;
    }

    
    public function clinicasByCidade($categoria, $subcategoria, $cidade) {

        if(isset($categoria)){ $cat_query = "and cc.id='$categoria'"; }
        if(isset($subcategoria)){ $sub_query = "and s.app_subcategorias_id='$subcategoria'"; }

        $data = date('Y-m-d');

        $sql = $this->mysqli->prepare("
        SELECT c.id, c.nome, c.celular, c.avatar, c.email, e.cidade, e.estado, e.cep, e.endereco, e.numero, e.complemento
        FROM app_users as c
        INNER JOIN app_users_planos as p on p.app_users_id = c.id
        INNER JOIN app_users_end as e on e.app_users_id = c.id
        LEFT JOIN app_users_subcategorias as s on s.app_users_id = c.id
        LEFT JOIN app_subcategorias as su on su.id = s.app_subcategorias_id
        LEFT JOIN app_categorias as cc on cc.id = su.app_categorias_id
        WHERE e.cidade ='$cidade' and p.data_validade >= '$data' and c.status=1 and c.status_aprovado=1
        $cat_query
        $sub_query
        GROUP BY c.id 
        ORDER BY c.id LIMIT 1
        "
        );
        $sql->execute();
        $sql->bind_result($this->id, $this->nome, $this->celular, $this->avatar, $this->email, $this->cidade, $this->estado, $this->cep, $this->endereco, $this->numero, $this->complemento);
        $sql->store_result();
        $rows = $sql->num_rows;

        $usuarios = [];

        if($rows == 0){
            $Param['rows'] = $rows;
            array_push($usuarios, $Param);
        }
        else {
            while($row = $sql->fetch()) {

                $Param['id'] = $this->id;
                $Param['nome'] = ucwords($this->nome);
                $Param['celular'] = $this->celular;
                $Param['avatar'] = $this->avatar;
                $Param['email'] = $this->email;
                $Param['cidade'] = $this->cidade;
                $Param['estado'] = $this->estado;
                $Param['cep'] = $this->cep;
                $Param['endereco'] = $this->endereco;
                $Param['numero'] = $this->numero;
                $Param['complemento'] = $this->complemento;
                $Param['rows'] = $rows;

                array_push($usuarios, $Param);
            }
        }
        return $usuarios;
    }



    public function clinicasDistancia($categoria, $subcategoria, $lat, $long) {

        if(isset($categoria)){ $cat_query = "and cc.id='$categoria'"; }
        if(isset($subcategoria)){ $sub_query = "and s.app_subcategorias_id='$subcategoria'"; }

        $data = date('Y-m-d');


        
        $sql = $this->mysqli->prepare("
        SELECT c.id, c.nome, c.celular, c.avatar, c.email, e.cidade, e.estado, e.cep, e.endereco, e.numero, e.complemento, 
        round((acos(sin(radians('$lat')) * sin(radians(e.latitude)) +
        cos(radians('$lat')) * cos(radians(e.latitude)) *
        cos(radians('$long') - radians(e.longitude))) * 6378)) as distancia
        FROM app_users as c
        INNER JOIN app_users_planos as p on p.app_users_id = c.id
        INNER JOIN app_users_end as e on e.app_users_id = c.id
        LEFT JOIN app_users_subcategorias as s on s.app_users_id = c.id
        LEFT JOIN app_subcategorias as su on su.id = s.app_subcategorias_id
        LEFT JOIN app_categorias as cc on cc.id = su.app_categorias_id
        WHERE p.data_validade >= '$data' and c.status=1 and c.status_aprovado=1
        $cat_query
        $sub_query
        GROUP BY c.id 
        ORDER BY distancia LIMIT 1
        "
        );
        $sql->execute();
        $sql->bind_result($this->id, $this->nome, $this->celular, $this->avatar, $this->email, $this->cidade, $this->estado, $this->cep, $this->endereco, $this->numero, $this->complemento, $this->distancia);
        $sql->store_result();
        $rows = $sql->num_rows;

        $usuarios = [];

        if($rows == 0){
            $Param['rows'] = $rows;
            array_push($usuarios, $Param);
        }
        else {
            while($row = $sql->fetch()) {

                $Param['id'] = $this->id;
                $Param['nome'] = ucwords($this->nome);
                $Param['celular'] = $this->celular;
                $Param['avatar'] = $this->avatar;
                $Param['email'] = $this->email;
                $Param['cidade'] = $this->cidade;
                $Param['estado'] = $this->estado;
                $Param['cep'] = $this->cep;
                $Param['endereco'] = $this->endereco;
                $Param['numero'] = $this->numero;
                $Param['complemento'] = $this->complemento;
                $Param['distancia'] = $this->distancia.'km';
                $Param['rows'] = $rows;

                array_push($usuarios, $Param);
            }
        }
        return $usuarios;
    }


    



}

