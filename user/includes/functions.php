<?php

//SESSION START
ob_start();
session_start();

//DESLOGAR
if (isset($_GET['acao']) && $_GET['acao'] == 'sair'):
    unset($_SESSION['id']);
    session_destroy();
    header('Location:' . HOME_URI . '/cadastros/index-web');
endif;

function dataBR($campo) {

    if (substr($campo, 2, 1) == '/') {
        $campo = substr($campo, 6, 4) . '-' . substr($campo, 3, 2) . '-' . substr($campo, 0, 2); //2012-10-10
    } else {
        $campo = substr($campo, 8, 2) . '/' . substr($campo, 5, 2) . '/' . substr($campo, 0, 4); //10/10/2012
    }

    return($campo);
}

function jsonReturn($val) {
    echo json_encode($val);
    exit;
}

function getNomeTipo($tipo){

    switch ($tipo) {
        case 1:
            return 'usuario';
        case 2:
            return 'clinica';
        case 3:
            return 'vendedor';
    }
}

function MoipBR($status){

    if($status == null) {
        $status = "- - -";
    }

    if($status == "IN_ANALYSIS") {
        $status="EM ANÁLISE";
        $classe = "emanalise";
    }
    if($status == "CREATED") {
        $status="CRIADO";
        $classe="criado";
    }
    if($status == "WAITING") {
        $status="AGUARDANDO";
        $classe = "aguardando";
    }

    if($status == "PRE-AUTHORIZED") {
        $status="PRÉ AUTORIZADO";
        $classe = "preautorizado";
    }
    if($status == "AUTHORIZED") {
        $status="AUTORIZADO";
        $classe = "autorizado";
    }
    if($status == "CANCELLED") {
        $status="CANCELADO";
        $classe = "cancelado";
    }
    if($status == "SETTLED") {
        $status="CONCLUÍDO";
        $classe = "concluido";
    }

    return $status;
}

function horaMin($hora) {

    $ah = explode(":", $hora);

    return $ah[0].":".$ah[1];
}

function montaInQuery($arrayIn) { // retorna o array em (1,2,3,4) para consulta com IN no Mysql

    $string = "(";

    foreach ($arrayIn as $in) {
        $string .= $in.',';
    }
    $string .= "0)";

    return $string;
}

function categNome($categ){

    switch ($categ) {
        case '1':
            return 'Tentante';
            break;
        case '2':
            return 'Gestante';
            break;
        case '3':
            return 'Mãe';
            break;
    }
}

function datissima($data) {
    $data = date("d-m-y", strtotime($data));
    $data = str_replace('-', '/', $data);
    return $data;
}

function dataBR2($data) {
    $data = implode("/", array_reverse(explode("-", $data)));
    return $data;
}

function dataUS($data) {
    $data = implode("-", array_reverse(explode("/", $data)));
    return $data;
}

function horarioBR($campo) {

    $campo = explode(" ", $campo);
    $campo = $campo[1];
    return $campo;
}

function moneySQL($money) {
    $source = array('.', ',');
    $replace = array('', '.');
    $money = str_replace('R$', '', $money);
    $money = str_replace($source, $replace, $money);
    return $money;
}

function moneyView($money) {
    $money = ' R$ ' . number_format($money, 2, ',', '.');
    return $money;
}

function moedaAdd($get_valor) {
    $get_valor = $get_valor . ".00";
    return $get_valor; //retorna o valor formatado para gravar no banco
}

function md5_hash($string) {
    $string = md5($string);
    return $string;
}

function limitarTexto($texto, $limite = 100) {
    $contador = mb_strlen($texto);
    if ($contador >= $limite) {
        $texto = mb_substr($texto, 0, mb_strrpos(mb_substr($texto, 0, $limite), ' '), 'UTF-8') . '...';
        return $texto;
    } else {
        return $texto;
    }
}

function load_view($controller, $action, $mensagem, $view, $view2, $view3, $view4, $view5) {

    require_once VIEWS . '/' . $controller . "/" . $controller . '-' . $action . '.php';
}

