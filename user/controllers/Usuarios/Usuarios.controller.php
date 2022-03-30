<?php

require_once MODELS . '/Usuarios/Usuarios.class.php';
require_once MODELS . '/Planos/Planos.class.php';
require_once MODELS . '/Usuarios/Enderecos.class.php';
require_once MODELS . '/Agenda/Agenda.class.php';
require_once MODELS . '/Secure/Secure.class.php';
require_once MODELS . '/Emails/Emails.class.php';
require_once HELPERS . '/UsuariosHelper.class.php';
require_once HELPERS . '/EnderecosHelper.class.php';

class UsuariosController {

    public function __construct() {

        $request = file_get_contents('php://input');
        $this->input = json_decode($request);
        $this->secure = new Secure();

        $this->req = $_REQUEST;
        $this->data_atual = date('Y-m-d H:i:s');
        $this->dia_atual = date('Y-m-d');
    }

    public function updateavatar($id) {

        // $this->secure->tokens_secure($this->input->token);

        $this->pasta = '../../uploads/avatar';

        $usuarios =  new Usuarios();

        $this->id = $id;

        // echo $this->id; exit;

        $this->avatar = renameUpload(basename($_FILES['avatar']['name']));
        $this->avatar_tmp = $_FILES['avatar']['tmp_name'];

        if(!empty($this->avatar)) {

            //ENVIA PARA PASTA IMAGEM TEMPORÁRIA
            $this->avatar_final = $this->avatar;

            move_uploaded_file($this->avatar_tmp, $this->pasta . "/temporarias/" . $this->avatar_final);

            $imagem = new TutsupRedimensionaImagem();

            $imagem->imagem = $this->pasta . '/temporarias/' . $this->avatar_final;
            $imagem->imagem_destino = $this->pasta . '/' . $this->avatar_final;

            $imagem->largura = 200;
            $imagem->altura = 0;
            $imagem->qualidade = 100;

            $nova_imagem = $imagem->executa();

            unlink($this->pasta . "/temporarias/" . $this->avatar_final); // remove o arquivo da pasta temporária
        }
        else {
            $this->avatar_final = "avatar.png";
        }

        $result = $usuarios->updateAvatar($this->id, $this->avatar_final);

        jsonReturn(array($result));
    }



    public function savefoto($id) {

        // $this->secure->tokens_secure($this->input->token);

        $this->pasta = '../../uploads/imagens';

        $usuarios = new Usuarios();

        $this->id = $id;

        $this->img = renameUpload(basename($_FILES['img']['name']));
        $this->img_tmp = $_FILES['img']['tmp_name'];

        move_uploaded_file($this->img_tmp, $this->pasta . "/temporarias/" . $this->img);

        $imagem = new TutsupRedimensionaImagem();

        $imagem->imagem = $this->pasta . '/temporarias/' . $this->img;
        $imagem->imagem_destino = $this->pasta . '/' . $this->img;

        $imagem->largura = 800;
        $imagem->altura = 0;
        $imagem->qualidade = 100;

        $nova_imagem = $imagem->executa();

        unlink($this->pasta . "/temporarias/" . $this->img); // remove o arquivo da pasta temporária

        $result = $usuarios->saveFoto($this->id, $this->img);

        jsonReturn(array($result));
    }


    public function save_vendedor(){

        $this->secure->tokens_secure($this->input->token);

        $helper = new UsuariosHelper();
        $usuarios =  new Usuarios();
        $emails = new Emails();

        $emailCheck = $helper->validateEmail($this->input->email, $tipo=3);

        $senha = generateRandomString();

        // VALIDAÇÕES DE EMAIL
        if($emailCheck) {
            jsonReturn(array($emailCheck));
        }

        $this->hash = $helper->cryptPassword($senha, $this->input->nome, $this->input->email);

        $result = $usuarios->save(
            $tipo=3,
            $this->input->nome,
            $this->input->nome_responsavel,
            $this->input->email,
            $this->hash,
            $this->input->razao_social,
            tiraCarac($this->input->documento),
            $this->input->telefone,
            $this->input->celular,
            dataUS($this->input->data_nascimento),
            $avatar='avatar.png',
            $this->data_atual,
            $this->data_atual,
            $status='1',
            $status_aprovado='1',
            $destaque='2',
            $this->input->id_clinica
        );

        $emails->cadastro($this->input->email, $this->input->nome, $senha);

        jsonReturn(array($result));
    }


