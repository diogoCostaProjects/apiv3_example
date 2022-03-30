<?php


require_once MODELS . '/Secure/Secure.class.php';
require_once MODELS . '/Conexao/Conexao.class.php';
require_once HELPERS . '/AgendaHelper.class.php';


class Agenda extends Conexao {


    public function __construct() {
        $this->Conecta();
        $this->data_atual = date('Y-m-d H:i:s');
        $this->helper = new UsuariosHelper();
        $this->tabela = "app_users_agenda";

    }



    public function save($id_user, $day, $horario_in, $horario_out, $valor, $status) {

        $sql_cadastro = $this->mysqli->prepare("
            INSERT INTO `app_users_agenda`(`app_users_id`, `day`, `horario_in`, `horario_out`, `valor`, `status`)
            VALUES (
                '$id_user', '$day', '$horario_in', '$horario_out', '$valor', '$status'
            )"
        );

        $sql_cadastro->execute();
        $this->id_cadastro = $sql_cadastro->insert_id;

        $Param = ["status"=>"01",
                  "msg" => "Horário adicionado à agenda",
                  "id" => $this->id_cadastro
                ];

        return $Param;
    }


    public function update($id, $horario_in, $horario_out, $valor) {

        $sql_cadastro = $this->mysqli->prepare("
        UPDATE app_users_agenda
        SET horario_in='$horario_in', horario_out='$horario_out', valor='$valor'
        WHERE id='$id'
        ");

        $sql_cadastro->execute();

        $Param = ["status"=>"01",
                  "msg"=>"Agenda atualizada"
                ];

        return $Param;
    }


    public function delete($id) {

        $sql_cadastro = $this->mysqli->prepare("
        DELETE FROM app_users_agenda
        WHERE id='$id'
        ");

        $sql_cadastro->execute();

        $Param = ["status"=>"01",
                  "msg"=>"Horário removido"
                ];

        return $Param;
    }

    public function updateStatus($day, $status, $id_clinica) {

        $sql_cadastro = $this->mysqli->prepare("
        UPDATE app_users_agenda
        SET status='$status'
        WHERE day='$day' and app_users_id = '$id_clinica'
        ");

        $sql_cadastro->execute();

        $msg = $status==1?'Dia ativo':'Dia inativo';

        $Param = ["status"=>"01",
                  "msg"=>$msg
                ];

        return $Param;
    }


    public function listAll($id_user, $day) {

        if($day!= ""){ $day_q = "AND day='$day'"; }

        $helper = new AgendaHelper();

        $sql = $this->mysqli->prepare("
        SELECT id, day, horario_in, horario_out, valor, status
        FROM app_users_agenda
        WHERE app_users_id='$id_user' and status=1 $day_q
        ORDER BY day
        "
        );
        $sql->execute();
        $sql->bind_result($this->id, $this->day, $this->horario_in, $this->horario_out, $this->valor, $this->status);
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
                $Param['day'] = $this->day;
                $Param['day_nome'] =  $helper->diaSemana($this->day-1);
                $Param['horario_in'] = $this->horario_in;
                $Param['horario_out'] = $this->horario_out;
                $Param['valor'] = $this->valor;
                $Param['status'] = $this->status;
                $Param['rows'] = $rows;

                array_push($usuarios, $Param);
            }
        }
        return $usuarios;
    }


    public function verifyAgenda($id_agenda, $data){

        $sql = $this->mysqli->prepare("
        SELECT id
        FROM app_consultas
        WHERE app_users_agenda_id='$id_agenda' and data='$data 00:00:00' LIMIT 1
        "
        );
        $sql->execute();
        $sql->bind_result($this->id_consulta);
        $sql->store_result();
        $sql->fetch();

        return $this->id_consulta;
    }


    public function listAllVendedor($id_user, $day, $data) {

        if($day!= ""){ $day_q = "AND day='$day'"; }

        $helper = new AgendaHelper();

        $sql = $this->mysqli->prepare("
        SELECT id, day, horario_in, horario_out, valor, status
        FROM app_users_agenda
        WHERE app_users_id='$id_user' and status=1 $day_q
        ORDER BY horario_in, day
        "
        );
        $sql->execute();
        $sql->bind_result($this->id, $this->day, $this->horario_in, $this->horario_out, $this->valor, $this->status);
        $sql->store_result();
        $rows = $sql->num_rows;

        $usuarios = [];
        $rows_ = 0;

        if($rows == 0){
            $Param['rows'] = $rows;
            array_push($usuarios, $Param);
        }
        else {
            while($row = $sql->fetch()) {

                $consulta_marcada = $this->verifyAgenda($this->id, $data);

                if($consulta_marcada=="") {

                    $rows_ ++;

                    $Param['id'] = $this->id;
                    $Param['day'] = $this->day;
                    $Param['day_nome'] =  $helper->diaSemana($this->day-1);
                    $Param['horario_in'] = $this->horario_in;
                    $Param['horario_out'] = $this->horario_out;
                    $Param['status'] = $this->status;
                    $Param['valor'] = $this->valor;
                    $Param['rows'] = $rows_;

                    array_push($usuarios, $Param);
                }

            }
        }
        return $usuarios;
    }




    public function listCategorias() {

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
                $Param['subcategorias'] = $this->listSubcategorias($this->id_categ);

                array_push($usuarios, $Param);
            }
        }
        return $usuarios;
    }


    public function listSubcategorias($id_categoria) {

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









    public function recuperarsenha() {

        $request = file_get_contents('php://input');
        $input = json_decode($request);

        $this->email = strtolower($input->email);

        //VERIFICA SE JÁ EXISTE E-MAIL
        $sql = $this->mysqli->prepare("SELECT id, nome, token FROM `$this->tabela` WHERE email='$this->email'");
        $sql->execute();
        $sql->bind_result($this->id, $this->nome, $this->token);
        $sql->store_result();
        $rows = $sql->num_rows;
        $sql->fetch();

        if ($rows > 0) {

          //ENVIA E-MAIL RECUPERACAO DE SENHA
          $mail = new Emails();
          $mail->recuperarsenha($this->email, $this->nome, $this->token);

          $Param['status'] = '01';
          $Param['msg'] = 'As instruções para alteração de senha foram enviadas para o seu e-mail.';
          $lista[] = $Param;

          $json = json_encode($lista);
          echo $json;
        }
        if ($rows == 0) {
            $Param['status'] = '02';
            $Param['msg'] = 'Não encontramos o seu e-mail em nosso cadastro, favor, tente outros dados';
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
