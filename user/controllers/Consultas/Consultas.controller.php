<?php

require_once MODELS . '/Usuarios/Usuarios.class.php';
require_once MODELS . '/Gcm/Gcm.class.php';
require_once MODELS . '/Consultas/Consultas.class.php';
require_once MODELS . '/Usuarios/Enderecos.class.php';
require_once MODELS . '/Secure/Secure.class.php';
require_once MODELS . '/Emails/Emails.class.php';
require_once HELPERS . '/UsuariosHelper.class.php';
require_once HELPERS . '/EnderecosHelper.class.php';

class ConsultasController {

    public function __construct() {

        $request = file_get_contents('php://input');
        $this->input = json_decode($request);
        $this->secure = new Secure();
        
        $this->req = $_REQUEST;
        $this->data_atual = date('Y-m-d H:i:s');
        $this->dia_atual = date('Y-m-d');
    }

   
    public function novaconsulta(){
       
        $this->secure->tokens_secure($this->input->token);

        $consultas =  new Consultas();
                
        $result = $consultas->save(
            $this->input->id_de, 
            $this->input->id_para, 
            $this->input->id_vendedor, 
            $this->input->id_categoria, 
            $this->input->id_subcategoria, 
            $this->input->id_agenda, 
            dataUS($this->input->data), 
            $this->input->descricao, 
            $this->input->id_cupom, 
            $status='1'
        );
        
        jsonReturn(array($result));
    }

    public function find() {
        
        $this->secure->tokens_secure($this->input->token);

        $consultas =  new Consultas();

        $lista_consultas = $consultas->listAll($this->req['id'], 
                                               $this->req['paciente'], 
                                               $this->req['clinica'], 
                                               $this->req['vendedor'], 
                                               $this->req['categoria'], 
                                               $this->req['subcategoria'], 
                                               dataUS($this->req['data_de']), 
                                               dataUS($this->req['data_ate']), 
                                               $this->req['status'],
                                               $this->req['celular']);

        jsonReturn($lista_consultas);
    }

    public function delete() {
        
        $this->secure->tokens_secure($this->input->token);

        $consultas =  new Consultas();

        $return = $consultas->delete($this->input->id);

        jsonReturn(array($return));
    }

   
  
    public function updatestatus() {
        
        $this->secure->tokens_secure($this->input->token);  
                
        $consultas =  new Consultas();
        $notify = new Gcm();
        $status_nome = $consultas->statusNome($this->input->status);

        $result = $consultas->updateStatus($this->input->id, $this->input->status);
        
        $notify->novo_status_android($this->input->id, $status_nome);
        $notify->novo_status_ios($this->input->id, $status_nome);

        jsonReturn(array($result));
    }     

   

    public function listid($id){
        
        $this->secure->tokens_secure($this->input->token);  
        
        $usuariosOBJ =  new Usuarios();
        
        $usuario = $usuariosOBJ->listId($id);

        jsonReturn(array($usuario));
    }
   
}