    public function save_paciente(){

        $this->secure->tokens_secure($this->input->token);

        $helper = new UsuariosHelper();
        $usuarios =  new Usuarios();
        $emails = new Emails();

        $emailCheck = $helper->validateEmail($this->input->email, $tipo=1);

        $senha = generateRandomString();

        // VALIDAÇÕES DE EMAIL
        if($emailCheck) {
            jsonReturn(array($emailCheck));
        }

        $this->hash = $helper->cryptPassword($senha, $this->input->nome, $this->input->email);

        $result = $usuarios->savePaciente(
            $tipo=1,
            $this->input->nome,
            $this->input->nome_responsavel,
            $this->input->email,
            $this->hash,
            $this->input->razao_social,
            tiraCarac($this->input->documento),
            $this->input->telefone,
            $this->input->celular,
            dataUS($this->input->data_nascimento),
            $avatar='avatar.png',
            $this->data_atual,
            $this->data_atual,
            $status='1',
            $status_aprovado='1',
            $destaque='2',
            $this->input->id_clinica,
            $this->input->id_vendedor
        );

        // $emails->cadastro($this->input->email, $this->input->nome, $senha);

        jsonReturn(array($result));
    }

    public function save_paciente_app(){

        $this->secure->tokens_secure($this->input->token);

        $helper = new UsuariosHelper();
        $helperEndereco = new EnderecosHelper();
        $usuarios =  new Usuarios();
        $endedecoObj =  new Enderecos();
        
        $emailCheck = $helper->validateEmail($this->input->email, $tipo=1);

        // VALIDAÇÕES DE EMAIL
        if($emailCheck) {
            jsonReturn(array($emailCheck));
        }

        $this->hash = $helper->cryptPassword($this->input->password, $this->input->nome, $this->input->email);

        $result = $usuarios->savePaciente(
            $tipo=1,
            $this->input->nome,
            $this->input->nome_responsavel,
            $this->input->email,
            $this->hash,
            $this->input->razao_social,
            tiraCarac($this->input->documento),
            $this->input->telefone,
            $this->input->celular,
            dataUS($this->input->data_nascimento),
            $avatar='avatar.png',
            $this->data_atual,
            $this->data_atual,
            $status='1',
            $status_aprovado='1',
            $destaque='2',
            $this->input->id_clinica,
            $this->input->id_vendedor
        );

        $endereco_final = $helperEndereco->gerarEndereco(
            $this->input->latitude, 
            $this->input->longitude, 
            $this->input->cep,
            $this->input->estado, 
            $this->input->cidade, 
            $this->input->bairro, 
            $this->input->endereco, 
            $this->input->numero, 
            $this->input->complemento
        );
        
        // ao se cadastrar adiciona o endereço do app através da lat/long

        $endedecoObj->save(
            $result['id'],
            $endereco_final['cep'], 
            $endereco_final['estado'], 
            $endereco_final['cidade'],
            $endereco_final['endereco'], 
            $endereco_final['bairro'], 
            $endereco_final['numero'], 
            $endereco_final['complemento'], 
            $endereco_final['latitude'], 
            $endereco_final['longitude']);

        jsonReturn(array($result));
    }

    public function list_vendedores($id_clinica) {

        $this->secure->tokens_secure($this->input->token);

        $usuarios =  new Usuarios();

        $lista_vendedores = $usuarios->listVendedores($id_clinica);

        jsonReturn($lista_vendedores);
    }

