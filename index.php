<?php
header('Content-Type: application/json; charset=ISO-8859-1');
error_reporting(E_ALL ^ E_WARNING); 

if(!isset($_GET['cep'])) {
    http_response_code(400);
    echo mb_convert_encoding("O cep não pode ser nulo", "ISO-8859-1", "auto");
    return;
}

$cep = str_replace('-', '', $_GET['cep']);

$url = 'http://www.buscacep.correios.com.br/sistemas/buscacep/resultadoBuscaCepEndereco.cfm';
$data = "relaxation=$cep&tipoCEP=ALL&semelhante=N";

$ch = curl_init( $url );
curl_setopt( $ch, CURLOPT_POST, 1);
curl_setopt( $ch, CURLOPT_POSTFIELDS, $data);
curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt( $ch, CURLOPT_HEADER, 0);
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);

$result = curl_exec($ch);
$result = str_replace("&nbsp;", "", $result);

$dom = new DOMDocument();  

$html = $dom->loadHTML('<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">'.$result);  
 
$dom->preserveWhiteSpace = false;   

$tables = $dom->getElementsByTagName('table');   
$row_headers = NULL;

$table = array();
$rows = $tables->item(0)->getElementsByTagName('tr');   

$cols = $rows[1]->getElementsByTagName('td'); 

$cidadeEstado = explode("/", $cols[2]->nodeValue);

$cepArray['endereco'] = $cols[0]->nodeValue;
$cepArray['bairro'] = $cols[1]->nodeValue;
$cepArray['cidade'] = $cidadeEstado[0];
$cepArray['uf'] = $cidadeEstado[1];
$cepArray['cep'] = $cols[3]->nodeValue;
$cepArray['unico'] = ($cols[0]->nodeValue == '') ? 'S' : 'N';

$json = mb_convert_encoding(json_encode($cepArray, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT), "ISO-8859-1", "auto");

http_response_code(200);

echo html_entity_decode($json);
?>