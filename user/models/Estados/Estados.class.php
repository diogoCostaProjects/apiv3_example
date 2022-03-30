<?php

require_once MODELS . '/Conexao/Conexao.class.php';

class Estados extends Conexao {

    public function __construct() {

        $this->Conecta();
        $this->tabela = "estados";
        $this->tabela_cidades = "cidades";
    }

    public function RetornaID($estado, $cidade) {

      $sql = $this->mysqli->prepare("SELECT cod_estados FROM `$this->tabela` WHERE sigla='$estado'");
      $sql->execute();
      $sql->bind_result($this->id_estado);
      $sql->fetch();
      $sql->close();

      $sql2 = $this->mysqli->prepare("SELECT cod_cidades FROM `$this->tabela_cidades` WHERE nome='$cidade'");
      $sql2->execute();
      $sql2->bind_result($this->id_cidade);
      $sql2->fetch();
      $sql2->close();

    }

    public function RetornaNome($estado, $cidade) {

      $sql = $this->mysqli->prepare("SELECT sigla FROM `$this->tabela` WHERE cod_estados='$estado'");
      $sql->execute();
      $sql->bind_result($this->id_estado);
      $sql->fetch();
      $sql->close();

      $sql2 = $this->mysqli->prepare("SELECT nome FROM `$this->tabela_cidades` WHERE cod_cidades='$cidade'");
      $sql2->execute();
      $sql2->bind_result($this->id_cidade);
      $sql2->fetch();
      $sql2->close();

    }

    public function RetornaNomeInteiro($estado, $cidade) {

      $sql = $this->mysqli->prepare("SELECT nome FROM `$this->tabela` WHERE cod_estados='$estado'");
      $sql->execute();
      $sql->bind_result($this->id_estado);
      $sql->fetch();
      $sql->close();

      $sql2 = $this->mysqli->prepare("SELECT nome FROM `$this->tabela_cidades` WHERE cod_cidades='$cidade'");
      $sql2->execute();
      $sql2->bind_result($this->id_cidade);
      $sql2->fetch();
      $sql2->close();

    }

    public function listAll() {

        $request = file_get_contents('php://input');
        $input = json_decode($request);

        $sql = $this->mysqli->prepare("SELECT cod_estados, sigla, nome
          FROM `$this->tabela`
          WHERE status='1'
          ORDER BY nome ASC
          ");
          $sql->execute();
          $sql->bind_result($this->id, $this->nome, $this->sigla);

          $lista = array();
          while ($row = $sql->fetch()) {

            $Param['id'] = $this->id;
            $Param['nome'] = $this->nome;
            $Param['sigla'] = $this->sigla;

            $lista[] = $Param;
          }

          $json = json_encode($lista);
          echo $json;

    }

    public function listcidadesAll() {

        $request = file_get_contents('php://input');
        $input = json_decode($request);

        $this->id_estado = $input->id;

        $sql = $this->mysqli->prepare("SELECT cod_cidades, nome
          FROM `$this->tabela_cidades`
          WHERE estados_cod_estados='$this->id_estado'
          ORDER BY nome ASC
          ");
          $sql->execute();
          $sql->bind_result($this->id, $this->nome);

          $lista = array();
          while ($row = $sql->fetch()) {

            $Param['id'] = $this->id;
            $Param['nome'] = $this->nome;

            $lista[] = $Param;
          }

          $json = json_encode($lista);
          echo $json;

    }

    public function listcidadeID() {

        $request = file_get_contents('php://input');
        $input = json_decode($request);

        $this->id_cidade = $input->id;

        $sql = $this->mysqli->prepare("SELECT cod_cidades, nome
          FROM `$this->tabela_cidades`
          WHERE cod_cidades='$this->id_cidade'
          ");
          $sql->execute();
          $sql->bind_result($this->id, $this->nome);

          $lista = array();
          while ($row = $sql->fetch()) {

            $Param['id'] = $this->id;
            $Param['nome'] = $this->nome;

            $lista[] = $Param;
          }

          $json = json_encode($lista);
          echo $json;

    }

}