    public function list_pacientes($id_vendedor) {

        $this->secure->tokens_secure($this->input->token);

        $usuarios =  new Usuarios();

        $lista_pacientes = $usuarios->listPacientes($id_vendedor);

        jsonReturn($lista_pacientes);
    }


    public function listenderecopaciente($paciente){
        
        $this->secure->tokens_secure($this->input->token);

        $enderecos =  new Enderecos();

        $endereco_paciente = $enderecos->listIDPaciente($paciente);

        jsonReturn($endereco_paciente);
    }


    public function list_pacientes_clinica($id_clinica) {

        $this->secure->tokens_secure($this->input->token);

        $usuarios =  new Usuarios();

        $lista_pacientes = $usuarios->listPacientesClinica($id_clinica);

        jsonReturn($lista_pacientes);
    }

    public function delete() {

        $this->secure->tokens_secure($this->input->token);

        $usuarios =  new Usuarios();

        $return = $usuarios->delete($this->input->id);

        jsonReturn(array($return));
    }


    public function save_clinica() {

        $this->secure->tokens_secure($this->input->token);

        $helper = new UsuariosHelper();
        $helper_enderecos = new EnderecosHelper();
        $usuarios =  new Usuarios();
        $enderecos =  new Enderecos();
        $planosOBJ =  new Planos();
        $plano = $planosOBJ->listID($id=1);

        $validade_plano = date('Y-m-d', strtotime('+'.$plano['free_dias'].' days', strtotime($this->dia_atual)));

        $this->hash = $helper->cryptPassword($this->input->password, $this->input->nome, $this->input->email);
        $endereco_final = $helper_enderecos->gerarEndereco($lat="", $long="", $this->input->cep, $this->input->estado, $this->input->cidade, $this->input->bairro, $this->input->endereco, $this->input->numero, $this->input->complemento);

        $emailCheck = $helper->validateEmail($this->input->email, $this->input->tipo=2);

        // VALIDAÇÕES DE EMAIL
        if($emailCheck) {
            jsonReturn(array($emailCheck));
        }

        $result = $usuarios->save(
            $tipo=2,
            $this->input->nome,
            $this->input->nome_responsavel,
            $this->input->email,
            $this->hash,
            $this->input->razao_social,
            tiraCarac($this->input->documento),
            $this->input->telefone,
            $this->input->celular,
            dataUS($this->input->data_nascimento),
            $avatar='avatar.png',
            $this->data_atual,
            $this->data_atual,
            $status='1',
            $status_aprovado='2',
            $destaque='2',
            $id_clinica=""
        );

        $enderecos->save(
            $result['id'],
            $this->input->cep,
            $this->input->estado,
            $this->input->cidade,
            $this->input->endereco,
            $this->input->bairro,
            $this->input->numero,
            $this->input->complemento,
            $endereco_final['latitude'],
            $endereco_final['longitude']
        );

        foreach($this->input->subcategorias as $sub) {
            $usuarios->saveSubcategorias($result['id'], $sub);
        }

        $agenda = new Agenda();

        $agenda->save($result['id'], $day=1, $horario_in="08:00", $horario_out="12:00", $valor="0.00", $status=1);
        $agenda->save($result['id'], $day=2, $horario_in="08:00", $horario_out="12:00", $valor="0.00", $status=1);
        $agenda->save($result['id'], $day=3, $horario_in="08:00", $horario_out="12:00", $valor="0.00", $status=1);
        $agenda->save($result['id'], $day=4, $horario_in="08:00", $horario_out="12:00", $valor="0.00", $status=1);
        $agenda->save($result['id'], $day=5, $horario_in="08:00", $horario_out="12:00", $valor="0.00", $status=1);
        $agenda->save($result['id'], $day=6, $horario_in="08:00", $horario_out="12:00", $valor="0.00", $status=1);
        $agenda->save($result['id'], $day=7, $horario_in="08:00", $horario_out="12:00", $valor="0.00", $status=1);

        $usuarios->saveUsersInfo($result['id'], $whatsapp='', $instagram='', $facebook='', $linkedin='', $site='');
        $planosOBJ->save($result['id'], $id_plano=1, $validade_plano, $ativo=1);

        jsonReturn(array($result));
    }