function secure($string) {
    $_GET = array_map('trim', $_GET);
    $_POST = array_map('trim', $_POST);
    $_COOKIE = array_map('trim', $_COOKIE);
    $_REQUEST = array_map('trim', $_REQUEST);
    if (get_magic_quotes_gpc()) {
        $_GET = array_map('stripslashes', $_GET);
        $_POST = array_map('stripslashes', $_POST);
        $_COOKIE = array_map('stripslashes', $_COOKIE);
        $_REQUEST = array_map('stripslashes', $_REQUEST);
    }
    $_GET = array_map('mysql_real_escape_string', $_GET);
    $_POST = array_map('mysql_real_escape_string', $_POST);
    $_COOKIE = array_map('mysql_real_escape_string', $_COOKIE);
    $_REQUEST = array_map('mysql_real_escape_string', $_REQUEST);

    return $string;
}

function redimensionarImagem($imagem, $largura, $altura) {
    // Verifica extens�o do arquivo
    $extensao = strrchr($imagem, '.');
    switch ($extensao) {
        case '.png':
            $funcao_cria_imagem = 'imagecreatefrompng';
            $funcao_salva_imagem = 'imagepng';

            break;
        case '.gif':
            $funcao_cria_imagem = 'imagecreatefromgif';
            $funcao_salva_imagem = 'imagegif';

            break;
        case '.jpg':
            $funcao_cria_imagem = 'imagecreatefromjpeg';
            $funcao_salva_imagem = 'imagejpeg';

            break;
    }

    // Cria um identificador para nova imagem
    $imagem_original = $funcao_cria_imagem($imagem);

    // Salva o tamanho antigo da imagem
    list($largura_antiga, $altura_antiga) = getimagesize($imagem);

    // Cria uma nova imagem com o tamanho indicado
    // Esta imagem servir� de base para a imagem a ser reduzida
    $imagem_tmp = imagecreatetruecolor($largura, $altura);

    // Faz a interpola��o da imagem base com a imagem original
    imagecopyresampled($imagem_tmp, $imagem_original, 0, 0, 0, 0, $largura, $altura, $largura_antiga, $altura_antiga);

    // Salva a nova imagem
    $resultado = $funcao_salva_imagem($imagem_tmp, "../views/_depoimentos/" . $imagem . $extensao);

    // Libera memoria
    imagedestroy($imagem_original);
    imagedestroy($imagem_tmp);

    return $resultado;
}

function url_amigavel($texto) {

    $url = $texto;
    $url = preg_replace('~[^\\pL0-9_]+~u', '-', $url);
    $url = trim($url, "-");
    $url = iconv("utf-8", "us-ascii//TRANSLIT", $url);
    $url = strtolower($url);
    $url = preg_replace('~[^-a-z0-9_]+~', '', $url);
    return $url;
}

function geraSalt($tamanho = 8, $maiusculas = true, $numeros = true, $simbolos = false) {
    $lmin = 'abcdefghijklmnopqrstuvwxyz';
    $lmai = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $num = '1234567890';
    $simb = '!@#$%*-';
    $retorno = '';
    $caracteres = '';
    $caracteres .= $lmin;
    if ($maiusculas)
        $caracteres .= $lmai;
    if ($numeros)
        $caracteres .= $num;
    if ($simbolos)
        $caracteres .= $simb;
    $len = strlen($caracteres);
    for ($n = 1; $n <= $tamanho; $n++) {
        $rand = mt_rand(1, $len);
        $retorno .= $caracteres[$rand - 1];
    }
    return $retorno;
}

function Mask($mask,$str){

    $str = str_replace(" ","",$str);

    for($i=0;$i<strlen($str);$i++){
        $mask[strpos($mask,"#")] = $str[$i];
    }

    return $mask;

}

function renameUpload($file) {

    if (!empty($file)) {

        $extensao = strrchr($file, '.');
        $extensao = strtolower($extensao);
        $extensao = str_replace('.', '', $extensao);

        $file = md5(uniqid(time())) . ".";
        $file_final = $file . $extensao;

        return $file_final;
        exit;
    }

    if (empty($file)) {
        $file_final = '';
        return $file_final;
        exit;
    }
}

function geraToken($nome, $email){
    $token = md5($nome . $email);
    return $token;
}

function Date30days($data){
    $date = date('Y-m-d', strtotime("+30 days",strtotime($data)));
    $date = $date . " " . date('H:i:s');
    return $date;
}

function geraLatLong($endereco, $numero, $nome_cidade){

    $address = $endereco . $numero . "," . $nome_cidade . "," . "Brazil";
    $prepAddr = str_replace(' ','+',$address);

    $geocode= file_get_contents('https://maps.google.com/maps/api/geocode/json?key='.KEY_API.'&address='.$prepAddr.'&sensor=false');

    $output= json_decode($geocode);

    $lat = $output->results[0]->geometry->location->lat;
    $long = $output->results[0]->geometry->location->lng;

    return array($lat, $long);
}

