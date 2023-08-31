<?php
$endpoint = $argv[1];
$xmlContent = $argv[2];
$method = $argv[3];

// Opções do cliente SOAP
$options = array(
    'location' => $endpoint,
    'uri' => 'http://www.ans.gov.br/padroes/tiss/schemas', 
    'trace' => 1,      // Ativar rastreamento
    'exceptions' => 1, // Ativar exceções
);

// Criação do cliente SOAP
$client = new SoapClient(null, $options);

try {
    $noh = '';
    if ($method == 'tissSolicitacaoProcedimento_Operation') {
        $noh = 'autorizacaoProcedimentoWS';
    } elseif ($method == 'tissCancelaGuia_Operation') {
        $noh = 'reciboCancelaGuiaWS';
    } else {
        $arr = array('status' => false, 'method' => 'metodo nao previsto');
        echo json_encode($arr);
        return false;
    }

    // Invoca o método da API SOAP com o conteúdo XML e método específico
    $response = $client->__doRequest($xmlContent, $endpoint, $method, SOAP_1_1, 0);
    $xmlObject = simplexml_load_string($response);
    $result = $xmlObject->children('soap', true)->children('ans', true)->$noh;
    $jsonString = json_encode($result, JSON_PRETTY_PRINT);
    echo $jsonString;

} catch (SoapFault $e) {
    echo 'Erro na requisição SOAP: ' . $e->getMessage();

}
?>