    public function categorias($id){

        // $this->secure->tokens_secure($this->input->token);

        $usuariosOBJ =  new Usuarios();

        $categorias = $usuariosOBJ->listCategorias($id);

        jsonReturn($categorias);
    }

    public function savefcm(){

        $usuariosOBJ =  new Usuarios();

        $result = $usuariosOBJ->saveFcm($this->input->id_user, $this->input->type, $this->input->registration_id);

        jsonReturn(array($result));
    }



    public function saveendereco(){

        $this->secure->tokens_secure($this->input->token);

        $helper_enderecos = new EnderecosHelper();
        $enderecos =  new Enderecos();

        $endereco_final = $helper_enderecos->gerarEndereco($this->input->latitude,
                                                          $this->input->longitude,
                                                          $this->input->cep,
                                                          $this->input->estado,
                                                          $this->input->cidade,
                                                          $this->input->bairro,
                                                          $this->input->endereco,
                                                          $this->input->numero,
                                                          $this->input->complemento);

        $enderecos->setId_user($this->input->id_user);
        $enderecos->setEstado($endereco_final['estado']);
        $enderecos->setCidade($endereco_final['cidade']);
        $enderecos->setBairro($endereco_final['bairro']);
        $enderecos->setEndereco($endereco_final['endereco']);
        $enderecos->setCep($endereco_final['cep']);
        $enderecos->setNumero($endereco_final['numero']);
        $enderecos->setComplemento($endereco_final['complemento']);
        $enderecos->setLatitude($endereco_final['latitude']);
        $enderecos->setLongitude($endereco_final['longitude']);

        $enderecos->save();

        $result['status'] = '01';
        $result['msg'] = 'Endereço adicionado.';

        jsonReturn(array($result));
    }


    public function updateendereco(){

        $this->secure->tokens_secure($this->input->token);

        $helper_enderecos = new EnderecosHelper();
        $enderecos =  new Enderecos();

        $endereco_final = $helper_enderecos->gerarEndereco($lat="", $long="", $this->input->cep, $this->input->estado, $this->input->cidade, $this->input->bairro, $this->input->endereco, $this->input->numero, $this->input->complemento);

        $enderecos->update(
            $this->input->id_endereco,
            $this->input->cep,
            $this->input->estado,
            $this->input->cidade,
            $this->input->endereco,
            $this->input->bairro,
            $this->input->numero,
            $this->input->complemento,
            $endereco_final['latitude'],
            $endereco_final['longitude']
        );

        $result['status'] = '01';
        $result['msg'] = 'Endereço atualizado.';

        jsonReturn(array($result));
    }


    public function updateendereco_location(){

        $this->secure->tokens_secure($this->input->token);

        $helper_enderecos = new EnderecosHelper();
        $enderecos =  new Enderecos();

        $endereco_final = $helper_enderecos->gerarEndereco($this->input->latitude, $this->input->longitude, $this->input->cep, $this->input->estado, $this->input->cidade, $this->input->bairro, $this->input->endereco, $this->input->numero, $this->input->complemento);

        $enderecos->updateLocation(
            $this->input->id_user,
            $endereco_final['cep'],
            $endereco_final['estado'],
            $endereco_final['cidade'],
            $endereco_final['endereco'],
            $endereco_final['bairro'],
            $endereco_final['numero'],
            $this->input->complemento,
            $endereco_final['latitude'],
            $endereco_final['longitude']
        );

        $result['status'] = '01';
        $result['msg'] = 'Endereço atualizado.';

        jsonReturn(array($result));
    }

