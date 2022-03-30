<?php

// require_once MODELS . '/Conexao/Conexao.class.php';
require_once MODELS . '/Secure/Secure.class.php';
require_once MODELS . '/ResizeFiles/ResizeFiles.class.php';
require_once MODELS . '/Emails/Emails.class.php';
require_once MODELS . '/Estados/Estados.class.php';
require_once MODELS . '/Conexao/Conexao.class.php';
require_once MODELS . '/phpMailer/Enviar.class.php';


class Usuarios extends Conexao {


    public function __construct() {
        $this->Conecta();
        $this->data_atual = date('Y-m-d H:i:s');
        // $this->helper = new UsuariosHelper();
        $this->tabela = "app_users";
    }



    public function save($tipo, $nome, $nome_responsavel, $email, $password, $razao_social, $documento, $telefone, $celular, $data_nascimento, $avatar, $data_cadastro, $u_login, $status, $status_aprovado, $destaque, $id_clinica) {

        $sql_cadastro = $this->mysqli->prepare("
            INSERT INTO `app_users`(`tipo`, `nome`, `nome_responsavel`, `email`, `password`, `razao_social`,
            `documento`, `telefone`, `celular`, `data_nascimento`, `avatar`, `data_cadastro`, `u_login`, `destaque`, `status`, `status_aprovado`, `id_clinica`)
            VALUES (
                '$tipo', '$nome', '$nome_responsavel', '$email', '$password', '$razao_social', '$documento', '$telefone', '$celular',
                '$data_nascimento', '$avatar', '$data_cadastro', '$u_login', '$destaque', '$status', '$status_aprovado', '$id_clinica'
            )"
        );

        $sql_cadastro->execute();
        $this->id_cadastro = $sql_cadastro->insert_id;

        $Param = ["status"=>"01",
                  "msg" => "Cadastro adicionado",
                  "id" => $this->id_cadastro,
                  "nome" => $nome,
                  "email" => $email,
                  "avatar" => $avatar
                ];

        return $Param;
    }


    public function savePaciente($tipo, $nome, $nome_responsavel, $email, $password, $razao_social, $documento, $telefone, $celular, $data_nascimento, $avatar, $data_cadastro, $u_login, $status, $status_aprovado, $destaque, $id_clinica, $id_vendedor) {

        $sql_cadastro = $this->mysqli->prepare("
            INSERT INTO `app_users`(`tipo`, `nome`, `nome_responsavel`, `email`, `password`, `razao_social`,
            `documento`, `telefone`, `celular`, `data_nascimento`, `avatar`, `data_cadastro`, `u_login`, `destaque`, `status`, `status_aprovado`, `id_clinica`, `id_vendedor`)
            VALUES (
                '$tipo', '$nome', '$nome_responsavel', '$email', '$password', '$razao_social', '$documento', '$telefone', '$celular',
                '$data_nascimento', '$avatar', '$data_cadastro', '$u_login', '$destaque', '$status', '$status_aprovado', '$id_clinica', '$id_vendedor'
            )"
        );

        $sql_cadastro->execute();
        $this->id_cadastro = $sql_cadastro->insert_id;

        $Param = ["status"=>"01",
                  "msg" => "Cadastro adicionado",
                  "id" => $this->id_cadastro,
                  "nome" => $nome,
                  "email" => $email,
                  "avatar" => $avatar
                ];

        return $Param;
    }

    public function saveUsersInfo($id_user, $whatsapp, $instagram, $facebook, $linkedin, $site) {

        $sql_cadastro = $this->mysqli->prepare("
            INSERT INTO `app_users_info`(`app_users_id`, `whatsapp`, `instagram`, `facebook`, `linkedin`, `site`)
            VALUES ('$id_user', '$whatsapp', '$instagram', '$facebook', '$linkedin', '$site')"
        );
        $sql_cadastro->execute();
    }

