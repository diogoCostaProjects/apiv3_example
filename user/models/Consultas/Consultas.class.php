<?php

// require_once MODELS . '/Conexao/Conexao.class.php';
require_once MODELS . '/Secure/Secure.class.php';
require_once MODELS . '/ResizeFiles/ResizeFiles.class.php';
require_once MODELS . '/Emails/Emails.class.php';
require_once MODELS . '/Estados/Estados.class.php';
require_once MODELS . '/Conexao/Conexao.class.php';
require_once HELPERS . '/AgendaHelper.class.php';


class Consultas extends Conexao {
    

    public function __construct() {
        $this->Conecta();
        $this->data_atual = date('Y-m-d H:i:s');
        $this->helper_agenda = new AgendaHelper();
        $this->tabela = "app_consultas";
    }
        

         
    public function save($id_de, $id_para, $id_vendedor, $id_categoria, $id_subcategoria, $id_agenda, $data, $descricao, $id_cupom, $status) {
        
        $sql_cadastro = $this->mysqli->prepare("
            INSERT INTO `app_consultas`(`id_de`, `id_para`, `id_vendedor`, `id_categoria`, `id_subcategoria`, `app_users_agenda_id`, `data`, `descricao`, `id_cupom`, `app_consultas_status_id`) VALUES  (
                '$id_de', '$id_para', '$id_vendedor', '$id_categoria', '$id_subcategoria', '$id_agenda', '$data', '$descricao', '$id_cupom', '$status'
            )"
        );
            
        $sql_cadastro->execute();
        $this->id_cadastro = $sql_cadastro->insert_id;
            
        $Param = ["status"=>"01", 
                  "msg" => "Consulta agendada", 
                  "id" => $this->id_cadastro
                ];

        return $Param;
    }
   
    public function delete($id){
        
        $sql_cadastro = $this->mysqli->prepare("DELETE FROM app_consultas WHERE id='$id'");
        $sql_cadastro->execute();

        $result = array(
            "status"=>"01",
            "msg"=>"Consulta removida"
        ); 

        return $result;
    }


    public function statusNome($id){
        
        $sql_cadastro = $this->mysqli->prepare("SELECT nome FROM app_consultas_status WHERE id='$id'");
        $sql_cadastro->execute();
        $sql_cadastro->bind_result($this->nome);
        $sql_cadastro->store_result();
        $sql_cadastro->fetch();
        
        return $this->nome;
    }