    public function update() {

        $this->secure->tokens_secure($this->input->token);

        $helper = new UsuariosHelper();
        $usuarios =  new Usuarios();

        $emailCheck = $helper->validateEmailUpdate($this->input->email, $this->input->tipo, $this->input->id);

        // VALIDAÇÕES DE EMAIL E CPF
        if($emailCheck) { jsonReturn(array($emailCheck)); }

        $result = $usuarios->update($this->input->id, $this->input->nome, $this->input->email, tiraCarac($this->input->documento), $this->input->celular, dataUS($this->input->data_nascimento), $this->input->telefone, $this->input->nome_responsavel);

        jsonReturn(array($result));
    }

    public function updateinfo() {

        // $this->secure->tokens_secure($this->input->token);

        $usuarios =  new Usuarios();

        $result = $usuarios->updateInfo($this->input->id, $this->input->whatsapp, $this->input->instagram, $this->input->facebook, $this->input->linkedin, $this->input->site);

        jsonReturn(array($result));
    }


    public function updatesubcategorias() {

        $this->secure->tokens_secure($this->input->token);

        $usuarios =  new Usuarios();

        // VALIDAÇÕES DE EMAIL E CPF
        $usuarios->deleteAllSubcategorias($this->input->id);

        foreach($this->input->subcategorias as $sub) {
            $usuarios->saveSubcategorias($this->input->id, $sub);
        }

        $result = array(
            "status"=> "01",
            "msg"=> "Subcategorias alteradas"
        );

        jsonReturn(array($result));
    }

    public function updatepassword() {

        $this->secure->tokens_secure($this->input->token);

        $helper = new UsuariosHelper();
        $usuarios =  new Usuarios();

        $usuarioDados = $usuarios->listId($this->input->id);

        $this->hash  = $helper->cryptPassword($this->input->password, $usuarioDados['nome'], $usuarioDados['email']);

        $result = $usuarios->updatePassword($this->input->id, $this->hash);

        jsonReturn(array($result));
    }

    public function login() {

        $this->secure->tokens_secure($this->input->token);

        $helper = new UsuariosHelper();
        $login = $helper->validateLogin($this->input->email, $this->input->password);

        jsonReturn(array($login));
    }

    public function listid($id){

        $this->secure->tokens_secure($this->input->token);

        $usuariosOBJ =  new Usuarios();

        $usuario = $usuariosOBJ->listId($id);

        jsonReturn(array($usuario));
    }

    public function listinfo($id){

        $this->secure->tokens_secure($this->input->token);

        $usuariosOBJ =  new Usuarios();

        $usuario = $usuariosOBJ->listInfo($id);

        jsonReturn(array($usuario));
    }

    public function find(){

        $this->secure->tokens_secure($this->input->token);

        $usuariosOBJ =  new Usuarios();
        $helper =  new UsuariosHelper();

        $filter = $helper->filters($this->req['nome'], $this->req['email'], $this->req['cpf'], $this->req['tipo']);

        $usuario = $usuariosOBJ->find($filter);

        jsonReturn($usuario);
    }

    public function findenderecos($id){

        $this->secure->tokens_secure($this->input->token);

        $enderecosOBJ =  new Enderecos();

        $enderecos = $enderecosOBJ->find($id);

        jsonReturn($enderecos);
    }

    public function findenderecoid($id){

        $this->secure->tokens_secure($this->input->token);

        $enderecosOBJ =  new Enderecos();

        $enderecos = $enderecosOBJ->listID($id);

        jsonReturn($enderecos);
    }

    public function recuperarsenha() {

        $usuariosOBJ =  new Usuarios();
        $usuariosOBJ->recuperarsenha($this->input->email);
    }

    public function updatepasswordtoken() {

        $usuariosOBJ =  new Usuarios();
        $usuariosOBJ->updatepasswordtoken($this->input->password, $this->input->token_senha);
    }

    public function verificatoken() {

        $usuariosOBJ =  new Usuarios();
        $usuariosOBJ->verificatoken($this->input->token_senha);
    }


}
