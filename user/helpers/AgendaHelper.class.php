<?php

require_once MODELS . '/Conexao/Conexao.class.php';

/**
 * Helpers são responsáveis por todas validaçoes e regras de negócio que o Modelo pode possuir
 *
 */

class AgendaHelper extends Conexao {

    public function __construct() {
        $this->tabela = "app_ofertas";
        $this->Conecta();
    }

    public function diaSemana($day){
        $this->dias = array('Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado');
        return $this->dias[$day];
    }

    
    public function dias($id_clinica){
        
        $sql = $this->mysqli->prepare("
        SELECT id, day, status
        FROM app_users_agenda
        WHERE app_users_id='$id_clinica'
        GROUP BY day
        ORDER BY day
        "
        );
        $sql->execute();
        $sql->bind_result($this->id, $this->day, $this->status);
        $sql->store_result();
        
        while($row = $sql->fetch()){
            $param['id'] = $this->id;
            $param['day'] = $this->day;
            $param['day_nome'] = $this->diaSemana($this->day-1);
            $param['status'] = $this->status;
            $lista[] = $param;
        }

        return $lista;
    }

    
}
