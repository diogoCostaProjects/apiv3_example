<?php

require_once MODELS . '/Conexao/Conexao.class.php';

class Gcm extends Conexao {


    public function __construct() {

        $this->Conecta();
        $this->tabela = "app_servicos";
        $this->tabela_gcm = "app_fcm";

    }


    public function novo_status_ios($id_consulta, $status) {


      $query_push = "
      SELECT f.registration_id
      FROM `$this->tabela_gcm` as f
      INNER JOIN app_users as u on u.id = f.app_users_id
      INNER JOIN app_consultas as c on c.id_de = u.id
      WHERE c.id='$id_consulta' and f.type=2
      ";


      $sql_push = $this->mysqli->query($query_push);

        $i = 0;
        $data = array();
        while ($res = $sql_push->fetch_object()) {

          $this->id_cadastro = $res->id_cadastro;
          $data[$i] = $res->registration_id;
          $i++;
        }

        $registrationIDs = array_values($data);


        $url = 'https://fcm.googleapis.com/fcm/send';

        if (!empty($registrationIDs)):

            $msg = array(
              'title'  =>  'Novo status de consulta.',
              'body'     => 'A consulta #'.$id_consulta.'está com status: '.$status.'.',
              'vibrate'   => 1,
              'sound'     => 1,
            );
            $fields = array(
              'registration_ids'  => array_values($registrationIDs),
              'notification'      => $msg
            );

            $headers = array(
              'Authorization: key=AAAAAQlpxwc:APA91bH83FIGCi4G3LfvwcruywOpqyua-tNAt9PEdL3WYr5NW9KOY0m_CCr_nthdlgiDP1miR2mIBCmjct52i32L-unBkOY4YSGz4M3jTv2b10RgMyzSujsNL_2CszHjN0mcMoupLfV7',
              'Content-Type: application/json'
            );

            // Open connection
            $ch = curl_init();

            // Set the url, number of POST vars, POST data
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

            // Execute post
            $result = curl_exec($ch);

          


            // Close connection
            curl_close($ch);

        endif;
    }


    public function novo_status_android($id_consulta, $status) {
        
        //SELECIONA GCM de quem tenha consulta com data_de igual a data de hoje
        $query_push = "
        SELECT f.registration_id
        FROM `$this->tabela_gcm` as f
        INNER JOIN app_users as u on u.id = f.app_users_id
        INNER JOIN app_consultas as c on c.id_de = u.id
        WHERE c.id='$id_consulta' and f.type=1
        ";


        // echo $query_push; exit;

        $sql_push = $this->mysqli->query($query_push);

        $i = 0;
        $data = array();
        while ($res = $sql_push->fetch_object()) {

          $this->id_cadastro = $res->id_cadastro;
          $data[$i] = $res->registration_id;
          $i++;
        }

        $registrationIDs = array_values($data);


        $url = 'https://fcm.googleapis.com/fcm/send';

        if(sizeof($registrationIDs) > 1000){


            $newId = array_chunk($registrationIDs, 1000);

            foreach ($newId as $inner_id) {

              $fields = array(
                'registration_ids' => $inner_id,
                'data' => array(
                  "titulo" => "Novo status de consulta.",
                  "descricao" => "A consulta #".$id_consulta."está com status: ".$status."."
                ),
              );


              $headers = array(
                'Authorization: key=AAAAAQlpxwc:APA91bH83FIGCi4G3LfvwcruywOpqyua-tNAt9PEdL3WYr5NW9KOY0m_CCr_nthdlgiDP1miR2mIBCmjct52i32L-unBkOY4YSGz4M3jTv2b10RgMyzSujsNL_2CszHjN0mcMoupLfV7',
                'Content-Type: application/json'
              );

        }
      }else{

        $fields = array(
          'registration_ids' => $registrationIDs,
          'data' => array(
            "titulo" => "Novo status de consulta.",
            "descricao" => "A consulta #".$id_consulta."está com status: ".$status."."
          ),
        );

        $headers = array(
          'Authorization: key=AAAAAQlpxwc:APA91bH83FIGCi4G3LfvwcruywOpqyua-tNAt9PEdL3WYr5NW9KOY0m_CCr_nthdlgiDP1miR2mIBCmjct52i32L-unBkOY4YSGz4M3jTv2b10RgMyzSujsNL_2CszHjN0mcMoupLfV7',
          'Content-Type: application/json'
        );

      }

      // Open connection
      $ch = curl_init();

      // Set the url, number of POST vars, POST data
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

      // Execute post

      $result = curl_exec($ch);
      // echo $result; exit;
      // Close connection
      curl_close($ch);


    }
}