    public function updateStatus($id, $status) {
                
        $sql_cadastro = $this->mysqli->prepare("
        UPDATE app_consultas
        SET app_consultas_status_id='$status'
        WHERE id='$id'
        ");

        $sql_cadastro->execute();
                    
        $Param = ["status"=>"01", 
                  "msg"=>"Status atualizado"
                ];

        return $Param;
    }
    
    public function listAll($id, $id_de, $id_para, $id_vendedor, $id_categoria, $id_subcategoria, $data_de, $data_ate, $status, $celular) {
                     

        // filtros da consulta

        if($id !="")                            { $q_id = "AND c.id = '$id'"; }
        if($id_de != "")                        { $q_paciente = "AND c.id_de ='$id_de'"; }
        if($id_para != "")                      { $q_clinica = "AND c.id_para ='$id_para'"; }
        if($id_vendedor != "")                  { $q_vendedor = "AND c.id_vendedor ='$id_vendedor'"; }
        if($id_categoria != "")                 { $q_categoria = "AND c.id_categoria ='$id_categoria'"; }
        if($id_subcategoria != "")              { $q_subcategoria = "AND c.id_subcategoria ='$id_subcategoria'"; }
        if($data_de != "" && $data_ate != "")   { $q_data = "AND c.data between '$data_de 00:00:00' and '$data_ate 00:00:00'"; }
        if($status !="")                        { $q_status = "AND c.app_consultas_status_id='$status'"; }
        if($celular !="")                        { $q_cel = "AND p.celular like '%$celular%'"; }


        
        
        $sql = $this->mysqli->prepare("
        SELECT c.id, c.id_de, c.id_para, c.id_vendedor, c.id_categoria, c.id_subcategoria, c.data, c.descricao, c.id_cupom, p.nome, p.avatar,
        cl.nome, cl.avatar, v.nome, v.avatar, cat.nome, sub.nome, st.nome, a.day, a.horario_in, a.horario_out
        FROM app_consultas as c 
        INNER JOIN app_users as p on p.id = c.id_de
        INNER JOIN app_users as cl on cl.id = c.id_para
        LEFT JOIN app_users as v on v.id = c.id_vendedor
        INNER JOIN app_users_agenda as a on a.id = c.app_users_agenda_id
        LEFT JOIN app_categorias as cat on cat.id = c.id_categoria
        LEFT JOIN app_subcategorias as sub on sub.id = c.id_subcategoria
        INNER JOIN app_consultas_status as st on st.id = c.app_consultas_status_id
        WHERE c.id<>0
        $q_id
        $q_paciente
        $q_clinica
        $q_vendedor
        $q_categoria
        $q_subcategoria
        $q_data
        $q_status
        GROUP BY c.id
        ORDER BY c.id DESC
        "
        );
               
        $sql->execute();
        $sql->bind_result($this->id_consulta, $this->id_paciente, $this->id_clinica, $this->id_vendedor, $this->id_categoria, $this->id_subcategoria, $this->data, $this->descricao, 
        $this->id_cupom, $this->nome_paciente, $this->avatar_paciente, $this->nome_clinica, $this->avatar_clinica, $this->nome_vendedor, $this->avatar_vendedor, $this->categoria, $this->subcategoria, $this->status,
        $this->dia, $this->horario_in, $this->horario_out
        );
        $sql->store_result();
        $rows = $sql->num_rows;
        
        $consultas = [];

        if($rows == 0){
            $Param['rows'] = $rows;
            array_push($consultas, $Param);
        }
        else {
            while($row = $sql->fetch()) {
            
                $Param['id_consulta'] = $this->id_consulta;
                $Param['id_paciente'] = $this->id_paciente;
                $Param['id_clinica'] = $this->id_clinica;
                $Param['id_vendedor'] = $this->id_vendedor;
                $Param['id_categoria'] = $this->id_categoria;
                $Param['id_subcategoria'] = $this->id_subcategoria;
                $Param['data'] = dataBR($this->data);
                $Param['descricao'] = $this->descricao;
                $Param['id_cupom'] = $this->id_cupom;
                $Param['nome_paciente'] = $this->nome_paciente;
                $Param['avatar_paciente'] = $this->avatar_paciente;
                $Param['nome_clinica'] = $this->nome_clinica;
                $Param['avatar_clinica'] = $this->avatar_clinica;
                $Param['nome_vendedor'] = $this->nome_vendedor;
                $Param['avatar_vendedor'] = $this->avatar_vendedor;
                $Param['categoria'] = $this->categoria;
                $Param['subcategoria'] = $this->subcategoria;
                $Param['status'] = $this->status;
                $Param['dia'] = $this->helper_agenda->diaSemana($this->dia);
                $Param['horario_in'] = $this->horario_in;
                $Param['horario_out'] = $this->horario_out;
                $Param['rows'] = $rows;
    
                array_push($consultas, $Param);
            }
        }
        return $consultas;
    }


    public function contPacientesVendedor($id_vendedor) {
        
        $sql = $this->mysqli->prepare("SELECT count(id) FROM app_users WHERE id_vendedor='$id_vendedor'");
        $sql->execute();
        $sql->bind_result($this->qtd_vendedor);
        $sql->store_result();
        $sql->fetch();

        return $this->qtd_vendedor;
    }

    public function contPacientesClinica($id_clinica) {
        
        $sql = $this->mysqli->prepare("SELECT count(id) FROM app_users WHERE id_clinica='$id_clinica'");
        $sql->execute();
        $sql->bind_result($this->qtd_cli);
        $sql->store_result();
        $sql->fetch();

        return $this->qtd_cli;
    }


    public function contConsultasVendedor($id_vendedor) {
        
        $sql = $this->mysqli->prepare("SELECT count(id) FROM app_consultas WHERE id_vendedor='$id_vendedor'");
        $sql->execute();
        $sql->bind_result($this->qtd_consultasvendedor);
        $sql->store_result();
        $sql->fetch();

        return $this->qtd_consultasvendedor;
    }

    public function contConsultasAndamentoClinica($id_clinica){
        $sql = $this->mysqli->prepare("SELECT count(id) FROM app_consultas WHERE id_para='$id_clinica' and app_consultas_status_id=2");
        $sql->execute();
        $sql->bind_result($this->qtd_andamento);
        $sql->store_result();
        $sql->fetch();

        return $this->qtd_andamento;
    }

    public function contConsultasFinalizadasClinica($id_clinica){
        $sql = $this->mysqli->prepare("SELECT count(id) FROM app_consultas WHERE id_para='$id_clinica' and app_consultas_status_id=3");
        $sql->execute();
        $sql->bind_result($this->qtd_finalizadas);
        $sql->store_result();
        $sql->fetch();

        return $this->qtd_finalizadas;
    }

    public function contConsultasCanceladasClinica($id_clinica){
        $sql = $this->mysqli->prepare("SELECT count(id) FROM app_consultas WHERE id_para='$id_clinica' and app_consultas_status_id=4");
        $sql->execute();
        $sql->bind_result($this->qtd_canceladas);
        $sql->store_result();
        $sql->fetch();

        return $this->qtd_canceladas;
    }


    public function dataCadastro($id_vendedor) {
        
        $sql = $this->mysqli->prepare("SELECT data_cadastro FROM app_users WHERE id_vendedor='$id_vendedor'");
        $sql->execute();
        $sql->bind_result($this->data_cadastro);
        $sql->store_result();
        $sql->fetch();

        return dataBR($this->data_cadastro);
    }

    public function dataCadastroC($id) {
        
        $sql = $this->mysqli->prepare("SELECT data_cadastro FROM app_users WHERE id='$id'");
        $sql->execute();
        $sql->bind_result($this->data_cadastro);
        $sql->store_result();
        $sql->fetch();

        return dataBR($this->data_cadastro);
    }



    public function listAllDashVendedores($id, $id_de, $id_para, $id_vendedor, $id_categoria, $id_subcategoria, $data_de, $data_ate, $status, $celular) {
                     

        // filtros da consulta

        if($id !="")                            { $q_id = "AND c.id = '$id'"; }
        if($id_de != "")                        { $q_paciente = "AND c.id_de ='$id_de'"; }
        if($id_para != "")                      { $q_clinica = "AND c.id_para ='$id_para'"; }
        if($id_vendedor != "")                  { $q_vendedor = "AND c.id_vendedor ='$id_vendedor'"; }
        if($id_categoria != "")                 { $q_categoria = "AND c.id_categoria ='$id_categoria'"; }
        if($id_subcategoria != "")              { $q_subcategoria = "AND c.id_subcategoria ='$id_subcategoria'"; }
        if($data_de != "" && $data_ate != "")   { $q_data = "AND c.data between '$data_de 00:00:00' and '$data_ate 00:00:00'"; }
        if($status !="")                        { $q_status = "AND c.app_consultas_status_id='$status'"; }
        if($celular !="")                        { $q_cel = "AND p.celular like '%$celular%'"; }


        $sql = $this->mysqli->prepare("
        SELECT c.id, c.id_de, c.id_para, c.id_vendedor, c.id_categoria, c.id_subcategoria, c.data, c.descricao, c.id_cupom, p.nome, p.avatar,
        cl.nome, cl.avatar, v.nome, v.avatar, cat.nome, sub.nome, st.nome, a.day, a.horario_in, a.horario_out
        FROM app_consultas as c 
        INNER JOIN app_users as p on p.id = c.id_de
        INNER JOIN app_users as cl on cl.id = c.id_para
        LEFT JOIN app_users as v on v.id = c.id_vendedor
        INNER JOIN app_users_agenda as a on a.id = c.app_users_agenda_id
        LEFT JOIN app_categorias as cat on cat.id = c.id_categoria
        LEFT JOIN app_subcategorias as sub on sub.id = c.id_subcategoria
        INNER JOIN app_consultas_status as st on st.id = c.app_consultas_status_id
        WHERE c.id<>0
        $q_id
        $q_paciente
        $q_clinica
        $q_vendedor
        $q_categoria
        $q_subcategoria
        $q_data
        $q_status
        GROUP BY c.id
        ORDER BY c.id DESC
        LIMIT 5
        "
        );
               
        $sql->execute();
        $sql->bind_result($this->id_consulta, $this->id_paciente, $this->id_clinica, $this->id_vendedor, $this->id_categoria, $this->id_subcategoria, $this->data, $this->descricao, 
        $this->id_cupom, $this->nome_paciente, $this->avatar_paciente, $this->nome_clinica, $this->avatar_clinica, $this->nome_vendedor, $this->avatar_vendedor, $this->categoria, $this->subcategoria, $this->status,
        $this->dia, $this->horario_in, $this->horario_out
        );
        $sql->store_result();
        $rows = $sql->num_rows;
        
        $consultas = [];

        if($rows == 0){
            $Param['rows'] = $rows;
            array_push($consultas, $Param);
        }
        else {
            while($row = $sql->fetch()) {
            
                $Param['id_consulta'] = $this->id_consulta;
                $Param['id_paciente'] = $this->id_paciente;
                $Param['id_clinica'] = $this->id_clinica;
                $Param['id_vendedor'] = $this->id_vendedor;
                $Param['id_categoria'] = $this->id_categoria;
                $Param['id_subcategoria'] = $this->id_subcategoria;
                $Param['data'] = dataBR($this->data);
                $Param['descricao'] = $this->descricao;
                $Param['id_cupom'] = $this->id_cupom;
                $Param['nome_paciente'] = $this->nome_paciente;
                $Param['avatar_paciente'] = $this->avatar_paciente;
                $Param['nome_clinica'] = $this->nome_clinica;
                $Param['avatar_clinica'] = $this->avatar_clinica;
                $Param['nome_vendedor'] = $this->nome_vendedor;
                $Param['avatar_vendedor'] = $this->avatar_vendedor;
                $Param['categoria'] = $this->categoria;
                $Param['subcategoria'] = $this->subcategoria;
                $Param['status'] = $this->status;
                $Param['dia'] = $this->helper_agenda->diaSemana($this->dia);
                $Param['horario_in'] = $this->horario_in;
                $Param['horario_out'] = $this->horario_out;
                $Param['rows'] = $rows;
    
                array_push($consultas, $Param);
            }
        }
        return $consultas;
    }
  


    public function listAllDashClinicas($id, $id_de, $id_para, $id_vendedor, $id_categoria, $id_subcategoria, $data_de, $data_ate, $status, $celular) {
                     

        // filtros da consulta

        if($id !="")                            { $q_id = "AND c.id = '$id'"; }
        if($id_de != "")                        { $q_paciente = "AND c.id_de ='$id_de'"; }
        if($id_para != "")                      { $q_clinica = "AND c.id_para ='$id_para'"; }
        if($id_vendedor != "")                  { $q_vendedor = "AND c.id_vendedor ='$id_vendedor'"; }
        if($id_categoria != "")                 { $q_categoria = "AND c.id_categoria ='$id_categoria'"; }
        if($id_subcategoria != "")              { $q_subcategoria = "AND c.id_subcategoria ='$id_subcategoria'"; }
        if($data_de != "" && $data_ate != "")   { $q_data = "AND c.data between '$data_de 00:00:00' and '$data_ate 00:00:00'"; }
        if($status !="")                        { $q_status = "AND c.app_consultas_status_id='$status'"; }
        if($celular !="")                        { $q_cel = "AND p.celular like '%$celular%'"; }


       

        $sql = $this->mysqli->prepare("
        SELECT c.id, c.id_de, c.id_para, c.id_vendedor, c.id_categoria, c.id_subcategoria, c.data, c.descricao, c.id_cupom, p.nome, p.avatar,
        cl.nome, cl.avatar, v.nome, v.avatar, cat.nome, sub.nome, st.nome, a.day, a.horario_in, a.horario_out
        FROM app_consultas as c 
        INNER JOIN app_users as p on p.id = c.id_de
        INNER JOIN app_users as cl on cl.id = c.id_para
        LEFT JOIN app_users as v on v.id = c.id_vendedor
        INNER JOIN app_users_agenda as a on a.id = c.app_users_agenda_id
        LEFT JOIN app_categorias as cat on cat.id = c.id_categoria
        LEFT JOIN app_subcategorias as sub on sub.id = c.id_subcategoria
        INNER JOIN app_consultas_status as st on st.id = c.app_consultas_status_id
        WHERE c.id<>0
        $q_id
        $q_paciente
        $q_clinica
        $q_vendedor
        $q_categoria
        $q_subcategoria
        $q_data
        $q_status
        GROUP BY c.id
        ORDER BY c.id ASC
        LIMIT 5
        "
        );
               
        $sql->execute();
        $sql->bind_result($this->id_consulta, $this->id_paciente, $this->id_clinica, $this->id_vendedor, $this->id_categoria, $this->id_subcategoria, $this->data, $this->descricao, 
        $this->id_cupom, $this->nome_paciente, $this->avatar_paciente, $this->nome_clinica, $this->avatar_clinica, $this->nome_vendedor, $this->avatar_vendedor, $this->categoria, $this->subcategoria, $this->status,
        $this->dia, $this->horario_in, $this->horario_out
        );
        $sql->store_result();
        $rows = $sql->num_rows;
        
        $consultas = [];

        if($rows == 0){
            $Param['rows'] = $rows;
            array_push($consultas, $Param);
        }
        else {
            while($row = $sql->fetch()) {
            
                $Param['id_consulta'] = $this->id_consulta;
                $Param['id_paciente'] = $this->id_paciente;
                $Param['id_clinica'] = $this->id_clinica;
                $Param['id_vendedor'] = $this->id_vendedor;
                $Param['id_categoria'] = $this->id_categoria;
                $Param['id_subcategoria'] = $this->id_subcategoria;
                $Param['data'] = dataBR($this->data);
                $Param['descricao'] = $this->descricao;
                $Param['id_cupom'] = $this->id_cupom;
                $Param['nome_paciente'] = $this->nome_paciente;
                $Param['avatar_paciente'] = $this->avatar_paciente;
                $Param['nome_clinica'] = $this->nome_clinica;
                $Param['avatar_clinica'] = $this->avatar_clinica;
                $Param['nome_vendedor'] = $this->nome_vendedor;
                $Param['avatar_vendedor'] = $this->avatar_vendedor;
                $Param['categoria'] = $this->categoria;
                $Param['subcategoria'] = $this->subcategoria;
                $Param['status'] = $this->status;
                $Param['dia'] = $this->helper_agenda->diaSemana($this->dia);
                $Param['horario_in'] = $this->horario_in;
                $Param['horario_out'] = $this->horario_out;
                $Param['rows'] = $rows;
    
                array_push($consultas, $Param);
            }
        }
        return $consultas;
    }


}
