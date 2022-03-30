<?php


require_once MODELS . '/Secure/Secure.class.php';


class MapasController {

    public function __construct() {

        $request = file_get_contents('php://input');
        $this->input = json_decode($request);
        $this->secure = new Secure();
        
        $this->req = $_REQUEST;
    }

    
    public function search_enderecos() {

        // $this->secure->tokens_secure($this->input->token); 
        

        $curl = curl_init();

        $endereco  = str_replace(' ', '%20', $this->input->endereco);
        $cidade = $this->input->cidade;
        
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://maps.googleapis.com/maps/api/place/findplacefromtext/json?input='.$endereco.'&inputtype=textquery&locationbias=circle:2000@47.6918452,-122.2226413&fields=formatted_address,name,rating,opening_hours,geometry&key=AIzaSyC8vwf88YzZJOGZO8CevSGFXrWmNJEJBZo',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);
        
        curl_close($curl);

        echo $response;
        
    }

    public function search_rotas(){
       
        $curl = curl_init();

        if($this->input->latitude_origem!="" && $this->input->longitude_origem!="" ) { // busca por latitude e longitude

            $latitude_origem = str_replace(' ', '%20', $this->input->latitude_origem);
            $longitude_origem = str_replace(' ', '%20', $this->input->longitude_origem);

            $latitude_destino = str_replace(' ', '%20', $this->input->latitude_destino);
            $longitude_destino = str_replace(' ', '%20', $this->input->longitude_destino);
            
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://maps.googleapis.com/maps/api/directions/json?origin='.$latitude_origem.','.$longitude_origem.'&destination='.$latitude_destino.','.$longitude_destino.'&key=AIzaSyC8vwf88YzZJOGZO8CevSGFXrWmNJEJBZo',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
              ));
              
              $response = curl_exec($curl);
              
              curl_close($curl);
              echo $response;
        }
        else { // busca por endereço
            
            $endereco_origem = str_replace(' ', '%20', $this->input->endereco_origem);
            $endereco_destino = str_replace(' ', '%20', $this->input->endereco_destino);
            
            
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://maps.googleapis.com/maps/api/directions/json?origin='.$endereco_origem.'&destination='.$endereco_destino.'&key=AIzaSyC8vwf88YzZJOGZO8CevSGFXrWmNJEJBZo',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
              ));
              
              $response = curl_exec($curl);
              
              curl_close($curl);
              echo $response;
        }
    }

    public function search_cidade(){

        $cidade = geraEndCompleto($this->req['lat'], $this->req['long']);

        jsonReturn(array(array("cidade" => $cidade[1])));
    }


   
       
}

