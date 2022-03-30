<?php

require_once MODELS . '/Conexao/Conexao.class.php';
/**
 * Helpers são responsáveis por todas validaçoes e regras de negócio que o Modelo pode possuir
 *
 */

class UsuariosHelper extends Conexao {

    public function __construct() {
        $this->tabela = "app_users";
        $this->Conecta();
    }

    public function cryptPassword($password, $nome, $email){

        $this->custo = '08';
        $this->salt = geraSalt(22);
        $this->token = geraToken($nome, $email);
        $this->hash = crypt($password, '$2a$' . $this->custo . '$' . $this->salt . '$');

        return $this->hash;
    }

    public function filters($nome, $email, $cpf, $tipo) {

        $filter = "";

        if(isset($nome)){ $filter.="AND nome like'%$nome%'"; }
        if(isset($email)){ $filter.="AND email like'%$email%'"; }
        if(isset($cpf)){ $filter.="AND cpf='$cpf'"; }
        if(isset($tipo)){ $filter.="AND tipo='$tipo'"; }

        return $filter;
    }

    public function validateEmail($email, $tipo) {

        $sql = $this->mysqli->prepare("SELECT id FROM `$this->tabela` WHERE email = '$email'");
        $sql->execute();
        $sql->bind_result($this->id);
        $sql->fetch();

        if(isset($this->id)){
            $error['status'] = '02';
            $error['msg'] = 'Email em uso, por favor tente outros dados.';

            return $error;
        }
    }

    public function validateEmailUpdate($email, $tipo, $id) {

        $sql = $this->mysqli->prepare("SELECT id FROM `$this->tabela` WHERE email = '$email' AND id<>'$id'");
        $sql->execute();
        $sql->bind_result($this->id);
        $sql->fetch();

        if(isset($this->id)){
            $error['status'] = '02';
            $error['msg'] = 'Já existe um usuário com este email, por favor tente outros dados.';

            return $error;
        }
    }

    public function validateLogin($email, $password) {

        $sql = $this->mysqli->prepare("
        SELECT id, nome, celular, email, password, tipo, avatar, status_aprovado
        FROM `$this->tabela`
        WHERE email = '$email' AND status='1'"
        );
        $sql->execute();
        $sql->bind_result($this->id, $this->nome,  $this->celular, $this->email, $this->password_hash, $this->tipo, $this->avatar, $this->status_aprovado);
        $sql->store_result();
        $sql->fetch();
        $sql->close();

        if (crypt($password, $this->password_hash) === $this->password_hash) {

                if($this->status_aprovado == 2){
                        $error['status'] = '03';
                        $error['msg'] = 'Cadastro em análise, aguarde enquanto verificamos seus dados.';

                        return $error;
                }
                else {
                        $success['status'] = '01';
                        $success['id'] = $this->id;
                        $success['nome'] = $this->nome;
                        $success['email'] = $this->email;
                        $success['tipo'] = $this->tipo;
                        $success['nome_tipo'] = getNomeTipo($this->tipo);
                        $success['avatar'] = $this->avatar;
                        $success['msg'] = 'Login efetuado com sucesso.';

                        return $success;
                }
        }

        else {
            $error['status'] = '02';
            $error['msg'] = 'E-mail ou Senha incorretos, tente outros dados!';

            return $error;
        }
    }

    public function validateCpf($cpf, $tipo) {

        $sql = $this->mysqli->prepare("SELECT id FROM `$this->tabela` WHERE cpf = '$cpf' AND tipo='$tipo'");
        $sql->execute();
        $sql->bind_result($this->id);
        $sql->fetch();
        $sql->close();

        if(isset($this->id)){
            $error['status'] = '02';
            $error['msg'] = 'Já existe um usuário com este cpf, por favor tente outros dados.';

            return $error;
        }
    }

    public function validateCpfUpdate($cpf, $tipo, $id) {

        $sql = $this->mysqli->prepare("SELECT id FROM `$this->tabela` WHERE cpf = '$cpf' AND tipo='$tipo' AND id<>'$id'");
        $sql->execute();
        $sql->bind_result($this->id);
        $sql->fetch();
        $sql->close();

        if(isset($this->id)){
            $error['status'] = '02';
            $error['msg'] = 'Já existe um usuário com este cpf, por favor tente outros dados.';

            return $error;
        }
    }
}
