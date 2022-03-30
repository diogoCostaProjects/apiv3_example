<?php


class Secure {


    public function tokens_secure($token) {

      if (crypt($token, COD_API) === COD_API) {


      }else{
        $Param['msg'] = 'falha na autenticação 401';
        $Param['status'] = '03';

        $lista[] = $Param;

        $json = json_encode($lista);
        echo $json;

        exit;
      }

    }


}
