<?php

require_once MODELS . '/Usuarios/Usuarios.class.php';
require_once MODELS . '/Consultas/Consultas.class.php';
require_once MODELS . '/Usuarios/Enderecos.class.php';
require_once MODELS . '/Secure/Secure.class.php';
require_once MODELS . '/Emails/Emails.class.php';
require_once HELPERS . '/UsuariosHelper.class.php';
require_once HELPERS . '/EnderecosHelper.class.php';

class DashboardController {

    public function __construct() {

        $request = file_get_contents('php://input');
        $this->input = json_decode($request);
        $this->secure = new Secure();
        
        $this->req = $_REQUEST;
        $this->data_atual = date('Y-m-d H:i:s');
        $this->dia_atual = date('Y-m-d');
    }
    

    public function vendedores() {
        
        $this->secure->tokens_secure($this->input->token);

        $consultas =  new Consultas();

        $lista_consultas['consultas'] = $consultas->listAllDashVendedores($this->req['id']="", 
                                               $this->req['paciente']="", 
                                               $this->req['clinica']="", 
                                               $this->req['vendedor'], 
                                               $this->req['categoria']="", 
                                               $this->req['subcategoria']="", 
                                               $data_de="", 
                                               $data_ate="", 
                                               $this->req['status'],
                                               $celular="");
        $lista_consultas['qtd_pacientes'] = $consultas->contPacientesVendedor($this->req['vendedor']);
        $lista_consultas['qtd_consultas'] = $consultas->contConsultasVendedor($this->req['vendedor']);
        $lista_consultas['data_cadastro'] = $consultas->dataCadastro($this->req['vendedor']);

        jsonReturn(array($lista_consultas));
    }


    
    
    public function clinicas() {
        
        $this->secure->tokens_secure($this->input->token);

        $consultas =  new Consultas();

        $lista_consultas['consultas'] = $consultas->listAllDashClinicas($this->req['id']="", 
                                               $this->req['paciente']="", 
                                               $this->req['clinica'], 
                                               $this->req['vendedor']="", 
                                               $this->req['categoria']="", 
                                               $this->req['subcategoria']="", 
                                               dataUS($this->req['data_de']), 
                                               dataUS($this->req['data_ate']), 
                                               $status=1,
                                               $celular="");
        $lista_consultas['qtd_pacientes'] = $consultas->contPacientesClinica($this->req['clinica']);
        $lista_consultas['qtd_consultas_andamento'] = $consultas->contConsultasAndamentoClinica($this->req['clinica']);
        $lista_consultas['qtd_consultas_finalizadas'] = $consultas->contConsultasFinalizadasClinica($this->req['clinica']);
        $lista_consultas['qtd_consultas_canceladas'] = $consultas->contConsultasCanceladasClinica($this->req['clinica']);
        $lista_consultas['data_cadastro'] = $consultas->dataCadastroC($this->req['clinica']);

        jsonReturn(array($lista_consultas));
    }

    
   
}