    public function updateInfo($id_user, $whatsapp, $instagram, $facebook, $linkedin, $site) {

        $sql_cadastro = $this->mysqli->prepare("UPDATE app_users_info SET whatsapp='$whatsapp', instagram='$instagram', facebook='$facebook', linkedin='$linkedin', site='$site' WHERE app_users_id='$id_user'");
        $sql_cadastro->execute();

        return array("status"=>"01", "msg"=>"Redes sociais atualizadas.");
    }

    public function saveFoto($id_user, $foto) {

        $sql_cadastro = $this->mysqli->prepare("
            INSERT INTO `app_users_fotos`(`app_users_id`, `url`, `data`)
            VALUES ('$id_user', '$foto', '$this->data_atual')"
        );
        $sql_cadastro->execute();

        return array(
            "status"=>"01",
            "msg"=>"Imagem adicionada."
        );
    }

    public function saveSubcategorias($id_user, $id_subcategoria) {

        $sql_cadastro = $this->mysqli->prepare("
            INSERT INTO `app_users_subcategorias`(`app_users_id`, `app_subcategorias_id`)
            VALUES ('$id_user', '$id_subcategoria')"
        );
        $sql_cadastro->execute();
    }

    public function deleteAllSubcategorias($id_user){

        $sql_cadastro = $this->mysqli->prepare("DELETE FROM app_users_subcategorias WHERE app_users_id='$id_user'");
        $sql_cadastro->execute();
    }

    public function delete($id_user){

        $sql_cadastro = $this->mysqli->prepare("DELETE FROM app_users WHERE id='$id_user'");
        $sql_cadastro->execute();

        $result = array(
            "status"=>"01",
            "msg"=>"Usuário removido"
        );

        return $result;
    }