function geraEnd($lat, $long)
{

    $geocode = "https://maps.googleapis.com/maps/api/geocode/json?latlng=$lat,$long&sensor=false&key=AIzaSyAQUpPqQ7UDU__BucfNDzOo1CxNWBR3yC8";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $geocode);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $response = curl_exec($ch);
    curl_close($ch);
    $output = json_decode($response);

    $dataarray = get_object_vars($output);

    if ($dataarray['status'] == 'OK' && $dataarray['status'] != 'INVALID_REQUEST') {

        if (isset($dataarray['results'][0]->formatted_address)) {

            $estado = $output->results[0]->address_components[3]->short_name;
            $cidade = $output->results[0]->address_components[4]->short_name;

        } else {
            $estado = null;
            $cidade = null;
        }
    } else {
         $estado = null;
         $cidade = null;
    }

    return array($cidade, $estado) ;

}

function generateRandomString($length = 8) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}



// GERA ENDEREÇO COMPLETO
function geraEndCompleto($lat, $lon) {

$url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=$lat,$lon&key=".KEY_API;

$data = file_get_contents($url);
$jsondata = json_decode($data,true);

if (!check_status($jsondata)) return array(); // verifica se encontrou o endereço, por enquanto retorna o array vazio, mas pode servir para alertar o usuário a usar a localização
    $pais  = google_getCountry($jsondata);
    $estado = google_getProvince($jsondata);
    $cidade =  google_getCity($jsondata);
    $endereco = google_getStreet($jsondata);
    $cep = google_getPostalCode($jsondata);
    $sigla_pais = google_getCountryCode($jsondata);
    $formatted_address = google_getAddress($jsondata);

    $arrayLocalizacao = explode(",", $formatted_address);// quebra resultado da localização
    /*[0] => R. Marieta Mena Barreto   [1] => 210 - Alto Petrópolis    [2] => Porto Alegre - RS    [3] => 91260-090    [4] => Brazil   */
    $arrayCid = explode("-", $arrayLocalizacao[2]); // pega cidade/estado
    $arrayBairro = explode("-", $arrayLocalizacao[1]); // pega numero/bairro

    $estado = trim($arrayCid[1]);
    $cidade = trim($arrayCid[0]);
    $bairro = $arrayBairro[1];
    $endereco = $arrayLocalizacao[0];
    $cep = $arrayLocalizacao[3];

    return array($estado, $cidade, $bairro, $endereco, $cep);   // pegar bairro desta variável quebrando ela com 'str'
}


function check_status($jsondata) {
    if ($jsondata["status"] == "OK") { return true; }
    else {  return false; }
}


function Find_Long_Name_Given_Type($type, $array, $short_name = false) {
    foreach( $array as $value) {
        if (in_array($type, $value["types"])) {
            if ($short_name)
                return $value["short_name"];
            return $value["long_name"];
        }
    }
}
function google_getCountry($jsondata) {
    return Find_Long_Name_Given_Type("country", $jsondata["results"][0]["address_components"]);
}
function google_getProvince($jsondata) {
    return Find_Long_Name_Given_Type("administrative_area_level_1", $jsondata["results"][0]["address_components"], true);
}
function google_getCity($jsondata) {
    return Find_Long_Name_Given_Type("locality", $jsondata["results"][0]["address_components"]);
}
function google_getStreet($jsondata) {
    return Find_Long_Name_Given_Type("street_number", $jsondata["results"][0]["address_components"]) . ' ' . Find_Long_Name_Given_Type("route", $jsondata["results"][0]["address_components"]);
}
function google_getPostalCode($jsondata) {
    return Find_Long_Name_Given_Type("postal_code", $jsondata["results"][0]["address_components"]);
}
function google_getCountryCode($jsondata) {
    return Find_Long_Name_Given_Type("country", $jsondata["results"][0]["address_components"], true);
}
function google_getAddress($jsondata) {
    return $jsondata["results"][0]["formatted_address"];
}



function tiraCarac($valor){
    $pontos = array("-", ".", "(", ")"," ", "/");
    $result = str_replace($pontos, "", $valor);

  return $result;
}
