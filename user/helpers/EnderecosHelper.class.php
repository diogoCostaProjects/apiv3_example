<?php

require_once MODELS . '/Conexao/Conexao.class.php';
/**
 * Helpers são responsáveis por todas validaçoes e regras de negócio que o Modelo pode possuir
 *
 */

class EnderecosHelper extends Conexao {

    public function __construct() {
        $this->tabela = "app_users";
        $this->Conecta();
    }
   
    public function gerarEndereco(
        $latitude, 
        $longitude, 
        $cep, 
        $estado, 
        $cidade, 
        $bairro, 
        $endereco, 
        $numero, 
        $complemento) {
        
        if($latitude != "" && $longitude!= "") {
            $endereco_completo = geraEndCompleto($latitude, $longitude);

            $param = [
                'estado' => $endereco_completo[0], 
                'cidade' => $endereco_completo[1],
                'bairro' => $endereco_completo[2],
                'endereco' => $endereco_completo[3],
                'cep' => $endereco_completo[4],
                'numero' => '',
                'complemento' => '',
                'latitude' => $latitude,
                'longitude' => $longitude,
            ];
        } 
        else {

            $lat_long = geraLatLong($endereco, $numero, $cidade);

            $param = [
                'estado' => $estado, 
                'cidade' => $cidade,
                'bairro' => $bairro,
                'endereco' => $endereco,
                'cep' => $cep,
                'numero' => $numero,
                'complemento' => $complemento,
                'latitude' => $lat_long[0],
                'longitude' => $lat_long[1]
            ];
        }

        return $param;
    }
}