    public function update($id, $nome, $email, $documento, $celular, $data_nascimento, $telefone, $nome_responsavel) {

        $sql_cadastro = $this->mysqli->prepare("
        UPDATE `$this->tabela`
        SET nome='$nome', email='$email', documento='$documento', celular='$celular', data_nascimento='$data_nascimento', telefone='$telefone', nome_responsavel='$nome_responsavel'
        WHERE id='$id'
        ");

        $sql_cadastro->execute();

        $Param = ["status"=>"01",
                  "msg"=>"Cadastro atualizado"
                ];

        return $Param;
    }

    public function updateAvatar($id, $avatar) {


        $sql_cadastro = $this->mysqli->prepare("
        UPDATE `$this->tabela`
        SET avatar='$avatar'
        WHERE id='$id'
        ");

        $sql_cadastro->execute();

        $Param = ["status"=>"01",
                  "msg"=>"Imagem de perfil atualizada"
                ];

        return $Param;
    }

    // verificar pois não esta salvando certo a senha
    public function updatePassword($id, $password) {

        $sql_cadastro = $this->mysqli->prepare("
        UPDATE `$this->tabela`
        SET password='$password'
        WHERE id='$id'
        ");

        $sql_cadastro->execute();

        $Param = ["status"=>"01",
                  "msg"=>"Senha atualizada"
                ];

        return $Param;
    }

    public function listId($id) {

        // echo "SELECT id, nome, email, documento, data_nascimento, celular, avatar FROM `$this->tabela` WHERE id='$id'"; exit;

        $sql = $this->mysqli->prepare("SELECT id, nome, email, documento, data_nascimento, celular, avatar, id_clinica, nome_responsavel, razao_social, telefone, tipo FROM `$this->tabela` WHERE id='$id'");
        $sql->execute();
        $sql->bind_result($this->id, $this->nome, $this->email, $this->documento,  $this->data_nascimento, $this->celular, $this->avatar, $this->id_clinica, $this->nome_responsavel, $this->razao_social, $this->telefone, $this->tipo);
        $sql->fetch();

        $Param['id'] = $this->id;
        $Param['nome'] = ucwords($this->nome);
        $Param['email'] = $this->email;
        $Param['tipo'] = getNomeTipo($this->tipo);
        $Param['telefone'] = $this->telefone;
        $Param['celular'] = $this->celular;
        $Param['documento'] = $this->documento;
        $Param['id_clinica'] = $this->id_clinica;
        $Param['nome_responsavel'] = $this->nome_responsavel;
        $Param['razao_social'] = $this->razao_social;
        $Param['data_nascimento'] = dataBR($this->data_nascimento);
        $Param['avatar'] = $this->avatar;

        return $Param;
    }

    public function listInfo($id) {

        // echo "SELECT id, nome, email, documento, data_nascimento, celular, avatar FROM `$this->tabela` WHERE id='$id'"; exit;

        $sql = $this->mysqli->prepare("SELECT id, whatsapp, instagram, facebook, linkedin, site FROM app_users_info WHERE app_users_id='$id'");
        $sql->execute();
        $sql->bind_result($this->id, $this->whatsapp, $this->instagram, $this->facebook,  $this->linkedin, $this->site);
        $sql->fetch();

        $Param['id'] = $this->id;
        $Param['whatsapp'] = $this->whatsapp;
        $Param['instagram'] = $this->instagram;
        $Param['facebook'] = $this->facebook;
        $Param['linkedin'] = $this->linkedin;
        $Param['site'] = $this->site;

        return $Param;
    }


    public function saveFcm($id_user, $type, $registration_id) {

        $sql = $this->mysqli->prepare("INSERT INTO app_fcm (app_users_id, type, registration_id) VALUES ('$id_user', '$type', '$registration_id')");
        $sql->execute();

        $Param['status'] = '01';
        $Param['msg'] = 'OK';

        return $Param;
    }

    public function listVendedores($id_clinica) {

        $sql = $this->mysqli->prepare("
        SELECT id, nome, celular, avatar, IF(status=1,'Ativo', 'Inativo'), documento, email
        FROM `$this->tabela`
        WHERE id_clinica ='$id_clinica' and tipo = 3
        "
        );
        $sql->execute();
        $sql->bind_result($this->id, $this->nome, $this->celular, $this->avatar, $this->status, $this->documento, $this->email);
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
                $Param['status'] = $this->status;
                $Param['documento'] = $this->documento;
                $Param['celular'] = $this->celular;
                $Param['rows'] = $rows;

                array_push($usuarios, $Param);
            }
        }
        return $usuarios;
    }


    public function listPacientes($id_vendedor) {

        $sql = $this->mysqli->prepare("
        SELECT id, nome, celular, avatar, IF(status=1,'Ativo', 'Inativo'), documento, email
        FROM `$this->tabela`
        WHERE id_vendedor ='$id_vendedor' and tipo = 1
        "
        );
        $sql->execute();
        $sql->bind_result($this->id, $this->nome, $this->celular, $this->avatar, $this->status, $this->documento, $this->email);
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
                $Param['status'] = $this->status;
                $Param['documento'] = $this->documento;
                $Param['celular'] = $this->celular;
                $Param['rows'] = $rows;

                array_push($usuarios, $Param);
            }
        }
        return $usuarios;
    }


    public function listPacientesClinica($id_clinica) {

        $sql = $this->mysqli->prepare("
        SELECT id, nome, celular, avatar, IF(status=1,'Ativo', 'Inativo'), documento, email
        FROM `$this->tabela`
        WHERE id_clinica ='$id_clinica' and tipo = 1
        "
        );
        $sql->execute();
        $sql->bind_result($this->id, $this->nome, $this->celular, $this->avatar, $this->status, $this->documento, $this->email);
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
                $Param['status'] = $this->status;
                $Param['documento'] = $this->documento;
                $Param['celular'] = $this->celular;
                $Param['rows'] = $rows;

                array_push($usuarios, $Param);
            }
        }
        return $usuarios;
    }

    public function listCategorias($clinica) {

        $sql = $this->mysqli->prepare("
        SELECT id, nome
        FROM app_categorias
        WHERE status=1
        "
        );
        $sql->execute();
        $sql->bind_result($this->id_categ, $this->nome_categ);
        $sql->store_result();
        $rows = $sql->num_rows;

        $usuarios = [];

        if($rows == 0){
            $Param['rows'] = $rows;
            array_push($usuarios, $Param);
        }
        else {
            while($row = $sql->fetch()) {

                $Param['id'] = $this->id_categ;
                $Param['nome'] = $this->nome_categ;
                $Param['rows'] = $rows;
                $Param['subcategorias'] = $this->listSubcategorias($this->id_categ, $clinica);

                array_push($usuarios, $Param);
            }
        }
        return $usuarios;
    }

    public function isChecked($sub, $clinica){
        
        $sql = $this->mysqli->prepare("
        SELECT id
        FROM app_users_subcategorias
        WHERE app_users_id='$clinica' and app_subcategorias_id='$sub'
        "
        );
        $sql->execute();
        $sql->bind_result($this->id_cli_sub);
        $sql->store_result();
        $sql->fetch();

        if($this->id_cli_sub != ""){
            return 'checked';
        } else {
            return '';
        }
    }

    public function listSubcategorias($id_categoria, $clinica) {

        $sql = $this->mysqli->prepare("
        SELECT id, nome
        FROM app_subcategorias
        WHERE status=1 and app_categorias_id='$id_categoria'
        "
        );
        $sql->execute();
        $sql->bind_result($this->id_subcateg, $this->nome_subcateg);
        $sql->store_result();
        $rows = $sql->num_rows;

        $usuarios = [];

        if($rows == 0){
            $Param['rows'] = $rows;
            array_push($usuarios, $Param);
        }
        else {
            while($row = $sql->fetch()) {

                $Param['id'] = $this->id_subcateg;
                $Param['nome'] = $this->nome_subcateg;
                $Param['checked'] = $this->isChecked($this->id_subcateg, $clinica);
                $Param['rows'] = $rows;

                array_push($usuarios, $Param);
            }
        }
        return $usuarios;
    }




    // public function loginFace(){


    //     $this->email = $_POST['email'];
    //     $this->nome = $_POST['nome'];
    //     $this->avatar = renameUpload(basename($_FILES['avatar']['name']));
    //     $this->avatar_tmp = $_FILES['avatar']['tmp_name'];
    //     $this->id_categoria = $_POST['id_categoria'];
    //     $this->latitude = $_POST['latitude'];
    //     $this->longitude = $_POST['longitude'];
    //     $this->tipo = 1;

    //     $equalsResult = $this->dao->equalsEmail($this->email);

    //     if($equalsResult['id'] != "") {
    //         $equalsResult['msg'] = "Login efetuado com sucesso.";
    //         $this->CadastrosArray[] = $equalsResult;
    //     }
    //     else {

    //             if(!empty($this->avatar)) {

    //                 $this->avatar_final = $this->avatar;
    //                 move_uploaded_file($this->avatar_tmp, $this->pasta . "/" . $this->avatar_final);

    //             }
    //             else{
    //                 $this->avatar_final = "avatarm.png";
    //             }

    //             $enderecoCompleto = geraEndCompleto($this->latitude, $this->longitude);
    //             $estados = new Estados();
    //             // [0] => RS
    //             // [1] => Porto Alegre
    //             // [2] => Protásio Alves
    //             // [3] => Av. Protásio Alves
    //             // [4] => 91310
    //             $this->estado = $enderecoCompleto[0];
    //             $this->cidade = $enderecoCompleto[1];
    //             $this->bairro = $enderecoCompleto[2];
    //             $this->endereco = $enderecoCompleto[3];
    //             $this->cep = $enderecoCompleto[4];


    //             $estados->RetornaID($this->estado, $this->cidade);


    //             $resultSave = $this->dao->save($this->nome, $this->email, $this->documento=null, $this->data_nascimento=null, $this->password=null,
    //                                         $this->tipo, $this->id_categoria, $this->latitude,
    //                                         $this->longitude, $this->avatar, $this->status=1, $this->celular=null, $estados->id_estado,
    //                                         $estados->id_cidade, $this->endereco, $this->bairro, $this->cep, $this->numero=0, $this->complemento=0
    //                                         );
    //             $resultSave['msg'] = "Login efetuado com sucesso.";

    //             $this->CadastrosArray[] = $resultSave;

    //     }
    //     $json = json_encode($this->CadastrosArray);
    //     echo $json;

    // }









     public function recuperarsenha($email) {

        //VERIFICA SE JÁ EXISTE E-MAIL
        $sql = $this->mysqli->prepare("SELECT * FROM `$this->tabela` WHERE email='$email'");
        $sql->execute();
        $sql->store_result();
        $rows = $sql->num_rows;
        $sql->fetch();


        if ($rows > 0){

          $this->token = geraToken(rand(5, 15), rand(100, 500), rand(6000, 10000));

          $sql = $this->mysqli->prepare("UPDATE `$this->tabela` SET token_senha = ? WHERE email = ?");
          $sql->bind_param('ss', $this->token, $email);
          $sql->execute();

          //ENVIA E-MAIL RECUPERACAO DE SENHA
          $mail = new EnviarEmail();
          $mail->recuperarsenha($this->nome, $email, $this->token);

          $Param['status'] = '01';
          $Param['msg'] = 'As instruções para alteração de senha foram enviadas para o seu e-mail.';
          $lista[] = $Param;

          $json = json_encode($lista);
          echo $json;
        }
        if ($rows == 0) {
          $Param['status'] = '02';
          $Param['msg'] = 'Não encontramos o seu e-mail em nosso cadastro, por favor, tente outros dados';
          $lista[] = $Param;
          $json = json_encode($lista);
          echo $json;
        }

      }


      public function updatepasswordtoken($password, $token) {

        $this->custo = '08';
        $this->salt = geraSalt(22);

        // Gera um hash baseado em bcrypt
        $this->hash = crypt($password, '$2a$' . $this->custo . '$' . $this->salt . '$');

        $sql = $this->mysqli->prepare("UPDATE `$this->tabela` SET password = ? WHERE token_senha = ? ");
        $sql->bind_param('ss', $this->hash, $token);
        $sql->execute();

        $lista = array();

        if ($sql->affected_rows) {

          $Param['status'] = '01';
          $Param['token'] = $this->token;
          $Param['msg'] = 'Senha alterada com sucesso!';
          $lista[] = $Param;
          $json = json_encode($lista);
          echo $json;
        }
        else {
          $Param['status'] = '02';
          $Param['msg'] = 'Erro ao alterar senha, tente novamente!';
          $lista[] = $Param;
          $json = json_encode($lista);
          echo $json;
        }
      }


      public function verificatoken($token) {

        // echo "SELECT * FROM `$this->tabela` WHERE token_senha='$token'"; exit;

        //VERIFICA SE JÁ EXISTE E-MAIL
        $sql = $this->mysqli->prepare("SELECT * FROM `$this->tabela` WHERE token_senha='$token'");
        $sql->execute();
        $sql->store_result();
        $rows = $sql->num_rows;

        if ($rows > 0) {

          $Param['status'] = '01';
          $Param['msg'] = 'Token OK';
          $lista[] = $Param;

          $json = json_encode($lista);
          echo $json;
        }
        if ($rows == 0) {
          $Param['status'] = '02';
          $Param['msg'] = 'Token Inexistente';
          $lista[] = $Param;
          $json = json_encode($lista);
          echo $json;
        }

      }







//     public function updatepassword() {

//         $this->secure->tokens_secure($this->input->token);

//         $result = $this->dao->updatePassword($this->input->password, $this->input->id);

//         $resultArray[] = $result;
//         $json = json_encode($resultArray);
//         echo $json;

//     }

//    public function saveFcm() {

//         $this->secure->tokens_secure($this->input->token);

//         $result = $this->dao->saveFcm($this->input->id, $this->input->type, $this->input->fcm);

//         $json = json_encode($result);
//         echo $json;
//     }
}
